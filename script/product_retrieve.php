<?php
ini_set("display_errors", 1);
error_reporting(E_NONE);
include("../backend/customer.php");
$pUPC = $_GET['pUPC'];
$pSPC = $_GET['pSPC'];
$pStore = $_GET['cStoreID'];
$locStore = getenv("pos");

//print $cSID . "|" . $cID;

$sql = "SELECT tblProduct.pUPC as pUPC,
	tblProduct.pSku AS prodSku,
	tblInventory.$pSPC AS spcPrice,
	tblInventory.iCost AS cost,
	tblInventory.iTax1 AS tax1,
	tblInventory.iTax2 AS tax2,
	tblProduct.pDescription AS pDescription, 
	tblProduct.pCSize AS pCSize 
	FROM pos.tblProduct
	INNER JOIN pos.tblInventory ON tblProduct.pID=tblInventory.iProduct  
	WHERE tblProduct.pUPC='$pUPC' 
	AND tblInventory.iStoreID=$pStore;";
//print $sql;
if($result = @mysql_query($sql)) {
	if(mysql_num_rows($result) == 0) {
		$sql = "SELECT tblProduct.pUPC as pUPC,
			tblProduct.pSku AS prodSku,
			tblInventory.$pSPC AS spcPrice,
			tblInventory.iCost AS cost,
			tblInventory.iTax1 AS tax1,
			tblInventory.iTax2 AS tax2,
			tblProduct.pDescription AS pDescription, 
			tblProduct.pCSize AS pCSize 
			FROM pos.tblProduct
			INNER JOIN pos.tblInventory ON tblProduct.pID=tblInventory.iProduct  
			WHERE tblProduct.pUPC='$pUPC' 
			AND tblInventory.iStoreID=$locStore;";
		$result = @mysql_query($sql);
	}
	while($row = @mysql_fetch_assoc($result)) {
		$result = "";
		$result .= $row['pUPC'] . "|";
		$result .= $row['prodSku'] . "|";
		$result .= $row['spcPrice'] . "|";
		$result .= $row['cost'] . "|";
		$result .= taxAmt($row['tax1']) . "|";
		$result .= taxAmt($row['tax2']) . "|";
		$result .= $row['pDescription'] . "|";
		$result .= $row['pCSize'];
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