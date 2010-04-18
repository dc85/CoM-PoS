<?php
ini_set("display_errors", 1);
error_reporting(E_NONE);
include("../backend/customer.php");

$d = getdate(time());
$today = date("Y-m-d H:i:s",$d[0]); 

$eleName = array("cTitle","cFirstN","cLastN",
			"cAKA","cDoB","cRIN","cCardNum","cCustType","cCoName",
			"cShirtSize","cPantSize","cExpert","cpType4","cPhone4",
			"cpType5","cPhone5","cE1","cE2","cE3","cE4","cE5","cE6",
			"cE7","cE8","cE9","cE10","cUnitB","cAdrBus","cCityB","cProvB",
			"cZipB","cpType1","cPhone1","cEmail1","cUnitH","cAdrHome",
			"cCityH","cProvH","cZipH","cpType2","cPhone2","cEmail2",
			"cUnitS","cAdrShip","cCityS","cProvS","cZipS","cpType3",
			"cPhone3","cEmail3","cTax1","cTax2","cSPC","cEcoFee",
			"cCustRep","cExpNum","cExpDate","cCredit");
//$eleArray = var_dump($_POST);
//print_r($eleArray);

$sql = "INSERT INTO pos.tblCustomer(`cStoreID`,`cTitle`,`cFirstN`,`cLastN`,`cAKA`,`cDoB`,`cCustType`,`cExpert`,`cE1`,
	`cE2`,`cE3`,`cE4`,`cE5`,`cE6`,`cE7`,`cE8`,`cE9`,`cE10`,`cCoName`,`cUnitB`,`cAdrBus`,`cCityB`,`cProvB`,`cZipB`,
	`cUnitH`,`cAdrHome`,`cCityH`,`cProvH`,`cZipH`,`cUnitS`,`cAdrShip`,`cCityS`,`cProvS`,`cZipS`,`cpType1`,`cPhone1`,
	`cpType2`,`cPhone2`,`cpType3`,`cPhone3`,`cpType4`,`cPhone4`,`cpType5`,`cPhone5`,`cEmail1`,`cEmail2`,`cEmail3`,`cRIN`,
	`cDL`,`cCardNum`,`cSPC`,`cTax1`,`cTax2`,`cEcoFee`,`cExpNum`,`cExpDate`,`cCustRep`,`cShirtSize`,`cPantSize`,`cNote`,
	`cAware`,`cCredit`,`cCBal`,`cBalance`,`cModBy`,`cModDate`,`cCrtDate`,`cIsActive`,`cDND`)
	VALUES('1','".$_POST['cTitle']."','".$_POST['cFirstN']."',
	'".$_POST['cLastN']."','".$_POST['cAKA']."','".$_POST['cDoB']."',
	'".$_POST['cCustType']."','".$_POST['cExpert']."','".$_POST['cE1']."',
	'".$_POST['cE2']."','".$_POST['cE3']."','".$_POST['cE4']."',
	'".$_POST['cE5']."','".$_POST['cE6']."','".$_POST['cE7']."',
	'".$_POST['cE8']."','".$_POST['cE9']."','".$_POST['cE10']."',
	'".$_POST['cCoName']."','".$_POST['cUnitB']."','".$_POST['cAdrBus']."',
	'".$_POST['cCityB']."','".$_POST['cProvB']."','".$_POST['cZipB']."',
	'".$_POST['cUnitH']."','".$_POST['cAdrHome']."','".$_POST['cCityH']."',
	'".$_POST['cProvH']."','".$_POST['cZipH']."','".$_POST['cUnitS']."',
	'".$_POST['cAdrShip']."','".$_POST['cCityS']."','".$_POST['cProvS']."',
	'".$_POST['cZipS']."','".$_POST['cpType1']."','".$_POST['cPhone1']."',
	'".$_POST['cpType2']."','".$_POST['cPhone2']."','".$_POST['cpType3']."',
	'".$_POST['cPhone3']."','".$_POST['cpType4']."','".$_POST['cPhone4']."',
	'".$_POST['cpType5']."','".$_POST['cPhone5']."','".$_POST['cEmail1']."',
	'".$_POST['cEmail2']."','".$_POST['cEmail3']."','".$_POST['cRIN']."',
	'',
	'".$_POST['cCardNum']."','".$_POST['sSPC']."','','','','','','','','','','','','','','','$today','$today','1','0'
	);";

	
	if($result = @mysql_query($sql)) {
		//echo $row[0];
		print "Data for new customer added";
		//echo "Is THERE";
		//echo $row[1];
	} else {
		print (mysql_error());
	}
?>