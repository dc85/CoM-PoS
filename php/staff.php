<?php
// @ error reporting setting  (  modify as needed )
ini_set("display_errors", 1);
error_reporting(E_NONE);
include("../backend/staff.php");
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

$title_array = get_customer_title();
$ctype_array = get_customer_type();
$cexpert_array = get_customer_expert();
$prov_array = getProvList();
$taxcat_array = get_tax_category();
$spcl_array = get_spc_level2();
$efee_array = array("","Charge Eco Fee","No Eco Fee");
$staff_array = get_staff_list();
$tline_array = get_linetype();
$shirts_array = get_shirt_list();
$alvl_array = get_account_level();
$shift_array = get_shifts();
?>

<html>
<head>
<title>Staff</title>
<link href="../css/staff.css" rel="stylesheet" type="text/css" />
<link href="../css/datepicker.css" rel="stylesheet" type="text/css" />
<script src="../js/jquery.js" type="text/javascript"></script>
<script src="../js/date.js" type="text/javascript"></script>
<script src="../js/jquery.datePicker.js" type="text/javascript"></script>
<script src="../js/menu.js" type="text/javascript"></script>
<script src="../js/jquery.hotkeys.js" type="text/javascript"></script>
<script src="../js/general.js" type="text/javascript"></script>
<script src="../js/staff.js" type="text/javascript"></script>
<script type="text/javascript">
	$(document).keypress(function(e){
		if(e.keyCode==13){
			reloadStaffIframe();
		}
	});

	function loadFocus() {
		document.getElementById("staffSearch").focus();
	}

	$(function() {
		Date.format = 'yyyy-mm-dd';
		$('.date-pick').datePicker({startDate:'1996-01-01'});
	});
</script>
</head>
<body onload="loadFocus();">
<div id="debug">
<textarea id="debugger" style="width:100%;height:100%;background:black;color:green;"></textarea>
</div>
<div id="returnMessage">
<center><label id="lb_returnMessage" style="font-size:18pt;"></label></center>
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
	print "<td><a href=\"#\" onclick=\"JavaScript:openTimesheet();\" title=\"Store Timesheet/Weekly Summary\"><img src=\"../images/menu_timesheet.png\" border=0></img> Timesheet</a></td>";
	print "<td><a href=\"#\" onclick=\"JavaScript:openProduct();\" title=\"Product List/Details\"><img src=\"../images/menu_product.png\" border=0></img> Product</a></td>";
	print "<td><a href=\"#\" style=\"background:#F3F3F3;color:#2C2E22;cursor:pointer;\" title=\"Staff List/Information\"><img src=\"../images/menu_staff.png\" border=0></img> Staff</a></td>";
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
	print "<td><a href=\"#\" style=\"background:#F3F3F3;color:#2C2E22;cursor:pointer;\" title=\"Staff List/Information\"><img src=\"../images/menu_staff.png\" border=0></img> Staff</a></td>";
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
<div id="control"><select id="search_sStore" size="1">
	<option value="0">ALL STORES</option>
	<option value="1">COLORS OF MAPLE</option>
	<option value="2">OAKRIDGES</option>
</select> <input type="text" name="staffSearch" id="staffSearch"></input>
<input type="button" name="staffSubmit" value="Search Staff"
	onclick="reloadStaffIframe();"></input> <input type="hidden"
	id="pageSID" value="-1"></input> <input type="hidden" id="pageSSID"
	value="-1"></input></div>
</div>

<div id="main">
<div id="table"><iframe src="../php/staff_print.php" name="tableFrame"
	onLoad="hideLoadingZone()" frameborder="0" WIDTH=100% HEIGHT=100%> </iframe>
<div id="loadingZone">Loading...</div>
</div>
<div id="spec">
<table>
	<thead>
		<tr>
			<th class="head" style="color: #ffffff; background-color: #000000;">Staff
			Information</th>
		</tr>
	</thead>
</table>
<table>
	<tbody>
		<tr>
			<th class="label" style="background-color: #ffffff;">Store Name:</th>
			<th class="info" style="font-weight: normal"><select id="sStoreID"
				size="1" onchange="staffEditUpdate('sStoreID');">
				<option value="opt1"></option>
				<option value="opt2">COLORS OF MAPLE</option>
				<option value="opt3">OAKRIDGES</option>
			</select></th>
		</tr>
		<tr>
			<th class="label" style="background-color: #ffffff;">Staff Name:</th>
			<th class="info" style="font-weight: normal"><input type="text"
				id="sFullName" size=30 readonly></input>
			</th>
		</tr>
		<tr>
			<th class="label" style="background-color: #ffffff;">Email :</th>
			<th class="info" style="font-weight: normal"><input type="text"
				id="cEmail1" size=45 readonly></input>
			</th>
		</tr>
		<tr>
			<th class="label" style="background-color: #ffffff;">Staff ID:</th>
			<th class="info" style="font-weight: normal"><input type="text"
				id="sID" size=5 readonly></input>
			</th>
		</tr>
		<tr>
			<th class="label" style="background-color: #ffffff;">Staff Number:</th>
			<th class="info" style="font-weight: normal"><input type="text"
				id="sCNumber" size=15 onchange="staffEditUpdate('sCNumber');"></input>
			</th>
		</tr>
		<tr>
			<th class="label" style="background-color: #ffffff;">Staff Customer ID:</th>
			<th class="info" style="font-weight: normal"><input type="text"
				id="sCustomer" size=5 onchange="staffEditUpdate('sCustomer');"></input>
			</th>
		</tr>
		<tr>
			<th class="label" style="background-color: #ffffff;">Account Name:</th>
			<th class="info" style="font-weight: normal"><input type="text"
				id="sStaff" size=25 onchange="staffEditUpdate('sStaff');"></input>
			</th>
		</tr>
		<tr>
			<th class="label" style="background-color: #ffffff;">Account Level:</th>
			<th class="info" style="font-weight: normal"><?php
			print make_dropdown_list("","sLevel","",$alvl_array,"Account Level","staffEditUpdate('sLevel')");
			?>
			</th>
		</tr>
		<tr>
			<th class="label" style="background-color: #ffffff;">Account Password:</th>
			<th class="info" style="font-weight: normal"><input type="text"
				id="sPassword" size=20></input>
				<input type="button" style="border:1px solid black;" onclick="updatePassword();" value="Update"></input>
			</th>
		</tr>
		<!-- <tr>
			<th class="label" style="background-color: #ffffff;">Password Duration:</th>
			<th class="info" style="font-weight: normal"><input type="text"
				id="sPswdExpMM" size=25 onchange="staffEditUpdate('sPswdExpMM');"></input>
			</th>
		</tr>
		<tr>
			<th class="label" style="background-color: #ffffff;">Password Expiration Date:</th>
			<th class="info" style="font-weight: normal"><input type="text"
				id="sPswdExp" size=25 onchange="staffEditUpdate('sPswdExp');"></input>
			</th>
		</tr> -->
		<tr>
			<th class="label" style="background-color: #ffffff;">Store Credit:</th>
			<th class="info" style="font-weight: normal"><input type="text"
				id="sStoreCredit" size=15 onchange="staffEditUpdate('sStoreCredit');"></input>
			</th>
		</tr>
		<tr>
			<th class="label" style="background-color: #ffffff;">Hourly Rate:</th>
			<th class="info" style="font-weight: normal"><input type="text"
				id="sHrRate" size=10 onchange="staffEditUpdate('sHrRate');"></input>
			</th>
		</tr>
		<tr>
			<th class="label" style="background-color: #ffffff;">Default Daily Hour:</th>
			<th class="info" style="font-weight: normal"><input type="text"
				id="sDfHr" size=10 onchange="staffEditUpdate('sDfHr');"></input>
			</th>
		</tr>
	</tbody>
</table>
<table>
	<thead>
		<tr>
			<th class="head" style="color: #ffffff; background-color: #000000;">Weekly
			Schedule</th>
		</tr>
	</thead>
</table>
<table>
	<tbody>
		<tr>
			<th class="label" style="background-color: #ffffff;">Sunday:</th>
			<th class="info" style="font-weight: normal"><?php
			print make_dropdown_list("","sSchedule0","",$shift_array,"Sunday Schedule","staffEditUpdate('sSchedule0')");
			?>
			</th>
		</tr>
		<tr>
			<th class="label" style="background-color: #ffffff;">Monday:</th>
			<th class="info" style="font-weight: normal"><?php
			print make_dropdown_list("","sSchedule1","",$shift_array,"Monday Schedule","staffEditUpdate('sSchedule1')");
			?>
			</th>
		</tr>
		<tr>
			<th class="label" style="background-color: #ffffff;">Tuesday:</th>
			<th class="info" style="font-weight: normal"><?php
			print make_dropdown_list("","sSchedule2","",$shift_array,"Tuesday Schedule","staffEditUpdate('sSchedule2')");
			?>
			</th>
		</tr>
		<tr>
			<th class="label" style="background-color: #ffffff;">Wednesday:</th>
			<th class="info" style="font-weight: normal"><?php
			print make_dropdown_list("","sSchedule3","",$shift_array,"Wednesday Schedule","staffEditUpdate('sSchedule3')");
			?>
			</th>
		</tr>
		<tr>
			<th class="label" style="background-color: #ffffff;">Thursday:</th>
			<th class="info" style="font-weight: normal"><?php
			print make_dropdown_list("","sSchedule4","",$shift_array,"Thursday Schedule","staffEditUpdate('sSchedule4')");
			?>
			</th>
		</tr>
		<tr>
			<th class="label" style="background-color: #ffffff;">Friday:</th>
			<th class="info" style="font-weight: normal"><?php
			print make_dropdown_list("","sSchedule5","",$shift_array,"Friday Schedule","staffEditUpdate('sSchedule5')");
			?>
			</th>
		</tr>
		<tr>
			<th class="label" style="background-color: #ffffff;">Saturday:</th>
			<th class="info" style="font-weight: normal"><?php
			print make_dropdown_list("","sSchedule6","",$shift_array,"Saturday Schedule","staffEditUpdate('sSchedule6')");
			?>
			</th>
		</tr>
		<tr>
		<th><b style="font-size: 14pt;">Total Hours:</b> <label  style="font-size: 14pt;" id="staff_totalhour" value=""></label></th>
		</tr>
	</tbody>
</table>
<br/>
<center>
<table><tr>
<td><input type="button" value="Save Changes" onclick="staffEidtHelp();"></input></td>
<td><input type="button" value="Cancel Changes" onclick="staffEditCancel()"></input></td>
<td></td>
</tr></table>
</center>

<div id="staff_active"><input type="button" id="sIsActive"
	value="Active" onclick="staffStatSet();"
	style="height: 100px; width: 200px; font-size: 20px"></input></div>
</div>
</div>

<div id="footer">
<table>
	<tr>
		<td><a style="border: 0; padding-top: 1px; width: 76px;"
			class="footeritem" href="#" onClick="JavaScript:showAddStaffPopup();"
			title="Add a new staff into the database"><img
			src="../images/menu_addnewstaff.png" border=0></img>Add Staff</a></td>
	</tr>
</table>
</div>
<div id="backgroundPopup"></div>

<div id="addStaffPopupContact"><a id="addStaffPopupContactClose">x</a>
<table>
	<tr>
		<td
			style="background: black; padding: 6px;">
		<b style="font-weight: bold; font-size: 14px; color: white;">Add New
		Staff</b></td>
		<td><label id="nc_warning" style="color:red;font-size:12pt;font-weight:bold;"></label></td>
	</tr>
</table>
<div id="addStaffPopupContent">
<div id="addStaffPopupControl">
<center>
<table>
	<tr>
		<td><a href="#" class="addStaff" onclick="addNewStaffHelp();">Save</a></td>
		<td><a href="#" class="addStaff" onclick="clearAddStaff();">Reset</a></td>
		<td><a href="#" class="addStaff" onclick="cancelAddStaffPopup();">Cancel</a></td>
		<td><a href="#" class="addStaff" onclick="disableAddStaffPopup();">Close</a></td>
	</tr>
</table>
</center>
</div>
<div id="add1">
<table>
	<thead>
		<tr>
			<th><label class="head">Staff Infomation</label></th>
		</tr>
	</thead>
</table>
<table>
	<tbody>
		<tr>
			<th class="label" style="background-color: #ffffff;">Store Name:</th>
			<th class="info" style="font-weight: normal"><select id="new_sStoreID"
				size="1">
				<option value="0"></option>
				<option value="1">COLORS OF MAPLE</option>
				<option value="2">OAKRIDGES</option>
			</select></th>
		</tr>
		<tr>
			<th class="label" style="background-color: #ffffff;">First Name:</th>
			<th class="info" style="font-weight: normal"><input type="text"
				id="new_cFirstN" size=20></input>
			</th>
		</tr>
		<tr>
			<th class="label" style="background-color: #ffffff;">Last Name:</th>
			<th class="info" style="font-weight: normal"><input type="text"
				id="new_cLastN" size=20></input>
			</th>
		</tr>
		<tr>
			<th class="label" style="background-color: #ffffff;">Staff ID:</th>
			<th class="info" style="font-weight: normal"><input type="text"
				id="new_sID" size=5 disabled></input>
			</th>
		</tr>
		<tr>
			<th class="label" style="background-color: #ffffff;">Staff Number:</th>
			<th class="info" style="font-weight: normal"><input type="text"
				id="new_sCNumber" size=15 onchange="checkInput('new_sCNumber');"></input>
			</th>
		</tr>
		<tr>
			<th class="label" style="background-color: #ffffff;">Staff Customer ID:</th>
			<th class="info" style="font-weight: normal"><input type="text"
				id="new_sCustomer" size=5 disabled></input>
			</th>
		</tr>
		<tr>
			<th class="label" style="background-color: #ffffff;">Account Name:</th>
			<th class="info" style="font-weight: normal"><input type="text"
				id="new_sStaff" size=25 onchange="checkInput('new_sStaff');"></input>
			</th>
		</tr>
		<tr>
			<th class="label" style="background-color: #ffffff;">Account Level:</th>
			<th class="info" style="font-weight: normal"><?php
			//$val_array = get_account_level();
			print make_dropdown_list("","new_sLevel","",$alvl_array,"Account Level","");
			?>
			</th>
		</tr>
		<tr>
			<th class="label" style="background-color: #ffffff;">Account Password:</th>
			<th class="info" style="font-weight: normal"><input type="password"
				id="new_sPassword" size=25></input>
			</th>
		</tr>
		<tr>
			<th class="label" style="background-color: #ffffff;">Password Duration:</th>
			<th class="info" style="font-weight: normal"><select id="new_sPswdExpMM"
				size="1">
				<option value="0"></option>
				<option value="1">1 month</option>
				<option value="2">3 months</option>
				<option value="2">6 months</option>
				<option value="2">1 year</option>
			</select>
			</th>
		</tr>
		<tr>
			<th class="label" style="background-color: #ffffff;">Password Expiration Date:</th>
			<th class="info" style="font-weight: normal"><input type="text"
				id="new_sPswdExp" size=25 disabled></input>
			</th>
		</tr>
		<tr>
			<th class="label" style="background-color: #ffffff;">Store Credit:</th>
			<th class="info" style="font-weight: normal"><input type="text"
				id="new_sStoreCredit" size=15 onchange="checkInput('new_sStoreCredit');"></input>
			</th>
		</tr>
		<tr>
			<th class="label" style="background-color: #ffffff;">Hourly Rate:</th>
			<th class="info" style="font-weight: normal"><input type="text"
				id="new_sHrRate" size=10 onchange="checkInput('new_sHrRate');"></input>
			</th>
		</tr>
		<tr>
			<th class="label" style="background-color: #ffffff;">Default Daily Hour:</th>
			<th class="info" style="font-weight: normal"><input type="text"
				id="new_sDfHr" size=10></input>
			</th>
		</tr>
	</tbody>
</table>
<table>
	<thead>
		<tr>
			<th><label class="head">Weekly Schedule</label></th>
		</tr>
	</thead>
</table>
<table>
	<tbody>
		<tr>
			<th class="label" style="background-color: #ffffff;">Sunday:</th>
			<th class="info" style="font-weight: normal"><?php
			//$val_array = get_shifts();
			print make_dropdown_list("","new_sSchedule0","",$shift_array,"Sunday Schedule","");
			?>
			</th>
		</tr>
		<tr>
			<th class="label" style="background-color: #ffffff;">Monday:</th>
			<th class="info" style="font-weight: normal"><?php
			print make_dropdown_list("","new_sSchedule1","",$shift_array,"Monday Schedule","");
			?>
			</th>
		</tr>
		<tr>
			<th class="label" style="background-color: #ffffff;">Tuesday:</th>
			<th class="info" style="font-weight: normal"><?php
			print make_dropdown_list("","new_sSchedule2","",$shift_array,"Tuesday Schedule","");
			?>
			</th>
		</tr>
		<tr>
			<th class="label" style="background-color: #ffffff;">Wednesday:</th>
			<th class="info" style="font-weight: normal"><?php
			print make_dropdown_list("","new_sSchedule3","",$shift_array,"Wednesday Schedule","");
			?>
			</th>
		</tr>
		<tr>
			<th class="label" style="background-color: #ffffff;">Thursday:</th>
			<th class="info" style="font-weight: normal"><?php
			print make_dropdown_list("","new_sSchedule4","",$shift_array,"Thursday Schedule","");
			?>
			</th>
		</tr>
		<tr>
			<th class="label" style="background-color: #ffffff;">Friday:</th>
			<th class="info" style="font-weight: normal"><?php
			print make_dropdown_list("","new_sSchedule5","",$shift_array,"Friday Schedule","");
			?>
			</th>
		</tr>
		<tr>
			<th class="label" style="background-color: #ffffff;">Saturday:</th>
			<th class="info" style="font-weight: normal"><?php
			print make_dropdown_list("","new_sSchedule6","",$shift_array,"Saturday Schedule","");
			?>
			</th>
		</tr>
	</tbody>
</table>
<br />
<table style="position:absolute; top:550px; left: 275px;">
	<tr>
		<td><a href="#" class="addStaff" onclick="nextClick();" style="bottom: 50px;">Next</a></td>
	</tr>
</table>
</div>
<div id="add2">
<table>
	<thead>
		<tr>
			<th><label class="head">Basic Information</label></th>
		</tr>
	</thead>
</table>
<table>
	<tbody>
		<tr>
			<th class="label"><label
				class="add1">Title:</label></th>
			<th class="info" style="font-weight: normal"><?php
			//$val_array = get_customer_title();
			print make_dropdown_list("","new_cTitle","",$title_array,"Customer Title","");
			?></th>
		</tr>
		<tr>
			<th class="label"><label class="add1">AKA:</label></th>
			<th class="info" style="font-weight: normal"><input type="text"
				id="new_cAKA" size=25></input></th>
		</tr>
		<tr>
			<th class="label"><label class="add1">Date of Birth:</label></th>
			<th class="info" style="font-weight: normal"><input type="text" class="date-pick"
				id="new_cDoB"></input></th>
		</tr>
		<tr>
			<th class="label"><label class="add1">Driver's License:</label></th>
			<th class="info" style="font-weight: normal"><input type="text"
				id="new_cRIN" size=25 onchange="checkInput('new_cRIN');"></input></th>
		</tr>
		<tr>
			<th class="label" style="background-color: #ffffff;"><label class="add1">COM Number:</label></th>
			<th class="info" style="font-weight: normal"><input type="text"
				id="new_cCardNum" size=10 onchange="checkInput('new_cCardNum');"></input>
			</th>
		</tr>
		<tr>
			<th class="label" style="background-color: #ffffff;"><label class="add1">Customer Type:</label></th>
			<th class="info" style="font-weight: normal"><?php
			//$val_array = get_customer_type();
			print make_dropdown_list("","new_cCustType","",$ctype_array,"Customer Type","");
			?></th>
		</tr>
		<tr>
			<th class="label" style="background-color: #ffffff;"><label class="add1">Company Name:</label></th>
			<th class="info" style="font-weight: normal"><input type="text"
				id="new_cCoName" size=40></input>
			</th>
		</tr>
		<tr>
			<th class="label" style="background-color: #ffffff;"><label class="add1">Shirt Size:</label></th>
			<th class="info" style="font-weight: normal"><?php
			//$val_array = get_shirt_list();
			print make_dropdown_list("","new_cShirtSize","",$shirts_array,"Shirt Size","");
			?></th>
		</tr>
		<tr>
			<th class="label" style="background-color: #ffffff;"><label class="add1">Pant Size:</label></th>
			<th class="info" style="font-weight: normal"><input type="text"
				id="new_cPantSize" size=10 onchange="checkInput('new_cPantSize');">
			(i.e. 30x30)</input></th>
		</tr>
		<tr>
			<th class="label" style="background-color: #ffffff;"><label class="add1">Expert Type:</label></th>
			<th class="info" style="font-weight: normal"><?php
			//$val_array = get_customer_expert();
			print make_dropdown_list("","new_cExpert","",$cexpert_array,"Expert Type","");
			?></th>
		</tr>
	</tbody>
</table>
<table>
	<thead>
		<tr>
			<th><label class="head">Primary Contact</label></th>
		</tr>
	</thead>
</table>
<table>
	<tbody>
		<tr>
			<th class="label" style="background-color: #ffffff;"><label class="add1">Unit:</label></th>
			<th class="info" style="font-weight: normal"><input type="text"
				id="new_cUnitB" size=8 onchange="checkInput('new_cUnitB');"></input></th>
		</tr>
		<tr>
			<th class="label" style="background-color: #ffffff;"><label class="add1">Address:</label></th>
			<th class="info" style="font-weight: normal"><input type="text"
				id="new_cAdrBus" size=55></input>
			</th>
		</tr>
		<tr>
			<th class="label" style="background-color: #ffffff;"><label class="add1">City:</label></th>
			<th class="info" style="font-weight: normal"><input type="text"
				id="new_cCityB" size=40></input></th>
		</tr>
		<tr>
			<th class="label" style="background-color: #ffffff;"><label class="add1">Province:</label></th>
			<th class="info" style="font-weight: normal">
			<?php
				//$val_array = getProvList();
				print make_dropdown_list("","new_cProvB","",$prov_array,"Contact Type","");
			?>
			</th>
		</tr>
		<tr>
			<th class="label" style="background-color: #ffffff;"><label class="add1">Zip Code:</label></th>
			<th class="info" style="font-weight: normal"><input type="text"
				id="new_cZipB" size=10 onchange="checkInput('new_cZipB');"></input></th>
		</tr>
		<tr>
			<th class="label" style="background-color: #ffffff;"><label class="add1">Contact:</label></th>
			<th class="info" style="font-weight: normal">
			<?php
				//$val_array = get_linetype();
				print make_dropdown_list("","new_cpType1","",$tline_array,"Contact Type","");
			?>
			<input type="text" id="new_cPhone1" size=18 onchange="checkInput('new_cPhone1');"></input>
			</th>
		</tr>
		<tr>
			<th class="label" style="background-color: #ffffff;"><label class="add1">Email:</label></th>
			<th class="info" style="font-weight: normal"><input type="text"
				id="new_cEmail1" size=40></input>
			</th>
		</tr>
	</tbody>
</table>
<br />
<table style="position:absolute; top:550px;left: 150px;">
	<tr>
		<td><a href="#" class="addStaff" onclick="prevClick();">Previous</a></td>
		<td><a href="#" class="addStaff" onclick="nextClick();">Next</a></td>
	</tr>
</table>
</div>
<div id="add3">
<table>
	<thead>
		<tr>
			<th><label class="head">Secondary Contact</label></th>
		</tr>
	</thead>
</table>
<table>
	<tbody>
		<tr>
			<th class="label" style="background-color: #ffffff;"><label class="add1">Unit:</label></th>
			<th class="info" style="font-weight: normal"><input type="text"
				id="new_cUnitH" size=8 onchange="checkInput('new_cUnitH');"></input></th>
		</tr>
		<tr>
			<th class="label" style="background-color: #ffffff;"><label class="add1">Address:</label></th>
			<th class="info" style="font-weight: normal"><input type="text"
				id="new_cAdrHome" size=55></input>
			</th>
		</tr>
		<tr>
			<th class="label" style="background-color: #ffffff;"><label class="add1">City:</label></th>
			<th class="info" style="font-weight: normal"><input type="text"
				id="new_cCityH" size=40></input></th>
		</tr>
		<tr>
			<th class="label" style="background-color: #ffffff;"><label class="add1">Province:</label></th>
			<th class="info" style="font-weight: normal">
			<?php
				//$val_array = getProvList();
				print make_dropdown_list("","new_cProvH","",$prov_array,"Contact Type","");
			?>
			</th>
		</tr>
		<tr>
			<th class="label" style="background-color: #ffffff;"><label class="add1">Zip Code:</label></th>
			<th class="info" style="font-weight: normal"><input type="text"
				id="new_cZipH" size=10 onchange="checkInput('new_cZipH');"></input></th>
		</tr>
		<tr>
			<th class="label" style="background-color: #ffffff;"><label class="add1">Contact:</label></th>
			<th class="info" style="font-weight: normal">
			<?php
				//$val_array = get_linetype();
				print make_dropdown_list("","new_cpType2","",$tline_array,"Contact Type","");
			?>
			<input type="text" id="new_cPhone2" size=18 onchange="checkInput('new_cPhone2');"></input>
			</th>
		</tr>
		<tr>
			<th class="label" style="background-color: #ffffff;"><label class="add1">Email:</label></th>
			<th class="info" style="font-weight: normal"><input type="text"
				id="new_cEmail2" size=40></input>
			</th>
		</tr>
	</tbody>
</table>
<table>
	<thead>
		<tr>
			<th><label class="head">Additional Contact</label></th>
		</tr>
	</thead>
</table>
<table>
	<tbody>
		<tr>
			<th class="label" style="background-color: #ffffff;"><label class="add1">Unit:</label></th>
			<th class="info" style="font-weight: normal"><input type="text"
				id="new_cUnitS" size=8 onchange="checkInput('new_cUnitS');"></input></th>
		</tr>
		<tr>
			<th class="label" style="background-color: #ffffff;"><label class="add1">Address:</label></th>
			<th class="info" style="font-weight: normal"><input type="text"
				id="new_cAdrShip" size=55></input>
			</th>
		</tr>
		<tr>
			<th class="label" style="background-color: #ffffff;"><label class="add1">City:</label></th>
			<th class="info" style="font-weight: normal"><input type="text"
				id="new_cCityS" size=40></input></th>
		</tr>
		<tr>
			<th class="label" style="background-color: #ffffff;"><label class="add1">Province:</label></th>
			<th class="info" style="font-weight: normal">
			<?php
				//$val_array = getProvList();
				print make_dropdown_list("","new_cProvS","",$prov_array,"Contact Type","");
			?>
			</th>
		</tr>
		<tr>
			<th class="label" style="background-color: #ffffff;"><label class="add1">Zip Code:</label></th>
			<th class="info" style="font-weight: normal"><input type="text"
				id="new_cZipS" size=10 onchange="checkInput('new_cZipS');"></input></th>
		</tr>
		<tr>
			<th class="label" style="background-color: #ffffff;"><label class="add1">Contact:</label></th>
			<th class="info" style="font-weight: normal">
			<?php
				//$val_array = get_linetype();
				print make_dropdown_list("","new_cpType3","",$tline_array,"Contact Type","");
			?>
			<input type="text" id="new_cPhone3" size=18 onchange="checkInput('new_cPhone3');"></input>
			</th>
		</tr>
		<tr>
			<th class="label" style="background-color: #ffffff;"><label class="add1">Email:</label></th>
			<th class="info" style="font-weight: normal"><input type="text"
				id="new_cEmail3" size=40></input>
			</th>
		</tr>
	</tbody>
</table>
<table>
	<thead>
		<tr>
			<th><label class="head">Customer Expertise</label></th>
		</tr>
	</thead>
</table>
<table>
	<tbody>
		<tr>
			<th class="new_new_expertise" style="background-color: #ffffff;">Interior:</th>
			<th class="new_expertise"><input type="checkbox" id="new_cE1"></input></th>
			<th class="new_expertise" style="background-color: #ffffff;">Deck &
			Fence:</th>
			<th class="new_expertise"><input type="checkbox" id="new_cE6"></input></th>
		</tr>
		<tr>
			<th class="new_expertise" style="background-color: #ffffff;">Exterior:</th>
			<th class="new_expertise"><input type="checkbox" id="new_cE2"></input></th>
			<th class="new_expertise" style="background-color: #ffffff;">Drywall &
			Taping:</th>
			<th class="new_expertise"><input type="checkbox" id="new_cE7"></input></th>
		</tr>
		<tr>
			<th class="new_expertise" style="background-color: #ffffff;">Residential:</th>
			<th class="new_expertise"><input type="checkbox" id="new_cE3"></input></th>
			<th class="new_expertise" style="background-color: #ffffff;">Spraying:</th>
			<th class="new_expertise"><input type="checkbox" id="new_cE8"></input></th>
		</tr>
		<tr>
			<th class="new_expertise" style="background-color: #ffffff;">Commercial:</th>
			<th class="new_expertise"><input type="checkbox" id="new_cE4"></input></th>
			<th class="new_expertise" style="background-color: #ffffff;">Faux
			Finish:</th>
			<th class="new_expertise"><input type="checkbox" id="new_cE9"></input></th>
		</tr>
		<tr>
			<th class="new_expertise" style="background-color: #ffffff;">Stain
			Work:</th>
			<th class="new_expertise"><input type="checkbox" id="new_cE5"></input></th>
			<th class="new_expertise" style="background-color: #ffffff;">Wall
			Paper:</th>
			<th class="new_expertise"><input type="checkbox" id="new_cE10"></input></th>
		</tr>
	</tbody>
</table>
<br />
<table style="position:absolute; top:550px; left: 150px;">
	<tr>
		<td><a href="#" class="addStaff" onclick="prevClick();">Previous</a></td>
		<td><a href="#" class="addStaff" onclick="nextClick();">Next</a></td>
	</tr>
</table>
</div>
<div id="add4">
<table>
	<thead>
		<tr>
			<th><label class="head">Additional Numbers</label></th>
		</tr>
	</thead>
</table>
<table>
<tbody>
		<tr>
			<th class="label" style="background-color: #ffffff;"><label class="add1">Phone 4:</label></th>
			<th class="info" style="font-weight: normal">
			<?php
				//$val_array = get_linetype();
				print make_dropdown_list("","new_cpType4","",$tline_array,"Contact Type","");
			?>
			<input type="text" id="new_cPhone4" size=18 onchange="checkInput('new_cPhone4');"></input>
			</th>
		</tr>
		<tr>
			<th class="label" style="background-color: #ffffff;"><label class="add1">Phone 5:</label></th>
			<th class="info" style="font-weight: normal">
			<?php
				//$val_array = get_linetype();
				print make_dropdown_list("","new_cpType5","",$tline_array,"Contact Type","");
			?>
			<input type="text" id="new_cPhone5" size=18 onchange="checkInput('new_cPhone5');"></input>
			</th>
		</tr>
</tbody>
</table>
<table>
	<thead>
		<tr>
			<th><label class="head">Finance Information</label></th>
		</tr>
	</thead>
</table>
<table>
	<tbody>
		<tr>
			<th class="label" style="background-color: #ffffff;">Customer Tax 1:</th>
			<th class="info" style="font-weight: normal"><?php
			//$val_array = get_tax_category();
			print make_dropdown_list("","new_cTax1","",$taxcat_array,"Customer Tax 1","");
			?></th>
		</tr>
		<tr>
			<th class="label" style="background-color: #ffffff;">Customer Tax 2:</th>
			<th class="info" style="font-weight: normal"><?php
			//$val_array = get_tax_category();
			print make_dropdown_list("","new_cTax2","",$taxcat_array,"Customer Tax 2","");
			?></th>
		</tr>
		<tr>
			<th class="label" style="background-color: #ffffff;">SPC Level:</th>
			<th class="info" style="font-weight: normal"><?php
			//$val_array = get_spc_level2();
			print make_dropdown_list("","new_cSPC","",$spcl_array,"Customer SPC Level","");
			?></th>
		</tr>
		<tr>
			<th class="label" style="background-color: #ffffff;">ECO Fee:</th>
			<th class="info" style="font-weight: normal"><?php
			//$val_array = array("","Charge Eco Fee","No Eco Fee");
			print make_dropdown_list("","new_cEcoFee","",$efee_array,"Customer Eco Fee","");
			?></th>
		</tr>
		<tr>
			<th class="label" style="background-color: #ffffff;">Customer Rep:</th>
			<th class="info" style="font-weight: normal"><?php
			//$val_array = get_staff_list();
			print make_dropdown_list("","new_cCustRep","",$staff_array,"Customer Rep.","");
			?></th>
		</tr>
		<tr>
			<th class="label" style="background-color: #ffffff;">Excempt #:</th>
			<th class="info" style="font-weight: normal"><input type="text"
				id="new_cExpNum" size=20></input>
			</th>
		</tr>
		<tr>
			<th class="label" style="background-color: #ffffff;">Expires In:</th>
			<th class="info" style="font-weight: normal">
			<input name="datePick" class="date-pick" id="new_cExpDate" size=10></input></th>
		</tr>
		<tr>
			<th class="label" style="background-color: #ffffff;">Credit:</th>
			<th class="info" style="font-weight: normal"><input type="text"
				id="new_cCredit" size=20 onchange="checkInput('new_cCredit');"></input>
			</th>
		</tr>
	</tbody>
</table>
<table style="position:absolute; top:550px; left: 150px;">
	<tr>
		<td><a href="#" class="addStaff" onclick="prevClick();">Previous</a></td>
	</tr>
</table>
</div>
</div>
</div>
</body>

</html>
