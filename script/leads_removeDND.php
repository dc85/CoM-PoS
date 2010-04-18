<?php
include("../backend/methods.php");
include("../backend/db_methods.php");

$sID = $_GET['selSID'];
$cID = $_GET['selCID'];

$sql = "UPDATE pos.tblCustomer ".
"SET tblCustomer.cDND=NULL ".
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