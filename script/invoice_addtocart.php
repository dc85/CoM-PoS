<?php
include("../backend/methods.php");
include("../backend/invoice.php");

$scUPC = $_GET['upc'];
$scSPC = $_GET['spc'];
$scSku = $_GET['sku'];
$scQty = (int)$_GET['qty'];
$scPID = $_GET['selPID'];
$supplier = $_GET['supplier'];
$scUnitPrice = $_GET['unitPrice'];
$scUnitCost = $_GET['unitCost'];
//$scTotalPrice = $_GET['totalPrice'];
$sccTax1 = $_GET['tax1'];
$sccTax2 = $_GET['tax2'];
$scFree = $_GET['free'];
$scSNum = $_GET['sessionID'];
$scCID = $_GET['cID'];
$scProduct = $_GET['prod'];
$scDate = $_GET['date'];
$scColour = $_GET['colour'];
$scNote = $_GET['note'];
$scBrand = $_GET['brand'];
$scTint = $_GET['scTint'];
$scTintNote = $_GET['scTintNote'];
$scCSize = $_GET['csize'];
$scProdCode = $_GET['prodCode'];

if(true) {
	//print "ATTACK<br>";
	if($scFree == "true") {
		$scFree = "1";
	} else if($scFree == "false") {
		$scFree = "0";
	}
	//print "OPTION ENTER <br>";
	if($fb = addQty($scSNum,$scUPC,$scFree)) {
		//print "OPTION 1 <br>";
		//feed = feed + parseFloat(csize)*0.4;
		$Qty = $scQty + $fb;
		//print $Qty."\n";
		if((int)$Qty < 0) {
			print "-1";
			return;
		} else if((int)$Qty == 0) {
			$sql = "DELETE FROM pos.tblShoppingCart WHERE scInvcNum='$scSNum' AND scPUPC='$scUPC' AND scIsFree=$scFree;";
			//print "ADDTOCARTLESSTHANZERO";
		} else {
			$scTotalPrice = $Qty*$scUnitPrice;
			$tax1 = number_format($scTotalPrice*$sccTax1,2,'.','');
			$tax2 = number_format($scTotalPrice*$sccTax2,2,'.','');
			$cprice = number_format((float)$scCSize*0.4,2,'.','');
			$scTPrice = $scTotalPrice + $tax1 + $tax2 + $cprice;
			//print "Item exists: building query to update quantity...\n";
			$sql = "UPDATE pos.tblShoppingCart SET scQty=$Qty,scTotal=$scTPrice WHERE scInvcNum='$scSNum' AND scPUPC='$scUPC' AND scIsFree=$scFree;";
		}
	} else {
		//print "OPTION 2 <br>";
		$scTotalPrice = $scQty*$scUnitPrice;
		$tax1 = number_format($scTotalPrice*$sccTax1,2,'.','');
		$tax2 = number_format($scTotalPrice*$sccTax2,2,'.','');
		$cprice = number_format((float)$scCSize*0.4,2,'.','');
		$scTPrice = $scTotalPrice + $tax1 + $tax2 + $cprice;
		$pStr = "[".get_supplier($supplier)."] - ".$scUPC;
		//print "new item: building query to add item...\n";
		if(strlen($scBrand) > 1) {
		$sql = "INSERT INTO pos.tblShoppingCart(scInvcNum,scCID,".
			"scPID,scIsFree,scQty,scPrice,scSPC,scTax1,scTax2,scEcoFee,".
			"scTotal,scTCost,scColour,scNote,scTint,scTintNote,scTrnsDate,".
			"scCrtDate,scPDescription,scPUPC,scSku,scProdCode) ".
			"VALUES('$scSNum',$scCID,'$pStr',$scFree,$scQty,'$scUnitPrice',".
			"$scSPC,$sccTax1,$sccTax2,$cprice,$scTPrice,$scUnitCost,".
			"'$scColour','[$scBrand] - $scNote','$scTint','$scTintNote','$scDate',".
			"'$scDate','$scProduct','$scUPC','$scSku','$scProdCode');";
		} else {
		$sql = "INSERT INTO pos.tblShoppingCart(scInvcNum,scCID,".
			"scPID,scIsFree,scQty,scPrice,scSPC,scTax1,scTax2,scEcoFee,".
			"scTotal,scTCost,scColour,scNote,scTint,scTintNote,scTrnsDate,".
			"scCrtDate,scPDescription,scPUPC,scSku,scProdCode) ".
			"VALUES('$scSNum',$scCID,'$pStr',$scFree,$scQty,'$scUnitPrice',".
			"$scSPC,$sccTax1,$sccTax2,$cprice,$scTPrice,$scUnitCost,".
			"'$scColour','','$scTint','$scTintNote','$scDate',".
			"'$scDate','$scProduct','$scUPC','$scSku','$scProdCode');";	
		}
	}
	//print $writesql . "<BR>";
	if(@mysql_query($sql)) {
		print "1";
	} else {
		print "0";
		print $sql."\n";
		print mysql_error()."\n";
	}
	//$writer->preserve_error();
}

function addQty($ses,$upc,$free) {
	if(true) {
		$arg = "SELECT tblShoppingCart.scQty AS scQty FROM pos.tblShoppingCart WHERE scInvcNum='$ses' AND scPUPC='$upc' AND scIsFree=$free;";
		//print $arg."\n";
		$result = @mysql_query($arg);
		if($row = @mysql_fetch_assoc($result)) {
			//print $row['scQty']."\n";
			return $row['scQty'];
		} else {
			print (mysql_error());
			return false;
		}
	}
}
?>