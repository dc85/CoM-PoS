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
<title>Rental</title>
<link href="../css/rental.css" rel="stylesheet" type="text/css" />
<link href="../datepicker/css/datepicker.css" rel="stylesheet"
	type="text/css" />
<script src="../js/jquery.js" type="text/javascript"></script>
<script src="../js/menu.js" type="text/javascript"></script>
<script src="../js/jquery.hotkeys.js" type="text/javascript"></script>
<script src="../js/general.js" type="text/javascript"></script>
<script src="../js/rental.js" type="text/javascript"></script>
<script type="text/javascript" src="../datepicker/js/datepicker.js"></script>
<script type="text/javascript" src="../datepicker/js/eye.js"></script>
<script type="text/javascript" src="../datepicker/js/utils.js"></script>
<script type="text/javascript"
	src="../datepicker/js/layout.js?ver=1.0.2"></script>
<script type="text/javascript">
			$(document).keypress(function(e){
				if(e.keyCode==13){
					reloadCIframe();
				}
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
	print "<td><a href=\"#\" style=\"background:#F3F3F3;color:#2C2E22;cursor:pointer;\" title=\"Store Rentals\"><img src=\"../images/menu_rental.png\" border=0></img> Rental</a></td>";
	print "<td><a href=\"#\" onclick=\"JavaScript:openReports();\" title=\"View Store Reports\"><img src=\"../images/menu_reports.png\" border=0></img> Reports</a></td>";
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
	print "<td><a href=\"#\" style=\"background:#F3F3F3;color:#2C2E22;cursor:pointer;\" title=\"Store Rentals\"><img src=\"../images/menu_rental.png\" border=0></img> Rental</a></td>";
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
	print "<td><a href=\"#\" style=\"background:#F3F3F3;color:#2C2E22;cursor:pointer;\" title=\"Store Rentals\"><img src=\"../images/menu_rental.png\" border=0></img> Rental</a></td>";
	print "<td><a href=\"#\" onclick=\"JavaScript:openReports();\" title=\"View Store Reports\"><img src=\"../images/menu_reports.png\" border=0></img> Reports</a></td>";
	print "<td><a href=\"#\" onclick=\"JavaScript:openScheduler();\" title=\"Schedule Staff Shift\"><img src=\"../images/menu_scheduler.png\"  border=0></img> Scheduler</a></td>";
	print "<td><a href=\"#\" onclick=\"JavaScript:openSeminar();\" title=\"Setup Seminar Sessions\"><img src=\"../images/menu_seminar.png\" border=0></img> Seminar</a></td>";
	print "<td><a href=\"#\" onclick=\"JavaScript:openLabels();\" title=\"Birthday Label Printing\"><img src=\"../images/menu_labels.png\" border=0></img> Labels</a></td>";
	print "</tr></table>";
	print "</div>";
}
?>
<div id="control"><input type="button" name="refresh"
	value="Refresh Rental Table"
	onClick="JavaScript: alert('Empty function');"> <label for="showOnRent"
	style="color: white;">Show On Rent Items</label> <input type="checkbox"
	id="showOnRent" checked></input> <label for="showOnRent"
	style="color: white;">Show Overdue Items</label> <input type="checkbox"
	id="showOverDue" checked></input> <label for="showOnRent"
	style="color: white;">Show Returned Items</label> <input
	type="checkbox" id="showReturned"></input> <input type="button"
	id="openTicket" value="New Rent" onclick="JavaScript: newRent();"></input>"
<input type="hidden" id="pageRID" value="-1"></input></div>
</div>

<div id="main">
<div id="table"><iframe src="../php/rental_print.php?query=*"
	name="tableFrame" onLoad="hideLoadingZone()" frameborder="0" WIDTH=100%
	HEIGHT=100%> </iframe>
<div id="loadingZone">Loading...</div>
</div>
<div id="detail">
<table>
	<thead>
		<tr>
			<th class="serie" style="color: #ffffff; background-color: #000000;">Item
			Information</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<th class="info"><b>Rental ID</b></th>
			<th class="info" id="rID"></th>
		</tr>
		<tr>
			<th class="info"><b>Rental Invoice</b></th>
			<th class="info" id="rInvoice"></th>
		</tr>
		<tr>
			<th class="info"><b>Start Date</b></th>
			<th class="info" id="rStart"></th>
		</tr>
		<tr>
			<th class="info"><b>End Date</b></th>
			<th class="info" id="rEnd"></th>
		</tr>
		<tr>
			<th class="info"><b>Item UPC</b></th>
			<th class="info" id="rUPC"></th>
		</tr>
		<tr>
			<th class="info"><b>Item Type</b></th>
			<th class="info" id="rPType"></th>
		</tr>
		<tr>
			<th class="info"><b>Item Description</b></th>
			<th class="info" id="rPDescription"></th>
		</tr>

	</tbody>
</table>
<br></br>
<table>
	<thead>
		<tr>
			<th class="serie" style="color: #ffffff; background-color: #000000;">Customer
			Information</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<th class="info"><b>Customer ID</b></th>
			<th class="info" id="rRenter"></th>
		</tr>
		<tr>
			<th class="info"><b>Customer Last Name</b></th>
			<th class="info" id="rLastN"></th>
		</tr>
		<tr>
			<th class="info"><b>Customer First Name</b></th>
			<th class="info" id="rFirstN"></th>
		</tr>
		<tr>
			<th class="info"><b>Customer Phone #</b></th>
			<th class="info" id="rPhone"></th>
		</tr>
		<tr>
			<th class="info"><b>Deposit ID</b></th>
			<th class="info" id="rDepositID"></th>
		</tr>
		<tr>
			<th class="info"><b>Deposit ID Type</b></th>
			<th class="info" id="rDepositIDType"></th>
		</tr>
		<tr>
			<th class="info"><b>Deposit Amount</b></th>
			<th class="info" id="rDepositAmt"></th>
		</tr>
		<tr>
			<th class="info"><b>Deposit Type</b></th>
			<th class="info" id="rDepositType"></th>
		</tr>
		<tr>
			<th class="info"><b>Deposit Process Staff</b></th>
			<th class="info" id="rStaff"></th>
		</tr>
		<tr>
			<th class="info"><b>Deposit Store</b></th>
			<th class="info" id="rStore"></th>
		</tr>
	</tbody>
</table>
<br></br>

<center><input type="button" id="rStatus"
	style="font-size: 20px; height: 80px; width: 180px; border: 1px solid;"
	onclick="JavaScript: setReturnStatus();" value="UNKNOWN"></input></center>
</div>
</div>

<div id="footer"><!-- 
<table>
	<tr>
		<td><a style="border: 0; padding-top: 1px; width: 100px;"
			class="footeritem" href="#" onClick="JavaScript:showAddStaffPopup();"
			title="Add a new staff into the database"><img
			src="../images/menu_newrental.png" border=0></img>New Rental</a></td>
		<td><a style="border: 0; padding-top: 1px; width: 100px;"
			class="footeritem" href="#"
			onClick="JavaScript:showEditStaffPopup();"
			title="Edit existing staff record"><img
			src="../images/menu_editrental.png" border=0></img>Edit Rental</a></td>
	</tr>
</table>
 --></div>
 <div id="backgroundPopup"></div>

<div id="addLeadsPopupContact"><a id="addLeadsPopupContactClose">x</a>
<table>
	<tr>
		<td
			style="background: black; padding: 6px; border-bottom: 1px dotted #000000;">
		<b style="font-weight: bold; font-size: 16px; color: white;">New
		Leads</b></td>
	</tr>
</table>
<div id="addLeadsPopupContent">
<div id="addLeadsPopupControl">
<center>
<table>
	<tr>
		<td><a href="#" onclick="addNewLeads();">Save</a></td>
		<td><a href="#" onclick="clearAddLeads();">Reset</a></td>
		<td><a href="#" onclick="cancelAddLeadsPopup();">Cancel</a></td>
		<td><a href="#" onclick="disableAddLeadsPopup();">Close</a></td>
	</tr>
</table>
</center>
</div>
<div id="add1">
</div>
</div>
</div>
 
</body>

</html>
