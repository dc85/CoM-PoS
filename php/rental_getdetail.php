<?php
ini_set("display_errors", 1);
error_reporting(E_NONE);
include("../backend/rental.php");
$list_info = array("rID","rInvoice","rStart","rEnd",
			"rUPC","rPDescription","rPType","rRenter","rLastN",
			"rFirstN","rPhone","rDepositID","rDepositIDType","rDepositAmt",
			"rDepositType","rStaff","rStore","rStatus");
$id = $_GET['id'];
$sql = "SELECT tblRental.rID as rID,
	tblRental.rInvoice as rInvoice,
	tblRental.rStart as rStart,
	tblRental.rEnd as rEnd,
	tblRental.rUPC as rUPC,
	tblRental.rPType as rPType,
	tblRental.rPDescription as rPDescription,
	tblRental.rCID as rRenter,
	tblRental.rLastN as rLastN,
	tblRental.rFirstN as rFirstN,
	tblRental.rPhone as rPhone,
	tblRental.rDID as rDepositID,
	tblRental.rDIDType as rDepositIDType,
	tblRental.rDAmt as rDepositAmt,
	tblRental.rDType as rDepositType,
	tblRental.rStaff as rStaff,
	tblRental.rStore as rStore,
	tblRental.rIsReturned as rStatus 
	FROM pos.tblRental 
	WHERE tblRental.rID=$id;";
/*$itemList = array('rentalID','rentInvoice','rentStart','rentEnd','rentItemID','rentItem','rentItemType',
'rentID','rentLName','rentFName','rentDepositID','rentDepositIDType','rentDepositAmt','rentDepositType',
'rentStaff','rentStore','rentReturned');*/

if($sql_result = @mysql_query($sql)) {
	while($row = @mysql_fetch_assoc($sql_result)) {
		$result = "";
		foreach($list_info as $info) {
			if($info == "rStatus") {
				$result .= $row[$info];
			} else {
				$result .= $row[$info] . "|";
			}
		}
		//print $sql;
		print $result;
		return;
	}
} else {
	print $sql;
	print (mysql_error());
}

/*if($result = @mysql_query($sql)) {
	while($row = @mysql_fetch_assoc($result)) {
		$result = "";
		foreach($itemList as $item) {
			$result .= $row[$item] . "|";			
		}
		print substr($result,0,strlen($result) - 1);
		return;
	}
} else {
	print $sql;
	print (mysql_error());
}*/
?>