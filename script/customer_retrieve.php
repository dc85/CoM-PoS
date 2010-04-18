<?php
ini_set("display_errors", 1);
error_reporting(E_NONE);
include("../backend/customer.php");
$cSID = $_GET['custSID'];
$cID = $_GET['custCID'];
//print $cSID . "|" . $cID;

$list_info = array("cIsActive","cTitle","cFirstN",
	"cLastN","cAKA","cDoB","cLic","cComNum","cType",
	"cCompany","cShirt","cPants","cExpert","cpType4","cPhone4",
	"cpType5","cPhone5","cE1","cE2","cE3","cE4","cE5","cE6",
	"cE7","cE8","cE9","cE10","cUnit1","cAdr1","cCity1",
	"cProv1","cZip1","cpType1","cPhone1","cEmail1","cUnit2",
	"cAdr2","cCity2","cProv2","cZip2","cpType2","cPhone2",
	"cEmail2","cUnit3","cAdr3","cCity3","cProv3",
	"cZip3","cpType3","cPhone3","cEmail3","cGST","cPST","cSPC",
	"cEcoFee","cCustRep","cExcempt","cExpires","cARCredit",
	"cARRemain","cAR",
	"cPromo","cNote");

$sql = "SELECT tblCustomer.cDelStatus as cIsActive,
tblCustomer.cTitle as cTitle,
tblCustomer.cFirstN as cFirstN,
tblCustomer.cLastN as cLastN,
tblCustomer.cAKA as cAKA,
tblCustomer.cDoB as cDoB,
tblCustomer.cRIN as cLic,
tblCustomer.cCardNum as cComNum,
tblCustomer.cCustType as cType,
tblCustomer.cCoName as cCompany,
tblCustomer.cShirtSize as cShirt,
tblCustomer.cPantSize as cPants,
tblCustomer.cExpert as cExpert,
tblCustomer.cpType4 as cpType4,
tblCustomer.cPhone4 as cPhone4,
tblCustomer.cpType5 as cpType5,
tblCustomer.cPhone5 as cPhone5,
tblCustomer.cE1 as cE1,
tblCustomer.cE2 as cE2,
tblCustomer.cE3 as cE3,
tblCustomer.cE4 as cE4,
tblCustomer.cE5 as cE5,
tblCustomer.cE6 as cE6,
tblCustomer.cE7 as cE7,
tblCustomer.cE8 as cE8,
tblCustomer.cE9 as cE9,
tblCustomer.cE10 as cE10,
tblCustomer.cUnitB as cUnit1,
tblCustomer.cAdrBus as cAdr1,
tblCustomer.cCityB as cCity1,
tblCustomer.cProvB as cProv1,
tblCustomer.cZipB as cZip1,
tblCustomer.cpType1 as cpType1,
tblCustomer.cPhone1 as cPhone1,
tblCustomer.cEmail1 as cEmail1,
tblCustomer.cUnitH as cUnit2,
tblCustomer.cAdrHome as cAdr2,
tblCustomer.cCityH as cCity2,
tblCustomer.cProvH as cProv2,
tblCustomer.cZipH as cZip2,
tblCustomer.cpType2 as cpType2,
tblCustomer.cPhone2 as cPhone2,
tblCustomer.cEmail2 as cEmail2,
tblCustomer.cUnitS as cUnit3,
tblCustomer.cAdrShip as cAdr3,
tblCustomer.cCityS as cCity3,
tblCustomer.cProvS as cProv3,
tblCustomer.cZipS as cZip3,
tblCustomer.cpType3 as cpType3,
tblCustomer.cPhone3 as cPhone3,
tblCustomer.cEmail3 as cEmail3,
tblCustomer.cTax1 as cGST,
tblCustomer.cTax2 as cPST,
tblCustomer.cSPC as cSPC,
tblCustomer.cEcoFee as cEcoFee,
tblCustomer.cCustRep as cCustRep,
tblCustomer.cExpNum as cExcempt,
tblCustomer.cExpDate as cExpires,
tblCustomer.cCredit as cARCredit,
tblCustomer.cCBal as cARRemain,
tblCustomer.cBalance as cAR,
tblCustomer.cNote as cPromo,
tblCustomer.cNote as cNote 
FROM pos.tblCustomer  
WHERE tblCustomer.cStoreID='$cSID' 
AND tblCustomer.cID='$cID';";
//print $sql;
if($result = @mysql_query($sql)) {
	//print $sql;
	//print_r ($result);
	while($row = @mysql_fetch_assoc($result)) {
		$line = "";
		foreach($list_info as $info) {
			if($info == "cNote") {
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
	//print $sql;
	print (mysql_error());
}
?>