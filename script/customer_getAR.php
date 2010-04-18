<?php 
ini_set("display_errors", 1);
error_reporting(E_NONE);
include("../backend/customer.php");
$cID = $_GET['cID'];
$printStr = "";
//$tFrame = $_GET['time'];

$datestr = getdate(time());
$today = date("Y-m-d",$datestr[0]);
$date = strtotime($today) + 24*60*60;

//print $date . " " .  $today;
$startDate = $date - (30*24*60*60);
$start = date("Y-m-d H:i:s",$startDate);
$end = date("Y-m-d H:i:s",$date);
$sql1 = "SELECT SUM(tblInvoice.iPayment) AS payment,SUM(tblInvoice.iActPay) AS actpay FROM pos.tblInvoice WHERE iCustID=$cID AND iPIF=0 AND iInvcDate BETWEEN '$start' AND '$end';";

$startDate = $date - (60*24*60*60);
$endDate = $date - (30*24*60*60);
$start = date("Y-m-d H:i:s",$startDate);
$end = date("Y-m-d H:i:s",$endDate);
$sql2 = "SELECT SUM(tblInvoice.iPayment) AS payment,SUM(tblInvoice.iActPay) AS actpay FROM pos.tblInvoice WHERE iCustID=$cID AND iPIF=0 AND iInvcDate BETWEEN '$start' AND '$end';";

$startDate = $date - (90*24*60*60);
$endDate = $date - (60*24*60*60);
$start = date("Y-m-d H:i:s",$startDate);
$end = date("Y-m-d H:i:s",$endDate);
$sql3 = "SELECT SUM(tblInvoice.iPayment) AS payment,SUM(tblInvoice.iActPay) AS actpay FROM pos.tblInvoice WHERE iCustID=$cID AND iPIF=0 AND iInvcDate BETWEEN '$start' AND '$end';";

$startDate = $date - (90*24*60*60);
$start = date("Y-m-d H:i:s",$startDate);
$sql4 = "SELECT SUM(tblInvoice.iPayment) AS payment,SUM(tblInvoice.iActPay) AS actpay FROM pos.tblInvoice WHERE iCustID=$cID AND iPIF=0 AND iInvcDate <= '$start';";

/*echo $sql1."\n";
echo $sql2."\n";
echo $sql3."\n";
echo $sql4."\n";*/

if($result = @mysql_query($sql1)) {
	if($row = @mysql_fetch_assoc($result)) {
		//print $sql ."\n";
		$printStr .= (float)$row['payment'] - (float)$row['actpay'];
		$printStr .= ",";
	}
} else {
	if(mysql_error() == "Query was empty") {
		//print $sql1 ."\n";
		$printStr .= "0.00,";
	} else {
		//print (mysql_error())."\n";
		$printStr .= "NA,";
	}
}
if($result = @mysql_query($sql2)) {
	if($row = @mysql_fetch_assoc($result)) {
		//print $sql ."\n";
		$printStr .= (float)$row['payment'] - (float)$row['actpay'];
		$printStr .= ",";
	}
} else {
	if(mysql_error() == "Query was empty") {
		//print $sql2 ."\n";
		$printStr .= "0.00,";
	} else {
		//print (mysql_error())."\n";
		$printStr .= "NA,";
	}
}
if($result = @mysql_query($sql3)) {
	if($row = @mysql_fetch_assoc($result)) {
		//print $sql ."\n";
		$printStr .= (float)$row['payment'] - (float)$row['actpay'];
		$printStr .= ",";
	}
} else {
	if(mysql_error() == "Query was empty") {
		//print $sql3 ."\n";
		$printStr .= "0.00,";
	} else {
		//print $sql3 ."\n";
		//print (mysql_error())."\n";
		$printStr .= "NA,";
	}
}
if($result = @mysql_query($sql4)) {
	if($row = @mysql_fetch_assoc($result)) {
		//print $sql ."\n";
		$printStr .= (float)$row['payment'] - (float)$row['actpay'];
	}
} else {
	if(mysql_error() == "Query was empty") {
		//print $sql4 ."\n";
		$printStr .= "0.00";
	} else {
		//print $sql4 ."\n";
		//print (mysql_error())."\n";
		$printStr .= "NA";
	}
} 
print $printStr;
?>