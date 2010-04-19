<?php
// @ error reporting setting  (  modify as needed )
ini_set("display_errors", 1);
error_reporting(E_ALL);
include("../backend/methods.php");
include("../backend/db_methods.php");

//@ explicity start session  ( remove if needless )
session_start();

//@ if logoff
/*if(!empty($_GET['logoff'])) { $_SESSION = array(); }

//@ is authorized?
if(empty($_SESSION['exp_user']) || @$_SESSION['exp_user']['expires'] < time()) {
header("location:../src/login.html");	//@ redirect
} else {
$_SESSION['exp_user']['expires'] = time()+(30*60);	//@ renew 45 minutes
}*/
$today = getdate(time());
$sessionDate = date("Y-m-d H:i:s",$today[0]);
$session = $_POST['ts'];
$custSid = $_POST['a'];
$custStore = get_store_name($_POST['a']);
/*PROBLEMS WITH THESE VARS*/
$custID = $_POST['b'];
$custSPC = $_POST['c'];
$custFName = $_POST['d'];
$custLName = $_POST['e'];
$coName = $_POST['f'];

$week = date("W", $today[0]);
$month = date("m", $today[0]);
$year = date("Y", $today[0]);
$cal_default = date("m/d/Y",$today[0]);
?>

<html>
<head>
<title>Invoice</title>
<link href="../css/invoice.css" rel="stylesheet" type="text/css" />
<link href="../css/datepicker.css" rel="stylesheet" type="text/css" />
<link href="../css/jquery.impromptu.css" rel="stylesheet"
	type="text/css" />
<script src="../js/jquery.js" type="text/javascript"></script>
<!-- <script src="../js/jquery.tools.min.js" type="text/javascript"></script> -->
<script src="../js/date.js" type="text/javascript"></script>
<script src="../js/jquery.bgiframe.min.js" type="text/javascript"></script>
<script src="../js/jquery.hotkeys.js" type="text/javascript"></script>
<script src="../js/jquery.datePicker.js" type="text/javascript"></script>
<script src="../js/jquery.impromptu.js" type="text/javascript"></script>
<script src="../js/general.js" type="text/javascript"></script>
<script src="../js/invoice.js" type="text/javascript"></script>

<script type="text/javascript">
	function setFocus() {
		document.getElementById('prodSearch').focus();
		var id = document.getElementById('presStaff').value;
		document.getElementById('invTeller').selectedIndex = id;
		document.getElementById('repayTeller').selectedIndex = id;
	}
	
	$(document).keypress(function(e){
		if(e.keyCode==13){
			reloadPIframeInInv();
		}
	});

	$(function() {
		Date.firstDayOfWeek = 0;
		Date.format = 'yyyy-mm-dd';
		$('.date-pick').datePicker({startDate:'1996-01-01'}).val(new Date().asString()).trigger('change');
	});
</script>
</head>
<body onLoad="setFocus();">
<div id="debug"><textarea id="debugger"
	style="width: 100%; height: 100%; background: black; color: green;"></textarea>
</div>
<div id="returnMessage">
<label id="lb_returnMessage" style="padding-left: 5px;"></label>
</div>


<div id="menu">
<div id="signout"><?php print $_SESSION['exp_user']['sStaff']." @ ".get_store_name($_SESSION['exp_user']['localSID']);?>
&nbsp;&nbsp;&nbsp;&nbsp;[<a href="#" onclick="logout();">Logout</a>]</div>
<div id="sidemenu">
<div id="tools"><img src="../images/menu_calculator.png"
	onclick="openCalculator()" title="Calculator"></img> <img
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
	print "<td><a href=\"#\" onclick=\"openTimesheet();\" title=\"Store Timesheet/Weekly Summary\"><img src=\"../images/menu_timesheet.png\" border=0></img> Timesheet</a></td>";
	print "<td><a href=\"#\" onclick=\"openProduct();\" title=\"Product List/Details\"><img src=\"../images/menu_product.png\" border=0></img> Product</a></td>";
	print "<td><a href=\"#\" onclick=\"openStaff();\" title=\"Staff List/Information\"><img src=\"../images/menu_staff.png\" border=0></img> Staff</a></td>";
	print "<td><a href=\"#\" onclick=\"openLeads();\" title=\"Leads List/Information\"><img src=\"../images/menu_leads.png\" border=0></img> Leads</a></td>";
	//print "<td><a href=\"#\" onclick=\"openInvCustomer('$custID','$custSid');\" title=\"Customer List/Information\"><img src=\"../images/menu_customer.png\" border=0></img> Customer</a></td>";
	print "<td><a href=\"#\" onclick=\"openCustomer();\" title=\"Customer List/Information\"><img src=\"../images/menu_customer.png\" border=0></img> Customer</a></td>";
	print "<td><a href=\"#\" onclick=\"openRental();\" title=\"Store Rentals\"><img src=\"../images/menu_rental.png\" border=0></img> Rental</a></td>";
	print "<td><a href=\"#\" onclick=\"openReports();\" title=\"View Store Reports\"><img src=\"../images/menu_reports.png\" border=0></img> Reports</a></td>";
	print "<td><a href=\"#\" onclick=\"openScheduler();\" title=\"Schedule Staff Shift\"><img src=\"../images/menu_scheduler.png\"  border=0></img> Scheduler</a></td>";
	print "<td><a href=\"#\" onclick=\"openSeminar();\" title=\"Setup Seminar Sessions\"><img src=\"../images/menu_seminar.png\" border=0></img> Seminar</a></td>";
	print "<td><a href=\"#\" onclick=\"openLabels();\" title=\"Birthday Label Printing\"><img src=\"../images/menu_labels.png\" border=0></img> Labels</a></td>";
	print "</tr></table>";
	print "</div>";
} else if ($_SESSION['exp_user']['usrlevel'] == "5") {
	print "<div id=\"menuitems\">";
	print "<table><tr>";
	print "<td><a href=\"#\" onclick=\"openTimesheet();\" title=\"Store Timesheet/Weekly Summary\"><img src=\"../images/menu_timesheet.png\" border=0></img> Timesheet</a></td>";
	print "<td><a href=\"#\" onclick=\"openProduct();\" title=\"Product List/Details\"><img src=\"../images/menu_product.png\" border=0></img> Product</a></td>";
	print "<td><a href=\"#\" onclick=\"openLeads();\" title=\"Leads List/Information\"><img src=\"../images/menu_leads.png\" border=0></img> Leads</a></td>";
	//print "<td><a href=\"#\" onclick=\"openInvCustomer('$custID','$custSid');\" title=\"Customer List/Information\"><img src=\"../images/menu_customer.png\" border=0></img> Customer</a></td>";
	print "<td><a href=\"#\" onclick=\"openCustomer();\" title=\"Customer List/Information\"><img src=\"../images/menu_customer.png\" border=0></img> Customer</a></td>";
	print "<td><a href=\"#\" onclick=\"openRental();\" title=\"Store Rentals\"><img src=\"../images/menu_rental.png\" border=0></img> Rental</a></td>";
	print "<td><a href=\"#\" onclick=\"openSeminar();\" title=\"Setup Seminar Sessions\"><img src=\"../images/menu_seminar.png\" border=0></img> Seminar</a></td>";
	print "</tr></table>";
	print "</div>";
} else {
	print "<div id=\"menuitems\">";
	print "<table><tr>";
	print "<td><a href=\"#\" onclick=\"openTimesheet();\" title=\"Store Timesheet/Weekly Summary\"><img src=\"../images/menu_timesheet.png\" border=0></img> Timesheet</a></td>";
	print "<td><a href=\"#\" onclick=\"openProduct();\" title=\"Product List/Details\"><img src=\"../images/menu_product.png\" border=0></img> Product</a></td>";
	print "<td><a href=\"#\" onclick=\"openStaff();\" title=\"Staff List/Information\"><img src=\"../images/menu_staff.png\" border=0></img> Staff</a></td>";
	print "<td><a href=\"#\" onclick=\"openLeads();\" title=\"Leads List/Information\"><img src=\"../images/menu_leads.png\" border=0></img> Leads</a></td>";
	//print "<td><a href=\"#\" onclick=\"openInvCustomer('$custID','$custSid');\" title=\"Customer List/Information\"><img src=\"../images/menu_customer.png\" border=0></img> Customer</a></td>";
	print "<td><a href=\"#\" onclick=\"openCustomer();\" title=\"Customer List/Information\"><img src=\"../images/menu_customer.png\" border=0></img> Customer</a></td>";
	print "<td><a href=\"#\" onclick=\"openRental();\" title=\"Store Rentals\"><img src=\"../images/menu_rental.png\" border=0></img> Rental</a></td>";
	print "<td><a href=\"#\" onclick=\"openReports();\" title=\"View Store Reports\"><img src=\"../images/menu_reports.png\" border=0></img> Reports</a></td>";
	print "<td><a href=\"#\" onclick=\"openScheduler();\" title=\"Schedule Staff Shift\"><img src=\"../images/menu_scheduler.png\"  border=0></img> Scheduler</a></td>";
	print "<td><a href=\"#\" onclick=\"openSeminar();\" title=\"Setup Seminar Sessions\"><img src=\"../images/menu_seminar.png\" border=0></img> Seminar</a></td>";
	print "<td><a href=\"#\" onclick=\"openLabels();\" title=\"Birthday Label Printing\"><img src=\"../images/menu_labels.png\" border=0></img> Labels</a></td>";
	print "</tr></table>";
	print "</div>";
}
?>
<div id="custLab"><label><?php print "<b>Customer: $custFName, $custLName - $coName@$custStore<br></b>";?></label>
<label id="sessionID"><?php print "<b>Session: " . $session . "</b>";?></label>
<input type="hidden" id="defStoreID"
	value="<?php print $_SESSION['exp_user']['localSID'];?>"></input> <input
	type="hidden" id="custID" value="<?php print $custID;?>"></input> <input
	type="hidden" id="cLastN" value="<?php print $custFName;?>"></input> <input
	type="hidden" id="cFirstN" value="<?php print $custLName;?>"></input> <input
	type="hidden" id="session" value="<?php print $session;?>"></input> <input
	type="hidden" id="cStoreID" value="<?php print $_POST['a'];?>"></input>
<input type="hidden" id="coName" value="<?php print $coName;?>"></input>
<input type="hidden" id="scDate" value="<?php print $sessionDate;?>"></input>
<input type="hidden" id="presStaff"
	value="<?php print $_SESSION['exp_user']['sID'];?>"></input> <input
	type="hidden" id="invUCost" value="-1"></input> <input type="hidden"
	id="invCSize" value="-1"></input> <input type="hidden"
	id="invProdCode" value="-1"></input> <input type="hidden" id="selPID"
	value="-1"></input> <input type="hidden" id="selSID" value="-1"></input>
<input type="hidden" id="selType" value="-1"></input></div>
<div id="control"><b class="control">Query:</b> <input type="text"
	name="prodSearch" size="30"></input> <input type="button"
	name="invSubmit" value="Search Product" onClick="reloadPIframeInInv()" style="border:2px solid grey;"></input>
	<input type="button" name="searchReset" value="Reset"
	onclick="resetInvSearch();" style="border:2px solid grey;"></input>
</div>
</div>
<div id="hiddenFrame"><iframe src="../php/scheduler_add.php"
	name="hiddenFrame" onload="hideQueryZone()" frameborder="0" WIDTH=100%
	HEIGHT=100%> </iframe></div>
<div id="main">
<div id="jqi"></div>
<div id="loadingZone">Loading...</div>
<div id="product_panel">
<div id="product_panel_table"><iframe
	src=<?php print "../php/invoice_product_print.php?spc=".$custSPC;?>
	name="invProdFrame" onLoad="hideLoadingZone()" frameborder="0"
	WIDTH=100% HEIGHT=100%> </iframe></div>
<div id="product_panel_control">
<input type="hidden" name="invProd"	id="invProd"></input>
<table style="color: white;">
	<tr>
		<td><b>Free </b><input type="checkbox" name="invFree" id="invFree"></input>
		<b>UPC </b><input type="text" name="invUPC" id="invUPC" size=12
			readonly></input> <b>Sku </b><input type="text" name="invSku"
			id="invSku" size=12 readonly></input> <b>Unit/$ </b><input
			type="text" name="invUPrice" id="invUPrice" size=6
			onChange="updateCartItem();" readonly></input> <b>Quantity </b><input
			type="text" name="invQty" id="invQty" size=5 onBlur="qty_tooltip.style.display='none'" 
			onChange="updateCartItem();" title="Enter number of product to be purchased"></input> <b>SPC Level </b><input
			type="text" name="invSPC" id="invSPC" value=<?php print $custSPC;?>
			size=5 readonly></input> <!-- <input type="button" name="invCalculate"
			value="Calculate" onClick="updateCartItem();"></input> --> <b>Total </b><input
			type="text" name="invTotal" id="invTotal" size=8 readonly></input> <b>Tax1
		</b><?php
		$val_array = get_tax_category();
		print make_dropdown_list("","invTax1","",$val_array,"Tax1","JavaScript: prodEditUpdate('invTax1')");
		?> <b>Tax2 </b><?php
		$val_array = get_tax_category();
		print make_dropdown_list("","invTax2","",$val_array,"Tax1","JavaScript: prodEditUpdate('invTax2')");
		?></td>
		<td><input type="button" value=" Add Item"
			style="width: 120px; background: #ffffff url(../images/invoice_addcart.png); background-repeat: no-repeat;"
			onClick="addToCart('add');"></input></td>
	</tr>

	<tr>
		<td><b>Filter </b><input type="text" name="paintFilter"
			id="paintFilter" onchange="getFilterList();" size=12 title="Enter paint colour filter code then select from menu on the right" disabled></input>
		<select name="paintColourSelect" id="paintColourSelect" size="1" title="Select paint colour from dropdown menu" onBlur="color_tooltip.style.display='none'" disabled>
			<option value="def1">Missing colour - filter to get more</option>
		</select><b>Brand </b><select name="paintBrandSelect" id="paintBrandSelect" size="1" title="Paint brand" onBlur="brand_tooltip.style.display='none'" disabled>
			<option value="def1b"></option>
			<option value="def2b">Para</option>
			<option value="def3b">Pratt</option>
		</select> 
		<b>Note </b><input type="text" name="paintFilterNote"
			id="paintFilterNote" size=50 title="Tint detail/formula RGB value" onBlur="tint_tooltip.style.display='none'" disabled></input>
		</td>
		<td><input type="button" value=" Remove Item"
			style="width: 120px; background: #ffffff url(../images/invoice_removecart.png); background-repeat: no-repeat;"
			onClick="addToCart('remove');"></input></td>
		<td><input type="button" value="Reset Invoice" onclick="resetInvoiceAll();" style="width: 120px; background: #ffffff;"></input></td>
	</tr>
</table>
</div>
</div>
<div id="cart_panel">
<div id="cart_panel_table"><iframe
	src=<?php print "../php/invoice_cart_print.php?session=".$session;?>
	name="invCartFrame" onLoad="hideLoadingZone()" frameborder="0"
	WIDTH=100% HEIGHT=100%> </iframe></div>
<div id="cart_panel_control"><input type="button" name="invRefresh"
	onClick="updateTotals();" value="Update"></input> <b>Items </b><input
	id="invoiceItems" readonly value="" size="3"></input> <b>Total </b><input
	id="invoiceTotal" readonly value="" size="10"></input> <label
	style="font-weight: bold;" for="date">&nbsp;&nbsp;&nbsp;Date: </label>
<input name="datePick" id="datePick" class="date-pick" /> <?php 
$reps = get_staff_list();

print make_formdropdown_list("Teller","invTeller","",$reps,"Teller that served the customer.",1);
print make_formdropdown_list("Server","invServer","",$reps,"Server that served the customer.",0);
?> <!-- <input type="button" name="invCheckout" onClick="showPaymentPopup();"
	value="Checkout"></input> <input type="button" name="invReset"
	onClick="resetCart();" value="Reset"></input> --></div>
</div>
</div>
<div id="footer">
<table>
	<tr>
		<td><a style="border: 0; padding-top: 1px; width: 76px;"
			class="footeritem" href="#" onClick="showPaymentPopup();"
			title="Checkout all items and open up the payment window"><img
			src="../images/menu_checkout.png" border=0></img>Checkout</a></td>
		<td><a style="border: 0; padding-top: 1px; width: 76px;"
			class="footeritem" href="#" onClick="showQuote();"
			title="Create Quote invoice"><img
			src="../images/menu_calculator.png" border=0></img>Quote</a></td>
	</tr>
</table>
</div>
<div id="checkout"></div>
<div id="backgroundPopup"></div>

<div id="checkoutPopupContact"><a id="checkoutPopupContactClose">x</a>
<table>
	<tr>
		<td
			style="background: black; padding: 6px; border-bottom: 1px dotted #000000;">
		<b style="font-weight: bold; font-size: 16px; color: white;">Make
		Payment</b></td>
	</tr>
</table>
<div id="checkoutPopupContent"><b>Total </b><input type="text"
	name="payTotal" id="payTotal" size=15 readonly></input> <!-- <b>Remain </b><input
	type="text" name="payRemain" id="payRemain" size=15 readonly></input> -->
<b>Payment with: </b> <select id="paySize" onchange="choose_payment();"
	size="1">
	<option value="0"></option>
	<option value="1">Single Payment</option>
	<option value="2">Multiple Payments</option>
</select> <br></br>
<label class="pay">Method 1</label>
<hr>
<?php
$payM = array("","CoM AR","Cash","Cheque","Visa","Master Card","American Express","Debit","Certificate");
print make_dropdown_list("Payment Method","payMethod1","",$payM,"Payment method 1","checkPayAuth(1);");
?> <b>Amount </b><input type="text" id="payAmount1" value="0.00"
	onChange="updatePay();" size=8></input> <b>Auth# </b><input type="text"
	id="payAuth1" size=30 disabled></input><br />
<b>Note </b><input type="text" id="payNote1" size=98></input> <br />
<label class="pay">Method 2</label>
<hr>
<?php
$payM = array("","CoM AR","Cash","Cheque","Visa","Master Card","American Express","Debit","Certificate");
print make_dropdown_list("Payment Method","payMethod2","",$payM,"Payment method 2","checkPayAuth(2);");
?> <b>Amount </b><input type="text" id="payAmount2" value="0.00"
	onChange="updatePay();" size=8></input> <b>Auth# </b><input type="text"
	id="payAuth2" size=30 disabled></input><br />
<b>Note </b><input type="text" id="payNote2" size=98></input> <br />
<label class="pay">Method 3</label>
<hr>
<?php
$payM = array("","CoM AR","Cash","Cheque","Visa","Master Card","American Express","Debit","Certificate");
print make_dropdown_list("Payment Method","payMethod3","",$payM,"Payment method 3","checkPayAuth(3);");
?> <b>Amount </b><input type="text" id="payAmount3" value="0.00"
	onChange="updatePay();" size=8></input> <b>Auth# </b><input type="text"
	id="payAuth3" size=30 disabled></input><br />
<b>Note </b><input type="text" id="payNote3" size=98></input>
<hr>
<b>PO Note </b><input type="text" id="payPO" size=50></input> <br />
<br />
<input type="button" name="payReset" value="Reset"
	onClick="resetPaymentPopup();"></input> <input type="button"
	name="payCancel" value="Cancel Payment" onClick="disablePopup();"></input>
<input type="button" name="payHold" value="Open/Hold"
	onClick="testChecked();"></input> <input type="button"
	name="payConfirm" onClick="confirmPay();" value="Pay Confirm"></input></div>
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
<div id="outstandingTable"><iframe
	src=<?php print "../script/invoice_outstanding_print.php?cID=".$custID;?>
	name="invOutstandingFrame" onLoad="hideLoadingZone()" frameborder="0"
	WIDTH=100% HEIGHT=100%> </iframe></div>
<div id="outstandingRepayTable"><iframe
	src=<?php print "../script/invoice_outstandingitems_print.php";?>
	name="invOSItemsFrame" onLoad="hideLoadingZone()" frameborder="0"
	WIDTH=100% HEIGHT=100%> </iframe></div>
<div id="outstandingRepayTable_control">
<center>
<table>
	<tr>
		<td><input type="button" value="Select All Invoices"
			onclick="alert(window.fullRepay);"></input></td>
		<td><input type="button" value="Print Invoices" onclick="testb()"></input></td>
	</tr>
	<tr>
		<td><input type="text" id="tb_os_invoice"></input></td>
	</tr>
</table>
</center>
</div>
<div id="outstandingPayTable"><iframe
	src=<?php print "../script/invoice_outstanding_pay_print.php?cID=".$custID;?>
	name="invRepaidFrame" onLoad="hideLoadingZone()" frameborder="0"
	WIDTH=100% HEIGHT=100%> </iframe></div>
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
		<td><?php print make_formdropdown_list("Teller","repayTeller","",$reps,"Teller that served the customer.",1);?></td>
	</tr>
	<tr>
		<td><?php print make_formdropdown_list("Server","repayServer","",$reps,"Server that served the customer.",0);?></td>
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
<div id="qty_tooltip">Enter how many to purchase</div>
<div id="color_tooltip">Select Paint Colour.</div>
<div id="tint_tooltip">Colour detail/formula and brand</div>
</body>

</html>
