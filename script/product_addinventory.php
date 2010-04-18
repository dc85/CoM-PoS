<?php
include("../backend/methods.php");
include("../backend/db_methods.php");

$sID = $_GET['selSID'];
$pID = $_GET['selCID'];

$sql = "UPDATE pos.tblInventory ".
"SET tblInventory.iQty=tblInventory.iQty+$qty ".
"WHERE tblInventory.iStoreID=$sID ".
"AND tblInventory.iProduct=$pID;";

if(mysql_query($sql)) {
	print "1Successfully added [$qty] of product [$pID] to store [$sID]";
} else {
	print "0";
	print $sql . "\n";
	print "ERROR: \n";
	print mysql_error();
}
?>