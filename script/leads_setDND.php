<?php
include("../backend/methods.php");
include("../backend/db_methods.php");

$sID = $_GET['selSID'];
$cID = $_GET['selCID'];
$op = $_GET['op'];

$datestr = getdate(time());
$setDate = date("Y-m-d H:i:s",$datestr[0]);

if($op == "set") {
	$sql = "UPDATE pos.tblCustomer ".
	"SET tblCustomer.cDND='$setDate' ".
	"WHERE tblCustomer.cStoreID=$sID ".
	"AND tblCustomer.cID=$cID;";
} else if($op == "remove") {
	$sql = "UPDATE pos.tblCustomer ".
	"SET tblCustomer.cDND=NULL ".
	"WHERE tblCustomer.cStoreID=$sID ".
	"AND tblCustomer.cID=$cID;";
}

if(mysql_query($sql)) {
	print "1";
} else {
	print "0";
	print $sql . "\n";
	print "ERROR: \n";
	print mysql_error();
}
?>