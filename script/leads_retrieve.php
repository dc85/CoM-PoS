<?php
ini_set("display_errors", 1);
error_reporting(E_NONE);
include("../backend/customer.php");
$cSID = $_GET['custStoreID'];
$cID = $_GET['custID'];
//print $cSID . "|" . $cID;

$list_info = array("cTitle","cLastN",
	"cFirstN","cAKA","cDoB","cLic","cComNum","cType",
	"cCompany","cShirt","cPants","cExpert","cpType5","cPhone4",
	"cpType5","cPhone5","cUnit1","cAdr1","cCity1",
	"cProv1","cZip1","cpType1","cPhone1","cEmail1","cUnit2",
	"cAdr2","cCity2","cProv2","cZip2","cpType2","cPhone2",
	"cEmail2","cUnit3","cAdr3","cCity3","cProv3",
	"cZip3","cpType3","cPhone3","cEmail3","cDND",
	"cLastCont","cContCode","cContType","cContStaff");

$sql = "SELECT tblCustomer.cTitle as cTitle,
tblCustomer.cLastN as cLastN,
tblCustomer.cFirstN as cFirstN,
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
tblCustomer.cDND as cDND,
tblCustomer.cLastCont as cLastCont,
tblCustomer.cContCode as cContCode,
tblCustomer.cContType as cContType, 
tblCustomer.cContStaff as cContStaff
FROM pos.tblCustomer 
INNER JOIN pos.tbleType ON tbleType.eID=tblCustomer.cExpert  
WHERE tblCustomer.cStoreID='$cSID' 
AND tblCustomer.cID='$cID';";
//print $sql;
if($result = @mysql_query($sql)) {
	//print $sql;
	//print_r ($result);
	while($row = @mysql_fetch_assoc($result)) {
		$line = "";
		foreach($list_info as $info) {
			if($info == "cLastType") {
				$line .= $row[$info];
			} else if($info == "cDND") {
				if(empty($row[$info])) {
					$line .= "NA|";
				} else {
					$line .= $row[$info] . "|";
				}
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