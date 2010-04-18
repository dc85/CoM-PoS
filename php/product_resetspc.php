<?php
include("../backend/methods.php");
include("../backend/db_methods.php");

$sID = $_GET['selSID'];
$pID = $_GET['selPID'];

$sql = "UPDATE pos.tblInventory 
SET tblInventory.iQty=tblInventory.iQty+$qty 
WHERE tblInventory.iStoreID=$sID 
AND tblInventory.iProduct=$pID;";

if(mysql_query($sql)) {
	print "Successfully added [$qty] of product [$pID] to store [$sID]";
} else {
	print mysql_error();
}
?>