<?php
// @ error reporting setting  (  modify as needed )
ini_set("display_errors", 1);
error_reporting(E_NONE);
include("../backend/timesheet.php");
include("../backend/methods.php");

//@ explicity start session  ( remove if needless )
session_start();

//@ if logoff
if(!empty($_GET['logoff'])) { $_SESSION = array(); }

//@ is authorized?
if(empty($_SESSION['exp_user']) || @$_SESSION['exp_user']['expires'] < time()) {
	header("location:../src/login.html");	//@ redirect
} else {
	$_SESSION['exp_user']['expires'] = time()+(15*60);	//@ renew 45 minutes
}
$today = getdate(time());
$cal_default = date("m/d/Y",$today[0]);
$cal_default2 = date("Y-m-d",$today[0]);

?>

<html>
<head>
<title>Timesheet</title>
<link href="../css/timesheet.css" rel="stylesheet" type="text/css" />
<link href="../css/datepicker.css" rel="stylesheet" type="text/css" />
<script src="../js/jquery.js" type="text/javascript"></script>
<script src="../js/jquery.hotkeys.js" type="text/javascript"></script>
<script src="../js/date.js" type="text/javascript"></script>
<script src="../js/jquery.bgiframe.min.js" type="text/javascript"></script>
<script src="../js/jquery.datePicker.js" type="text/javascript"></script>
<script src="../js/general.js" type="text/javascript"></script>
<script src="../js/timesheet.js" type="text/javascript"></script>
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
		</script>
</head>
<body>
<div id="debug">
	<textarea id="debugger" style="width:100%;height:100%;background:black;color:green;"></textarea>
</div>
<div id="returnMessage"><center>
<label id="lb_returnMessage" style="font-size:18pt;"></label></center>
</div>
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
	print "<td><a href=\"#\" style=\"background:#F3F3F3;color:#2C2E22;cursor:pointer;\" title=\"Store Timesheet/Weekly Summary\"><img src=\"../images/menu_timesheet.png\" border=0></img> Timesheet</a></td>";
	print "<td><a href=\"#\" onclick=\"JavaScript:openProduct();\" title=\"Product List/Details\"><img src=\"../images/menu_product.png\" border=0></img> Product</a></td>";
	print "<td><a href=\"#\" onclick=\"JavaScript:openStaff();\" title=\"Staff List/Information\"><img src=\"../images/menu_staff.png\" border=0></img> Staff</a></td>";
	print "<td><a href=\"#\" onclick=\"JavaScript:openLeads();\" title=\"Leads List/Information\"><img src=\"../images/menu_leads.png\" border=0></img> Leads</a></td>";
	print "<td><a href=\"#\" onclick=\"JavaScript:openCustomer();\" title=\"Customer List/Information\"><img src=\"../images/menu_customer.png\" border=0></img> Customer</a></td>";
	print "<td><a href=\"#\" onclick=\"JavaScript:openRental();\" title=\"Store Rentals\"><img src=\"../images/menu_rental.png\" border=0></img> Rental</a></td>";
	print "<td><a href=\"#\" onclick=\"JavaScript:openReports();\" title=\"View Store Reports\"><img src=\"../images/menu_reports.png\" border=0></img> Reports</a></td>";
	print "<td><a href=\"#\" onclick=\"JavaScript:openScheduler();\" title=\"Schedule Staff Shift\"><img src=\"../images/menu_scheduler.png\"  border=0></img> Scheduler</a></td>";
	print "<td><a href=\"#\" onclick=\"JavaScript:openSeminar();\" title=\"Setup Seminar Sessions\"><img src=\"../images/menu_seminar.png\" border=0></img> Seminar</a></td>";
	print "<td><a href=\"#\" onclick=\"JavaScript:openLabels();\" title=\"Birthday Label Printing\"><img src=\"../images/menu_labels.png\" border=0></img> Labels</a></td>";
	print "</tr></table>";
	print "</div>";
} else if ($_SESSION['exp_user']['usrlevel'] == "5") {
	print "<div id=\"menuitems\">";
	print "<table><tr>";
	print "<td><a href=\"#\" style=\"background:#F3F3F3;color:#2C2E22;cursor:pointer;\" title=\"Store Timesheet/Weekly Summary\"><img src=\"../images/menu_timesheet.png\" border=0></img> Timesheet</a></td>";
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
	print "<td><a href=\"#\" style=\"background:#F3F3F3;color:#2C2E22;cursor:pointer;\" title=\"Store Timesheet/Weekly Summary\"><img src=\"../images/menu_timesheet.png\" border=0></img> Timesheet</a></td>";
	print "<td><a href=\"#\" onclick=\"JavaScript:openProduct();\" title=\"Product List/Details\"><img src=\"../images/menu_product.png\" border=0></img> Product</a></td>";
	print "<td><a href=\"#\" onclick=\"JavaScript:openStaff();\" title=\"Staff List/Information\"><img src=\"../images/menu_staff.png\" border=0></img> Staff</a></td>";
	print "<td><a href=\"#\" onclick=\"JavaScript:openLeads();\" title=\"Leads List/Information\"><img src=\"../images/menu_leads.png\" border=0></img> Leads</a></td>";
	print "<td><a href=\"#\" onclick=\"JavaScript:openCustomer();\" title=\"Customer List/Information\"><img src=\"../images/menu_customer.png\" border=0></img> Customer</a></td>";
	print "<td><a href=\"#\" onclick=\"JavaScript:openRental();\" title=\"Store Rentals\"><img src=\"../images/menu_rental.png\" border=0></img> Rental</a></td>";
	print "<td><a href=\"#\" onclick=\"JavaScript:openReports();\" title=\"View Store Reports\"><img src=\"../images/menu_reports.png\" border=0></img> Reports</a></td>";
	print "<td><a href=\"#\" onclick=\"JavaScript:openScheduler();\" title=\"Schedule Staff Shift\"><img src=\"../images/menu_scheduler.png\"  border=0></img> Scheduler</a></td>";
	print "<td><a href=\"#\" onclick=\"JavaScript:openSeminar();\" title=\"Setup Seminar Sessions\"><img src=\"../images/menu_seminar.png\" border=0></img> Seminar</a></td>";
	print "<td><a href=\"#\" onclick=\"JavaScript:openLabels();\" title=\"Birthday Label Printing\"><img src=\"../images/menu_labels.png\" border=0></img> Labels</a></td>";
	print "</tr></table>";
	print "</div>";
}
?>
<div id="control"><label style="font-weight: bold; color: white;"
	for="date">&nbsp;&nbsp;&nbsp;Date: </label> <input name="datePick"
	id="datePick" class="date-pick" /> <input type="button"
	name="timesheetSubmit" value="Search Date"
	onClick="JavaScript: reloadTimesheetIframe();"></input></div>
</div>

<div id="main">
<div id="table"><iframe
	src=<?php print "../php/timesheet_print.php?query=".$cal_default2;?>
	name="tableFrame" onLoad="hideLoadingZone()" frameborder="0" WIDTH=100%
	HEIGHT=100%> </iframe>
<div id="loadingZone">Loading...</div>
</div>
<div id="signin_lite">
<table>
<tr>
<td><input type="button" value="Clock In" style="border:0px;" onclick="submit_ci();"></input></td>
<td><input type="text" id="tb_ci"></input></td>
<td><input type="button" value="In/Out of Office" style="border:0px;" onclick="submit_oo();"></input></td>
<td><input type="text" id="tb_oo"></input></td>
<td><input type="button" value="Clock Out" style="border:0px;" onclick="submit_co();"></input></td>
<td><input type="text" id="tb_co"></input></td>
</tr>
</table>
</div>
<div id="total"><iframe
	src=<?php print "../php/timesheet_summary.php?date=".$cal_default2;?>
	name="totalFrame" onLoad="hideLoadingZone()" frameborder="0" WIDTH=100%
	HEIGHT=100%> </iframe></div>
<div id="promo"><iframe
	src=<?php print "../php/promo_print.php";?>
	name="promoFrame" onLoad="hideLoadingZone()" frameborder="0" WIDTH=100%
	HEIGHT=100%> </iframe></div>

<div id="spec"><iframe
	src=<?php print "../php/timesheetgraph.php?date=".$cal_default;?>
	name="specFrame" frameborder="0" WIDTH=100% HEIGHT=100%> </iframe></div>
</div>

<div id="footer">
<div id="footeritem">
<table>
	<tr>
		<td></td>
	</tr>
</table>
</div>
</div>
</body>

</html>
