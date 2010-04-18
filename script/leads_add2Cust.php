<?php
include("../backend/methods.php");
include("../backend/db_methods.php");

$sID = $_GET['selSID'];
$cID = $_GET['selCID'];
$num = $_GET['num'];

$sql = "UPDATE pos.tblCustomer ".
"SET tblCustomer.cCardNum='$num' ".
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