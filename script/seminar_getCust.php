<?php
include("../backend/seminar.php");
$cID=$_GET['cID'];

$result = "";
$sql = "SELECT tblCustomer.cLastN as lName,
	tblCustomer.cFirstN as fName,
	tblCustomer.cCoName as coName,
	tblCustomer.cID as cID,
	tblCustomer.cPhone1 as sPhone1,
	tblCustomer.cPhone2 as sPhone2
	FROM pos.tblCustomer 
	WHERE cCardNum=$cID;";
if($sql_result = @mysql_query($sql)) {
	while($row = @mysql_fetch_assoc($sql_result)) {
		$result = $row['lName'].", ".$row['fName']."|".$row['coName']."|".$row['cID']."|".$row['sPhone2']."|".$row['sPhone2'];
	}
	print $result;
} else {
	print mysql_error();
}
?>