<?php
// @ error reporting setting  (  modify as needed )
ini_set("display_errors", 1);
error_reporting(E_ALL);
include("../backend/methods.php");
include("../backend/db_methods.php");

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
$week = date("W", $today[0]);
$month = date("m", $today[0]);
$year = date("Y", $today[0]);
?>

<html>
<head>
<title>Scheduler</title>
<link href="../css/scheduler.css" rel="stylesheet" type="text/css" />
<script src="../js/jquery.js" type="text/javascript"></script>
<script src="../js/menu.js" type="text/javascript"></script>
<script src="../js/jquery.hotkeys.js" type="text/javascript"></script>
<script src="../js/general.js" type="text/javascript"></script>
<script src="../js/scheduler.js" type="text/javascript"></script>
<script type="text/javascript">
	function winSize() {
		alert("height:" +document.body.clientHeight + " width:" + document.body.clientWidth);
	}
</script>
</head>
<body>
<div id="debug">
	<textarea id="debugger" style="width:100%;height:400px;background:black;color:green;"></textarea>
</div>
<div id="sidemenu">
<div id="tools"><img src="../images/menu_calculator.png"
	onclick="openCalculator();" title="Calculator"></img> <img
	src="../images/menu_sendmail.png"
	onclick="JavaScript:location.href='mailto:me@mydomain.com'"
	title="Email"></img></div>
<div id="logo"><img src="../images/CoM-vert.png"
	style="height: 185px; width: 35px;"></img></div>
</div>
<div id="menu">
<div id="signout"><?php print $_SESSION['exp_user']['sStaff']." @ ".get_store_name($_SESSION['exp_user']['localSID']);?>
&nbsp;&nbsp;&nbsp;&nbsp;[<a href="#" onclick="logout();">Logout</a>]
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
	print "<td><a href=\"#\" style=\"background:#F3F3F3;color:#2C2E22;cursor:pointer;\" title=\"Schedule Staff Shift\"><img src=\"../images/menu_scheduler.png\"  border=0></img> Scheduler</a></td>";
	print "<td><a href=\"#\" onclick=\"JavaScript:openSeminar();\" title=\"Setup Seminar Sessions\"><img src=\"../images/menu_seminar.png\" border=0></img> Seminar</a></td>";
	print "<td><a href=\"#\" onclick=\"JavaScript:openLabels();\" title=\"Birthday Label Printing\"><img src=\"../images/menu_labels.png\" border=0></img> Labels</a></td>";
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
	print "<td><a href=\"#\" style=\"background:#F3F3F3;color:#2C2E22;cursor:pointer;\" title=\"Schedule Staff Shift\"><img src=\"../images/menu_scheduler.png\"  border=0></img> Scheduler</a></td>";
	print "<td><a href=\"#\" onclick=\"JavaScript:openSeminar();\" title=\"Setup Seminar Sessions\"><img src=\"../images/menu_seminar.png\" border=0></img> Seminar</a></td>";
	print "<td><a href=\"#\" onclick=\"JavaScript:openLabels();\" title=\"Birthday Label Printing\"><img src=\"../images/menu_labels.png\" border=0></img> Labels</a></td>";
	print "</tr></table>";
	print "</div>";
}
?>
<div id="control">
<table>
	<tr>
		<td><input type="radio" id="111" name="length" value="byWeek" checked><b
			class="control">Weekly Schedule</b></td>
		<td><?php
		$week_list = array(1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,48,49,50,51,52);
		print make_controldropdown_list("Week","whichWeek",$week,$week_list,"Week of schedule to see");
		?></td>
		<td><?php
		$year_list = array("2008","2009","2010","2011");
		print make_controldropdown_list("Year","whichYear",$year,$year_list,"Year of schedule to see");
		?></td>
		<td><input type="radio" id="222" name="length" value="byMonth"><b
			class="control">Monthly Schedule</b></td>
		<td><?php 
		$monthn_list = array(1,2,3,4,5,6,7,8,9,10,11,12);
		print make_controldropdown_list("Month","whichMonth",$month,$monthn_list,"Month of schedule to see");
		?></td>
		<td><input type="button" name="custSubmit" value="See Schedule"
			onClick="reloadSIframe()"></input></td>
	</tr>
</table>
</div>
</div>
<div id="hiddenFrame"><iframe src="../php/scheduler_add.php"
	name="hiddenFrame" onLoad="hideQueryZone()" frameborder="0" WIDTH=100%
	HEIGHT=100%> </iframe></div>
<div id="main">
<div id="loadingZone">Loading...</div>
<div id="queryZone">Querying...</div>
<iframe src="../php/scheduler_print.php" name="tableFrame"
	onLoad="hideLoadingZone()" frameborder="0" WIDTH=100% HEIGHT=100%> </iframe>
</div>

<div id="footer">
<table>
	<tr>
		<td><a style="border: 0; padding-top: 1px; width: 103px;"
			class="footeritem" href="#" onClick="JavaScript:addSchedule();"
			title="Add the schedule to the system if not already done so"><img
			src="../images/menu_add.png" border=0></img>Add Schedule</a></td>
		<td><a style="border: 0; padding-top: 1px; width: 121px;"
			class="footeritem" href="#" onClick="JavaScript:updateSchedule();"
			title="Update the changes in the schedule"><img
			src="../images/menu_update.png" border=0></img>Update Schedule</a></td>
		<td><a style="border: 0; padding-top: 1px; width: 96px;"
			class="footeritem" href="#" onClick="JavaScript:printSchedule();"
			title="Generate the schedule in PDF"><img
			src="../images/menu_pdf.png" border=0></img>Preview PDF</a></td>
	</tr>
</table>
</div>

</body>

</html>
