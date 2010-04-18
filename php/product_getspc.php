<?php
ini_set("display_errors", 1);
error_reporting(E_NONE);
include("../backend/product.php");

$sql = "SELECT tblspctype.sLvl as slvl
	FROM pos.tblspctype;";
//print $sql;
if($result = @mysql_query($sql)) {
	//print $sql;
	//print_r ($result);
	$line = "";
	while($row = @mysql_fetch_assoc($result)) {
		$line .= $row["slvl"] . "|";
		//print_r($row);
		//return;
	}
	print $line;
} else {
	print $sql;
	print (mysql_error());
}
?>