<?php
ini_set("display_errors", 1);
error_reporting(E_NONE);
include("../backend/product.php");
$input = $_GET["input"];

$sql = "SELECT tblBSize.bPrice as bPrice FROM pos.tblBSize WHERE bID=$input";
if($result = @mysql_query($sql)) {
	if($row = @mysql_fetch_assoc($result)) {
		print $row["bPrice"];
	}
} else {
	//print $sql;
	print (mysql_error());
}
?>