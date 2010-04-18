<?php
include("../backend/seminar.php");
$cID=$_GET['cID'];
$comNum=$_GET['comNum'];
$paid=$_GET['paid'];
$ta=$_GET['ta'];
$da = getdate(time());
$date = date("Y-m-d H:i:m", $da[0]);

$result = "";
$sql = "INSERT INTO pos.tblSemnr(`sCustID`,`sStoreID`,`sCustNum`,`sPaid`,`sCrtDate`,`sLearn`,`sClosed`) 
	VALUES($cID,1,$comNum,$paid,'$date','$ta',0);";
if($sql_result = @mysql_query($sql)) {
	print "Customer [".$comNum."] Added to Seminar.";
} else {
	print mysql_error();
}
?>