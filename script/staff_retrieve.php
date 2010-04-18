<?php
ini_set("display_errors", 1);
error_reporting(E_NONE);
include("../backend/staff.php");
$store=$_GET['sStoreID'];
$id=$_GET['sID'];
//print $cSID . "|" . $cID;
$list_info = array('storeID','sFullName','sEmail','sID',
	'sSCNum','sCID','sStaff','sLevel','sPassword',
	/*'sExpMM','sExp',*/'sStCredit','sHrRate','sDfHr',
	'sSchedule0','sSchedule1','sSchedule2',
	'sSchedule3','sSchedule4','sSchedule5',
	'sSchedule6','sIsActive');
$val = array("","DAY OFF","FRONT SHIFT 1","FRONT SHIFT 2","MIDDLE SHIFT 1","MIDDLE SHIFT 2","BACK SHIFT 1","BACK SHIFT 2","IT");

$sql = "SELECT tblStaff.sStoreID AS storeID,".
	"tblCustomer.cFirstN AS sFirstN,".
	"tblCustomer.cLAStN AS sLastN,".
	"tblCustomer.cEmail1 AS sEmail,".
	"tblStaff.sID AS sID,".
	"tblStaff.sCNumber AS sSCNum,".
	"tblStaff.sCustomer AS sCID,".
	"tblStaff.sStaff AS sStaff,".
	"tblStaff.sLevel AS sLevel,".
	"tblStaff.sPassword AS sPassword,".
//	"tblStaff.sPswdExpMM AS sExpMM,".
//	"tblStaff.sPswdExp AS sExp,".
	"tblStaff.sStoreCredit AS sStCredit,".
	"tblStaff.sHrRate AS sHrRate,".
	"tblStaff.sDfHr AS sDfHr,".
	"tblStaff.sSchedule0 AS sSchedule0,".
	"tblStaff.sSchedule1 AS sSchedule1,".
	"tblStaff.sSchedule2 AS sSchedule2,".
	"tblStaff.sSchedule3 AS sSchedule3,".
	"tblStaff.sSchedule4 AS sSchedule4,".
	"tblStaff.sSchedule5 AS sSchedule5,".
	"tblStaff.sSchedule6 AS sSchedule6,".
	"tblStaff.sIsActive AS sIsActive ".
	"FROM pos.tblStaff ".
	"INNER JOIN pos.tblCustomer ON tblStaff.sCustomer=tblCustomer.cID ".
	"WHERE tblStaff.sStoreID=$store ".
	"AND tblStaff.sID=$id;";

$sql2 = "SELECT tblStaff.sStoreID AS storeID,".
	"tblStaff.sStaff AS sFirstN,".
	"tblCustomer.cEmail1 AS sEmail,".
	"tblStaff.sID AS sID,".
	"tblStaff.sCNumber AS sSCNum,".
	"tblStaff.sCustomer AS sCID,".
	"tblStaff.sStaff AS sStaff,".
	"tblStaff.sLevel AS sLevel,".
	"tblStaff.sPassword AS sPassword,".
//	"tblStaff.sPswdExpMM AS sExpMM,".
//	"tblStaff.sPswdExp AS sExp,".
	"tblStaff.sStoreCredit AS sStCredit,".
	"tblStaff.sHrRate AS sHrRate,".
	"tblStaff.sDfHr AS sDfHr,".
	"tblStaff.sSchedule0 AS sSchedule0,".
	"tblStaff.sSchedule1 AS sSchedule1,".
	"tblStaff.sSchedule2 AS sSchedule2,".
	"tblStaff.sSchedule3 AS sSchedule3,".
	"tblStaff.sSchedule4 AS sSchedule4,".
	"tblStaff.sSchedule5 AS sSchedule5,".
	"tblStaff.sSchedule6 AS sSchedule6,".
	"tblStaff.sIsActive AS sIsActive ".
	"FROM pos.tblStaff ".
	"WHERE tblStaff.sStoreID=$store ".
	"AND tblStaff.sID=$id;";
//print $sql . "\n";
if($sql_result = @mysql_query($sql)) {
	if(mysql_num_rows($sql_result) == 0) {
		$sql_result = @mysql_query($sql2);
	}
	while($row = @mysql_fetch_assoc($sql_result)) {
		$result = "";
		foreach($list_info as $info) {
			if($info == "sIsActive") {
				$result .= $row[$info];
			} else if($info == "sFullName") {
				$result .= $row["sFirstN"] . "," . $row["sLastN"] . "|";
			} else if($info == "sLastN") {
				$result = $result;
			} else {
				$result .= $row[$info] . "|";
			}
		}
		//print "1|";
		print "1|".$result;
		return;
	}
} else {
	//print "0|";
	print "1|".$sql."\n";
	print "ERROR: ";
	print (mysql_error())."\n";
}
?>