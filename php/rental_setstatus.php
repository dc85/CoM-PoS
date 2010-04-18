<?php
ini_set("display_errors", 1);
error_reporting(E_NONE);
include("../backend/rental.php");
$id=$_GET['rID'];
$return=$_GET['return'];

$sql = "UPDATE pos.tblRental 
	SET tblRental.rIsReturned=$return 
	WHERE tblRental.rID=$id;";

if(mysql_query($sql)) {
	print $return;
} else {
	print $sql;
	print (mysql_error());
}
?>