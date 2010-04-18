<?php
ini_set("display_errors", 1);
//error_reporting(E_NONE);
//include("../backend/staff.php");
include("../backend/mysqldb.php");

$database = new MySQLDB;

$count = 0;
//print "WTFZ IS GOING ONZ";
$numVars = array("sCustomer","sLevel","sStoreCredit","sPswdExpMM","sHrRate","sDfHr",
	"sSchedule0","sSchedule1","sSchedule2","sSchedule3","sSchedule4","sSchedule5",
	"sSchedule6");
//print "WTFZ IS GOING ONZ";
$q_array = array();
//mysql_query("BEGIN");
foreach($_GET as $key=>$value) {
	if($key != "sID") {
		if(in_array($key,$numVars)) {
			$sql = "UPDATE pos.tblStaff SET $key=$value WHERE sID=".$_GET['sID'];
			$q_array[]["query"] = $sql;
		} else {
			$sql = "UPDATE pos.tblStaff SET $key='$value' WHERE sID=".$_GET['sID'];
			$q_array[]["query"] = $sql;
		}
		/*if(@mysql_query($sql)) {
			$count++;
		} else {
			mysql_query("ROLLBACK");
			//print $sql . "\n";
			//print (mysql_error()). "\n";
			print "WEBPOS_ERR(02251024)";
			exit();
		}*/
	}
}
//mysql_query("COMMIT");
//print "WEBPOS_MSG(02251024)";

//print_r($q_array);
if($database->transaction($q_array)) {
	print "WEBPOS_MSG(02251024)";
	exit();
} else {
	print "WEBPOS_ERR(02251024)";
	exit();
}
//print "1$count field(s) updated in tblStaff.\n";
?>