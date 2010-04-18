<?php
// @ error reporting setting  (  modify as needed )
ini_set("display_errors", 1);
error_reporting(E_ALL);

include("../backend/customer.php");
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
?>

<html>
<head>
<title>Leads</title>
<link href="../css/leads.css" rel="stylesheet" type="text/css" />
<link href="../css/jquery.impromptu.css" rel="stylesheet"
	type="text/css" />
<script src="../js/jquery.js" type="text/javascript"></script>
<script src="../js/menu.js" type="text/javascript"></script>
<script src="../js/jquery.hotkeys.js" type="text/javascript"></script>
<script src="../js/jquery.impromptu.js" type="text/javascript"></script>
<script src="../js/general.js" type="text/javascript"></script>
<script src="../js/leads.js" type="text/javascript"></script>
<script type="text/javascript">
	$(document).keypress(function(e){
		if(e.keyCode==13){
			reloadLIframe();
		}
	});

	function setFocus() {
		document.getElementById("leadSearch").focus();
	}
</script>
</head>
<body onload="setFocus();">
<div id="debug"><textarea id="debugger"
	style="width: 100%; height: 100%; background: black; color: green;"></textarea>
</div>
<div id="returnMessage">
<center><label id="lb_returnMessage" style="font-size:18pt;"></label></center>
</div>
<div id="menu">
<div id="signout"><?php print $_SESSION['exp_user']['sStaff']." @ ".get_store_name($_SESSION['exp_user']['localSID']);?>
&nbsp;&nbsp;&nbsp;&nbsp;[<a href="#" onclick="logout();">Logout</a>]</div>
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
	print "<td><a href=\"#\" style=\"background:#F3F3F3;color:#2C2E22;cursor:pointer;\" title=\"Leads List/Information\"><img src=\"../images/menu_leads.png\" border=0></img> Leads</a></td>";
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
	print "<td><a href=\"#\" style=\"background:#F3F3F3;color:#2C2E22;cursor:pointer;\" title=\"Leads List/Information\"><img src=\"../images/menu_leads.png\" border=0></img> Leads</a></td>";
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
	print "<td><a href=\"#\" style=\"background:#F3F3F3;color:#2C2E22;cursor:pointer;\" title=\"Leads List/Information\"><img src=\"../images/menu_leads.png\" border=0></img> Leads</a></td>";
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
<div id="control"><b style="color: white;">Filter By: </b> <select
	name="leadFilter" size="1">
	<option value="lf1"></option>
	<option value="lf2">ARCHITECT</option>
	<option value="lf3">COLOUR CONSULTANT</option>
	<option value="lf4">CONSTRUCTION</option>
	<option value="lf5">DESIGNER</option>
	<option value="lf6">DRY WALLER</option>
	<option value="lf7">GENERAL CONTRACTOR</option>
	<option value="lf8">PAINTER</option>
	<option value="lf9">RESTORATION</option>
</select> <b style="color: white;">Zip: </b> <input type="text"
	name="leadZip" id="leadZip" title="format(XXX XXX)" onchange="checkZip('leadZip');" maxlength=7></input> <input
	type="text" name="leadSearch" id="leadSearch" size=50></input> <input
	type="button" name="leadSubmit" value="Search Leads"
	onClick="reloadLIframe();"></input> <input type="button"
	name="leadReset" value="Reset" onClick="resetLead();"></input>
<input type="hidden" id="selSID" value="-1"></input> <input
	type="hidden" id="selCID" value="-1"></input>
<input type="hidden" id="usrLvl" value="<?php print $_SESSION['exp_user']['usrlevel'];?>"></input>
<input type="hidden" id="defStaff" value="<?php print $_SESSION['exp_user']['sStaff'];?>"></input>	
	</div>
</div>

<div id="main">
<div id="phonescript_bg">
<div id="phonescript_picture">
	<img id="phonePicture" style="height: 500px; width: auto;" src="../script_pictures/phonescript.jpg"></img>
	
</div>
<div id="phonescript_control">
<input type="button" style="border: 1px solid black;" value="CLOSE X" onclick="closePhoneScript()"></input>
</div>
</div>

<div id="faxscript_bg">
<div id="faxscript_picture">
	<img id="faxPicture" style="height: 500px; width: auto;" src="../script_pictures/faxscript.jpg"></img>
	
</div>
<div id="faxscript_control">
<input type="button" style="border: 1px solid black;" value="Send" onclick="alert('Fax modem not installed')"></input>
<br />
<input type="button" style="border: 1px solid black;" value="CLOSE X" onclick="closeFaxScript()"></input>
</div>
</div>

<div id="mailscript_bg">
<div id="mailscript_picture">
	<img id="mailPicture" style="height: 500px; width: auto;" src="../script_pictures/faxscript.jpg"></img>
	
</div>
<div id="mailscript_control">
<input type="button" style="border: 1px solid black;" value="Send" onclick="alert('Mail server not configured')"></input>
<br />
<input type="button" style="border: 1px solid black;" value="CLOSE X" onclick="closeMailScript()"></input>
</div>
</div>

<div id="table"><iframe src="../php/leads_print.php?query=8dsauhfds7dadga73dkfga" name="tableFrame"
	onload="hideLoadingZone()" frameborder="0" WIDTH=100% HEIGHT=100%> </iframe>
<div id="loadingZone">Loading...</div>
</div>
<div id="spec">
<table style="position: absolute; top: 350px;">
	<tr>
		<td>
		<table>
			<thead>
				<tr>
					<th class="head" style="color: #ffffff; background-color: #000000;">Lead
					Information</th>
					<th id="cSPC" class="info" style="font-weight: normal"></th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<th class="label">Title:</th>
					<th class="info" style="font-weight: normal"><?php
					$val_array = get_customer_title();
					print make_dropdown_list("","cTitle","",$val_array,"Customer Title","leadsEditUpdate('cTitle')");
					?></th>
				</tr>
				<tr>
					<th class="label">First Name:</th>
					<th class="info" style="font-weight: normal"><input type="text"
						id="cFirstN" size=30 onchange="leadsEditUpdate('cFirstN');"></input>
					</th>
				</tr>
				<tr>
					<th class="label">Last Name:</th>
					<th class="info" style="font-weight: normal"><input type="text"
						id="cLastN" size=30 onchange="leadsEditUpdate('cLastN');"></input>
					</th>
				</tr>
				<tr>
					<th class="label">AKA:</th>
					<th class="info" style="font-weight: normal"><input type="text"
						id="cAKA" size=25 onchange="leadsEditUpdate('cAKA');"></input></th>
				</tr>
				<tr>
					<th class="label">Date of Birth:</th>
					<th class="info" style="font-weight: normal"><input type="text"
						id="cDoB" size=25 onchange="leadsEditUpdate('cDoB');"></input></th>
				</tr>
				<tr>
					<th class="label">Driver's License:</th>
					<th class="info" style="font-weight: normal"><input type="text"
						id="cRIN" size=25 onchange="leadsEditUpdate('cRIN');"></input></th>
				</tr>
				<tr>
					<th class="label">COM Number:</th>
					<th class="info" style="font-weight: normal"><input type="text"
						id="cCardNum" size=10 onchange="leadsEditUpdate('cCardNum');"></input>
					</th>
				</tr>
				<tr>
					<th class="label">Customer Type:</th>
					<th class="info" style="font-weight: normal"><?php
					$val_array = get_customer_type();
					print make_dropdown_list("","cCustType","",$val_array,"Customer Type","leadsEditUpdate('cCustType')");
					?></th>
				</tr>
				<tr>
					<th class="label">Company Name:</th>
					<th class="info" style="font-weight: normal"><input type="text"
						id="cCoName" size=30 onchange="leadsEditUpdate('cCoName');"></input>
					</th>
				</tr>
				<tr>
					<th class="label">Expert Type:</th>
					<th class="info" style="font-weight: normal"><?php
					$val_array = get_customer_expert();
					print make_dropdown_list("","cExpert","",$val_array,"Expert Type","leadsEditUpdate('cExpert')");
					?></th>
				</tr>
				<tr>
					<th class="label">Fax: <img id="cpType4"
						src="../images/phone_contact.png"
						style="height: 11px; width: 11px;" onclick="callPhone('cPhone4');"
						title="Phone"></img></th>
					<th class="info" style="font-weight: normal"><input type="text"
						id="cPhone4" size=18 onchange="leadsEditUpdate('cPhone4');"></input>
					</th>
				</tr>
				<tr>
					<th class="label">Cell: <img id="cpType5"
						src="../images/phone_contact.png"
						style="height: 11px; width: 11px;" onclick="callPhone('cPhone5');"
						title="Phone"></img></th>
					<th class="info" style="font-weight: normal"><input type="text"
						id="cPhone5" size=18 onchange="leadsEditUpdate('cPhone5');"></input>
					</th>
				</tr>
			</tbody>
		</table>
		</td>
	</tr>
	<tr><td>
	<center><input type="button" style="width: 120px; height: 30px; font-size:14px; border: 2px solid black;"
	onclick="editLeadsHelp();" value="Save Changes"></input> <input
	type="button" style="width: 120px; height: 30px; font-size:14px; border: 2px solid black;"
	onclick="refreshLeads();" value="Cancel Changes"></input></center>
	</td></tr>
</table>

<table style="position: absolute; left: 320px; top: 350px;">
	<thead>
		<tr>
			<th class="head" style="color: #ffffff; background-color: #000000;">Primary
			Contact</th>
			<th class="info" id="cNote" style="font-weight: normal"></th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<th class="address">Unit:</th>
			<th class="info" style="font-weight: normal"><input type="text"
				id="cUnitB" size=8 onchange="leadsEditUpdate('cUnitB');"></input></th>
		</tr>
		<tr>
			<th class="address">Address:</th>
			<th class="info" style="font-weight: normal"><input type="text"
				id="cAdrBus" size=35 onchange="leadsEditUpdate('cAdrBus');"></input>
			</th>
		</tr>
		<tr>
			<th class="address">City:</th>
			<th class="info" style="font-weight: normal"><input type="text"
				id="cCityB" size=35 onchange="leadsEditUpdate('cCityB');"></input></th>
		</tr>
		<tr>
			<th class="address">Province:</th>
			<th class="info" style="font-weight: normal"><?php
			$val_array = getProvList();
			print make_dropdown_list("","cProvB","",$val_array,"Province of Business Address","leadsEditUpdate('cProvB')");
			?></th>
		</tr>
		<tr>
			<th class="address">Zip Code:</th>
			<th class="info" style="font-weight: normal"><input type="text"
				id="cZipB" size=10 onchange="checkZip('cZipB');" maxlength=7></input></th>
		</tr>
		<tr>
			<th class="address">Contact: <img id="cpType1"
				src="../images/phone_contact.png" style="height: 11px; width: 11px;"
				onclick="callPhone('cPhone1');" title="Phone"></img></th>
			<th class="info" style="font-weight: normal"><input type="text"
				id="cPhone1" size=18 onchange="leadsEditUpdate('cPhone1');"></input>
			</th>
		</tr>
		<tr>
			<th class="address">Email: <img src="../images/menu_sendmail.png"
				onclick="sendMail('cEmail1');" title="Email"></img></th>
			<th class="info" style="font-weight: normal"><input type="text"
				id="cEmail1" size=35 onchange="leadsEditUpdate('cEmail1');"></input>
			</th>
		</tr>
	</tbody>
</table>

<table style="position: absolute; left: 660px; top: 350px;">
	<thead>
		<tr>
			<th class="head" style="color: #ffffff; background-color: #000000;">Secondary
			Contact</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<th class="address">Unit:</th>
			<th class="info" style="font-weight: normal"><input type="text"
				id="cUnitH" size=8 onchange="leadsEditUpdate('cUnitH');"></input></th>
		</tr>
		<tr>
			<th class="address">Address:</th>
			<th class="info" style="font-weight: normal"><input type="text"
				id="cAdrHome" size=35 onchange="leadsEditUpdate('cAdrHome');"></input>
			</th>
		</tr>
		<tr>
			<th class="address">City:</th>
			<th class="info" style="font-weight: normal"><input type="text"
				id="cCityH" size=35 onchange="leadsEditUpdate('cCityH');"></input></th>
		</tr>
		<tr>
			<th class="address">Province:</th>
			<th class="info" style="font-weight: normal"><?php
			$val_array = getProvList();
			print make_dropdown_list("","cProvH","",$val_array,"Province of Home Address","leadsEditUpdate('cProvH')");
			?></th>
		</tr>
		<tr>
			<th class="address">Zip Code:</th>
			<th class="info" style="font-weight: normal"><input type="text"
				id="cZipH" size=10 onchange="checkZip('cZipH');" maxlength=7></input></th>
		</tr>
		<tr>
			<th class="address">Contact: <img id="cpType2"
				src="../images/phone_contact.png" style="height: 11px; width: 11px;"
				onclick="callPhone('cPhone2');" title="Email"></img></th>
			<th class="info" style="font-weight: normal"><input type="text"
				id="cPhone2" size=18 onchange="leadsEditUpdate('cPhone2');"></input>
			</th>
		</tr>
		<tr>
			<th class="address">Email: <img src="../images/menu_sendmail.png"
				onclick="sendMail('cEmail2');" title="Email"></img></th>
			<th class="info" style="font-weight: normal"><input type="text"
				id="cEmail2" size=35 onchange="leadsEditUpdate('cEmail2');"></input>
			</th>
		</tr>
	</tbody>
</table>
<table style="position: absolute; left: 1000px; top: 350px;">
	<thead>
		<tr>
			<th class="head" style="color: #ffffff; background-color: #000000;">Additional
			Contact</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<th class="address">Unit:</th>
			<th class="info" style="font-weight: normal"><input type="text"
				id="cUnitS" size=8 onchange="leadsEditUpdate('cUnitS');"></input></th>
		</tr>
		<tr>
			<th class="address">Address:</th>
			<th class="info" style="font-weight: normal"><input type="text"
				id="cAdrShip" size=35 onchange="leadsEditUpdate('cAdrShip');"></input>
			</th>
		</tr>
		<tr>
			<th class="address">City:</th>
			<th class="info" style="font-weight: normal"><input type="text"
				id="cCityS" size=35 onchange="leadsEditUpdate('cCityS');"></input></th>
		</tr>
		<tr>
			<th class="address">Province:</th>
			<th class="info" style="font-weight: normal"><?php
			$val_array = getProvList();
			print make_dropdown_list("","cProvS","",$val_array,"Province of Shipping Address","leadsEditUpdate('cProvS')");
			?></th>
		</tr>
		<tr>
			<th class="address">Zip Code:</th>
			<th class="info" style="font-weight: normal"><input type="text"
				id="cZipS" size=10 onchange="checkZip('cZipS');" maxlength=7></input></th>
		</tr>
		<tr>
			<th class="address">Contact: <img id="cpType3"
				src="../images/phone_contact.png" style="height: 11px; width: 11px;"
				onclick="callPhone('cPhone3');" title="Phone"></img></th>
			<th class="info" style="font-weight: normal"><input type="text"
				id="cPhone3" size=18 onchange="leadsEditUpdate('cPhone3');"></input>
			</th>
		</tr>
		<tr>
			<th class="address">Email: <img src="../images/menu_sendmail.png"
				onclick="sendMail('cEmail3');" title="Email"></img></th>
			<th class="info" style="font-weight: normal"><input type="text"
				id="cEmail3" size=35 onchange="leadsEditUpdate('cEmail3');"></input>
			</th>
		</tr>
	</tbody>
</table>
</div>
</div>
<br />

<div id="callControl">
<div id="contactCard">
<table>
<tr><td><label><b>Date: </b></label><label id="cLastCont"></label></tr>
<tr><td><label><b>Contact Type: </b></label><label id="cContType"></label></td></tr>
<tr><td><label><b>Contact Success: </b></label><label id="cContCode"></label></td></tr>
<?php if($_SESSION['exp_user']['usrlevel'] == "1") { ?>
<tr><td><label><b>Staff: </b></label><label id="cContStaff"></label></td></tr>
<?php }?>
</table>
</div>
<div id="contactCard_control">
<input type="button" style="height:28px; width:60px;" value="Update" onclick="updateContact();"></input>
</div>
<div id="doNotCall">
	<center><label for="cDND" id="lb_cDND" style="color:white"></label></center>
</div>
<div id="doNotCall_control">
	<input id="cDND" type="checkbox" onclick="setDND();"></input>
</div>
</div>

<div id="addToCustomer">
	<input type="button" style="background: url('../images/lead_addcust.png') no-repeat; height:40px; width: 150px;" value="Add to Customer" onclick="add2Cust();"></input>
	<input type="button" style="background: url('../images/lead_fax.png') no-repeat; height:40px; width: 100px;" value="Fax" onclick="scriptFax();"></input>
	<input type="button" style="background: url('../images/lead_email.png') no-repeat; height:40px; width: 120px;" value="Email" onclick="scriptMail();"></input>
	<br />
	<input type="button" style="background: url('../images/phone_business.png') no-repeat; height:40px; width: 376px;" value="Phone Script" onclick="openPhoneScript();"></input>
</div>

<div id="footer">
<table>
	<tr>
		<td><a style="border: 0; padding-top: 1px; width: 90px;"
			class="footeritem" href="#" onclick="showAddLeadsPopup();"
			title="Add a new leads"><img src="../images/menu_addcustomer.png"
			border=0></img>Add Leads</a></td>
	</tr>
</table>
</div>
<div id="backgroundPopup"></div>

<div id="addLeadsPopupContact"><a id="addLeadsPopupContactClose">x</a>
<h1>Add New Leads</h1>
<div id="addLeadsPopupContent">
<div id="addLeadsPopupControl">
<center>
<table>
	<tr>
		<td><a href="#" onclick="addNewCustomer();">Save</a></td>
		<td><a href="#" onclick="clearAddCustomer();">Reset</a></td>
		<td><a href="#" onclick="cancelAddCustomerPopup();">Cancel</a></td>
		<td><a href="#" onclick="disableAddCustomerPopup();">Close</a></td>
	</tr>
</table>
</center>
</div>
<div id="add1">
<table>
	<thead>
		<tr>
			<th class="head" style="color: #ffffff; background-color: #000000;">Lead
			Information</th>
			<th id="cSPC" class="info" style="font-weight: normal"></th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<th class="label">Title:</th>
			<th class="info" style="font-weight: normal"><?php
			$val_array = get_customer_title();
			print make_dropdown_list("","cTitle","",$val_array,"Customer Title","leadsEditUpdate('cTitle')");
			?></th>
		</tr>
		<tr>
			<th class="label">First Name:</th>
			<th class="info" style="font-weight: normal"><input type="text"
				id="cFirstN" size=30 onchange="leadsEditUpdate('cFirstN');"></input>
			</th>
		</tr>
		<tr>
			<th class="label">Last Name:</th>
			<th class="info" style="font-weight: normal"><input type="text"
				id="cLastN" size=30 onchange="leadsEditUpdate('cLastN');"></input></th>
		</tr>
		<tr>
			<th class="label">AKA:</th>
			<th class="info" style="font-weight: normal"><input type="text"
				id="cAKA" size=25 onchange="leadsEditUpdate('cAKA');"></input></th>
		</tr>
		<tr>
			<th class="label">Date of Birth:</th>
			<th class="info" style="font-weight: normal"><input type="text"
				id="cDoB" size=25 onchange="leadsEditUpdate('cDoB');"></input></th>
		</tr>
		<tr>
			<th class="label">Driver's License:</th>
			<th class="info" style="font-weight: normal"><input type="text"
				id="cRIN" size=25 onchange="leadsEditUpdate('cRIN');"></input></th>
		</tr>
		<tr>
			<th class="label">COM Number:</th>
			<th class="info" style="font-weight: normal"><input type="text"
				id="cCardNum" size=10 onchange="leadsEditUpdate('cCardNum');"></input>
			</th>
		</tr>
		<tr>
			<th class="label">Customer Type:</th>
			<th class="info" style="font-weight: normal"><?php
			$val_array = get_customer_type();
			print make_dropdown_list("","cCustType","",$val_array,"Customer Type","leadsEditUpdate('cCustType')");
			?></th>
		</tr>
		<tr>
			<th class="label">Company Name:</th>
			<th class="info" style="font-weight: normal"><input type="text"
				id="cCoName" size=30 onchange="leadsEditUpdate('cCoName');"></input>
			</th>
		</tr>
		<tr>
			<th class="label">Shirt Size:</th>
			<th class="info" style="font-weight: normal"><?php
			$val_array = get_shirt_list();
			print make_dropdown_list("","cShirtSize","",$val_array,"Shirt Size","leadsEditUpdate('cShirtSize')");
			?></th>
		</tr>
		<tr>
			<th class="label">Pant Size:</th>
			<th class="info" style="font-weight: normal"><input type="text"
				id="cPantSize" size=10 onchange="leadsEditUpdate('cPantSize');">
			(i.e. 30x30)</input></th>
		</tr>
		<tr>
			<th class="label">Expert Type:</th>
			<th class="info" style="font-weight: normal"><?php
			$val_array = get_customer_expert();
			print make_dropdown_list("","cExpert","",$val_array,"Expert Type","leadsEditUpdate('cExpert')");
			?></th>
		</tr>
		<tr>
			<th class="label">Fax: <img id="cpType4"
				src="../images/phone_contact.png" style="height: 11px; width: 11px;"
				onclick="callPhone('cPhone4');" title="Phone"></img></th>
			<th class="info" style="font-weight: normal"><input type="text"
				id="cPhone4" size=18 onchange="leadsEditUpdate('cPhone4');"></input>
			</th>
		</tr>
		<tr>
			<th class="label">Cell: <img id="cpType5"
				src="../images/phone_contact.png" style="height: 11px; width: 11px;"
				onclick="callPhone('cPhone5');" title="Phone"></img></th>
			<th class="info" style="font-weight: normal"><input type="text"
				id="cPhone5" size=18 onchange="leadsEditUpdate('cPhone5');"></input>
			</th>
		</tr>
	</tbody>
</table>
<table>
	<thead>
		<tr>
			<th class="head" style="color: #ffffff; background-color: #000000;">Primary
			Contact</th>
			<th class="info" id="cNote" style="font-weight: normal"></th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<th class="address">Unit:</th>
			<th class="info" style="font-weight: normal"><input type="text"
				id="cUnitB" size=8 onchange="leadsEditUpdate('cUnitB');"></input></th>
		</tr>
		<tr>
			<th class="address">Address:</th>
			<th class="info" style="font-weight: normal"><input type="text"
				id="cAdrBus" size=35 onchange="leadsEditUpdate('cAdrBus');"></input>
			</th>
		</tr>
		<tr>
			<th class="address">City:</th>
			<th class="info" style="font-weight: normal"><input type="text"
				id="cCityB" size=35 onchange="leadsEditUpdate('cCityB');"></input></th>
		</tr>
		<tr>
			<th class="address">Province:</th>
			<th class="info" style="font-weight: normal"><?php
			$val_array = getProvList();
			print make_dropdown_list("","cProvB","",$val_array,"Province of Business Address","leadsEditUpdate('cProvB')");
			?></th>
		</tr>
		<tr>
			<th class="address">Zip Code:</th>
			<th class="info" style="font-weight: normal"><input type="text"
				id="cZipB" size=10 onchange="checkZip('cZipB');" maxlength=7></input></th>
		</tr>
		<tr>
			<th class="address">Contact: <img id="cpType1"
				src="../images/phone_contact.png" style="height: 11px; width: 11px;"
				onclick="callPhone('cPhone1');" title="Phone"></img></th>
			<th class="info" style="font-weight: normal"><input type="text"
				id="cPhone1" size=18 onchange="leadsEditUpdate('cPhone1');"></input>
			</th>
		</tr>
		<tr>
			<th class="address">Email: <img src="../images/menu_sendmail.png"
				onclick="sendMail('cEmail1');" title="Email"></img></th>
			<th class="info" style="font-weight: normal"><input type="text"
				id="cEmail1" size=35 onchange="leadsEditUpdate('cEmail1');"></input>
			</th>
		</tr>
	</tbody>
</table>
<br />
<table style="position:absolute; top:550px; left: 275px;">
	<tr>
		<!-- <td><a href="#" onclick="submitSPC();">Previous</a></td> -->
		<td><a href="#" onclick="nextClick();" style="bottom: 50px;">Next</a></td>
	</tr>
</table>
</div>

<div id="add2">
<table>
	<thead>
		<tr>
			<th class="head" style="color: #ffffff; background-color: #000000;">Secondary
			Contact</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<th class="address">Unit:</th>
			<th class="info" style="font-weight: normal"><input type="text"
				id="cUnitH" size=8 onchange="leadsEditUpdate('cUnitH');"></input></th>
		</tr>
		<tr>
			<th class="address">Address:</th>
			<th class="info" style="font-weight: normal"><input type="text"
				id="cAdrHome" size=35 onchange="leadsEditUpdate('cAdrHome');"></input>
			</th>
		</tr>
		<tr>
			<th class="address">City:</th>
			<th class="info" style="font-weight: normal"><input type="text"
				id="cCityH" size=35 onchange="leadsEditUpdate('cCityH');"></input></th>
		</tr>
		<tr>
			<th class="address">Province:</th>
			<th class="info" style="font-weight: normal"><?php
			$val_array = getProvList();
			print make_dropdown_list("","cProvH","",$val_array,"Province of Home Address","leadsEditUpdate('cProvH')");
			?></th>
		</tr>
		<tr>
			<th class="address">Zip Code:</th>
			<th class="info" style="font-weight: normal"><input type="text"
				id="cZipH" size=10 onchange="checkZip('cZipH');" maxlength=7></input></th>
		</tr>
		<tr>
			<th class="address">Contact: <img id="cpType2"
				src="../images/phone_contact.png" style="height: 11px; width: 11px;"
				onclick="callPhone('cPhone2');" title="Email"></img></th>
			<th class="info" style="font-weight: normal"><input type="text"
				id="cPhone2" size=18 onchange="leadsEditUpdate('cPhone2');"></input>
			</th>
		</tr>
		<tr>
			<th class="address">Email: <img src="../images/menu_sendmail.png"
				onclick="sendMail('cEmail2');" title="Email"></img></th>
			<th class="info" style="font-weight: normal"><input type="text"
				id="cEmail2" size=35 onchange="leadsEditUpdate('cEmail2');"></input>
			</th>
		</tr>
	</tbody>
</table>
<table>
	<thead>
		<tr>
			<th class="head" style="color: #ffffff; background-color: #000000;">Additional
			Contact</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<th class="address">Unit:</th>
			<th class="info" style="font-weight: normal"><input type="text"
				id="cUnitS" size=8 onchange="leadsEditUpdate('cUnitS');"></input></th>
		</tr>
		<tr>
			<th class="address">Address:</th>
			<th class="info" style="font-weight: normal"><input type="text"
				id="cAdrShip" size=35 onchange="leadsEditUpdate('cAdrShip');"></input>
			</th>
		</tr>
		<tr>
			<th class="address">City:</th>
			<th class="info" style="font-weight: normal"><input type="text"
				id="cCityS" size=35 onchange="leadsEditUpdate('cCityS');"></input></th>
		</tr>
		<tr>
			<th class="address">Province:</th>
			<th class="info" style="font-weight: normal"><?php
			$val_array = getProvList();
			print make_dropdown_list("","cProvS","",$val_array,"Province of Shipping Address","leadsEditUpdate('cProvS')");
			?></th>
		</tr>
		<tr>
			<th class="address">Zip Code:</th>
			<th class="info" style="font-weight: normal"><input type="text"
				id="cZipS" size=10 onchange="checkZip('cZipS');" maxlength=7></input></th>
		</tr>
		<tr>
			<th class="address">Contact: <img id="cpType3"
				src="../images/phone_contact.png" style="height: 11px; width: 11px;"
				onclick="callPhone('cPhone3');" title="Phone"></img></th>
			<th class="info" style="font-weight: normal"><input type="text"
				id="cPhone3" size=18 onchange="leadsEditUpdate('cPhone3');"></input>
			</th>
		</tr>
		<tr>
			<th class="address">Email: <img src="../images/menu_sendmail.png"
				onclick="sendMail('cEmail3');" title="Email"></img></th>
			<th class="info" style="font-weight: normal"><input type="text"
				id="cEmail3" size=35 onchange="leadsEditUpdate('cEmail3');"></input>
			</th>
		</tr>
	</tbody>
</table>
<table style="position:absolute; top:550px; left: 150px;">
	<tr>
		<td><a href="#" onclick="prevClick();">Previous</a></td>
	</tr>
</table>
</div>
</div>
</div>
</body>

</html>
