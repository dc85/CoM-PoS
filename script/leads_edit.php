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

$numVars = 	array("cStoreID","cTitle","cCustType","cExpert","cProvB","cProvH",
	"cProvS","cpType1","cpType2","cpType3","cpType4","cpType5","cSPC","cCustRep",
	"cCalled","cModBy","cDelStatus","cDNC");
//print "WTFZ IS GOING ONZ";
$q_array = array();
//mysql_query("BEGIN");
foreach($_GET as $key=>$value) {
	if($key != "cID") {
		if(in_array($key,$numVars)) {
			$sql = "UPDATE pos.tblLead SET $key=$value,cModDate='$dateMod' WHERE cID=".$_GET['cID'];
			$q_array[]["query"] = $sql;
		} else {
			$sql = "UPDATE pos.tblLead SET $key='$value',cModDate='$dateMod' WHERE cID=".$_GET['cID'];
			$q_array[]["query"] = $sql;
		}
	}
}
//print_r($q_array);
if($database->transaction($q_array)) {
	print "1";
	exit();
} else {
	print "0";
	print $sql . "\n";
	print (mysql_error()). "\n";
	exit();
}
/*ini_set("display_errors", 1);
error_reporting(E_NONE);
include("../backend/customer.php");
$cID = $_POST['ecID'];
$count = 0;
//$count = $_GET['count'];

$tags = array("cIsActive","cTitle","cFirstN","cLastN",
			"cAKA","cDoB","cRIN","cCardNum","cCustType","cCoName",
			"cShirtSize","cPantSize","cExpert","cpType4","cPhone4",
			"cpType5","cPhone5","cUnitB","cAdrBus","cCityB","cProvB",
			"cZipB","cpType1","cPhone1","cEmail1","cUnitH","cAdrHome",
			"cCityH","cProvH","cZipH","cpType2","cPhone2","cEmail2",
			"cUnitS","cAdrShip","cCityS","cProvS","cZipS","cpType3",
			"cPhone3","cEmail3","cTax1","cTax2","cSPC","cEcoFee",
			"cCustRep","cExpNum","cExpDate","cCredit","cCBal","c90",
			"c60","c30","cCurrent","cBalance","cPromo","cNote");

$invoice_tags = array('c90','c60','c30','cCurrent','cPromo');

foreach($tags as $tag) {
	if(in_array($tag,$invoice_tags)) {
		if($pair[0] == "cPromo") {
			$sql = "UPDATE pos.tblCustomer SET $col='$pair[1]' WHERE cID=$cID;";
		} else {
			$sql = "UPDATE pos.tblCustomer SET $col=$pair[1] WHERE cID=$cID;";
		}
	} else {
		if(!empty($_POST[$tag])) {
			$col = "tblCustomer.".$tag;
			if($tag == "cFirstN" || $tag == "cLastN" || $tag == "cAKA" || 
					$tag == "cDoB" || $tag == "cCoName" || $tag == "cUnitB" ||
					$tag == "cAdrBus" || $tag == "cCityB" || $tag == "cProvB" || 
					$tag == "cZipB" || $tag == "cUnitH" || $tag == "cAdrHome" || 
					$tag == "cCityH" || $tag == "cProvH" || $tag == "cZipH" || 
					$tag == "cUnitS" || $tag == "cAdrShip" || $tag == "cCityS" || 
					$tag == "cProvS" || $tag == "cZipS" || $tag == "cPhone1" || 
					$tag == "cPhone2" || $tag == "cPhone3" || $tag == "cPhone4" || 
					$tag == "cPhone5" || $tag == "cEmail1" || $tag == "cEmail2" || 
					$tag == "cEmail3" || $tag == "cRIN" || $tag == "cDL" || 
					$tag == "cCardNum" || $tag == "cExpNum" || $tag == "cExpDate" || 
					$tag == "cPantSize" || $tag == "cNote" || $tag == "cAware" || 
					$tag == "cModDate" || $tag == "cCrtDate") {
				$sql = "UPDATE pos.tblCustomer SET $col='$_POST[$tag]' WHERE cID=$cID;";
			} else if($tag == "cE1" || $tag == "cE2" || $tag == "cE3" || 
					$tag == "cE4" || $tag == "cE5" || $tag == "cE6" || 
					$tag == "cE7" || $tag == "cE8" || $tag == "cE9" || 
					$tag == "cE10") {
				
			} else {
				$sql = "UPDATE pos.tblCustomer SET $col=$_POST[$tag] WHERE cID=$cID;";
			}
			if(@mysql_query($sql)) {
				//$c = (int)$count;
				$count++;
				//print $c;	
			} else {
				print $sql . "\n";
				print (mysql_error()). "\n";
			}
		}
	}
}
print "[$count] field(s) has been updated for customer ID [$cID].\n";*/
?>