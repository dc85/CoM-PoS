<?php
ini_set("display_errors", 1);
//error_reporting(E_NONE);
//include("../backend/staff.php");
include("../backend/mysqldb.php");

$database = new MySQLDB;
//var_dump($_GET);
$datestr = getdate(time());
$dateMod = date("Y-m-d H:i:s",$datestr[0]);
$count = 0;
//print "WTFZ IS GOING ONZ";
$tblprod_array = array('pSupplier','pName','pCatType','pDescription',
	'pUPC','pSku','pProdCode','pSize','pBSize','pCSize','pPicture');
$numVars = 	array("iTax1","iTax2","iCost","iPrice","iMinQty","iQty",
	"iMaxQty","iSPC1","iSPC2","iSPC3","iSPC4","iSPC5","iSPC6","iSPC7",
	"iSPC8","iSPC9","iSPC10");
//print "WTFZ IS GOING ONZ";
$q_array = array();
//mysql_query("BEGIN");
foreach($_GET as $key=>$value) {
	if($key != "sID" && $key != "pID") {
		if(in_array($key,$tblprod_array)) {
			if(in_array($key,$numVars)) {
				$sql = "UPDATE pos.tblProduct SET $key=$value,pModDate='$dateMod' WHERE pID=".$_GET['pID'];
				$q_array[]["query"] = $sql;
			} else {
				$sql = "UPDATE pos.tblProduct SET $key='$value',pModDate='$dateMod' WHERE pID=".$_GET['pID'];
				$q_array[]["query"] = $sql;
			}
			//echo $sql;
		} else {
			if(in_array($key,$numVars)) {
				$sql = "UPDATE pos.tblInventory SET $key=$value,iModDate='$dateMod' WHERE iStoreID=".$_GET['sID'].
					" AND iProduct=".$_GET['pID'];
				$q_array[]["query"] = $sql;
			} else {
				$sql = "UPDATE pos.tblInventory SET $key='$value',iModDate='$dateMod' WHERE iStoreID=".$_GET['sID'].
					" AND iProduct=".$_GET['pID'];
				$q_array[]["query"] = $sql;
			}
		}
	}
}
//print_r($q_array);
if($database->transaction($q_array)) {
	print "WEBPOS_MSG(02261027)";
	exit();
} else {
	print "WEBPOS_ERR(02261027)";
	exit();
}
//print "1$count field(s) updated in tblStaff.\n";
/*ini_set("display_errors", 1);
error_reporting(E_NONE);
include("../backend/product.php");
$str = $_GET['str'];
$sID = $_GET['ecStoreID'];
$pID = $_GET['ecID'];
$count = 0;
$ele_array = array('edit_pStore','edit_pSupplier','edit_pName',
			'edit_pCategory','edit_pDescription','edit_pUPC','edit_pSku',
			'edit_pCode','edit_pSize','edit_pBSize','edit_pCSize','edit_pCost',
			'edit_pMSRP','edit_pTax1','edit_pTax2','edit_pMaxQty','edit_pMinQty',
			'edit_pCurQty');

$tblprod_array = array('pSupplier','pName','pCatType',
		'pDescription','pUPC','pSku','pProdCode','pSize',
		'pBSize','pCSize');
$infoPairs = explode("|",$str);
//print_r($infoPairs);
foreach($infoPairs as $info) {
	$pair = explode("@",$info);
	if(in_array($pair[0],$tblprod_array)) {
		$col = "tblProduct.".$pair[0];
		if($pair[0] == 'pSupplier' || $pair[0] == 'pCatType' || 
			$pair[0] == 'pBSize') {
			$sql = "UPDATE pos.tblProduct SET $col=$pair[1] WHERE pID=$pID;";
		} else if($pair[0] == 'pCSize') {
			if($pair[1] == '0') {
				$adjusted = 0.00;
			} else if($pair[1] == '1') {
				$adjusted = 5.00;
			} else if($pair[1] == '2') {
				$adjusted = 0.25;
			} else if($pair[1] == '3') {
				$adjusted = 1.00;
			} else if($pair[1] == '4') {
				$adjusted = 2.00;
			} else if($pair[1] == '5') {
				$adjusted = 3.00;
			} else if($pair[1] == '6') {
				$adjusted = 3.50;
			} else {
				$adjusted = 0.00;
			}
			$sql = "UPDATE pos.tblProduct SET $col=$adjusted WHERE pID=$pID;";
		} else {
			$sql = "UPDATE pos.tblProduct SET $col='$pair[1]' WHERE pID=$pID;";
		}
	} else {
		$col = "tblInventory.".$pair[0];
		if($pair[0] == 'iTax1' || $pair[0] == 'iTax2' || $pair[0] == 'iCost'
		 || $pair[0] == 'iPrice' || $pair[0] == 'iMinQty' || $pair[0] == 'iQty'
		 || $pair[0] == 'iMaxQty' || $pair[0] == "iSPC1" || $pair[0] == "iSPC2"
		 || $pair[0] == "iSPC3" || $pair[0] == "iSPC4" || $pair[0] == "iSPC5"
		 || $pair[0] == "iSPC6" || $pair[0] == "iSPC7" || $pair[0] == "iSPC8"
		 || $pair[0] == "iSPC9" || $pair[0] == "iSPC10") {
			$sql = "UPDATE pos.tblInventory SET $col=$pair[1] WHERE iStoreID=$sID AND iProduct=$pID;";
		} else {
			$sql = "UPDATE pos.tblInventory SET $col='$pair[1]' WHERE iStoreID=$sID AND iProduct=$pID;";
		}
	}
	if(@mysql_query($sql)) {
		$count++;	
	} else {
		print (mysql_error());
	}
	//print $sql . "\n";
}
print "[$count] field(s) has been updated for product ID [$pID] in store [$sID].";*/
?>