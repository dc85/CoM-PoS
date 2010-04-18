<?php
include("../backend/methods.php");
include("../backend/invoice.php");

/*$scUPC = $_GET['upc'];
$scSNum = $_GET['sessionID'];
$cID = $_GET['cID'];*/
$id = $_GET["id"];

if(true) {
	$sql = "DELETE FROM pos.tblShoppingCart WHERE scID=$id;";
	
	if(@mysql_query($sql)) {
		print "Item removed from shopping cart.\n";
	} else {
		//print $sql."\n";
		print "0";
	}
	//$writer->preserve_error();
}
?>