<?php
$str = $_GET['str'];
$sID = $_GET['ecStoreID'];
$cID = $_GET['ecID'];
$intvars = array("cTitle","cCustType","cExpert",
"cE1","cE2","cE3","cE4","cE5","cE6","cE7","cE8","cE9","cE10",
"cpType1","cpType2","cpType3","cpType4","cpType5","cSPC",
"cTax1","cTax2","cEcoFee","cCustRep","cCredit","cCBal","cBalance","cDND");
$shirt_list = array(" ","S","M","L","XL");
$pants_list = array(" ","28X30","30X28","30X30","30X32","32X34","34X34","36X32","38X32","42X32");
$infoPairs = explode("|",$str);
print_r($infoPairs);
foreach($infoPairs as $info) {
	$pair = explode("@",$info);
	$col = "tblCustomer.".substr($pair[0],1);
	if($col == "cShirtSize") {
		$dex = (int)$col;
		$pair[1] = $shirt_list[$dex];
	}
	if($col == "cPantSize") {
		$dex = (int)$col;
		$pair[1] = $pants_list[$dex];
	}
	if(in_array(substr($pair[0],1),$intvars)) {
		$sql = "UPDATE pos.tblCustomer SET $col=$pair[1] WHERE cStoreID=$sID AND cID=$cID;";	
	} else {
		$sql = "UPDATE pos.tblCustomer SET $col='$pair[1]' WHERE cStoreID=$sID AND cID=$cID;";
	}
	
	print $sql . "<br>";
}
?>