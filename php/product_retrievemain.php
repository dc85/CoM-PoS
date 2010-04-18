<?php
ini_set("display_errors", 1);
error_reporting(E_NONE);
include("../backend/product.php");
$sid=$_GET['sid'];
$pid=$_GET['pid'];
//print $cSID . "|" . $cID;
/*$list_info = array('pStoreName','pSupplier','pName','pCatg',
	'pDesc','pUPC','pSku','pCode','pBSize',
	'pSize','pCSize','pCost','pMSRP','pTax1',
	'pTax2','pMaxS','pCurS','pMinS','pAlt');*/
$list_info = array('stoName','supName','prodName','prodCatg',
'prodDesc','prodUPC','prodSku','prodCode','pCSize','pBSize',
'pCost','pMSRP','pTax1','pTax2','pMinQty','pCurQty',
'pMaxQty','pPromo','ispc1','ispc2','ispc3','ispc4','ispc5'
,'ispc6','ispc7','ispc8','ispc9','ispc0','pPicture','pBPrice');
$val = array("","DAY OFF","FRONT SHIFT 1","FRONT SHIFT 2","MIDDLE SHIFT 1","MIDDLE SHIFT 2","BACK SHIFT 1","BACK SHIFT 2","IT");

$sql = "SELECT tblProduct.pSupplier as supName,
	tblProduct.pName as prodName,
	tblProduct.pCatType as prodCatg,
	tblProduct.pDescription as prodDesc,
	tblProduct.pUPC as prodUPC,
	tblProduct.pSku as prodSku,
	tblProduct.pProdCode as prodCode,
	tblProduct.pCSize as pCSize,
	tblProduct.pBSize as pBSize,
	tblInventory.iCost as pCost,
	tblInventory.iPrice as pMSRP,
	tblInventory.iTax1 as pTax1,
	tblInventory.iTax2 as pTax2,
	tblInventory.iMinQty as pMinQty, 
	tblInventory.iQty as pCurQty,
	tblInventory.iMaxQty as pMaxQty, 
	tblProduct.pPromo as pPromo,
	tblInventory.iSPC1 as ispc1,
	tblInventory.iSPC2 as ispc2,
	tblInventory.iSPC3 as ispc3,
	tblInventory.iSPC4 as ispc4,
	tblInventory.iSPC5 as ispc5,
	tblInventory.iSPC6 as ispc6,
	tblInventory.iSPC7 as ispc7,
	tblInventory.iSPC8 as ispc8,
	tblInventory.iSPC9 as ispc9,
	tblInventory.iSPC10 as ispc0,
	tblProduct.pPicture as pPicture,
	tblBSize.bPrice as pBPrice
	FROM pos.tblInventory 
	INNER JOIN pos.tblProduct ON tblProduct.pID=$pid  
	INNER JOIN pos.tblStore ON tblStore.sID=tblInventory.iStoreID 
	INNER JOIN pos.tblCatType ON tblCatType.ctID=tblProduct.pCatType 
	INNER JOIN pos.tblBSize ON tblBSize.bID=tblProduct.pBSize  
	WHERE tblInventory.iStoreID=$sid 
	AND tblInventory.iProduct=$pid;";
//print $sql;
if($result = @mysql_query($sql)) {
	//print $sql;
	//print_r ($result);
	while($row = @mysql_fetch_assoc($result)) {
		$line = "";
		foreach($list_info as $info) {
			$line .= $row[$info] . "|";
		}
		//print_r($row);
		//return;
	}
} else {
	print $sql;
	print (mysql_error());
}

$line .= "INVCOUNTSTARTHERE|"; 
$sql = "SELECT iQty FROM pos.tblInventory WHERE tblInventory.iProduct=$pid ORDER BY iStoreID ASC;";

if($result = @mysql_query($sql)) {
	//print $sql;
	//print_r ($result);
	while($row = @mysql_fetch_assoc($result)) {
		$line .= $row["iQty"] . "|";
	}
} else {
	print $sql;
	print (mysql_error());
}

print substr($line,0,strlen($line) - 1);
?>