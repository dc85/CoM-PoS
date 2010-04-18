<?php
include("../backend/rental.php");
$upc = $_POST['queryString'];
$result = "";
$sql = "SELECT tblCatType.cCatType as rPType,
	tblProduct.pDescription as rPDescription
	FROM pos.tblProduct 
	INNER JOIN pos.tblCatType ON tblProduct.pCatType=tblCatType.ctID 
	WHERE tblProduct.pUPC='$upc' LIMIT 1;";
//print $sql;
if($sql_result = @mysql_query($sql)) {
	while($row = @mysql_fetch_assoc($sql_result)) {
		$result .= $row['rPType'] . "|" . $row['rPDescription'];
	}
	print $result;
} else {
	print mysql_error();
}
?>