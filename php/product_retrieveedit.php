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
$list_info = array('edit_pStore','edit_pSupplier','edit_pName',
			'edit_pCategory','edit_pDescription','edit_pUPC','edit_pSku',
			'edit_pCode','edit_pBSize','edit_pCSize','edit_pCost',
			'edit_pMSRP','edit_pTax1','edit_pTax2','edit_pMaxQty','edit_pMinQty',
			'edit_pCurQty','edit_pBPrice');

/*$sql = "SELECT tblStaff.sStoreID as sStoreID,tblStore.sName as sName,tblStaff.sID as sID,tblStaff.sCNumber as sCNumber,
	tblStaff.sStaff as sStaff,tblStaff.sCreated as sCreated,
	tblStaff.sSchedule0 as sSchedule0,tblStaff.sSchedule1 as sSchedule1,tblStaff.sSchedule2 as sSchedule2,
	tblStaff.sSchedule3 as sSchedule3,tblStaff.sSchedule4 as sSchedule4,tblStaff.sSchedule5 as sSchedule5,
	tblStaff.sSchedule6 as sSchedule6,tblStaff.sIsActive as sIsActive 
	FROM pos.tblStaff,pos.tblStore
	WHERE tblStaff.sStoreID='$store' AND tblStaff.sID='$id' AND tblStore.sID=tblStaff.sStoreID;";*/
$sql = "SELECT tblInventory.iStoreID as edit_pStore,
	tblSupplier.sID as edit_pSupplier,
	tblProduct.pName as edit_pName,
	tblCatType.ctID as edit_pCategory,
	tblProduct.pDescription as edit_pDescription,
	tblProduct.pUPC as edit_pUPC,
	tblProduct.pSku as edit_pSku,
	tblProduct.pProdCode as edit_pCode,
	tblProduct.pBSize as edit_pBSize,
	tblProduct.pCSize as edit_pCSize,
	tblInventory.iCost as edit_pCost,
	tblInventory.iPrice as edit_pMSRP,
	tblInventory.iTax1 as edit_pTax1,
	tblInventory.iTax2 as edit_pTax2,
	tblInventory.iMaxQty as edit_pMaxQty,
	tblInventory.iMinQty as edit_pMinQty,
	tblInventory.iQty as edit_pCurQty, 
	tblBSize.bPrice as edit_pBPrice
	FROM pos.tblInventory 
	INNER JOIN pos.tblProduct ON tblProduct.pID=$pid 
	INNER JOIN pos.tblSupplier ON tblSupplier.sID=tblProduct.pSupplier  
	INNER JOIN pos.tblStore ON tblStore.sID=$sid 
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
			if($info == "edit_pBPrice") {
				$line .= $row[$info];
			} else {
				$line .= $row[$info] . "|";
			}
		}
		print $line;
		//print_r($row);
		//return;
	}
} else {
	print $sql;
	print (mysql_error());
}
?>