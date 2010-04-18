<?php
include("../backend/rental.php");

$result = "";
$sql = "SELECT tblProduct.pUPC as pUPC FROM pos.tblProduct;";
if($sql_result = @mysql_query($sql)) {
	while($row = @mysql_fetch_assoc($sql_result)) {
		$result .= $row['sName'] . "|";
	}
	print substr($result,0,-1);
} else {
	print mysql_error();
}
?>