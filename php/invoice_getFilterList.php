<?php
include("../backend/invoice.php");
$query = $_GET['key'];

$sql = "SELECT DISTINCT tblColour.ColourNum as num,tblColour.ColourName as name 
FROM pos.tblColour 
WHERE tblColour.ColourName LIKE '%$query%' OR tblColour.ColourNum LIKE '%$query%';";
//print $sql;
$data = @mysql_query($sql);
//print $data;
$result = "";

while($row = @mysql_fetch_assoc($data)) {
	//var_dump($row);
	//print $row["num"];
	//print $row;
	//print $row['ColourNum'] + " - " + $row['ColourName'] + "/";
	$result .= $row["num"] . " - " . $row["name"] . "/";
}
if($result == "") {
	print "null";
} else {
	print substr($result,0,strlen($result) - 1);
}
//print $result;
//print "001 - Powder Blue/002 - Hot Red"
?>