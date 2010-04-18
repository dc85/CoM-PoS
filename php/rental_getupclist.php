<?php
include("../backend/rental.php");
$queryString = $_POST['queryString'];
$result = "";
$sql = "SELECT tblProduct.pUPC as pUPC FROM pos.tblProduct WHERE tblProduct.pUPC LIKE '$queryString%' LIMIT 10;";
if($sql_result = @mysql_query($sql)) {
	echo "<table>";
	while($row = @mysql_fetch_assoc($sql_result)) {
		echo "<tr><th class=\"autocomplete\" onMouseOver=\"this.bgColor='#659CD8';\" onMouseOut=\"this.bgColor='#212427';\"><a onclick=\"fill(".$row['pUPC'].");\">".$row['pUPC']."</a></th></tr>";
		//$result .= $row['pUPC'] . "|";
	}
	echo "</table>";
	//print substr($result,0,-1);
} else {
	print mysql_error();
}
?>