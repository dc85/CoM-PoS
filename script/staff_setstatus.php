<?php
ini_set("display_errors", 1);
error_reporting(E_NONE);
include("../backend/staff.php");
//$store=$_GET['sStoreID'];
$id=$_GET['sID'];
$activity=$_GET['set'];

$sql = "UPDATE pos.tblStaff ".
	"SET tblStaff.sIsActive=$activity ".
	"WHERE tblStaff.sID=$id";

if(@mysql_query($sql)) {
	print "1".$activity;
} else {
	print "0".$sql."\n";
	print (mysql_error())."\n";
}
?>