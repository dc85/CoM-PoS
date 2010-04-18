<?php
ini_set('display_errors', 1);

error_reporting(E_NONE);

include('../backend/mysqldb.php');

$database = new MySQLDB;
$qry_ary = array();

$datestr = getdate(time());
$modDate = date("Y-m-d H:i:s",$datestr[0]);

//var_dump($_POST);
//$amountStr = 0;
if(isset($_POST['repayItems']) && !empty($_POST['repayItems']) || 
	isset($_POST['amount']) && !empty($_POST['amount'])) {
	$invStr = $_POST['repayItems'];
	$amountStr = substr($_POST['amount'],1);
} else {
	print 'WEBPOS_ERR(02131845)';
	return;
}

$invNums = "";

//print $invStr."\n";
$invoices = explode(',',$invStr);

$amount = (float)$amountStr;
$count = 0;
//print "TOTAL:".$amount."\n";
while($amount > 0.00) {
//while($count < 5) {
	$items = (explode('$',$invoices[$count]));
	$invNum = $items[0];
	$invNums .= $items[0].";";
	$due = (float)$items[1];
	//print "DUE:"+$due."\n";
	if($amount >= $due) { 
		$qry_ary[] = array("query" => "UPDATE pos.tblInvoice SET iActPay=iActPay+$due,iPIF=1 WHERE iInvcNum='$invNum'");
		//print "UPDATE pos.tblInvoice SET iActPay=iActPay+$due,iPIF=1 WHERE iInvcNum='$invNum'\n";
	} else {
		$qry_ary[] = array("query" => "UPDATE pos.tblInvoice SET iActPay=iActPay+$due WHERE iInvcNum='$invNum'");
		//print "UPDATE pos.tblInvoice SET iActPay=iActPay+$due WHERE iInvcNum='$invNum'\n";
	}
	$amount = $amount - $due;
	//print "AMT:".$amount."\n";
	$count++;
}

if($_POST['repayOpt'] == "2") {
	if(isset($_POST['repayAmt1']) && !empty($_POST['repayMethod1'])) {
		$qry_ary[] = array("query" => "INSERT INTO pos.tblPay(iStoreID,pRcptN,pRcptNum,".
			"pInvcID,pCustID,iSevStaff1,pPType,pAmount,pAuthNum,pNote,pIsRepay,pPayDate,".
			"pCrtDate,pDelStatus) VALUES(".$_POST['inSID'].",null,'".$_POST['inSession']."M1".
			"',0,".$_POST['inCID'].",".$_POST['repayTeller'].",".$_POST['repayMethod1'].
			",".$_POST['repayAmt1'].",'".$_POST['repayAuth1']."','".$invNums."',1,'".
			$modDate."','".$modDate."',0);");
	}
	
	if(isset($_POST['repayAmt2']) && !empty($_POST['repayMethod2'])) {
		$qry_ary[] = array("query" => "INSERT INTO pos.tblPay(iStoreID,pRcptN,pRcptNum,".
			"pInvcID,pCustID,iSevStaff1,pPType,pAmount,pAuthNum,pNote,pIsRepay,pPayDate,".
			"pCrtDate,pDelStatus) VALUES(".$_POST['inSID'].",null,'".$_POST['inSession']."M2".
			"',0,".$_POST['inCID'].",".$_POST['repayTeller'].",".$_POST['repayMethod2'].
			",".$_POST['repayAmt2'].",'".$_POST['repayAuth2']."','".$invNums."',1,'".
			$modDate."','".$modDate."',0);");
	}
	
	if(isset($_POST['repayAmt3']) && !empty($_POST['repayMethod3'])) {
		$qry_ary[] = array("query" => "INSERT INTO pos.tblPay(iStoreID,pRcptN,pRcptNum,".
			"pInvcID,pCustID,iSevStaff1,pPType,pAmount,pAuthNum,pNote,pIsRepay,pPayDate,".
			"pCrtDate,pDelStatus) VALUES(".$_POST['inSID'].",null,'".$_POST['inSession']."M3".
			"',0,".$_POST['inCID'].",".$_POST['repayTeller'].",".$_POST['repayMethod3'].
			",".$_POST['repayAmt3'].",'".$_POST['repayAuth3']."','".$invNums."',1,'".
			$modDate."','".$modDate."',0);");
	}
} else {
	if(isset($_POST['repayAmt1']) && !empty($_POST['repayMethod1'])) {
		$qry_ary[] = array("query" => "INSERT INTO pos.tblPay(iStoreID,pRcptN,pRcptNum,".
			"pInvcID,pCustID,iSevStaff1,pPType,pAmount,pAuthNum,pNote,pIsRepay,pPayDate,".
			"pCrtDate,pDelStatus) VALUES(".$_POST['inSID'].",null,'".$_POST['inSession'].
			"',0,".$_POST['inCID'].",".$_POST['repayTeller'].",".$_POST['repayMethod1'].
			",".$_POST['repayAmt1'].",'".$_POST['repayAuth1']."','".$invNums."',1,'".
			$modDate."','".$modDate."',0);");
	}
}
//print "QUERIES:\n";
//print count($qry_ary);
//print_r($qry_ary);
if($database->transaction($qry_ary)) {
	print "WEBPOS_MSG(02131131)";
} else {
	print "WEBPOS_ERR(02131131)";
}
?>