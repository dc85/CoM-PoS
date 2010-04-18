<?php
include("../backend/rental.php");
$store=$_GET['store'];

$result = "";
$sql = "SELECT tblStaff.sStaff as sName 
FROM pos.tblStaff 
WHERE sStoreID=$store;";
if($sql_result = @mysql_query($sql)) {
	while($row = @mysql_fetch_assoc($sql_result)) {
		$result .= $row['sName'] . "|";
	}
	print $result;
} else {
	print mysql_error();
}
?>