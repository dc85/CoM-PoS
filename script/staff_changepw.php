<?php
include("../backend/methods.php");
include("../backend/db_methods.php");

$store = $_GET['store'];
$staff = $_GET['staff'];
$pw = $_GET['pw'];

$sql = "UPDATE pos.tblStaff ".
"SET tblStaff.sPassword='".md5($pw)."' ".
"WHERE tblStaff.sID=$staff;";

if(mysql_query($sql)) {
	//print "Successfully changed password for staff [$staff]";
	print "1";
} else {
	/*print $sql . "\n";
	print "ERROR: \n";
	print mysql_error();*/
	print "0";
	print $sql . "\n";
	print "ERROR: ";
	print mysql_error();
}
?>