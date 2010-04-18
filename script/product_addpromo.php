<?php
include("../backend/methods.php");
include("../backend/db_methods.php");

$pID = $_GET['selPID'];
$promoNote = $_GET['note'];

if($promoNote == "removexoxo") {
	$sql .= "UPDATE pos.tblProduct ".
	"SET tblProduct.pPromo=NULL ".
	"WHERE tblProduct.pID=$pID;";
} else {
	$sql = "UPDATE pos.tblProduct ".
	"SET tblProduct.pPromo='$promoNote' ".
	"WHERE tblProduct.pID=$pID;";
}

if(mysql_query($sql)) {
	if($promoNote == "removexoxo") {
		print "1Successfully removed promo on product [$pID]";
	} else {
		print "1Successfully added promo note [$promoNote] to product [$pID]";
	}
} else {
	print "0";
	print $sql . "\n";
	print "ERROR: \n";
	print mysql_error();
}
?>