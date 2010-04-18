<?php
ini_set("display_errors", 1);
error_reporting(E_NONE);
include("../backend/staff.php");
$input = $_GET["input"];

$sql = "SELECT tblStaff.sID as sID FROM pos.tblStaff WHERE tblStaff.sCNumber='$input'";
if($result = @mysql_query($sql)) {
	if($row = @mysql_fetch_assoc($result)) {
		print $row["sID"];
	}
} else {
	//print $sql;
	print (mysql_error());
}
?>