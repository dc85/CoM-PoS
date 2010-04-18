<?php
include("../backend/methods.php");
include("../backend/db_methods.php");

$sID = $_GET['selSID'];
$cID = $_GET['selCID'];
$code = $_GET['code'];
$type = $_GET['type'];
$staff = $_GET['staff'];

$datestr = getdate(time());
$setDate = date("Y-m-d H:i:s",$datestr[0]);

$sql = "UPDATE pos.tblCustomer ".
"SET tblCustomer.cLastCont='$setDate', ".
"tblCustomer.cContCode=$code,".
"tblCustomer.cContType=$type,".
"tblCustomer.cContStaff='$staff' ".
"WHERE tblCustomer.cStoreID=$sID ".
"AND tblCustomer.cID=$cID;";

if(mysql_query($sql)) {
	print "1";
} else {
	print "0";
	print $sql . "\n";
	print "ERROR: \n";
	print mysql_error();
}
?>