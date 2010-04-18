<?php
ini_set("display_errors", 1);
error_reporting(E_NONE);
include("../backend/customer.php");

$d = getdate(time());
$today = date("Y-m-d H:i:s",$d[0]);
//print "TIMESTAMP: ".$today ."...\n";

$eleNames = array("cTitle","cFirstN","cLastN",
			"cAKA","cDoB","cRIN","cCardNum","cCustType","cCoName",
			"cShirtSize","cPantSize","cExpert","cpType4","cPhone4",
			"cpType5","cPhone5","cE1","cE2","cE3","cE4","cE5","cE6",
			"cE7","cE8","cE9","cE10","cUnitB","cAdrBus","cCityB","cProvB",
			"cZipB","cpType1","cPhone1","cEmail1","cUnitH","cAdrHome",
			"cCityH","cProvH","cZipH","cpType2","cPhone2","cEmail2",
			"cUnitS","cAdrShip","cCityS","cProvS","cZipS","cpType3",
			"cPhone3","cEmail3","cTax1","cTax2","cSPC","cEcoFee",
			"cCustRep","cExpNum","cExpDate","cCredit");
$varchars = array("cFirstN","cLastN","cAKA","cDoB","cCoName","cUnitB",
			"cAdrBus","cCityB","cZipB","cUnitH","cAdrHome","cCityH",
			"cZipH","cUnitS","cAdrShip","cCityS","cZipS","cPhone1",
			"cPhone2","cPhone3","cPhone4","cPhone5","cEmail1","cEmail2",
			"cEmail3","cRIN","cDL","cCardNum","cExpNum","cExpDate",
			"cPantSize","cNote","cAware","cModDate","cCrtDate");
$expert = array("cE1","cE2","cE3","cE4","cE5","cE6","cE7","cE8","cE9",
			"cE10","cTax1","cTax2","cEcoFee");
//$eleArray = var_dump($_POST);
//print_r($eleArray);
//print "Building Query...\n";
$sql = "INSERT INTO pos.tblCustomer(`cTitle`,`cFirstN`,
`cLastN`,`cAKA`,`cDoB`,`cRIN`,`cCardNum`,`cCustType`,`cCoName`,
`cShirtSize`,`cPantSize`,`cExpert`,`cpType4`,`cPhone4`,`cpType5`,
`cPhone5`,`cE1`,`cE2`,`cE3`,`cE4`,`cE5`,`cE6`,`cE7`,`cE8`,`cE9`,`cE10`,
`cUnitB`,`cAdrBus`,`cCityB`,`cProvB`,`cZipB`,`cpType1`,`cPhone1`,`cEmail1`,
`cUnitH`,`cAdrHome`,`cCityH`,`cProvH`,`cZipH`,`cpType2`,`cPhone2`,`cEmail2`,
`cUnitS`,`cAdrShip`,`cCityS`,`cProvS`,`cZipS`,`cpType3`,`cPhone3`,`cEmail3`,
`cTax1`,`cTax2`,`cSPC`,`cEcoFee`,`cCustRep`,`cExpNum`,`cExpDate`,`cCredit`,
`cNote`,`cAware`,`cCBal`,`cBalance`,`cModBy`,`cModDate`,`cCrtDate`,`cDelStatus`,`cDND`)	VALUES(";
foreach($eleNames as $eleName) {
	if(in_array($eleName,$varchars)) {
		if(!empty($_POST[$eleName])) {
			if($eleName == "cExpDate") {
				$eTime = strtotime($today);
				if($_POST[$eleName] == "3") {
					$eDate = date("Y-m-d H:i:s",strtotime('+1 year',$eTime));
				} else if($_POST[$eleName] == "2") {
					$eDate = date("Y-m-d H:i:s",strtotime('+6 months',$eTime));
				} else if($_POST[$eleName] == "1") {
					$eDate = date("Y-m-d H:i:s",strtotime('+1 month',$eTime));
				}
				$sql .= "'".$eDate."',";
			} else {
				$sql .= "'".$_POST[$eleName]."',";
			}
		} else {
			$sql .= "null,";
		}
	} else {
		if(!empty($_POST[$eleName])) {
			$sql .= $_POST[$eleName].",";
		} else {
			if(in_array($eleName,$expert)) {
				$sql .= "0,";
			} else {
				$sql .= "null,";
			}
		}
	}
}

//$sql = substr($sql,0,-1);
$sql .= "'','',null,null,null,'$today','$today',0,0);";
//print $sql;

//print "mysql BEGIN...\n";
@mysql_query("BEGIN");
//print "attempting to insert...\n";
if($result = @mysql_query($sql)) {
	@mysql_query("COMMIT");
	print "WEBPOS_MSG(02201403)";	
} else {
	@mysql_query("ROLLBACK");
	print "WEBPOS_ERR(02201403)";
}
/*if($result = @mysql_query($sql)) {
	//echo $row[0];
	print "New customer added...\n";
	//echo "Is THERE";
	//echo $row[1];
} else {
	print "SQL:".$sql . "\n";
	print mysql_error() . "\n";
}*/
?>