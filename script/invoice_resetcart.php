<?php
include("../backend/methods.php");
include("../backend/invoice.php");

$session = $_GET['session'];

$sql = "DELETE FROM pos.tblShoppingCart WHERE scInvcNum='$session';";

if(@mysql_query($sql)) {
	print "Cart Cleared";
} else {
	//print "Cart Clear Failed";
	print mysql_error();
}

?>