<?php
ob_start();
ini_set("display_errors", 1);
ini_set("output_buffering", 0);

error_reporting(E_NONE);
include("../backend/product.php");


$d = getdate(time());
$today = date("Y-m-d H:i:s",$d[0]); 
////print "TODAY ".$today;
//print "TIMESTAMP - " . $today . "\n";
//var_dump($_GET);
////print "\n";
function begin() {@mysql_query("BEGIN");}
function commit() {@mysql_query("COMMIT");}
function rollback() {@mysql_query("ROLLBACK");}

begin();

if($pID = checkProductTable()) {
	if($_GET["iStoreID"] == "0") {
		//print "INSERTING into all stores...\n";
		$sql = "SELECT tblStore.sID AS sID FROM pos.tblStore;";
		if($r = @mysql_query($sql)) {
			while($ids = @mysql_fetch_assoc($r)) {
				if(checkInventory($ids["sID"])) {
					if(insertInventory($pID,$ids["sID"])) {
						//print "Product added successfully to inventory at store sid=[".$ids["sID"]."].\n";
						//@mysql_query("COMMIT");
						//print "WEBPOS_MSG(02201632)";
						//return;
					}
				} else {
					rollback();
					print "WEBPOS_ERR(02201632)";
					exit;
					//print "Product already exist in store[".$ids["sID"]."].\n";
				}
			}
		} else {
			rollback();
			print "WEBPOS_ERR(02201632)";
			exit;
			//print "ERROR#2: Main thread - cannot find stores.\n";
			//print "SQL: ".$sql."\n";
			//print (mysql_error())."\n";
		}
	} else {
		if(checkInventory($_GET["iStoreID"])) {
			if(insertInventory($pID,$_GET["iStoreID"])) {
				//print "Product added successfully to inventory at store sid=[".$_GET["iStoreID"]."].\n";
				//@mysql_query("COMMIT");
				//print "WEBPOS_MSG(02201632)";
				//return;
			}
		} else {
			//print "Product already exist in store[".$_GET["iStoreID"]."].\n";
			print "WEBPOS_ERR(02201632)";
			return;
		}
	}
} else {
	print "WEBPOS_ERR(02201632)";
	return;
	//print "ERROR#1: Main thread - check product table failed.\n";
}
commit();

function insertInventory($pID,$sID) {
	global $today;
	$tblcols = array("iStoreID","iProduct","iMaxQty","iQty","iMinQty",
	"iCost","iPrice","iSPC1","iSPC2","iSPC3","iSPC4","iSPC5","iSPC6",
	"iSPC7","iSPC8","iSPC9","iSPC10","iTax1","iTax2");
	$varchars = array();
	//print "New inventory - building insert to product table...\n";
	$tblInventory_insert = "INSERT INTO pos.tblInventory(iStoreID,iProduct,iMaxQty,iQty,iMinQty,iCost,iPrice,iSPC1,iSPC2,iSPC3,iSPC4,iSPC5,iSPC6,iSPC7,iSPC8,iSPC9,iSPC10,iTax1,iTax2,iCrtDate,iModDate,iIsActive) VALUES(";
	foreach($tblcols as $col) {
		if($col == "iProduct") {
			$tblInventory_insert .= $pID.",";
		} else if($col == "iStoreID") {
			$tblInventory_insert .= $sID.",";
		}	else {
			if(!empty($_GET[$col])) {
				$tblInventory_insert .= $_GET[$col].",";
			} else {
				$tblInventory_insert .= "null,";
			}
		}
	}
	$tblInventory_insert .= "'$today','$today',1);";
	////print $tblInventory_insert."\n";
	if(@mysql_query($tblInventory_insert)) {
		//print "Query executed succesfully...\n";
		return true;
	} else {
		//print (mysql_error())."\n";
		//print "SQL: ".$tblInventory_insert."\n";
		@mysql_query("ROLLBACK");
		return false;
	}
}

function checkInventory($sID) {
	$tblInventory_check_qry = "SELECT tblInventory.iID as iID 
		FROM pos.tblInventory 
		INNER JOIN pos.tblProduct ON tblInventory.iProduct = tblProduct.pID 
		WHERE tblProduct.pUPC='".$_GET['pUPC']."'
		AND tblInventory.iStoreID=".$sID.";";
	$check_qry_result = @mysql_query($tblInventory_check_qry);
	if(@mysql_num_rows($check_qry_result) == 0) {
		return true;
	} else {
		////print "SQL: ".$tblInventory_check_qry."\n";
		@mysql_query("ROLLBACK");
		return false;
	}
}

function checkProductTable() {
	global $today;
	$tblcols = array("pSupplier","pCatType","pName",
	"pDescription","pUPC","pSku","pProdCode",
	"pBSize","pCSize");
	$varchars = array("pName","pDescription","pUPC",
	"pSku","pProdCode","pSize","pPromo");
	$tblProduct_qry = "SELECT tblProduct.pID AS pID FROM pos.tblProduct ".
	"WHERE tblProduct.pUPC='".$_GET['pUPC']."'";
	if($qry_result = @mysql_query($tblProduct_qry)) {
		if(@mysql_num_rows($qry_result) == 0) {
			//print "New product - building insert to product table...\n";
			$tblProduct_insert = "INSERT INTO pos.tblProduct(pSupplier,pCatType,".
			"pName,pDescription,pUPC,pSku,pProdCode,pBSize,pCSize,pCrtDate,".
			"pModDate,pIsActive) VALUES(";
			foreach($tblcols as $col) {
				if(in_array($col,$varchars)) {
					if(!empty($_GET[$col])) {
						$tblProduct_insert .= "'".$_GET[$col]."',";
					} else {
						$tblProduct_insert .= "null,";
					}
				} else {
					if(!empty($_GET[$col])) {
						$tblProduct_insert .= $_GET[$col].",";
					} else {
						$tblProduct_insert .= "null,";
					}
				}
			}
			$tblProduct_insert .= "'$today','$today',1);";
			////print $tblProduct_insert."\n";
			if(@mysql_query($tblProduct_insert)) {
				//print "Query executed succesfully...\n";
				if($qry_result = @mysql_query($tblProduct_qry)) {
					$qry_data = @mysql_fetch_assoc($qry_result);
					//print "New product id = ".$qry_data["pID"].".\n";
					return $qry_data["pID"];
				} else {
					rollback();
					print "WEBPOS_ERR(02201754)";
					exit;
				}
			} else {
				rollback();
				print "WEBPOS_ERR(02201754)";
				exit;
			}
		} else {
			//print "Existing product, fetching id...\n";
			$qry_data = @mysql_fetch_assoc($qry_result);
			//print "Existing product id = ".$qry_data["pID"].".\n";
			return $qry_data["pID"];
		}
	} else {
		rollback();
		print "WEBPOS_ERR(02201750)";
		exit;
		//print "ERROR#1: checkProductTable() - php new product.\n";
		//print "SQL: ".$tblProduct_qry.".\n";
		//print (mysql_error())."\n";
	}
}

function flush_now() {

  @apache_setenv('no-gzip', 1);
  @ini_set('output_buffering', 0);
  @ini_set('zlib.output_compression', 0);
  @ini_set('implicit_flush', 1);
  for ($i = 0; $i < ob_get_level(); $i++) { ob_end_flush(); }
  ob_implicit_flush(1);
  return true;

}
?>