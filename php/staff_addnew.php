<?php
//print var_dump($_POST);
ini_set("display_errors", 1);
error_reporting(E_NONE);
include("../backend/customer.php");

$d = getdate(time());
$date = date("Y-m-d");
$MM = (int)$_GET['nsPswdExpMM'];
$today = date("Y-m-d H:i:s",$d[0]);
$expDate = date("Y-m-d",strtotime(date("Y-m-d", strtotime($date)) . " +".$MM." month"));


$eleName = Array('nsStoreID','nsID','nsCustomer','nsCNumber','nsStaff',
'nsPassword','nsLevel','nsPswdExpMM','nsPswdExp','nsStoreCredit','nsHrRate',
'nsDfHr','nsSchedule0','nsSchedule1','nsSchedule2','nsSchedule3',
'nsSchedule4','nsSchedule5','nsSchedule6');
//$eleArray = var_dump($_GET);
//print_r($eleArray);

/*SELECT *,COUNT(0) FROM tbl WHERE GROUP BY name*/

$sql = "INSERT INTO pos.tblStaff(`sStoreID`,`sCNumber`,`sStaff`,`sPassword`,
`sLevel`,`sPswdExpMM`,`sPswdExp`,`sStoreCredit`,`sHrRate`,`sDfHr`,`sSchedule0`,
`sSchedule1`,`sSchedule2`,`sSchedule3`,`sSchedule4`,`sSchedule5`,`sSchedule6`,
`sCreated`,`sIsActive`)
	VALUES('".$_GET['nsStoreID']."','".$_GET['nsCNumber']."','".$_GET['nsStaff']."',
	'".$_GET['nsPassword']."','".$_GET['nsLevel']."','".$_GET['nsPswdExpMM']."',
	'$expDate','".$_GET['nsStoreCredit']."','".$_GET['nsHrRate']."',
	'".$_GET['nsDfHr']."','".$_GET['nsSchedule0']."','".$_GET['nsSchedule1']."',
	'".$_GET['nsSchedule2']."','".$_GET['nsSchedule3']."','".$_GET['nsSchedule4']."',
	'".$_GET['nsSchedule5']."','".$_GET['nsSchedule6']."','$today',1);";
$sql2 = "SELECT tblStaff.sID FROM pos.tblStaff WHERE tblStaff.sStoreID='".$_GET['nsStoreID']."'
AND tblStaff.sCNumber='".$_GET['nsCNumber']."';";
$storeName = get_store_name($_GET['nsStoreID']);
$sql3 = "INSERT INTO pos.tblCustomer(`cStoreID`,`cTitle`,`cFirstN`,`cLastN`,`cCustType`,`cCoName`)
	VALUES('".$_GET['nsStoreID']."','".$_GET['nsTitle']."','".$_GET['nsFirstN']."',
	'".$_GET['nsLastN']."',1,'$storeName');";
$sql4 = "SELECT tblCustomer.cID FROM pos.tblCustomer WHERE tblCustomer.cFirstN='".$_GET['nsFirstN']."'
AND tblCustomer.cLastN='".$_GET['nsLastN']."' AND tblCustomer.cStoreID='".$_GET['nsStoreID']."' 
AND tblCustomer.cCustType=1 AND tblCustomer.cCoName='".$storeName."';";

if($result = @mysql_query($sql)) {
	//print "sql successful\n";
	if($result2 = @mysql_query($sql2)) {
		//print "sql2 successful\n";
		if($result3 = @mysql_query($sql3)) {
			//print "sql3 successful\n";
			if($result4 = @mysql_query($sql4)) {
				print "Staff Inserted Successful.\n";
			} else {
				print (mysql_error())."\n";
			}
		} else {
			print (mysql_error())."\n";
		}
	} else {
		print (mysql_error())."\n";
	}
} else {
	print (mysql_error())."\n";
}
?>