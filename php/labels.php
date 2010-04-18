<?php
// @ error reporting setting  (  modify as needed )
ini_set("display_errors", 1);
error_reporting(E_NONE);
include("../backend/reports.php");
include("../backend/methods.php");

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

$today = getdate(time());

$cal_default = date("m/d/Y",$today[0]);
?>

<html>
<head>
<title>Labels</title>
<link href="../css/labels.css" rel="stylesheet" type="text/css" />
<link href="../css/datepicker.css" rel="stylesheet" type="text/css" />
<script src="../js/jquery.js" type="text/javascript"></script>
<script src="../js/date.js" type="text/javascript"></script>
<script src="../js/jquery.hotkeys.js" type="text/javascript"></script>
<script src="../js/jquery.datePicker.js" type="text/javascript"></script>
<script src="../js/general.js" type="text/javascript"></script>
<script src="../js/labels.js" type="text/javascript"></script>
<script type="text/javascript">
			$(document).keypress(function(e){
				if(e.keyCode==13){
					reloadCIframe();
				}
			});

			$(function() {
				Date.firstDayOfWeek = 0;
				Date.format = 'yyyy-mm-dd';
				$('.date-pick').datePicker({startDate:'1996-01-01'}).val(new Date().asString()).trigger('change');
			});

			function setDefPrint() {
				document.getElementById("lPrintOps").selectedIndex = 0;
			}
		</script>
</head>
<body onload="setDefPrint();">
<div id="debug"><textarea id="debugger"
	style="width: 380px; height: 400px; background: black; color: green;"></textarea>
</div>
<div id="menu">
<div id="signout"><?php print $_SESSION['exp_user']['sStaff']." @ ".get_store_name($_SESSION['exp_user']['localSID']);?>
&nbsp;&nbsp;&nbsp;&nbsp;[<a href="#" onclick="logout();">Logout</a>]
</div>
</div>
<div id="sidemenu">
<div id="tools"><img src="../images/menu_calculator.png"
	onclick="JavaScript:openCalculator();" title="Calculator"></img> <img
	src="../images/menu_sendmail.png"
	onclick="JavaScript:location.href='mailto:me@mydomain.com'"
	title="Email"></img></div>
<div id="logo"><img src="../images/CoM-vert.png"
	style="height: 185px; width: 35px;"></img></div>
</div>
<?php
if($_SESSION['exp_user']['usrlevel'] == "1") {
	print "<div id=\"menuitems\">";
	print "<table><tr>";
	print "<td><a href=\"#\" onclick=\"JavaScript:openTimesheet();\" title=\"Store Timesheet/Weekly Summary\"><img src=\"../images/menu_timesheet.png\" border=0></img> Timesheet</a></td>";
	print "<td><a href=\"#\" onclick=\"JavaScript:openProduct();\" title=\"Product List/Details\"><img src=\"../images/menu_product.png\" border=0></img> Product</a></td>";
	print "<td><a href=\"#\" onclick=\"JavaScript:openStaff();\" title=\"Staff List/Information\"><img src=\"../images/menu_staff.png\" border=0></img> Staff</a></td>";
	print "<td><a href=\"#\" onclick=\"JavaScript:openLeads();\" title=\"Leads List/Information\"><img src=\"../images/menu_leads.png\" border=0></img> Leads</a></td>";
	print "<td><a href=\"#\" onclick=\"JavaScript:openCustomer();\" title=\"Customer List/Information\"><img src=\"../images/menu_customer.png\" border=0></img> Customer</a></td>";
	print "<td><a href=\"#\" onclick=\"JavaScript:openRental();\" title=\"Store Rentals\"><img src=\"../images/menu_rental.png\" border=0></img> Rental</a></td>";
	print "<td><a href=\"#\" onclick=\"JavaScript:openReports();\" title=\"View Store Reports\"><img src=\"../images/menu_reports.png\" border=0></img> Reports</a></td>";
	print "<td><a href=\"#\" onclick=\"JavaScript:openScheduler();\" title=\"Schedule Staff Shift\"><img src=\"../images/menu_scheduler.png\"  border=0></img> Scheduler</a></td>";
	print "<td><a href=\"#\" onclick=\"JavaScript:openSeminar();\" title=\"Setup Seminar Sessions\"><img src=\"../images/menu_seminar.png\" border=0></img> Seminar</a></td>";
	print "<td><a href=\"#\" style=\"background:#F3F3F3;color:#2C2E22;cursor:pointer;\" title=\"Birthday Label Printing\"><img src=\"../images/menu_labels.png\" border=0></img> Labels</a></td>";
	print "</tr></table>";
	print "</div>";
} else if ($_SESSION['exp_user']['usrlevel'] == "5") {
	print "<div id=\"menuitems\">";
	print "<table><tr>";
	print "<td><a href=\"#\" onclick=\"JavaScript:openTimesheet();\" title=\"Store Timesheet/Weekly Summary\"><img src=\"../images/menu_timesheet.png\" border=0></img> Timesheet</a></td>";
	print "<td><a href=\"#\" onclick=\"JavaScript:openProduct();\" title=\"Product List/Details\"><img src=\"../images/menu_product.png\" border=0></img> Product</a></td>";
	print "<td><a href=\"#\" onclick=\"JavaScript:openLeads();\" title=\"Leads List/Information\"><img src=\"../images/menu_leads.png\" border=0></img> Leads</a></td>";
	print "<td><a href=\"#\" onclick=\"JavaScript:openCustomer();\" title=\"Customer List/Information\"><img src=\"../images/menu_customer.png\" border=0></img> Customer</a></td>";
	print "<td><a href=\"#\" onclick=\"JavaScript:openRental();\" title=\"Store Rentals\"><img src=\"../images/menu_rental.png\" border=0></img> Rental</a></td>";
	print "<td><a href=\"#\" onclick=\"JavaScript:openSeminar();\" title=\"Setup Seminar Sessions\"><img src=\"../images/menu_seminar.png\" border=0></img> Seminar</a></td>";
	print "</tr></table>";
	print "</div>";
} else {
	print "<div id=\"menuitems\">";
	print "<table><tr>";
	print "<td><a href=\"#\" onclick=\"JavaScript:openTimesheet();\" title=\"Store Timesheet/Weekly Summary\"><img src=\"../images/menu_timesheet.png\" border=0></img> Timesheet</a></td>";
	print "<td><a href=\"#\" onclick=\"JavaScript:openProduct();\" title=\"Product List/Details\"><img src=\"../images/menu_product.png\" border=0></img> Product</a></td>";
	print "<td><a href=\"#\" onclick=\"JavaScript:openStaff();\" title=\"Staff List/Information\"><img src=\"../images/menu_staff.png\" border=0></img> Staff</a></td>";
	print "<td><a href=\"#\" onclick=\"JavaScript:openLeads();\" title=\"Leads List/Information\"><img src=\"../images/menu_leads.png\" border=0></img> Leads</a></td>";
	print "<td><a href=\"#\" onclick=\"JavaScript:openCustomer();\" title=\"Customer List/Information\"><img src=\"../images/menu_customer.png\" border=0></img> Customer</a></td>";
	print "<td><a href=\"#\" onclick=\"JavaScript:openRental();\" title=\"Store Rentals\"><img src=\"../images/menu_rental.png\" border=0></img> Rental</a></td>";
	print "<td><a href=\"#\" onclick=\"JavaScript:openReports();\" title=\"View Store Reports\"><img src=\"../images/menu_reports.png\" border=0></img> Reports</a></td>";
	print "<td><a href=\"#\" onclick=\"JavaScript:openScheduler();\" title=\"Schedule Staff Shift\"><img src=\"../images/menu_scheduler.png\"  border=0></img> Scheduler</a></td>";
	print "<td><a href=\"#\" onclick=\"JavaScript:openSeminar();\" title=\"Setup Seminar Sessions\"><img src=\"../images/menu_seminar.png\" border=0></img> Seminar</a></td>";
	print "<td><a href=\"#\" style=\"background:#F3F3F3;color:#2C2E22;cursor:pointer;\" title=\"Birthday Label Printing\"><img src=\"../images/menu_labels.png\" border=0></img> Labels</a></td>";
	print "</tr></table>";
	print "</div>";
}
?>
<div id="main">
<!-- Birthday Selection -->
<div id="birthdayLabel">
<center>
<b style="font-size:14">Birthday Labels</b>
</center>
</div>
<div id="birthday">
<table><tr><td>
<table>
<tr><td>
<select name="lPMonth" size="12" style="width:100px;height:180px;">
	<option value="1">January</option>
	<option value="2">February</option>
	<option value="3">March</option>
	<option value="4">April</option>
	<option value="5">May</option>
	<option value="6">June</option>
	<option value="7">July</option>
	<option value="8">August</option>
	<option value="9">September</option>
	<option value="10">October</option>
	<option value="11">November</option>
	<option value="12">December</option>
</select>
</td></tr>
<tr><td>
<center>
<input type="button" value="Print Labels" name="birthday" 
	style="border:1px solid black;width:100px;" 
	onclick="return printLabelByMonth(this);"></input>
</center>
</td></tr>
</table>
</td><td>
<table><tr><td>
<input type="text" id="labSearchStr" 
onchange="return popLPN(this);" style="width:300px;"></input>
</td></tr>
<tr><td>
<select id="lPName" multiple size="11" style="width:300px;">
	<option value="1">Type filter in search field (name,company)</option>
</select>
</td></tr>
<tr><td>
<center>
<input type="button" value="Print Labels by Name" 
	style="border:1px solid black;width:145px;" 
	onclick="printLabelByName();"></input>
<input type="button" value="Select All Names" 
	style="border:1px solid black;width:145px;" 
	onclick="selectAllNames();"></input>
</center>
</td></tr>
</table>
</td></tr></table>
</div>

<!-- Purchase Selection -->
<div id="purchaseLabel">
<center>
<b style="font-size:14">Purchase Labels</b>
</center>
</div>
<div id="purchase">
<table><tr><td>
<table>
<tr><td>
<select name="lPMonth2" size="12" style="width:100px;height:180px;">
	<option value="7">7 Days</option>
	<option value="14">14 Days</option>
	<option value="21">21 Days</option>
	<option value="30">30 Days</option>
</select>
</td></tr>
<tr><td>
<center>
<input type="button" value="Print Labels" name="purchase" 
	style="border:1px solid black;width:100px;" 
	onclick="return printLabelByMonth(this);"></input>
</center>
</td></tr>
</table>
</td><td>
<table><tr><td>
<input type="text" id="labSearchStr2" 
onchange="return popLPN(this);" style="width:300px;"></input>
</td></tr>
<tr><td>
<select id="lPName2" size="11" style="width:300px;">
	<option value="1">Type filter in search field (name,company)</option>
</select>
</td></tr>
<tr><td>
<center>
<input type="button" value="Print Labels by Name" 
	style="border:1px solid black;width:145px;" 
	onclick="printLabelByName();"></input>
<input type="button" value="Select All Names" 
	style="border:1px solid black;width:145px;" 
	onclick="selectAllNames();"></input>
</center>
</td></tr>
</table>
</td></tr></table>
</div>

<!-- Label Selection -->
<div id="lTypeLabel">
<center>
<b style="font-size:14">Print Options</b>
</center>
</div>
<div id="lType">
<table>
<tr><td>
<select name="lPrintOps" size="8" style="width:250px;height:120px;" onclick="return showInfo(this);">
	<option value="5160">5160 (Default)</option>
	<option value="5161">5161</option>
	<option value="5162">5162</option>
	<option value="5163">5163</option>
	<option value="5164">5164</option>
	<option value="8600">8600</option>
	<option value="L7163">L7163</option>
</select>
</td></tr>
<tr><td>
<textarea id="printInfoBox" rows="2" cols="" style="width:250px;">Select on top to see details</textarea>
</td></tr>
</table>
</div>
</div>


<div id="footer">
<table>

</table>
</div>
</body>

</html>
