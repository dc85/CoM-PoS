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
<title>Reports</title>
<link href="../css/reports.css" rel="stylesheet" type="text/css" />
<link href="../css/datepicker.css" rel="stylesheet" type="text/css" />
<script src="../js/jquery.js" type="text/javascript"></script>
<script src="../js/general.js" type="text/javascript"></script>
<script src="../js/date.js" type="text/javascript"></script>
<script src="../js/jquery.bgiframe.min.js" type="text/javascript"></script>
<script src="../js/jquery.datePicker.js" type="text/javascript"></script>
<script src="../js/reports.js" type="text/javascript"></script>
<script type="text/javascript" charset="utf-8">
			$(function()
            {
				Date.firstDayOfWeek = 0;
				Date.format = 'yyyy-mm-dd';
				$('.date-pick').datePicker({startDate:'1996-01-01'})
				
				$('#start-date').bind(
					'dpClosed',
					function(e, selectedDates)
					{
						var d = selectedDates[0];
						if (d) {
							d = new Date(d);
							$('#end-date').dpSetStartDate(d.addDays(1).asString());
						}
					}
				);
				$('#end-date').bind(
					'dpClosed',
					function(e, selectedDates)
					{
						var d = selectedDates[0];
						if (d) {
							d = new Date(d);
							$('#start-date').dpSetEndDate(d.addDays(-1).asString());
						}
					}
				);
            });
		</script>

</head>
<body>
<div id="menu">
<div id="signout"><?php print $_SESSION['exp_user']['sStaff']." @ ".get_store_name($_SESSION['exp_user']['localSID']);?>
&nbsp;&nbsp;&nbsp;&nbsp;[<a href="#" onclick="logout();">Logout</a>]
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
	print "<td><a href=\"#\" style=\"background:#F3F3F3;color:#2C2E22;cursor:pointer;\" title=\"View Store Reports\"><img src=\"../images/menu_reports.png\" border=0></img> Reports</a></td>";
	print "<td><a href=\"#\" onclick=\"JavaScript:openScheduler();\" title=\"Schedule Staff Shift\"><img src=\"../images/menu_scheduler.png\"  border=0></img> Scheduler</a></td>";
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
	print "<td><a href=\"#\" style=\"background:#F3F3F3;color:#2C2E22;cursor:pointer;\" title=\"View Store Reports\"><img src=\"../images/menu_reports.png\" border=0></img> Reports</a></td>";
	print "<td><a href=\"#\" onclick=\"JavaScript:openScheduler();\" title=\"Schedule Staff Shift\"><img src=\"../images/menu_scheduler.png\"  border=0></img> Scheduler</a></td>";
	print "<td><a href=\"#\" onclick=\"JavaScript:openSeminar();\" title=\"Setup Seminar Sessions\"><img src=\"../images/menu_seminar.png\" border=0></img> Seminar</a></td>";
	print "<td><a href=\"#\" onclick=\"JavaScript:openLabels();\" title=\"Birthday Label Printing\"><img src=\"../images/menu_labels.png\" border=0></img> Labels</a></td>";
	print "</tr></table>";
	print "</div>";
}
?>
<div id="control"><input type="text" name="staffSearch" id="staffSearch"></input>
<input type="button" name="staffSubmit" value="Search Staff"
	onClick="JavaScript:reloadStaffIframe();"></input> <input type="hidden"
	id="pageSID" value="-1"></input> <input type="hidden" id="pageSSID"
	value="-1"></input></div>
</div>

<div id="main">
<div id="selector">
<h2 style="font-size: 20px; text-align: center;">Report Type</h2>
<table>
	<tr>
		<td><a style="font-size: 16px; font-weight: bold;" class="footeritem"
			href="#" onClick="JavaScript: showSalesReport();"
			title="Checkout all items and open up the payment window"><img
			src="../images/report_sales.png" border=0></img>Sales Report</a></td>
	</tr>
	<tr>
		<td><a style="font-size: 16px; font-weight: bold;" class="footeritem"
			href="#" onClick="JavaScript: showEmployeeReport();"
			title="Checkout all items and open up the payment window"><img
			src="../images/report_timesheet.png" border=0></img>Employee Report</a></td>
	</tr>
</table>
</div>
<div id="spec">
<div id="default">
<center>
<p style="font-weight: bold;">SELECT REPORT</p>
</center>
</div>
<div id="sales_report">
<div id="sales_report_control"><b>List By: </b> <select
	name="salesReportType" size="1">
	<option value="srt0"></option>
	<option value="srt1">By Store</option>
	<option value="srt2">By Employee</option>
	<option value="srt3">By Product</option>
	<option value="srt4">By Product Type</option>
</select> <label style="font-weight: bold;" for="start-date">&nbsp;&nbsp;&nbsp;Start
date:</label> <input name="start-date" id="start-date" class="date-pick" />

<label style="font-weight: bold;" for="end-date">&nbsp;&nbsp;&nbsp;End
date:</label> <input name="end-date" id="end-date" class="date-pick" />

<input type="button" value="Show Report"
	onClick="JavaScript: loadReport();"></input></div>
<div id="sales_report_table"><iframe src="../php/reports_print.php"
	name="tableFrame" frameborder="0" WIDTH=100% HEIGHT=100%> </iframe></div>
</div>
<div id="employee_report">
<div id="employee_report_control"><b>List By: </b> <select
	name="employeeReportType" size="1">
	<option value="srt0"></option>
	<option value="srt1">Timesheet</option>
	<option value="srt2">Sales</option>
	<option value="srt3">Activity</option>
</select> <label style="font-weight: bold;" for="start-date">&nbsp;&nbsp;&nbsp;Date:</label>
<input name="start-date" id="employee_date" class="date-pick" /> <input
	type="button" value="Show Report"
	onClick="JavaScript: loadEmployeeReport();"></input></div>
<div id="employee_report_table"><iframe
	src="../php/reports_employee_print.php" name="employeeTableFrame"
	frameborder="0" WIDTH=100% HEIGHT=100%> </iframe></div>
</div>
</div>
</div>

<div id="footer">
<table>
	<tr>
		<!-- <td><a style="border: 0; padding-top: 1px; width:76px;" class="footeritem" href="#" onClick="JavaScript:showAddStaffPopup();" title="Add a new staff into the database"><img src="../images/menu_addnewstaff.png"  border=0></img>Add Staff</a></td>
				<td><a style="border: 0; padding-top: 1px; width:76px;" class="footeritem" href="#" onClick="JavaScript:showEditStaffPopup();" title="Edit existing staff record"><img src="../images/menu_editstaff.png"  border=0></img>Edit Staff</a></td> -->
	</tr>
</table>
</div>
</body>

</html>
