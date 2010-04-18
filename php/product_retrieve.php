<?php
ini_set("display_errors", 1);
error_reporting(E_NONE);
include("../backend/customer.php");
$pUPC = $_GET['pUPC'];
$pSPC = $_GET['pSPC'];

//print $cSID . "|" . $cID;

$sql = "SELECT tblProduct.pUPC,tblProduct.pSku as prodSku,tblInventory.$pSPC as spcPrice,tblInventory.iTax1 as tax1,tblInventory.iTax2 as tax2,tblProduct.pDescription as pDescription 
FROM pos.tblProduct,pos.tblInventory 
WHERE tblProduct.pUPC='$pUPC' AND tblProduct.pID=tblInventory.iProduct;";
//print $sql;
if($result = @mysql_query($sql)) {
	while($row = @mysql_fetch_assoc($result)) {
		$result = "";
		$result .= $row['pUPC'] . "|";
		$result .= $row['prodSku'] . "|";
		$result .= $row['spcPrice'] . "|";
		$result .= taxAmt($row['tax1']) . "|";
		$result .= taxAmt($row['tax2']) . "|";
		$result .= $row['pDescription'];
		
		print $result;
		return;
	}
} else {
	print $sql;
	print (mysql_error());
}

function taxAmt($tax) {
	if($tax == "1") {
		return 0.00;
	} else if($tax == "2") {
		return 0.08;
	} else if($tax == "3") {
		return 0.05;
	} else {
		return 0.00;
	}
}
?>