<?php
ini_set("display_errors", 1);
error_reporting(E_NONE);
include("../backend/product.php");
$input = $_GET["input"];

$sql = "SELECT tblDSize.dID as cPrice FROM pos.tblDSize WHERE dSize='$input'";
if($result = @mysql_query($sql)) {
	if($row = @mysql_fetch_assoc($result)) {
		print $row["cPrice"];
	}
} else {
	//print $sql;
	print (mysql_error());
}
?>