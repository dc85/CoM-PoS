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
?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Customer</title>

<link href="../css/customer.css" rel="stylesheet" type="text/css" />
<link href="../css/datepicker.css" rel="stylesheet" type="text/css" />
<link href="../css/jquery.impromptu.css" rel="stylesheet"
	type="text/css" />
<script src="../js/jquery.js" type="text/javascript"></script>
<script src="../js/date.js" type="text/javascript"></script>
<script src="../js/menu.js" type="text/javascript"></script>
<script src="../js/jquery.hotkeys.js" type="text/javascript"></script>
<script src="../js/jquery.impromptu.js" type="text/javascript"></script>
<script src="../js/jquery.datePicker.js" type="text/javascript"></script>
<script type="text/javascript" src="../js/jquery.bgiframe.js"></script>
<script src="../js/general.js" type="text/javascript"></script>
<script src="../js/customer.js" type="text/javascript"></script>
<script type="text/javascript">
	$(document).keypress(function(e){
		if(e.keyCode==13){
			reloadCIframe();
		}
	});
	
	function setFocus() {
		document.getElementById("custSearch").focus();
		var id = document.getElementById('presStaff').value;
		//document.getElementById('invTeller').selectedIndex = id;
		document.getElementById('repayTeller').selectedIndex = id;
		document.getElementById('new_cCredit').value = 0.00;
		document.getElementById('new_cSPC').selectedIndex = 1;
	}

	/*function setLoad(sid,cid) {
		choose_rcust(sid,cid);
		//alert(sid + "/" + cid);
		document.getElementById("custSearch").focus();
		var id = document.getElementById('presStaff').value;
		//document.getElementById('invTeller').selectedIndex = id;
		document.getElementById('repayTeller').selectedIndex = id;
	}*/

	Date.firstDayOfWeek = 0;
	//Date.format = 'mmm/dd/yyyy';
	$(function()
			{
				$('#hireP-date')
				.datePicker({inline:true})
				.bind(
						'dateSelected',
						function(e, selectedDate, $td)
						{
							var today = selectedDate.toDateString();
							document.getElementById("hpDate").value = today.substr(0,3) + "," + today.substr(3);
						}
					);

				$('#hireCC-date')
				.datePicker({inline:true})
				.bind(
						'dateSelected',
						function(e, selectedDate, $td)
						{
							var today = selectedDate.toDateString();
							document.getElementById("hccDate").value = today.substr(0,3) + "," + today.substr(3);
						}
					);

				$('#start-date')
					.datePicker({startDate:'01/01/1996',inline:true})
					.bind(
							'dateSelected',
							function(e, selectedDate, $td)
							{
								//alert($('#start-date').dpGetSelected());
								//document.getElementById('date-start').value = new Date(selectedDate);
								//var list = selectedDate.asString().split('/');
								//document.getElementById('date-start').value = list[2]+"/"+list[0]+"/"+list[1];
								//document.getElementById('date-start').value = selectedDate.toDateString();
								document.getElementById('date-s').value = selectedDate.toDateString();
								document.getElementById('date-start').value = selectedDate.getFullYear() + "-" + (selectedDate.getMonth()+1) + "-" + selectedDate.getDate();  
								var range = document.getElementById("lookupDates").value;
								if(range == "0") {
									//alert(document.getElementById("date-end").value);
									if(document.getElementById("date-end").value !== "TO") {
										
										//$("#start-date").datePicker({startDate:'01/01/1996',inline:true});
										//$("#end-date").datePicker({startDate:'01/01/1996',inline:true});
										lookup_range();
										//$("#start-date").datePicker({startDate:'01/01/1996',inline:true}).dpSetSelected(new Date().asString());
										//$("#end-date").datePicker({startDate:'01/01/1996',inline:true}).dpSetSelected(new Date().asString());
									}
								} else {
									//alert("A");
									var endDateObj = new Date(selectedDate.getTime() + range*24*60*60*1000);
									document.getElementById('date-e').value = endDateObj.toDateString();
									$('#end-date').dpSetSelected(endDateObj.asString());
									setTimeout("lookup_range()",100);
								}
							}
						);
				$('#end-date')
					.datePicker({startDate:'01/01/1996',inline:true})
					.bind(
							'dateSelected',
							function(e, selectedDate, $td)
							{
								//document.getElementById('date-end').value = new Date(selectedDate);
								//$('#date-end').dpSetSelected(selectedDate.asString());
								//var list = selectedDate.asString().split('/');
								//document.getElementById('date-end').value = list[2]+"/"+list[0]+"/"+list[1];
								document.getElementById('date-e').value = selectedDate.toDateString();
								document.getElementById('date-end').value = selectedDate.getFullYear() + "-" + (selectedDate.getMonth()+1) + "-" + selectedDate.getDate();
								var range = document.getElementById("lookupDates").value;
								if(range == "0") {
									//alert(document.getElementById("date-start").value);
									if(document.getElementById("date-start").value !== "FROM") {
										lookup_range();
										//$("#start-date").datePicker({startDate:'01/01/1996',inline:true}).dpSetSelected(new Date().asString());
										//$("#end-date").datePicker({startDate:'01/01/1996',inline:true}).dpSetSelected(new Date().asString());
									}
								} else {
									//alert("A");
									var startDateObj = new Date(selectedDate.getTime() - range*24*60*60*1000);
									document.getElementById('date-s').value = startDateObj.toDateString();
									$('#start-date').dpSetSelected(startDateObj.asString());
									setTimeout("lookup_range()",100);
								}
							}
						);
				Date.format = 'yyyy-mm-dd';
				$('.date-pick').datePicker({startDate:'1996-01-01'});
				
				$('.date-s-pick').datePicker({startDate:'1996-01-01'});
				//$('.datePick2').datePicker({startDate:'2010-02-16'});
			});
</script>
</head>
<?php  
/*if(isset($_GET['loadchoice'])) {
	print "<body onload=\"setLoad('".$_GET['sID']."','".$_GET['cID']."');\">";
} else {
	print "<body onload=\"setFocus();\">";
}*/

?>
<body onload="setFocus();">
<div id="debug"><textarea id="debugger"
	style="width: 380px; height: 400px; background: black; color: green;"></textarea>
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
	print "<td><a href=\"#\" onclick=\"JavaScript:openLeads();\" title=\"Leads List/Information\"><img src=\"../images/menu_leads.png\" border=0></img> Leads</a></td>";
	print "<td><a href=\"#\" style=\"background:#F3F3F3;color:#2C2E22;cursor:pointer;\" title=\"Customer List/Information\"><img src=\"../images/menu_customer.png\" border=0></img> Customer</a></td>";
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
	print "<td><a href=\"#\" style=\"background:#F3F3F3;color:#2C2E22;cursor:pointer;\" title=\"Customer List/Information\"><img src=\"../images/menu_customer.png\" border=0></img> Customer</a></td>";
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
	print "<td><a href=\"#\" style=\"background:#F3F3F3;color:#2C2E22;cursor:pointer;\" title=\"Customer List/Information\"><img src=\"../images/menu_customer.png\" border=0></img> Customer</a></td>";
	print "<td><a href=\"#\" onclick=\"JavaScript:openRental();\" title=\"Store Rentals\"><img src=\"../images/menu_rental.png\" border=0></img> Rental</a></td>";
	print "<td><a href=\"#\" onclick=\"JavaScript:openReports();\" title=\"View Store Reports\"><img src=\"../images/menu_reports.png\" border=0></img> Reports</a></td>";
	print "<td><a href=\"#\" onclick=\"JavaScript:openScheduler();\" title=\"Schedule Staff Shift\"><img src=\"../images/menu_scheduler.png\"  border=0></img> Scheduler</a></td>";
	print "<td><a href=\"#\" onclick=\"JavaScript:openSeminar();\" title=\"Setup Seminar Sessions\"><img src=\"../images/menu_seminar.png\" border=0></img> Seminar</a></td>";
	print "<td><a href=\"#\" onclick=\"JavaScript:openLabels();\" title=\"Birthday Label Printing\"><img src=\"../images/menu_labels.png\" border=0></img> Labels</a></td>";
	print "</tr></table>";
	print "</div>";
}
?>
<div id="control">
<!-- <select id="custSearchRange" size="1">
	<option value="all">ALL</option>
	<option value="leads">LEADS ONLY</option>
	<option value="cust">CUSTOMER ONLY</option>
</select> -->
<input type="text" name="custSearch" id="custSearch" size=50 title="Enter Product Name,Description,UPC,ProdCode,or Sku"></input>
<input type="hidden" id="presStaff"
	value="<?php print $_SESSION['exp_user']['sID'];?>"></input> 
<input type="hidden" id="defStoreID"
	value="<?php print $_SESSION['exp_user']['localSID'];?>"></input> <input
	type="button" name="custSubmit" value="Search Customer"
	onclick="reloadCIframe();" style="border:2px solid grey;"></input>
	<input type="button" name="custReset" value="Reset"
	onclick="resetCustSearch();" style="border:2px solid grey;"></input> <input type="hidden"
	id="selSID" value="-1"></input> <input type="hidden" id="selCID"
	value="-1"></input><input type="hidden" id="selSPC" value="-1"></input><input
	type="hidden" id="selFirstN" value="-1"></input><input type="hidden"
	id="selLastN" value="-1"></input><input type="hidden" id="updateCount"
	value="0"></input></div>
</div>

<div id="main">
<div id="table"><iframe src="../php/customer_print.php"
	name="tableFrame" onload="hideLoadingZone()" frameborder="0" WIDTH=100%
	HEIGHT=100%> </iframe>
<div id="loadingZone">Loading...</div>
</div>
<div id="container">
<div id="info">
<table>
	<tr>
		<td>
		<table>
			<thead>
				<tr>
					<th class="head">Customer
					Information</th>
					<th><input type=button id="cIsActive" value="ACTIVE"
						style="font-size: 8pt; border: 1px solid;"></input></th>
				</tr>
			</thead>
		</table>
		<table>
			<tbody>
				<tr>
					<th class="label">Title:</th>
					<th class="info" style="font-weight: normal"><?php
					print make_dropdown_list("","cTitle","",$title_array,"Customer Title","custEditUpdate('cTitle')");
					?></th>
				</tr>
				<tr>
					<th class="label">First Name:</th>
					<th class="info" style="font-weight: normal"><input type="text"
						id="cFirstN" size=30 onchange="custEditUpdate('cFirstN');"></input>
					</th>
				</tr>
				<tr>
					<th class="label">Last Name:</th>
					<th class="info" style="font-weight: normal"><input type="text"
						id="cLastN" size=30 onchange="custEditUpdate('cLastN');"></input>
					</th>
				</tr>
				<tr>
					<th class="label">AKA:</th>
					<th class="info" style="font-weight: normal"><input type="text"
						id="cAKA" size=15 onchange="custEditUpdate('cAKA');"></input></th>
				</tr>
				<tr>
					<th class="label">Date of Birth:</th>
					<th class="info" style="font-weight: normal"><input type="text"
						id="cDoB" size=20 onchange="custEditUpdate('cDoB');"></input></th>
				</tr>
				<tr>
					<th class="label">Driver's License:</th>
					<th class="info" style="font-weight: normal"><input type="text"
						id="cRIN" size=25 onchange="custEditUpdate('cRIN');"></input></th>
				</tr>
				<tr>
					<th class="label">COM Number:</th>
					<th class="info" style="font-weight: normal"><input type="text"
						id="cCardNum" size=10 onchange="custEditUpdate('cCardNum');"></input>
					</th>
				</tr>
				<tr>
					<th class="label">Customer Type:</th>
					<th class="info" style="font-weight: normal"><?php
					print make_dropdown_list("","cCustType","",$ctype_array,"Customer Type","custEditUpdate('cCustType')");
					?></th>
				</tr>
				<tr>
					<th class="label">Company Name:</th>
					<th class="info" style="font-weight: normal"><input type="text"
						id="cCoName" size=30 onchange="custEditUpdate('cCoName');"></input>
					</th>
				</tr>
				<tr>
					<th class="label">Shirt Size:</th>
					<th class="info" style="font-weight: normal"><?php
					//$shirts_array = get_shirt_list();
					print make_dropdown_list("","cShirtSize","",$shirts_array,"Shirt Size","custEditUpdate('cShirtSize')");
					?></th>
				</tr>
				<tr>
					<th class="label">Pant Size:</th>
					<th class="info" style="font-weight: normal"><input type="text"
						id="cPantSize" size=10 onchange="custEditUpdate('cPantSize');">
					(i.e. 30x30)</input></th>
				</tr>
				<tr>
					<th class="label">Expert Type:</th>
					<th class="info" style="font-weight: normal"><?php
					//$val_array = get_customer_expert();
					print make_dropdown_list("","cExpert","",$cexpert_array,"Expert Type","custEditUpdate('cExpert')");
					?></th>
				</tr>
				<tr>
					<th class="label">Fax: <img id="cpType4"
						src="../images/phone_contact.png"
						style="height: 11px; width: 11px;" onclick="callPhone('cPhone4');"
						title="Phone"></img></th>
					<th class="info" style="font-weight: normal"><input type="text"
						id="cPhone4" size=18 onchange="custEditUpdate('cPhone4');"></input>
					</th>
				</tr>
				<tr>
					<th class="label">Cell: <img id="cpType5"
						src="../images/phone_contact.png"
						style="height: 11px; width: 11px;" onclick="callPhone('cPhone5');"
						title="Phone"></img></th>
					<th class="info" style="font-weight: normal"><input type="text"
						id="cPhone5" size=18 onchange="custEditUpdate('cPhone5');"></input>
					</th>
				</tr>
				<tr>
					<th class="label">Awareness: </th>
					<th class="info" style="font-weight: normal"><select id="cAware">
						<option value="-1"></option>
						<option value="0">Direct Mail</option>
						<option value="1">Fax</option>
						<option value="2">News Paper/Magazine</option>
						<option value="3">TV ads</option>
						<option value="4">Radio ads</option>
						<option value="5">Phone Book/411</option>
						<option value="6">Drive by</option>
						<option value="7">Other</option>
					</select>
					</th>
				</tr>
			</tbody>
		</table>
		</td>
	</tr>
</table>
<br />
<center><input type="button" style="width: 120px; height: 40px; font-size:14px; border: 2px solid black;"
	onclick="editCustomerHelp();" value="Save Changes"></input> <input
	type="button" style="width: 120px; height: 40px; font-size:14px; border: 2px solid black;"
	onclick="refreshCustomer();" value="Cancel Changes"></input></center>
</div>
<div id="contacts">
<table>
	<thead>
		<tr>
			<th class="head">Customer
			Expertise</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<th class="expertise">Interior:</th>
			<th class="expertise"><input type="checkbox" id="cE1" onclick="custEditUpdate('cE1');"></input></th>
			<th class="expertise">Deck & Fence:</th>
			<th class="expertise"><input type="checkbox" id="cE6" onclick="custEditUpdate('cE6');"></input></th>
		</tr>
		<tr>
			<th class="expertise">Exterior:</th>
			<th class="expertise"><input type="checkbox" id="cE2" onclick="custEditUpdate('cE2');"></input></th>
			<th class="expertise">Drywall & Taping:</th>
			<th class="expertise"><input type="checkbox" id="cE7" onclick="custEditUpdate('cE7');"></input></th>
		</tr>
		<tr>
			<th class="expertise">Residential:</th>
			<th class="expertise"><input type="checkbox" id="cE3" onclick="custEditUpdate('cE3');"></input></th>
			<th class="expertise">Spraying:</th>
			<th class="expertise"><input type="checkbox" id="cE8" onclick="custEditUpdate('cE8');"></input></th>
		</tr>
		<tr>
			<th class="expertise">Commercial:</th>
			<th class="expertise"><input type="checkbox" id="cE4" onclick="custEditUpdate('cE4');"></input></th>
			<th class="expertise">Faux Finish:</th>
			<th class="expertise"><input type="checkbox" id="cE9" onclick="custEditUpdate('cE9');"></input></th>
		</tr>
		<tr>
			<th class="expertise">Stain Work:</th>
			<th class="expertise"><input type="checkbox" id="cE5" onclick="custEditUpdate('cE5');"></input></th>
			<th class="expertise">Wall Paper:</th>
			<th class="expertise"><input type="checkbox" id="cE10" onclick="custEditUpdate('cE10');"></input></th>
		</tr>
	</tbody>
</table>
<table>
	<thead>
		<tr>
			<th class="head">Primary
			Contact</th>
		</tr>
	</thead>
</table>
<table>
	<tbody>
		<tr>
			<th class="address">Unit:</th>
			<th class="info" style="font-weight: normal"><input type="text"
				id="cUnitB" size=8 onchange="custEditUpdate('cUnitB');"></input></th>
		</tr>
		<tr>
			<th class="address">Address:</th>
			<th class="info" style="font-weight: normal"><input type="text"
				id="cAdrBus" size=32 onchange="custEditUpdate('cAdrBus');"></input>
			</th>
		</tr>
		<tr>
			<th class="address">City:</th>
			<th class="info" style="font-weight: normal"><input type="text"
				id="cCityB" size=25 onchange="custEditUpdate('cCityB');"></input></th>
		</tr>
		<tr>
			<th class="address">Province:</th>
			<th class="info" style="font-weight: normal"><?php
			print make_dropdown_list("","cProvB","",$prov_array,"Province of Business Address","custEditUpdate('cProvB')");
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
				id="cPhone1" size=18 onchange="custEditUpdate('cPhone1');"></input>
			</th>
		</tr>
		<tr>
			<th class="address">Email: <img src="../images/menu_sendmail.png"
				onclick="sendMail('cEmail1');" title="Email"></img></th>
			<th class="info" style="font-weight: normal"><input type="text"
				id="cEmail1" size=30 onchange="custEditUpdate('cEmail1');"></input>
			</th>
		</tr>
	</tbody>
</table>

<center>
	<input type="button" style="width: 120px; height: 23px; font-size:14px; border: 1px solid black; margin-bottom: 1px; margin-top: 2px;"
	onclick="printLabel()" value="Print Packing Slip"></input> <input
	type="button" style="width: 120px; height: 23px; font-size:14px; border: 1px solid black; margin-bottom: 1px; margin-top: 2px;"
	onclick="printLabel()" value="Print Label"></input>
	<br />
	<input type="button" style="width: 120px; height: 23px; font-size:14px; border: 1px solid black; margin-bottom: 1px;"
	onclick="alert('print card function')" value="Print Card"></input> <input
	type="button" style="width: 120px; height: 23px; font-size:14px; border: 1px solid black; margin-bottom: 1px;"
	onclick="alert('detailed report')" value="Detailed Report"></input>
	<br />
	<input type="button" style="width: 120px; height: 23px; font-size:14px; border: 1px solid black; margin-bottom: 1px;"
	onclick="addToSeminar()" value="Add to Seminar"></input> <input
	type="button" style="width: 120px; height: 23px; font-size:14px; border: 1px solid black; margin-bottom: 1px;"
	onclick="alert('purchase history')" value="Purchase History"></input>
	</center>
</div>
<div id="contacts2">
<table>
	<thead>
		<tr>
			<th class="head">Secondary
			Contact</th>
		</tr>
	</thead>
</table>
<table>
	<tbody>
		<tr>
			<th class="address">Unit:</th>
			<th class="info" style="font-weight: normal"><input type="text"
				id="cUnitH" size=8 onchange="custEditUpdate('cUnitH');"></input></th>
		</tr>
		<tr>
			<th class="address">Address:</th>
			<th class="info" style="font-weight: normal"><input type="text"
				id="cAdrHome" size=32 onchange="custEditUpdate('cAdrHome');"></input>
			</th>
		</tr>
		<tr>
			<th class="address">City:</th>
			<th class="info" style="font-weight: normal"><input type="text"
				id="cCityH" size=25 onchange="custEditUpdate('cCityH');"></input></th>
		</tr>
		<tr>
			<th class="address">Province:</th>
			<th class="info" style="font-weight: normal"><?php
			//$val_array = getProvList();
			print make_dropdown_list("","cProvH","",$prov_array,"Province of Home Address","custEditUpdate('cProvH')");
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
				id="cPhone2" size=18 onchange="custEditUpdate('cPhone2');"></input>
			</th>
		</tr>
		<tr>
			<th class="address">Email: <img src="../images/menu_sendmail.png"
				onclick="sendMail('cEmail2');" title="Email"></img></th>
			<th class="info" style="font-weight: normal"><input type="text"
				id="cEmail2" size=30 onchange="custEditUpdate('cEmail2');"></input>
			</th>
		</tr>
	</tbody>
</table>
<table>
	<thead>
		<tr>
			<th class="head">Additional
			Contact</th>
		</tr>
	</thead>
</table>
<table>
	<tbody>
		<tr>
			<th class="address">Unit:</th>
			<th class="info" style="font-weight: normal"><input type="text"
				id="cUnitS" size=8 onchange="custEditUpdate('cUnitS');"></input></th>
		</tr>
		<tr>
			<th class="address">Address:</th>
			<th class="info" style="font-weight: normal"><input type="text"
				id="cAdrShip" size=32 onchange="custEditUpdate('cAdrShip');"></input>
			</th>
		</tr>
		<tr>
			<th class="address">City:</th>
			<th class="info" style="font-weight: normal"><input type="text"
				id="cCityS" size=25 onchange="custEditUpdate('cCityS');"></input></th>
		</tr>
		<tr>
			<th class="address">Province:</th>
			<th class="info" style="font-weight: normal"><?php
			//$val_array = getProvList();
			print make_dropdown_list("","cProvS","",$prov_array,"Province of Shipping Address","custEditUpdate('cProvS')");
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
				id="cPhone3" size=18 onchange="custEditUpdate('cPhone3');"></input>
			</th>
		</tr>
		<tr>
			<th class="address">Email: <img src="../images/menu_sendmail.png"
				onclick="sendMail('cEmail3');" title="Email"></img></th>
			<th class="info" style="font-weight: normal"><input type="text"
				id="cEmail3" size=30 onchange="custEditUpdate('cEmail3');"></input>
			</th>
		</tr>
	</tbody>
</table>
</div>
<div id="finances">
<table>
	<thead>
		<tr>
			<th class="head">Finance
			Information</th>
		</tr>
	</thead>
</table>
<table>
	<tbody>
		<tr>
			<th class="label">Tax 1:</th>
			<th class="info" style="font-weight: normal"><?php
			print make_dropdown_list("","cTax1","",$taxcat_array,"Customer Tax 1","custEditUpdate('cTax1')");
			?></th>
		</tr>
		<tr>
			<th class="label">Tax 2:</th>
			<th class="info" style="font-weight: normal"><?php
			//$val_array = get_tax_category();
			print make_dropdown_list("","cTax2","",$taxcat_array,"Customer Tax 2","custEditUpdate('cTax2')");
			?></th>
		</tr>
		<tr>
			<th class="label">SPC Level:</th>
			<th class="info" style="font-weight: normal"><?php
			print make_dropdown_list("","cSPC","",$spcl_array,"Customer SPC Level","custEditUpdate('cSPC')");
			?></th>
		</tr>
		<tr>
			<th class="label">ECO Fee:</th>
			<th class="info" style="font-weight: normal"><?php
			//$val_array = array("","Charge Eco Fee","No Eco Fee");
			print make_dropdown_list("","cEcoFee","",$efee_array,"Customer Eco Fee","custEditUpdate('cEcoFee')");
			?></th>
		</tr>
		<tr>
			<th class="label">Customer Rep:</th>
			<th class="info" style="font-weight: normal"><?php
			print make_dropdown_list("","cCustRep","",$staff_array,"Customer Rep.","custEditUpdate('cCustRep')");
			?></th>
		</tr>
		<tr>
			<th class="label">Excempt #:</th>
			<th class="info" style="font-weight: normal"><input type="text"
				id="cExpNum" size=20 onchange="custEditUpdate('cExpNum');"></input>
			</th>
		</tr>
		<tr>
			<th class="label">Expires:</th>
			<th class="info" style="font-weight: normal"><input type="text" class="date-s-pick"
				id="cExpDate" size=10 onchange="custEditUpdate('cExpDate');"></input>
			</th>
		</tr>
	</tbody>
</table>
<table>
	<thead>
		<tr>
			<th class="head">Store
			Credit and AR</th>
		</tr>
	</thead>
</table>
<table>
	<tbody>
		<tr>
			<th class="label">AR Credit:</th>
			<th class="info" style="font-weight: normal"><input type="text"
				id="cCredit" READONLY size=10 onchange="custEditUpdate('cCredit');"></input>
			</th>
		</tr>
		<tr>
			<th class="label">AR Remain:</th>
			<th class="info" style="font-weight: normal"><input type="text"
				id="cCBal" READONLY size=10 onchange="custEditUpdate('cCBal');"></input>
			</th>
		</tr>
		<tr>
			<th class="label">Current:</th>
			<th class="info" style="font-weight: normal"><input type="text"
				id="cCurrent" READONLY size=10
				onchange="custEditUpdate('cCurrent');"></input></th>
		</tr>
		<tr>
			<th class="label">31-60:</th>
			<th class="info" style="font-weight: normal"><input type="text"
				id="c30" READONLY size=10 onchange="custEditUpdate('c30');"></input>
			</th>
		</tr>
		<tr>
			<th class="label">61-90:</th>
			<th class="info" style="font-weight: normal"><input type="text"
				id="c60" READONLY size=10 onchange="custEditUpdate('c60');"></input>
			</th>
		</tr>
		<tr>
			<th class="label">90+:</th>
			<th class="info" style="font-weight: normal"><input type="text"
				id="c90" READONLY size=10 onchange="custEditUpdate('c90');"></input>
			</th>
		</tr>
		<tr>
			<th class="label">AR:</th>
			<th class="info" style="font-weight: normal"><input type="text"
				id="cBalance" READONLY size=10
				onchange="custEditUpdate('cBalance');"></input></th>
		</tr>
		<tr>
			<th class="label">Note:</th>
			<th class="info" style="font-weight: normal"><textarea id="cNote"
				rows="3" cols="55" onchange="custEditUpdate('cNote');"></textarea></th>
		</tr>
	</tbody>
</table>
</div>
</div>
</div>

<div id="footer">
<div id="footer_left">
<table align="left">
	<tr>
		<td><a style="border: 0; padding-top: 1px; width: 76px;"
			class="footeritem" href="#"
			onclick="JavaScript:openCustomerInvoice();"
			title="Checkout all items and open up the payment window"><img
			src="../images/menu_invoice.png" border=0></img> Invoice</a></td>
		<td><a style="border: 0; padding-top: 1px; width: 126px;"
			class="footeritem" href="#"
			onclick="JavaScript:showAddCustomerPopup();"
			title="Add a new customer"><img src="../images/menu_addcustomer.png"
			border=0></img> Add Customer</a></td>
		<td><a style="border: 0; padding-top: 1px; width: 126px;"
			class="footeritem" href="#"
			onclick="printPriceList();"
			title="Print Customer Price List"><img src="../images/menu_addcustomer.png"
			border=0></img> Price List</a></td>
	</tr>
</table>
</div>
<div id="footer_right">
<table align="right">
	<tr>
		<td><a style="border: 0; padding-top: 1px; width: 135px;"
			class="footeritem" href="#" onClick="showHireConsultantPopup();"
			title="Checkout all items and open up the payment window"><img
			src="../images/menu_leads.png" border=0></img> Hire a Consultant</a></td>
		<td><a style="border: 0; padding-top: 1px; width: 110px;"
			class="footeritem" href="#" onClick="showHirePainterPopup();"
			title="Checkout all items and open up the payment window"><img
			src="../images/menu_hirepainter.png" border=0></img> Hire a Painter</a></td>
		<td><a style="border: 0; padding-top: 1px; width: 115px;"
			class="footeritem" href="#" onClick="showLookupPopup();"
			title="Checkout all items and open up the payment window"><img
			src="../images/menu_invlookup.png" border=0></img> Invoice Lookup</a></td>
		<td><a style="border: 0; padding-top: 1px; width: 85px;"
			class="footeritem" href="#" onClick="showOutstandingPopup();"
			title="Checkout all items and open up the payment window"><img
			src="../images/menu_outstanding.png" border=0></img> Repay AR</a></td>
	</tr>
</table>
</div>
</div>
<div id="backgroundPopup"></div>

<div id="addCustomerPopupContact"><a id="addCustomerPopupContactClose">x</a>
<table>
	<tr>
		<td
			style="background: black; padding: 6px;">
		<b style="font-weight: bold; font-size: 16px; color: white;">New
		Customer</b></td>
		<td><label id="nc_warning" style="color:red;font-size:12pt;font-weight:bold;"></label></td>
	</tr>
</table>
<div id="addCustomerPopupContent">
<div id="addCustomerPopupControl">
<center>
<table>
	<tr>
		<td><a href="#" class="addCust" onclick="addNewCustomerHelper();">Save</a></td>
		<td><a href="#" class="addCust" onclick="clearAddCustomer();">Reset</a></td>
		<td><a href="#" class="addCust" onclick="cancelAddCustomerPopup();">Cancel</a></td>
		<td><a href="#" class="addCust" onclick="disableAddCustomerPopup();">Close</a></td>
	</tr>
</table>
</center>
</div>
<div id="add1"><label class="add1">Scan:</label> <input type="text"
	id="new_scanRIN" size=90 onchange="scanRIN();"></input>
<table>
	<thead>
		<tr>
			<th class="head"
				style="color: #ffffff; background-color: #000000; font-size: 10pt;">Basic
			Information</th>
			<th></th>
		</tr>
	</thead>
</table>
<table>
	<tbody>
		<tr>
			<th class="label"><label class="add1">Title:</label></th>
			<th class="info" style="font-weight: normal"><?php
			//$val_array = get_customer_title();
			print make_dropdown_list("","new_cTitle","",$title_array,"Customer Title","");
			?></th>
		</tr>
		<tr>
			<th class="label"><label class="add1">First Name:</label></th>
			<th class="info" style="font-weight: normal"><input type="text"
				id="new_cFirstN" size=40></input></th>
		</tr>
		<tr>
			<th class="label"><label class="add1">Last Name:</label></th>
			<th class="info" style="font-weight: normal"><input type="text"
				id="new_cLastN" size=40></input></th>
		</tr>
		<tr>
			<th class="label"><label class="add1">AKA:</label></th>
			<th class="info" style="font-weight: normal"><input type="text"
				id="new_cAKA" size=25></input></th>
		</tr>
		<tr>
			<th class="label"><label class="add1">Company Name:</label></th>
			<th class="info" style="font-weight: normal"><input type="text"
				id="new_cCoName" size=40></input></th>
		</tr>
		<tr>
			<th class="label"><label class="add1">Date of Birth:</label></th>
			<th class="info" style="font-weight: normal"><input type="text"
				id="new_cDoB" size=25></input></th>
		</tr>
		<tr>
			<th class="label"><label class="add1">Driver's License:</label></th>
			<th class="info" style="font-weight: normal"><input type="text"
				id="new_cRIN" size=25 onchange="checkInput('new_cRIN');"></input></th>
		</tr>
		<tr>
			<th class="label"><label class="add1">COM Number:</label></th>
			<th class="info" style="font-weight: normal"><input type="text"
				id="new_cCardNum" size=10 onchange="checkInput('new_cCardNum');"></input></th>
		</tr>
		<tr>
			<th class="label"><label class="add1">Customer Type:</label></th>
			<th class="info" style="font-weight: normal"><?php
			//$val_array = get_customer_type();
			print make_dropdown_list("","new_cCustType","",$ctype_array,"Customer Type","");
			?></th>
		</tr>
		<tr>
			<th class="label"><label class="add1">Expert Type:</label></th>
			<th class="info" style="font-weight: normal"><?php
			//$val_array = get_customer_expert();
			print make_dropdown_list("","new_cExpert","",$cexpert_array,"Expert Type","");
			?></th>
		</tr>
		<tr>
			<th class="label"><label class="add1">Shirt Size:</label></th>
			<th class="info" style="font-weight: normal"><?php
			//$val_array = get_shirt_list();
			print make_dropdown_list("","new_cShirtSize","",$shirts_array,"Shirt Size","");
			?></th>
		</tr>
		<tr>
			<th class="label"><label class="add1">Pant Size:</label></th>
			<th class="info" style="font-weight: normal"><input type="text"
				id="new_cPantSize" size=10 onchange="checkInput('new_cPantSize');"> (i.e. 30x30)</input></th>
		</tr>
	</tbody>
</table>
<table>
	<thead>
		<tr>
			<th class="head"
				style="color: #ffffff; background-color: #000000; font-size: 10pt;">Primary
			Contact</th>
		</tr>
	</thead>
</table>
<table>
	<tbody>
		<tr>
			<th class="label"><label class="add1">Unit:</label></th>
			<th class="info" style="font-weight: normal"><input type="text"
				id="new_cUnitB" size=8 onchange="checkInput('new_cUnitB');"></input></th>
		</tr>
		<tr>
			<th class="label"><label class="add1">Address:</label></th>
			<th class="info" style="font-weight: normal"><input type="text"
				id="new_cAdrBus" size=55></input></th>
		</tr>
		<tr>
			<th class="label"><label class="add1">City:</label></th>
			<th class="info" style="font-weight: normal"><input type="text"
				id="new_cCityB" size=40></input></th>
		</tr>
		<tr>
			<th class="label"><label class="add1">Province:</label></th>
			<th class="info" style="font-weight: normal"><?php
			//$val_array = getProvList();
			print make_dropdown_list("","new_cProvB","",$prov_array,"Contact Type","");
			?></th>
		</tr>
		<tr>
			<th class="label"><label class="add1">Zip Code:</label></th>
			<th class="info" style="font-weight: normal"><input type="text"
				id="new_cZipB" size=10 onchange="checkZip('new_cZipB');" maxlength=7></input></th>
		</tr>
		<tr>
			<th class="label"><label class="add1">Contact:</label></th>
			<th class="info" style="font-weight: normal"><?php
			print make_dropdown_list("","new_cpType1","",$tline_array,"Contact Type","");
			?> <input type="text" id="new_cPhone1" size=18 onchange="checkInput('new_cPhone1');"></input></th>
		</tr>
		<tr>
			<th class="label"><label class="add1">Email:</label></th>
			<th class="info" style="font-weight: normal"><input type="text"
				id="new_cEmail1" size=40></input></th>
		</tr>
	</tbody>
</table>
<br />
<table style="position: absolute; top: 650px; left: 275px;">
	<tr>
		<!-- <td><a href="#" onclick="submitSPC();">Previous</a></td> -->
		<td><a href="#" class="addCust" onclick="nextClick();" style="bottom: 50px;">Next</a></td>
	</tr>
</table>
</div>

<div id="add2">
<table>
	<thead>
		<tr>
			<th class="head"
				style="color: #ffffff; background-color: #000000; font-size: 10pt;">Secondary
			Contact</th>
		</tr>
	</thead>
</table>
<table>
	<tbody>
		<tr>
			<th class="label"><label class="add1">Unit:</label></th>
			<th class="info" style="font-weight: normal"><input type="text"
				id="new_cUnitH" size=8 onchange="checkInput('new_cUnitH');"></input></th>
		</tr>
		<tr>
			<th class="label"><label class="add1">Address:</label></th>
			<th class="info" style="font-weight: normal"><input type="text"
				id="new_cAdrHome" size=55></input></th>
		</tr>
		<tr>
			<th class="label"><label class="add1">City:</label></th>
			<th class="info" style="font-weight: normal"><input type="text"
				id="new_cCityH" size=40></input></th>
		</tr>
		<tr>
			<th class="label"><label class="add1">Province:</label></th>
			<th class="info" style="font-weight: normal"><?php
			//$val_array = getProvList();
			print make_dropdown_list("","new_cProvH","",$prov_array,"Contact Type","");
			?></th>
		</tr>
		<tr>
			<th class="label"><label class="add1">Zip Code:</label></th>
			<th class="info" style="font-weight: normal"><input type="text"
				id="new_cZipH" size=10 onchange="checkZip('new_cZipH');" maxlength=7></input></th>
		</tr>
		<tr>
			<th class="label"><label class="add1">Contact:</label></th>
			<th class="info" style="font-weight: normal"><?php
			//$val_array = get_linetype();
			print make_dropdown_list("","new_cpType2","",$tline_array,"Contact Type","");
			?> <input type="text" id="new_cPhone2" size=18 onchange="checkInput('new_cPhone2');"></input></th>
		</tr>
		<tr>
			<th class="label"><label class="add1">Email:</label></th>
			<th class="info" style="font-weight: normal"><input type="text"
				id="new_cEmail2" size=40></input></th>
		</tr>
	</tbody>
</table>
<table>
	<thead>
		<tr>
			<th class="head"
				style="color: #ffffff; background-color: #000000; font-size: 10pt;">Additional
			Contact</th>
		</tr>
	</thead>
</table>
<table>
	<tbody>
		<tr>
			<th class="label"><label class="add1">Unit:</label></th>
			<th class="info" style="font-weight: normal"><input type="text"
				id="new_cUnitS" size=8  onchange="checkInput('new_cUnitS');"></input></th>
		</tr>
		<tr>
			<th class="label"><label class="add1">Address:</label></th>
			<th class="info" style="font-weight: normal"><input type="text"
				id="new_cAdrShip" size=55></input></th>
		</tr>
		<tr>
			<th class="label"><label class="add1">City:</label></th>
			<th class="info" style="font-weight: normal"><input type="text"
				id="new_cCityS" size=40></input></th>
		</tr>
		<tr>
			<th class="label"><label class="add1">Province:</label></th>
			<th class="info" style="font-weight: normal"><?php
			//$val_array = getProvList();
			print make_dropdown_list("","new_cProvS","",$prov_array,"Contact Type","");
			?></th>
		</tr>
		<tr>
			<th class="label"><label class="add1">Zip Code:</label></th>
			<th class="info" style="font-weight: normal"><input type="text"
				id="new_cZipS" size=10  onchange="checkZip('new_cZipS');" maxlength=7></input></th>
		</tr>
		<tr>
			<th class="label"><label class="add1">Contact:</label></th>
			<th class="info" style="font-weight: normal"><?php
			//$val_array = get_linetype();
			print make_dropdown_list("","new_cpType3","",$tline_array,"Contact Type","");
			?> <input type="text" id="new_cPhone3" size=18  onchange="checkInput('new_cPhone3');"></input></th>
		</tr>
		<tr>
			<th class="label"><label class="add1">Email:</label></th>
			<th class="info" style="font-weight: normal"><input type="text"
				id="new_cEmail3" size=40></input></th>
		</tr>
	</tbody>
</table>
<table>
	<thead>
		<tr>
			<th class="head"
				style="color: #ffffff; background-color: #000000; font-size: 10pt;">Customer
			Expertise</th>
		</tr>
	</thead>
</table>
<table>
	<tbody>
		<tr>
			<th class="new_new_expertise">Interior:</th>
			<th class="new_expertise"><input type="checkbox" id="new_cE1"></input></th>
			<th class="new_expertise">Deck & Fence:</th>
			<th class="new_expertise"><input type="checkbox" id="new_cE6"></input></th>
		</tr>
		<tr>
			<th class="new_expertise">Exterior:</th>
			<th class="new_expertise"><input type="checkbox" id="new_cE2"></input></th>
			<th class="new_expertise">Drywall & Taping:</th>
			<th class="new_expertise"><input type="checkbox" id="new_cE7"></input></th>
		</tr>
		<tr>
			<th class="new_expertise">Residential:</th>
			<th class="new_expertise"><input type="checkbox" id="new_cE3"></input></th>
			<th class="new_expertise">Spraying:</th>
			<th class="new_expertise"><input type="checkbox" id="new_cE8"></input></th>
		</tr>
		<tr>
			<th class="new_expertise">Commercial:</th>
			<th class="new_expertise"><input type="checkbox" id="new_cE4"></input></th>
			<th class="new_expertise">Faux Finish:</th>
			<th class="new_expertise"><input type="checkbox" id="new_cE9"></input></th>
		</tr>
		<tr>
			<th class="new_expertise">Stain Work:</th>
			<th class="new_expertise"><input type="checkbox" id="new_cE5"></input></th>
			<th class="new_expertise">Wall Paper:</th>
			<th class="new_expertise"><input type="checkbox" id="new_cE10"></input></th>
		</tr>
	</tbody>
</table>
<br />
<table style="position: absolute; top: 650px; left: 150px;">
	<tr>
		<td><a href="#" class="addCust" onclick="prevClick();">Previous</a></td>
		<td><a href="#" class="addCust" onclick="nextClick();">Next</a></td>
	</tr>
</table>
</div>

<div id="add3">
<table>
	<thead>
		<tr>
			<th class="head"
				style="color: #ffffff; background-color: #000000; font-size: 10pt;">Additional
			Numbers</th>
		</tr>
	</thead>
</table>
<table>
	<tbody>
	<tr>
			<th class="label"><label class="add1">Fax:</label></th>
			<th class="info" style="font-weight: normal"><?php
			//$val_array = get_linetype();
			print make_dropdown_list("","new_cpType4","",$tline_array,"Contact Type","");
			?> <input type="text" id="new_cPhone4" size=18  onchange="checkInput('new_cPhone4');"></input></th>
		</tr>
		<tr>
			<th class="label"><label class="add1">Cell:</label></th>
			<th class="info" style="font-weight: normal"><?php
			//$val_array = get_linetype();
			print make_dropdown_list("","new_cpType5","",$tline_array,"Contact Type","");
			?> <input type="text" id="new_cPhone5" size=18  onchange="checkInput('new_cPhone5');"></input></th>
		</tr>
</tbody>
</table>
<table>
	<thead>
		<tr>
			<th class="head"
				style="color: #ffffff; background-color: #000000; font-size: 10pt;">Finance
			Information</th>
		</tr>
	</thead>
</table>
<table>
	<tbody>
		<tr>
			<th class="label">Customer Tax 1:</th>
			<th class="info" style="font-weight: normal"><?php
			//$val_array = get_tax_category();
			print make_dropdown_list("","new_cTax1","",$taxcat_array,"Customer Tax 1","");
			?></th>
		</tr>
		<tr>
			<th class="label">Customer Tax 2:</th>
			<th class="info" style="font-weight: normal"><?php
			//$val_array = get_tax_category();
			print make_dropdown_list("","new_cTax2","",$taxcat_array,"Customer Tax 2","");
			?></th>
		</tr>
		<tr>
			<th class="label">SPC Level:</th>
			<th class="info" style="font-weight: normal"><?php
			//$val_array = get_spc_level2();
			print make_dropdown_listd("","new_cSPC","",$spcl_array,"Customer SPC Level","",1);
			?></th>
		</tr>
		<tr>
			<th class="label">ECO Fee:</th>
			<th class="info" style="font-weight: normal"><?php
			print make_dropdown_list("","new_cEcoFee","",$efee_array,"Customer Eco Fee","");
			?></th>
		</tr>
		<tr>
			<th class="label">Customer Rep:</th>
			<th class="info" style="font-weight: normal"><?php
			//$val_array = get_staff_list();
			print make_dropdown_list("","new_cCustRep","",$staff_array,"Customer Rep.","");
			?></th>
		</tr>
		<tr>
			<th class="label">Excempt #:</th>
			<th class="info" style="font-weight: normal"><input type="text"
				id="new_cExpNum" size=20></input></th>
		</tr>
		<tr>
			<th class="label">Expires In:</th>
			<th class="info" style="font-weight: normal">
			<input name="datePick" class="date-s-pick" id="new_cExpDate" size=10></input></th>
		</tr>
		<tr>
			<th class="label">Credit:</th>
			<th class="info" style="font-weight: normal"><input type="text"
				id="new_cCredit" size=20 disabled></input></th>
		</tr>
	</tbody>
</table>
<table style="position: absolute; top: 650px; left: 150px;">
	<tr>
		<td><a href="#" class="addCust" onclick="prevClick();">Previous</a></td>
	</tr>
</table>
</div>
</div>
</div>

<div id="outstandingPopupContact"><a id="outstandingPopupContactClose">x</a>
<table>
	<tr>
		<td
			style="background: black; padding: 6px; border-bottom: 1px dotted #000000;">
		<b style="font-weight: bold; font-size: 16px; color: white;">Outstanding
		Invoice</b></td>
	</tr>
</table>
<div id="outstandingPopupContent">
<div id="outstandingTable"><iframe src="" name="invOutstandingFrame"
	onLoad="hideLoadingZone()" frameborder="0" WIDTH=100% HEIGHT=100%> </iframe></div>
<div id="outstandingRepayTable"><iframe src="" name="invOSItemsFrame"
	onLoad="hideLoadingZone()" frameborder="0" WIDTH=100% HEIGHT=100%> </iframe></div>
<div id="outstandingRepayTable_control">
<center>
<table>
	<tr>
		<td><input type="button" value="Select All Invoices" id="selAllRepay"
			onclick="alert('no function');"></input></td>
		<td><input type="button" value="Print Invoices" onclick="testb()"></input></td>
	</tr>
	<tr>
		<td><input type="text" id="tb_os_invoice"></input></td>
	</tr>
</table>
</center>
</div>
<div id="outstandingPayTable"><iframe src="" name="invRepaidFrame"
	onLoad="hideLoadingZone()" frameborder="0" WIDTH=100% HEIGHT=100%> </iframe></div>
<div id="outstandingControl">
<center><label
	style="width: 100%; background: black; color: white; font-size: 16pt;">RePay
CoM AR</label></center>
<center><input name="datePick2" id="datePick2" class="date-pick" /></center>
<br />
<center>
<table style="border: 2px dashed grey; padding: 7px;">
	<tr align="center">
		<td><input type="text" style="background: lime; color: black;"
			id="repayAmt" value="$0.00" size=25 readonly></td>
	</tr>
	<tr align="center">
		<td><input type="checkbox" id="repayFull"
			onclick="checkRepayCB(this);">FULL Payment</td>
	</tr>
	<tr align="center">
		<td><input type="checkbox" id="repayFlat"
			onclick="checkRepayCB(this);">FLAT Payment</td>
	</tr>
	<tr align="center">
		<td><select id="repaySize" onchange="choose_repay();" size="1">
			<option value="0"></option>
			<option value="1">Single Payment</option>
			<option value="2">Multiple Payments</option>
		</select></td>
	</tr>
</table>
</center>
<br />
<br />
<center>
<table style="border: 2px solid grey">
	<tr>
		<td>
		<table style="border: 1px dotted black;">
			<tr>
				<td><label><b>Amount </b></label></td>
				<td><input type="text" id="repayAmt1" value="0.00" size=15></td>
			</tr>
			<tr>
				<td><label><b>Method </b></label></td>
				<td><?php
				$payM = array("","Cash","Cheque","Visa","Master Card","American Express","Certificate");
				print make_dropdown_list("","repayMethod1","",$payM,"rePayment method 1","checkRepayAuth(1);");
				?></td>
			</tr>
			<tr>
				<td><label><b>Auth# </b></label></td>
				<td><input type="text" id="repayAuth1" size=15 disabled></td>
			</tr>
		</table>
		</td>
	</tr>

	<tr>
		<td>
		<table style="border: 1px dotted black;">
			<tr>
				<td><label><b>Amount </b></label></td>
				<td><input type="text" id="repayAmt2" value="0.00" size=15></td>
			</tr>
			<tr>
				<td><label><b>Method </b></label></td>
				<td><?php
				print make_dropdown_list("","repayMethod2","",$payM,"rePayment method 2","checkRepayAuth(2);");
				?></td>
			</tr>
			<tr>
				<td><label><b>Auth# </b></label></td>
				<td><input type="text" id="repayAuth2" size=15 disabled></td>
			</tr>
		</table>
		</td>
	</tr>

	<tr>
		<td>
		<table style="border: 1px dotted black;">
			<tr>
				<td><label><b>Amount </b></label></td>
				<td><input type="text" id="repayAmt3" value="0.00" size=15></td>
			</tr>
			<tr>
				<td><label><b>Method </b></label></td>
				<td><?php
				print make_dropdown_list("","repayMethod3","",$payM,"rePayment method 3","checkRepayAuth(3);");
				?></td>
			</tr>
			<tr>
				<td><label><b>Auth# </b></label></td>
				<td><input type="text" id="repayAuth3" size=15 disabled></td>
			</tr>
		</table>
		</td>
	</tr>
	<tr>
		<td>
		<center><input type="button" style="width: 100px" value="Reset"
			onclick="clearRepay();"></input></center>
		</td>
	</tr>
</table>
</center>
<br />
<br />
<center>
<table style="border: 2px solid grey">
	<tr>
		<td><?php
		//$reps = get_staff_list();
		print make_formdropdown_list("Teller","repayTeller","",$staff_array,"Teller that served the customer.",1);?></td>
	</tr>
	<tr>
		<td><input type="text" id="repayPO" size=30></input></td>
	</tr>
	<tr>
		<td>
		<center><input type="button" style="width: 100px"
			value="Confirm Repay" onclick="confirmRepay();"></input></center>
		</td>
	</tr>
</table>
</center>
</div>
</div>
</div>

<div id="lookupPopupContact"><a id="lookupPopupContactClose">x</a>
<table>
	<tr>
		<td
			style="background: black; padding: 6px;">
		<b style="font-weight: bold; font-size: 16px; color: white;">Lookup Invoices</b></td>
	</tr>
</table>
<div id="lookupPopupContent" style="font-size:12pt;">
<div id="lookupDatepick">
<center><table><tr>
<td><div id="start-date" class="line-up"></div></td> 
<td><div id="end-date" class="line-up"></div></td>
</tr>
<tr>
<td><input name="date-start" id="date-start" class="text" value="FROM" size=34 readonly />
<input type="hidden" id="date-s" value="-1"></input></td>
<td><input name="date-end" id="date-end" class="text" value="TO" size=34 readonly />
<input type="hidden" id="date-e" value="-1"></input></td>
</tr>
<tr>
<td>
<select id="lookupDates" size="1">
	<option value="0">Free Range</option>
	<option value="1">Same Day</option>
	<option value="7">1 Week</option>
	<option value="14">2 Weeks</option>
	<option value="21">3 Weeks</option>
	<option value="30">30 Days</option>
	<option value="60">60 Days</option>
	<option value="90">90 Days</option>
</select>
<input type="button" value="Today" style="border:1px solid black;"></input>
</td>
<td></td>
</tr>
</table></center>
<center>
<label style="font-size:16px;font-weight:bold;color:black;"></label>
</center>
</div>
<div id="lookupPayment"><iframe src="" name="lookupPaymentFrame" frameborder="0" WIDTH=100% HEIGHT=100%></iframe></div>
<div id="lookupInvoice"><iframe src="" name="lookupInvoiceFrame" frameborder="0" WIDTH=100% HEIGHT=100%></iframe></div>
<div id="lookupProduct"><iframe src="" name="lookupProductFrame" frameborder="0" WIDTH=100% HEIGHT=100%></iframe></div>

</div>
</div>

<div id="hirePainterPopupContact"><a id="hirePainterPopupContactClose">x</a>
<table>
	<tr>
		<td
			style="background: black; padding: 6px;">
		<b style="font-weight: bold; font-size: 16px; color: white;">Hire a Painter</b></td>
	</tr>
</table>
<div id="hirePainterPopupContent">
<input type="hidden" id="hpContID" value="-1"></input>
<div id="painterTable">
<iframe src="" name="painterFrame" frameborder="0" WIDTH=100% HEIGHT=100%></iframe>
</div>
<div id="hirePaintControl">
<center>
<table>
<tr>
<td><label>Filter:</label><input type="text" name="hp" id="hpCustSearch" size=20 onchange="return popLPN(this);"></input></td>
<td><select id="hpCustID" name="hp" size="1" style="width:240px;">
</select></td>
<td></td>
</tr>
</table>
</center>
<center>
<table><tr><td>
<table>
<tr>
<td><input type="checkbox" name="hp" id="hpE1"></input><label>Interior</label></td>
<td><input type="checkbox" name="hp" id="hpE11"></input><label>Garage</label></td>
</tr><tr>
<td><input type="checkbox" name="hp" id="hpE2"></input><label>Exterior</label></td>
<td><input type="checkbox" name="hp" id="hpE12"></input><label>Basement</label></td>
</tr><tr>
<td><input type="checkbox" name="hp" id="hpE3"></input><label>Residential</label></td>
<td><input type="checkbox" name="hp" id="hpE13"></input><label>1st Floor</label></td>
</tr><tr>
<td><input type="checkbox" name="hp" id="hpE4"></input><label>Commercial</label></td>
<td><input type="checkbox" name="hp" id="hpE14"></input><label>2nd Floor</label></td>
</tr><tr>
<td><input type="checkbox" name="hp" id="hpE5"></input><label>Stain Work</label></td>
<td><input type="checkbox" name="hp" id="hpE15"></input><label>3rd Floor</label></td>
</tr><tr>
<td><input type="checkbox" name="hp" id="hpE6"></input><label>Deck & Fence</label></td>
<td><input type="checkbox" name="hp" id="hpE16"></input><label>Entire House</label></td>
</tr><tr>
<td><input type="checkbox" name="hp" id="hpE7"></input><label>Drywall & Taping</label></td>
<td><input type="checkbox" name="hp" id="hpE17"></input><label>Trims</label></td>
</tr><tr>
<td><input type="checkbox" name="hp" id="hpE8"></input><label>Spraying</label></td>
<td><label>#Colours </label><select name="hp" id="hpNumCols" size="1">
<option value="1">1</option>
<option value="2">2</option>
<option value="3">3</option>
<option value="4">4</option>
<option value="5">5</option>
<option value="6">6</option>
<option value="7">7</option>
<option value="8">8</option>
<option value="9">9</option>
<option value="10">10</option>
</select></td>
</tr><tr>
<td><input type="checkbox" name="hp" id="hpE9"></input><label>Faux Finish</label></td>
<td><label>#Rooms (w.c./kitch) </label><select name="hp" id="hpNumRms" size="1">
<option value="1">1</option>
<option value="2">2</option>
<option value="3">3</option>
<option value="4">4</option>
<option value="5">5</option>
<option value="6">6</option>
<option value="7">7</option>
<option value="8">8</option>
<option value="9">9</option>
<option value="10">10</option>
<option value="1">11</option>
<option value="2">12</option>
<option value="3">13</option>
<option value="4">14</option>
<option value="5">15</option>
<option value="6">16</option>
<option value="7">17</option>
<option value="8">18</option>
<option value="9">19</option>
<option value="10">20</option>
</select></td>
</tr><tr>
<td><input type="checkbox" id="hpE10"></input><label>Wall Paper</label></td>
<td><input type="button" value="Reset" onclick="resetDiv('hirePaintControl');" style="border:2px solid black;width:100px;"></input></td>
</tr><tr>
<td><input type="button" value="Find" id="findPainter" onclick="findHire();" style="border:2px solid black;width:100px;"></input></td>
<td><input type="button" value="Save" name="savP" onclick="return saveHire(this);" style="border:2px solid black;width:100px;"></input></td>
</tr>
</table></td><td>

<table>
<tr><td><div id="hireP-date" class="line-up"></div></td></tr>
<tr><td><input type="text" name="hp" id="hpDate" style="width:185px;"></input></td></tr>
<tr><td><textarea name="hp" id="hpNote" title="Hire Painter Note" style="width:185px;height:50px;"></textarea></td></tr>
</table>

</td></tr></table>
</center>

</div>
<div id="hiredPainterTable">
<iframe src="" name="hiredPainterFrame" frameborder="0" WIDTH=100% HEIGHT=100%></iframe>
</div>
</div>
</div>

<div id="hireConsultantPopupContact"><a id="hireConsultantPopupContactClose">x</a>
<table>
	<tr>
		<td
			style="background: black; padding: 6px;">
		<b style="font-weight: bold; font-size: 16px; color: white;">Hire a Consultant</b></td>
	</tr>
</table>
<div id="hireConsultantPopupContent">
<input type="hidden" id="hccContID" value="-1"></input>
<div id="consultantTable">
<iframe src="" name="consultantFrame" frameborder="0" WIDTH=100% HEIGHT=100%></iframe>
</div>
<div id="hireConsultControl">
<br />
<center>
<table>
<tr>
<td><label>Filter: </label><input type="text" id="hccCustSearch" onchange="return popLPN(this);" size=20></input></td>
<td><select id="hccCustID" size="1" style="width:240px;">
</select></td>
</tr>

</table>
</center>
<center>
<table><tr><td>
<table>
<tr>
<td><input type="checkbox" id="hccE1"></input><label>Basement</label></td>
<td><input type="checkbox" id="hccE2"></input><label>1st Floor</label></td>
</tr><tr>
<td><input type="checkbox" id="hccE3"></input><label>2nd Floor</label></td>
<td><input type="checkbox" id="hccE4"></input><label>3rd Floor</label></td>
</tr><tr>
<td><input type="checkbox" id="hccE5"></input><label>Entire House</label></td>
<td></td>
</tr><tr>
<td><label>#Rooms (w.c./kitch) </label><select id="hccNumRms" size="1">
<option value="1">1</option>
<option value="2">2</option>
<option value="3">3</option>
<option value="4">4</option>
<option value="5">5</option>
<option value="6">6</option>
<option value="7">7</option>
<option value="8">8</option>
<option value="9">9</option>
<option value="10">10</option>
<option value="1">11</option>
<option value="2">12</option>
<option value="3">13</option>
<option value="4">14</option>
<option value="5">15</option>
<option value="6">16</option>
<option value="7">17</option>
<option value="8">18</option>
<option value="9">19</option>
<option value="10">20</option>
</select></td>
<td>
<label>#Colours </label><select id="hccNumCols" size="1">
<option value="1">1</option>
<option value="2">2</option>
<option value="3">3</option>
<option value="4">4</option>
<option value="5">5</option>
<option value="6">6</option>
<option value="7">7</option>
<option value="8">8</option>
<option value="9">9</option>
<option value="10">10</option>
</select>
</td>
</tr><tr>
<td><input type="button" value="Reset" onclick="resetDiv('hireConsultControl');" style="border:2px solid black;width:100px;"></input></td>
<td><input type="button" value="Save" name="savCC" onclick="return saveHire(this);" style="border:2px solid black;width:100px;"></input></td>
</tr>
</table></td><td>

<table>
<tr><td><div id="hireCC-date" class="line-up"></div></td></tr>
<tr><td><input type="text" id="hccDate" style="width:185px;"></input></td></tr>
<tr><td><textarea id="hccNote" title="Hire Painter Note" style="width:185px;height:50px;"></textarea></td></tr>
</table>

</td></tr></table>
</center>

</div>

<div id="hiredConsultantTable">
<iframe src="" name="hiredConsultantFrame" frameborder="0" WIDTH=100% HEIGHT=100%></iframe>
</div>
</div>
</div>

</body>

</html>
