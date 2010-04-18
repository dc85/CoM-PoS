<?php
include("../backend/methods.php");
include("../backend/db_methods.php");

$id = $_GET['id'];
$curtime = getdate(time());
$date = date("Y-m-d",$curtime[0]) . " 00:00:00";
$time = date("Y-m-d H:i:s",$curtime[0]);

$sql = "UPDATE pos.tblTimesheet SET tsOut='$time',tsStatus=0 WHERE tsDate='$date' AND tsStaff='$id';";

if(mysql_query($sql)) {
	print "User [". $id ."] signed out at: " . $time;
} else {
	print mysql_error();
}
?>