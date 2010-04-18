<?php
ini_set("display_errors", 1);
error_reporting(E_ALL);

include("../backend/methods.php");
include("../backend/invoice.php");

//@ explicity start session  ( remove if needless )
session_start();

//@ if logoff
if(!empty($_GET['logoff'])) { $_SESSION = array(); }

//@ is authorized?
if(empty($_SESSION['exp_user']) || @$_SESSION['exp_user']['expires'] < time()) {
	header("location:../src/login.html");	//@ redirect
} else {
	$_SESSION['exp_user']['expires'] = time()+(30*60);	//@ renew 45 minutes
}

$datestr = getdate(time());
$today = date("Y-m-d H:i:s",$datestr[0]);
$var_list = array();
$pType = 0;
$pIF = 0;
//var_dump($_POST);
//print "\n";
mysql_query("BEGIN");
if(get_varaibles() > 9) {
	if(tblInvoice_insert()) {
		if($iID = tblInvoice_getID()) {
			if(tblTrans_insert($iID)) {
				
			} else {
				print "Something went wrong during the tblTrans insert.\n";
				return;
			}
			if(tblPay_insert($iID)) {
				tblCustomer_update();
				mysql_query("COMMIT");
				print "Invoicing Succeeded";
			} else {
				print "Something went wrong during the tblPay insert.\n";
				return;
			}
		} else {
			print "Something went wrong during the tblInvoice get ID.\n";
			return;
		}
	} else {
		print "Something went wrong during the tblInvoice insert.\n";
		return;
	}
} else {
	print "Not enough items posted.\n";
	var_dump($_POST);
}

function get_varaibles() {
	global $var_list,$pType,$pIF;
	$count = 0;
	$var_names = array("payOp","inSession","inCID","inSID",
		"inServer","inTeller","totalDue","pm1","pay1",
		"auth1","note1","pm2","pay2","auth2","note2","pm3","pay3",
		"auth3","note3","payPO","numPay","actPay");

	foreach($var_names as $name) {
		if(isset($_POST[$name])) {
			$var_list[$name] = $_POST[$name];
			$count++;
		} else {
			$var_list[$name] = "null";
		}
	}
	if($var_list['numPay'] == "1") {
		$pType = (int)($var_list['pm1']);
	} else {
		$pType = 0;
	}
	if((float)($var_list['totalDue']) == (float)($var_list['actPay'])) {
		$pIF = 1;
	} else {
		$pIF = 0;
	}
	return $count."\n";
	//print_r($var_list);
}

function tblCustomer_update() {
	global $var_list,$pType,$pIF,$today;
	$payType = array('pm1','pm2','pm3');
	print "Building customer update query...\n";
	foreach($payType as $pt) {
		if($var_list[$pt] == "1") {
			$tag = str_replace('pm','pay',$pt);
			$amt = $var_list[$tag];
			$sql = "UPDATE pos.tblCustomer SET tblCustomer.cBalance=tblCustomer.cBalance+$amt, ".
			"tblCustomer.cCBal=tblCustomer.cCBal-$amt ".
			"WHERE tblCustomer.cID=".$var_list['inCID'];
			//echo $sql;
			if(@mysql_query($sql)) {
				echo "tblCustomer UPDATE suceessful\n";
				return true;
			} else {
				mysql_query("ROLLBACK");
				print "SQL:".$sql . "\n";
				print mysql_error() . "\n";
				return false;
			}
		}
	}
}

function tblInvoice_insert() {
	global $var_list,$pType,$pIF,$today;
	print "Building invoice insert query...\n";
	$sql = "INSERT INTO pos.tblInvoice(iStoreID,iInvcNum,iCustID,".
		"iSevStaff1,iSevStaff2,iSevStaff3,iTotal,iPType,iPayment,".
		"iActPay,ipNote,iPIF,iPCode,iCrtDate,iInvcDate,iIsActive,".
		"iIsPending) VALUES(".$var_list["inSID"].",'".
		$var_list['inSession']."',".$var_list['inCID'].
		",".$var_list['inServer'].",".$var_list['inTeller'].
		",null,".$var_list['totalDue'].",".$pType.",".
		$var_list['totalDue'].",".$var_list['actPay'].
		",'".$var_list['payPO']."',".$pIF.",null,'".$today.
		"','".$today."',1,0);";
	if($result = @mysql_query($sql)) {
		//echo $row[0];
		print "tblInvoice entry successful...\n";
		return true;
	} else {
		mysql_query("ROLLBACK");
		print "SQL:".$sql . "\n";
		print mysql_error() . "\n";
		return false;
	}
}

function tblInvoice_getID() {
	global $var_list,$pType,$pIF,$today;
	print "Building invoice retrieve query...\n";
	$sql = "SELECT tblInvoice.iID AS iID FROM pos.tblInvoice WHERE tblInvoice.iCrtDate='$today'";
	if($result = @mysql_query($sql)) {
		$data = @mysql_fetch_assoc($result);
		$custID = $data["iID"];
		return $data["iID"];
	} else {
		mysql_query("ROLLBACK");
		print "Invoice ID SQL: ".$sql."\n";
		print mysql_error()."\n";
		return false;
	}
}

function tblTrans_insert($iID) {
	global $var_list,$pType,$pIF,$today;
	$sql = "SELECT * FROM pos.tblShoppingCart WHERE tblShoppingCart.scInvcNum='".$var_list['inSession']."'";
	if($result = @mysql_query($sql)) {
		$rc = @mysql_num_rows($result);
		$count = 0;
		print "$rc item(s) found in shopping cart...\n";
		while($row = @mysql_fetch_assoc($result)) {
			$insert_sql = "INSERT INTO pos.tblTrans(tInvcID,tCustID,tProdID,".
				"tIsFree,tQty,tPrice,tSPC,tTax1,tTax2,tEcoFee,tTotal,".
				"tTCost,tColour,tNote,tTint,tTintNote,tTrnsDate,tCrtDate,".
				"tStoreID) VALUES(".$iID.",".$row['scCID'].",'".$row['scPID'].
				"',".$row['scIsFree'].",".$row['scQty'].",".$row['scPrice'].
				",".$row['scSPC'].",".$row['scTax1'].",".$row['scTax2'].
				",".$row['scEcoFee'].",".$row['scTotal'].",".$row['scTCost'].
				",'".$row['scColour']."','".$row['scNote']."','".$row['scTint'].
				"','".$row['scTintNote']."','".$row['scTrnsDate'].
				"','".$row['scCrtDate']."',".$var_list["inSID"].");";
			if($result = @mysql_query($insert_sql)) {
				$count++;
			} else {
				mysql_query("ROLLBACK");
				print "tblTrans Insert SQL:".$insert_sql . "\n";
				print mysql_error() . "\n";
			}
		}
		print "$count item(s) inserted to tblTrans...\n";
		return true;
	} else {
		mysql_query("ROLLBACK");
		print "Shoppingcart search SQL: ".$sql."\n";
		print mysql_error()."\n";
		return false;
	}
}

function tblPay_insert($iID) {
	global $var_list,$pType,$pIF,$today;
	$num = (int)$var_list['numPay'];
	$count = 0;
	print "$num payments(s) need to be inserted to tblPay...\n";
	$pRctpN = str_replace("R","M",$var_list['inSession']);
	for($i=1;$i<=$num;$i++) {
		if($i == 1) {
			$pTag = array($var_list["pm1"],$var_list["pay1"],$var_list["auth1"],$var_list["note1"]);
		} else if($i == 2) {
			$pTag = array($var_list["pm2"],$var_list["pay2"],$var_list["auth2"],$var_list["note2"]);
		} else {
			$pTag = array($var_list["pm3"],$var_list["pay3"],$var_list["auth3"],$var_list["note3"]);
		}
		$pType = $var_list['inServer'];
		$insert_sql = "INSERT INTO pos.tblPay(iStoreID,pRcptN,pInvcID,pCustID,".
			"iSevStaff1,pPType,pAmount,pAuthNum,pNote,pIsRepay,pPayDate,".
			"pCrtDate,pDelStatus) VALUES(".$var_list["inSID"].",'".$pRctpN.
			"',".$iID.",".$var_list['inCID'].",".$var_list['inServer'].
			",".$pTag[0].",".$pTag[1].",".$pTag[2].",'".$pTag[3]."',0,'".
			$today."','".$today."',0);";
		if($result = @mysql_query($insert_sql)) {
			$count++;
		} else {
			mysql_query("ROLLBACK");
			print "tblTrans Insert SQL:".$insert_sql . "\n";
			print mysql_error() . "\n";
			return false;
		}
	}
	print "$count payments(s) inserted to tblPay...\n";
	return true;
}
?>