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
	$_SESSION['exp_user']['expires'] = time()+(15*60);	//@ renew 45 minutes
}

$supplier_list = get_psrchsupplier_list();
$sup_list = get_supplier_list();
$tax_cat = get_tax_category();
$prod_cat = get_product_category();
$cSize = get_C_size();
$bSize = get_B_sizes();
$allStore = getAllStores();
?>
<html>
<head>
<title>Products</title>
<link href="../css/product.css" rel="stylesheet" type="text/css" />
<script src="../js/jquery.js" type="text/javascript"></script>
<script src="../js/menu.js" type="text/javascript"></script>
<script src="../js/jquery.hotkeys.js" type="text/javascript"></script>
<script src="../js/general.js" type="text/javascript"></script>
<script src="../js/product.js" type="text/javascript"></script>
<script type="text/javascript">
	function setFocus() {
		document.getElementById('prodSearch').focus();
		var id = document.getElementById('defStoreID').value;
		document.getElementById('search_pStore').selectedIndex = id;
	}

	$(document).keypress(function(e){
		if(e.keyCode==13){
			reloadPIframe();
		}
	});
</script>
</head>
<body onLoad="setFocus();">
<div id="debug">
<textarea id="debugger" style="width:100%;height:400px;background:black;color:green;"></textarea>
</div>
<div id="returnMessage">
<center><label id="lb_returnMessage" style="font-size:18pt;"></label></center>
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
<div id="menu">
<div id="signout"><?php print $_SESSION['exp_user']['sStaff']." @ ".get_store_name($_SESSION['exp_user']['localSID']);?>
&nbsp;&nbsp;&nbsp;&nbsp;[<a href="#" onclick="logout();">Logout</a>]
</div>
<?php
if($_SESSION['exp_user']['usrlevel'] == "1") {
	print "<div id=\"menuitems\">";
	print "<table><tr>";
	print "<td><a href=\"#\" onclick=\"JavaScript:openTimesheet();\" title=\"Store Timesheet/Weekly Summary\"><img src=\"../images/menu_timesheet.png\" border=0></img> Timesheet</a></td>";
	print "<td><a href=\"#\" style=\"background:#F3F3F3;color:#2C2E22;cursor:pointer;\" title=\"Product List/Details\"><img src=\"../images/menu_product.png\" border=0></img> Product</a></td>";
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
	print "<td><a href=\"#\" onclick=\"JavaScript:openTimesheet();\" title=\"Store Timesheet/Weekly Summary\"><img src=\"../images/menu_timesheet.png\" border=0></img> Timesheet</a></td>";
	print "<td><a href=\"#\" style=\"background:#F3F3F3;color:#2C2E22;cursor:pointer;\" title=\"Product List/Details\"><img src=\"../images/menu_product.png\" border=0></img> Product</a></td>";
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
	print "<td><a href=\"#\" style=\"background:#F3F3F3;color:#2C2E22;cursor:pointer;\" title=\"Product List/Details\"><img src=\"../images/menu_product.png\" border=0></img> Product</a></td>";
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
<div id="control"><select id="search_pStore" size="1">
	<option value="0">ALL STORES</option>
	<option value="1">COLORS OF MAPLE</option>
	<option value="2">OAKRIDGES</option>
</select><?php
			print make_dropdown_list("","search_Supplier","",$supplier_list,"Supplier List","");
			?> <input type="text" id="prodSearch"></input> <input
	type="button" name="prodSubmit" value="Search Product"
	onclick="reloadPIframe();"></input> <input type="button"
	name="prodReset" value="Reset" onclick="resetPFields();"></input> <input
	type="button" name="adjustGlobalSPC" value="Adjust Global SPC"
	onclick="showEditSPCPopup();"></input> <input type="hidden" id="selSID"
	value="-1"></input> <input type="hidden" id="selPID" value="-1"></input>
<input type="hidden" id="pBPrice" value="-1"></input> <input
	type="hidden" id="new_pBPrice" value="-1"></input> <input type="hidden"
	id="new_pCPrice" value="-1"></input>
	<input type="hidden" id="defStoreID"
	value="<?php print $_SESSION['exp_user']['localSID'];?>"></input></div>
</div>

<div id="main">
<div id="table"><iframe src="../php/product_print.php?"
	name="tableFrame" onload="hideLoadingZone();" frameborder="0"
	WIDTH=100% HEIGHT=100%> </iframe>
<div id="loadingZone">Loading...</div>
</div>
<div id="spec">
<div id="spec_left">
<table>
	<thead>
		<tr>
			<th class="serie" style="color: #ffffff; background-color: #000000;">Product
			Information</th>
		</tr>
	</thead>
</table>
<table>
	<tbody>
		<tr>
			<th class="serie" style="background-color: #ffffff;">Store Name:</th>
			<th class="serie" style="font-weight: normal"><select id="iStoreID"
				size="1" onchange="prodEditUpdate('iStoreID');" disabled>
				<option value="opt1"></option>
				<option value="opt2">COLORS OF MAPLE</option>
				<option value="opt3">OAKRIDGES</option>
			</select></th>
			<th class="serie" style="background-color: #ffffff;">Product Cost:</th>
			<th style="font-weight: normal"><input type="text" id="iCost" size=25
				onchange="prodEditUpdate('iCost');"></input></th>
		</tr>
		<tr>
			<th class="serie" style="background-color: #ffffff;">Supplier:</th>
			<th style="font-weight: normal"><?php
			print make_dropdown_list("","pSupplier","",$sup_list,"Supplier List","JavaScript: prodEditUpdate('pSupplier')");
			?></th>
			<th class="serie" style="background-color: #ffffff;">Product MSRP:</th>
			<th style="font-weight: normal"><input type="text" id="iPrice"
				size=25 onchange="prodEditUpdate('iPrice');"></input></th>
		</tr>
		<tr>
			<th class="serie" style="background-color: #ffffff;">Product Name:</th>
			<th style="font-weight: normal"><input type="text" id="pName" size=25
				onchange="prodEditUpdate('pName');"></input></th>
			<th class="serie" style="background-color: #ffffff;">Product Tax 1:</th>
			<th style="font-weight: normal"><?php
			print make_dropdown_list("","iTax1","",$tax_cat,"Tax1","JavaScript: prodEditUpdate('iTax1')");
			?></th>
		</tr>
		<tr>
			<th class="serie" style="background-color: #ffffff;">Product
			Category:</th>
			<th style="font-weight: normal"><?php
			print make_dropdown_list("","pCatType","",$prod_cat,"Product Category List","JavaScript: prodEditUpdate('pCatType')");
			?></th>
			<th class="serie" style="background-color: #ffffff;">Product Tax 2:</th>
			<th style="font-weight: normal"><?php
			print make_dropdown_list("","iTax2","",$tax_cat,"Tax2","JavaScript: prodEditUpdate('iTax2')");
			?></th>
		</tr>
		<tr>
			<th class="serie" style="background-color: #ffffff;">Product UPC:</th>
			<th style="font-weight: normal"><input type="text" id="pUPC" size=25
				onchange="prodEditUpdate('pUPC');"></input></th>
			<th class="serie" style="background-color: #ffffff;">Minimum Stock:</th>
			<th style="font-weight: normal"><input type="text" id="iMinQty"
				size=25 onchange="prodEditUpdate('iMinQty');"></input></th>
		</tr>
		<tr>
			<th class="serie" style="background-color: #ffffff;">Product Sku:</th>
			<th style="font-weight: normal"><input type="text" id="pSku" size=25
				onchange="prodEditUpdate('pSku');"></input></th>
			<th class="serie" style="background-color: #ffffff;">Current
			Quantity:</th>
			<th style="font-weight: normal"><input type="text" id="iQty" size=25
				onchange="prodEditUpdate('iQty');"></input></th>
		</tr>
		<tr>
			<th class="serie" style="background-color: #ffffff;">Product Code:</th>
			<th style="font-weight: normal"><input type="text" id="pProdCode"
				size=25 onchange="prodEditUpdate('pProdCode');"></input></th>
			<th class="serie" style="background-color: #ffffff;">Max Stock:</th>
			<th style="font-weight: normal"><input type="text" id="iMaxQty"
				size=25 onchange="prodEditUpdate('iMaxQty');"></input></th>
		</tr>
		<tr>
			<th class="serie" style="background-color: #ffffff;">Product Size:</th>
			<th style="font-weight: normal"><?php 
			print make_dropdown_list("","pCSize","",$cSize,"Size","JavaScript: prodEditUpdate('pCSize')");
			?></th>
			<th class="serie" style="background-color: #ffffff;">Add Quantity:</th>
			<th><input type="text" id="psAddQty" size="10"></input>
			<input type="button" value="Add" style="border:1px solid black;width:35px;" onclick="addInventory()"></input></th>
		</tr>
		<!-- <tr>
			<th class="serie" style="background-color: #ffffff;">Product C Size:</th>
			<th id="pCSize" style="font-weight: normal"></th>
		</tr> -->
		<tr>
			<th class="serie" style="background-color: #ffffff;">Base:</th>
			<th style="font-weight: normal"><?php 
			print make_dropdown_list("","pBSize","",$bSize,"Base","JavaScript: prodEditUpdate('pBSize')");
			?></th>
			<th class="serie" style="background-color: #ffffff;">Promo Note:</th>
			<th><input type="text" id="pPromo" size="50"></input>
			<input type="button" value="Add" style="border:1px solid black;width:35px;" onclick="setPromo('add')"></input>
			<input type="button" value="Remove" style="border:1px solid black;width:55px;" onclick="setPromo('remove')"></input>
	
		</tr>
		<tr>
			<th class="serie" style="background-color: #ffffff;">Product
			Description:</th>
			<th style="font-weight: normal"><textarea id="pDescription" rows="6"
				cols="40" onchange="prodEditUpdate('pDescription');"></textarea></th>
			<th class="new_product"><b>Product Image :</b></th>
			<th class="new_product"><input type="file"
			id="pPicture" onchange="prodEditUpdate('pPicture');"></input></th>	
		</tr>
		<!-- <tr>
			<th class="serie" style="background-color: #ffffff;">Alternative:</th>
			<th id="pAlt" style="font-weight: normal"></th>
		</tr> -->
	</tbody>
</table>


</div>

<div id="spec_controls">
<center><input type="button" style="border: 1px solid black;" value="Save Changes"
	onclick="editProductHelp();"></input> <input type="button" style="border: 1px solid black;"
	value="Cancel Changes" onclick="refreshProduct();"></input></center>
</div>

<div id="prod_picture">
	<img id="thumbPicture" style="height: 80px; width: 100px;" alt="Product picture not avaliable"
		onMouseDown="zoomImage();"></img>
</div>

<div id="picture_bg">
<div id="full_picture">
	<img id="fullPPicture" style="height: 500px; width: auto;" alt="Product picture not avaliable"></img>
	
</div>
<div id="picture_control">
<input type="button" style="border: 1px solid black;" value="CLOSE X" onclick="restoreImage()"></input>
</div>
</div>

<div id="spec_right">
<?php if($_SESSION['exp_user']['usrlevel'] == "1") {?>
<table>
	<thead>
		<tr>
			<th class="serie" style="color: #ffffff; background-color: #ffffff;">&nbsp;</th>
			<th class="serie" style="color: #ffffff; background-color: #000000;">SPC
			Level</th>
			<th class="serie" style="color: #ffffff; background-color: #000000;">SPC
			Price</th>
			<th class="serie" style="color: #ffffff; background-color: #000000;">SPC+Tint+Eco</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<th class="serie" style="background-color: #ffffff;">SPC-1:</th>
			<th class="spc" style="font-weight: normal;"><input class="spc"
				type="text" id="iSPC1lvl" size=14
				onchange="calcSPCVals('iSPC1lvl');" disabled></input></th>
			<th class="spc" style="font-weight: normal;"><input class="spc"
				type="text" id="iSPC1prc" size=14
				onchange="calcSPCVals('iSPC1prc');" disabled></input></th>
			<th class="spc" id="iSPC1fee"
				style="font-weight: normal; background-color: #99ff99;"></th>
		</tr>
		<tr>
			<th class="serie" style="background-color: #ffffff;">SPC-2:</th>
			<th class="spc" style="font-weight: normal;"><input class="spc"
				type="text" id="iSPC2lvl" size=14
				onchange="calcSPCVals('iSPC2lvl');" disabled></input></th>
			<th class="spc" style="font-weight: normal;"><input class="spc"
				type="text" id="iSPC2prc" size=14
				onchange="calcSPCVals('iSPC2prc');" disabled></input></th>
			<th class="spc" id="iSPC2fee"
				style="font-weight: normal; background-color: #99ff99;"></th>
		</tr>
		<tr>
			<th class="serie" style="background-color: #ffffff;">SPC-3:</th>
			<th class="spc" style="font-weight: normal;"><input class="spc"
				type="text" id="iSPC3lvl" size=14
				onchange="calcSPCVals('iSPC3lvl');" disabled></input></th>
			<th class="spc" style="font-weight: normal;"><input class="spc"
				type="text" id="iSPC3prc" size=14
				onchange="calcSPCVals('iSPC3prc');" disabled></input></th>
			<th class="spc" id="iSPC3fee"
				style="font-weight: normal; background-color: #99ff99;"></th>
		</tr>
		<tr>
			<th class="serie" style="background-color: #ffffff;">SPC-4:</th>
			<th class="spc" style="font-weight: normal;"><input class="spc"
				type="text" id="iSPC4lvl" size=14
				onchange="calcSPCVals('iSPC4lvl');" disabled></input></th>
			<th class="spc" style="font-weight: normal;"><input class="spc"
				type="text" id="iSPC4prc" size=14
				onchange="calcSPCVals('iSPC4prc');" disabled></input></th>
			<th class="spc" id="iSPC4fee"
				style="font-weight: normal; background-color: #99ff99;"></th>
		</tr>
		<tr>
			<th class="serie" style="background-color: #ffffff;">SPC-5:</th>
			<th class="spc" style="font-weight: normal;"><input class="spc"
				type="text" id="iSPC5lvl" size=14
				onchange="calcSPCVals('iSPC5lvl');" disabled></input></th>
			<th class="spc" style="font-weight: normal;"><input class="spc"
				type="text" id="iSPC5prc" size=14
				onchange="calcSPCVals('iSPC5prc');" disabled></input></th>
			<th class="spc" id="iSPC5fee"
				style="font-weight: normal; background-color: #99ff99;"></th>
		</tr>
		<tr>
			<th class="serie" style="background-color: #ffffff;">SPC-6:</th>
			<th class="spc" style="font-weight: normal;"><input class="spc"
				type="text" id="iSPC6lvl" size=14
				onchange="calcSPCVals('iSPC6lvl');" disabled></input></th>
			<th class="spc" style="font-weight: normal;"><input class="spc"
				type="text" id="iSPC6prc" size=14
				onchange="calcSPCVals('iSPC6prc');" disabled></input></th>
			<th class="spc" id="iSPC6fee"
				style="font-weight: normal; background-color: #99ff99;"></th>
		</tr>
		<tr>
			<th class="serie" style="background-color: #ffffff;">SPECIAL 1:</th>
			<th class="spc" style="font-weight: normal;"><input class="spc"
				type="text" id="iSPC7lvl" size=14
				onchange="calcSPCVals('iSPC7lvl');" disabled></input></th>
			<th class="spc" style="font-weight: normal;"><input class="spc"
				type="text" id="iSPC7prc" size=14
				onchange="calcSPCVals('iSPC7prc');" disabled></input></th>
			<th class="spc" id="iSPC7fee"
				style="font-weight: normal; background-color: #99ff99;"></th>
		</tr>
		<tr>
			<th class="serie" style="background-color: #ffffff;">SPECIAL 2:</th>
			<th class="spc" style="font-weight: normal;"><input class="spc"
				type="text" id="iSPC8lvl" size=14
				onchange="calcSPCVals('iSPC8lvl');" disabled></input></th>
			<th class="spc" style="font-weight: normal;"><input class="spc"
				type="text" id="iSPC8prc" size=14
				onchange="calcSPCVals('iSPC8prc');" disabled></input></th>
			<th class="spc" id="iSPC8fee"
				style="font-weight: normal; background-color: #99ff99;"></th>
		</tr>
		<tr>
			<th class="serie" style="background-color: #ffffff;">SPECIAL 3:</th>
			<th class="spc" style="font-weight: normal;"><input class="spc"
				type="text" id="iSPC9lvl" size=14
				onchange="calcSPCVals('iSPC9lvl');" disabled></input></th>
			<th class="spc" style="font-weight: normal;"><input class="spc"
				type="text" id="iSPC9prc" size=14
				onchange="calcSPCVals('iSPC9prc');" disabled></input></th>
			<th class="spc" id="iSPC9fee"
				style="font-weight: normal; background-color: #99ff99;"></th>
		</tr>
		<tr>
			<th class="serie" style="background-color: #ffffff;">SPECIAL 4:</th>
			<th class="spc" style="font-weight: normal;"><input class="spc"
				type="text" id="iSPC10lvl" size=14
				onchange="calcSPCVals('iSPC10lvl');" disabled></input></th>
			<th class="spc" style="font-weight: normal;"><input class="spc"
				type="text" id="iSPC10prc" size=14
				onchange="calcSPCVals('iSPC10prc');" disabled></input></th>
			<th class="spc" id="iSPC10fee"
				style="font-weight: normal; background-color: #99ff99;"></th>
		</tr>
		<tr>
			<th class="serie" style="background-color: #ffffff;"></th>
			<th class="spc" style="font-weight: normal;"><input type="button"
				id="set_spc" style="" value="Reset SPC" onclick="resetSPC();"></input>
			</th>
			<th class="spc" style="font-weight: normal;"><input type="button"
				id="cal_spc" style="" value="Calculate SPC" onclick="calcSPC();"></input>
			</th>
			<th class="spc" style="font-weight: normal;"><input type="button"
				id="sav_spc" style="" value="Save SPC" onclick="saveSPC();"></input>
			</th>
		</tr>
	</tbody>
</table>
<?php }?>
<center><table>
<thead>
		<tr>
			<th class="serie" style="color: #ffffff; background-color: #99CCFF;">Stock by Location</th>
		</tr>
	</thead>
	<tbody>
		<?php 
			$invcount = 0;
			foreach($allStore as $store) {
				if($store != "ALL STORES") {
		?>
		<tr><td><?php echo $store;?></td><td><?php echo "<label id='invc$invcount'></label>";  $invcount++;?></td></tr>
		<?php }}?>
	</tbody>
</table></center>
</div>
</div>
</div>

<div id="footer">
<table>
	<tr>
		<!-- <td><a style="border: 0; width: 125px;" href="#"
			onClick="JavaScript: showEditProductPopup();"
			title="Edit the selected product"><img
			src="../images/menu_selectedrecord.png" border=0></img> Edit
		Record</a></td> -->
		<td><a style="border: 0; width: 100px;" href="#"
			onClick="JavaScript: showAddProductPopup();"
			title="Add a new product into the database"><img
			src="../images/menu_newrecord.png" border=0></img> New Product</a></td>
		<!-- <td><a style="border: 0; width:110px;" href="#" onClick="JavaScript:saveChanges();" title="Add a new staff into the database"><img src="../images/menu_savechanges.png"  border=0></img> Save Changes</a></td> -->
	</tr>
</table>
</div>

<div id="backgroundPopup"></div>

<div id="addProductPopupContact"><a id="addProductPopupContactClose">x</a>
<table>
	<tr>
		<td
			style="background: black; padding: 6px;">
		<b style="font-weight: bold; font-size: 12px; color: white;">Add New
		Product</b></td><td>
		<td><label id="np_warning" style="color:red;font-size:12pt;font-weight:bold;"></label></td>
	</tr>
</table>
<div id="addProductPopupContent">
<div id="new_spec_info">
<table>
	<tr>
		<td class="new_product"><b>Store (*):</b></td>
		<td class="new_product"><?php 
		print make_dropdown_list("","new_iStoreID","",$allStore,"Select store the product is added to","");
		?></td>
		<td></td>
	</tr>
	<tr>
		<td class="new_product"><b>Supplier (*):</b></td>
		<td class="new_product"><?php
		print make_dropdown_list("","new_pSupplier","",$sup_list,"Select product from Supplier List","");
		?></td>
		<td></td>
	</tr>
	<tr>
		<td class="new_product"><b>Product Name (*):</b></td>
		<td class="new_product"><input type="text"
			id="new_pName" size=25></input></td>
		<td></td>
	</tr>
	<tr>
		<td class="new_product"><b>Product Category (*):</b></td>
		<td class="new_product"><?php
		print make_dropdown_list("","new_pCatType","",$prod_cat,"Product Category List","");
		?></td>
		<td></td>
	</tr>
	<tr>
		<td class="new_product"><b>Product Description:</b></td>
		<td class="new_product"><input type="text"
			id="new_pDescription" size=25></input></td>
		<td></td>
	</tr>
	<tr>
		<td class="new_product"><b>Product UPC (*):</b></td>
		<td class="new_product"><input type="text"
			id="new_pUPC" size=25 onchange="checkInput('new_pUPC');"></input></td>
		<td></td>
	</tr>
	<tr>
		<td class="new_product"><b>Product Sku:</b></td>
		<td class="new_product"><input type="text"
			id="new_pSku" size=25></input></td>
		<td></td>
	</tr>
	<tr>
		<td class="new_product"><b>Product Code:</b></td>
		<td class="new_product"><input type="text"
			id="new_pProdCode" size=25></input></td>
		<td></td>
	</tr>
	<tr>
		<td class="new_product"><b>Base (*):</b></td>
		<td class="new_product"><?php 
		print make_dropdown_list("","new_pBSize","",$bSize,"Product Base","JavaScript:setBPrice()");
		?></td>
		<td></td>
	</tr>
	<!-- <tr>
		<td class="new_product"><b>Product Size:</b></td>
		<td class="new_product" style="background: yellow;">
			<input type="text" id="new_pSize" size=25></input>
		</td>
	</tr> -->
	<tr>
		<td class="new_product"><b>Size (*):</b></td>
		<td class="new_product"><?php 
		print make_dropdown_list("","new_pCSize","",$cSize,"Product C Size","JavaScript:setCPrice()");
		?></td>
		<td></td>
	</tr>
	<tr>
		<td class="new_product"><b>Product Cost (*):</b></td>
		<td class="new_product"><input type="text"
			id="new_iCost" size=25 onchange="checkInput('new_iCost');"></input></td>
		<td>
		<input type="button" value="Calculate" title="On click will fill in all the green price fields." onclick="fillPrices();"></input>
		</td>
	</tr>
	<tr>
		<td class="new_product"><b>Product MSRP:</b></td>
		<td class="new_product" style="background: #99ff99;"><input
			type="text" id="new_iPrice" size=25 disabled></input></td>
		<td></td>
	</tr>
	<tr>
		<td class="new_product"><b>Product Tax 1 (*):</b></td>
		<td class="new_product"><?php
		print make_dropdown_list("","new_iTax1","",$tax_cat,"Product Tax1","");
		?></td>
		<td></td>
	</tr>
	<tr>
		<td class="new_product"><b>Product Tax 2 (*):</b></td>
		<td class="new_product"><?php
		print make_dropdown_list("","new_iTax2","",$tax_cat,"Product Tax2","");
		?></td>
		<td></td>
	</tr>
	<tr>
		<td class="new_product"><b>Product Min Stock  (*):</b></td>
		<td class="new_product"><input type="text"
			id="new_iMinQty" size=25 onchange="checkInput('new_iMinQty');"></input></td>
		<td></td>
	</tr>
	<tr>
		<td class="new_product"><b>Current Stock (*):</b></td>
		<td class="new_product"><input type="text" 
		id="new_iQty" size=25 onchange="checkInput('new_iQty');"></input>
		</td>
		<td></td>
	</tr>
	<tr>
		<td class="new_product"><b>Product Max Stock (*):</b></td>
		<td class="new_product"><input type="text"
			id="new_iMaxQty" size=25 onchange="checkInput('new_iMaxQty');"></input></td>
		<td></td>
	</tr>
	<tr>
		<td class="new_product"><b>Product Image :</b></td>
		<td class="new_product"><input type="file"
			id="new_pPicture" onchange="checkInput('new_pPicture');"></input></td>		
	</tr>
</table>
<table>
	<tr><td>All fields marked with (*) are requried</td></tr>
</table>
</div>
<div id="new_spec_info2">
<table>
	<thead>
		<tr>
			<th class="serie" style="color: #ffffff; background-color: #ffffff;">&nbsp;</th>
			<th class="serie" style="color: #ffffff; background-color: #000000;">SPC
			Level</th>
			<th class="serie" style="color: #ffffff; background-color: #000000;">SPC
			Pricing</th>
			<th class="serie" style="color: #ffffff; background-color: #000000;">SPC+Tint+Eco</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<th class="serie" style="background-color: #ffffff;">SPC-1:</th>
			<th id="new_iSPC1lvl" style="font-weight: normal;">2.00</th>
			<th id="new_iSPC1" style="font-weight: normal;"
				style="background-color: #99ff99;"></th>
			<th id="new_iSPC1fee"
				style="font-weight: normal; background-color: #99ff99;"></th>
		</tr>
		<tr>
			<th class="serie" style="background-color: #ffffff;">SPC-2:</th>
			<th id="new_iSPC2lvl" style="font-weight: normal;">1.80</th>
			<th id="new_iSPC2" style="font-weight: normal;"
				style="background-color: #99ff99;"></th>
			<th id="new_iSPC2fee"
				style="font-weight: normal; background-color: #99ff99;"></th>
		</tr>
		<tr>
			<th class="serie" style="background-color: #ffffff;">SPC-3:</th>
			<th id="new_iSPC3lvl" style="font-weight: normal;">1.70</th>
			<th id="new_iSPC3" style="font-weight: normal;"
				style="background-color: #99ff99;"></th>
			<th id="new_iSPC3fee"
				style="font-weight: normal; background-color: #99ff99;"></th>
		</tr>
		<tr>
			<th class="serie" style="background-color: #ffffff;">SPC-4:</th>
			<th id="new_iSPC4lvl" style="font-weight: normal;">1.60</th>
			<th id="new_iSPC4" style="font-weight: normal;"
				style="background-color: #99ff99;"></th>
			<th id="new_iSPC4fee"
				style="font-weight: normal; background-color: #99ff99;"></th>
		</tr>
		<tr>
			<th class="serie" style="background-color: #ffffff;">SPC-5:</th>
			<th id="new_iSPC5lvl" style="font-weight: normal;">1.50</th>
			<th id="new_iSPC5" style="font-weight: normal;"
				style="background-color: #99ff99;"></th>
			<th id="new_iSPC5fee"
				style="font-weight: normal; background-color: #99ff99;"></th>
		</tr>
		<tr>
			<th class="serie" style="background-color: #ffffff;">SPC-6:</th>
			<th id="new_iSPC6lvl" style="font-weight: normal;">1.35</th>
			<th id="new_iSPC6" style="font-weight: normal;"
				style="background-color: #99ff99;"></th>
			<th id="new_iSPC6fee"
				style="font-weight: normal; background-color: #99ff99;"></th>
		</tr>
		<tr>
			<th class="serie" style="background-color: #ffffff;">SPECIAL 1:</th>
			<th id="new_iSPC7lvl" style="font-weight: normal;">1.25</th>
			<th id="new_iSPC7" style="font-weight: normal;"
				style="background-color: #99ff99;"></th>
			<th id="new_iSPC7fee"
				style="font-weight: normal; background-color: #99ff99;"></th>
		</tr>
		<tr>
			<th class="serie" style="background-color: #ffffff;">SPECIAL 2:</th>
			<th id="new_iSPC8lvl" style="font-weight: normal;">1.20</th>
			<th id="new_iSPC8" style="font-weight: normal;"
				style="background-color: #99ff99;"></th>
			<th id="new_iSPC8fee"
				style="font-weight: normal; background-color: #99ff99;"></th>
		</tr>
		<tr>
			<th class="serie" style="background-color: #ffffff;">SPECIAL 3:</th>
			<th id="new_iSPC9lvl" style="font-weight: normal;">1.10</th>
			<th id="new_iSPC9" style="font-weight: normal;"
				style="background-color: #99ff99;"></th>
			<th id="new_iSPC9fee"
				style="font-weight: normal; background-color: #99ff99;"></th>
		</tr>
		<tr>
			<th class="serie" style="background-color: #ffffff;">SPECIAL 4:</th>
			<th id="new_iSPC10lvl" style="font-weight: normal;">1.00</th>
			<th id="new_iSPC10" style="font-weight: normal;"
				style="background-color: #99ff99;"></th>
			<th id="new_iSPC10fee"
				style="font-weight: normal; background-color: #99ff99;"></th>
		</tr>
	</tbody>
</table>
</div>
</div>
<div id="new_spec_control">
<center>
<table>
	<tr>
		<td><a href="#" onclick="saveNewProductHelp();">Save</a></td>
		<td><a href="#" onclick="clearAddProduct();">Reset</a></td>
		<td><a href="#" onclick="cancelAddProductPopup();">Cancel</a></td>
		<td><a href="#" onclick="disableAddProductPopup();">Close</a></td>
	</tr>
</table>
</center>
</div>
</div>

<div id="editSPCPopupContact"><a id="editSPCPopupContactClose">x</a>
<table>
	<tr>
		<td
			style="background: black; padding: 6px; border-bottom: 1px dotted #000000;">
		<b style="font-weight: bold; font-size: 12px; color: white;">Change
		SPC Values</b></td>
	</tr>
</table>
<div id="editSPCPopupContent">
<center>
<table>
	<thead>
		<tr>
			<th class="spc" style="color: #ffffff; background-color: #ffffff;">&nbsp;</th>
			<th class="serie" style="color: #ffffff; background-color: #000000;">SPC
			Level</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<th class="spc" style="background-color: #ffffff;">SPC-1:</th>
			<th id="iSPC1lvl" style="font-weight: normal;"><input type="text"
				id="editSPC1" size=10
				onchange="JavaScript: updateSPCArray('editSPC1');"></input></th>
		
		
		<tr>
			<th class="spc" style="background-color: #ffffff;">SPC-2:</th>
			<th id="iSPC2lvl" style="font-weight: normal;"><input type="text"
				id="editSPC2" size=10
				onchange="JavaScript: updateSPCArray('editSPC2');"></input></th>
		
		
		<tr>
			<th class="spc" style="background-color: #ffffff;">SPC-3:</th>
			<th id="iSPC3lvl" style="font-weight: normal;"><input type="text"
				id="editSPC3" size=10
				onchange="JavaScript: updateSPCArray('editSPC3');"></input></th>
		</tr>
		<tr>
			<th class="spc" style="background-color: #ffffff;">SPC-4:</th>
			<th id="iSPC4lvl" style="font-weight: normal;"><input type="text"
				id="editSPC4" size=10
				onchange="JavaScript: updateSPCArray('editSPC4');"></input></th>
		</tr>
		<tr>
			<th class="spc" style="background-color: #ffffff;">SPC-5:</th>
			<th id="iSPC5lvl" style="font-weight: normal;"><input type="text"
				id="editSPC5" size=10
				onchange="JavaScript: updateSPCArray('editSPC5');"></input></th>
		</tr>
		<tr>
			<th class="spc" style="background-color: #ffffff;">SPC-6:</th>
			<th id="iSPC6lvl" style="font-weight: normal;"><input type="text"
				id="editSPC6" size=10
				onchange="JavaScript: updateSPCArray('editSPC6');"></input></th>
		</tr>
		<tr>
			<th class="spc" style="background-color: #ffffff;">SPECIAL 1:</th>
			<th id="iSPC7lvl" style="font-weight: normal;"><input type="text"
				id="editSPC7" size=10
				onchange="JavaScript: updateSPCArray('editSPC7');"></input></th>
		</tr>
		<tr>
			<th class="spc" style="background-color: #ffffff;">SPECIAL 2:</th>
			<th id="iSPC8lvl" style="font-weight: normal;"><input type="text"
				id="editSPC8" size=10
				onchange="JavaScript: updateSPCArray('editSPC8');"></input></th>
		</tr>
		<tr>
			<th class="spc" style="background-color: #ffffff;">SPECIAL 3:</th>
			<th id="iSPC9lvl" style="font-weight: normal;"><input type="text"
				id="editSPC9" size=10
				onchange="JavaScript: updateSPCArray('editSPC9');"></input></th>
		</tr>
		<tr>
			<th class="spc" style="background-color: #ffffff;">SPECIAL 4:</th>
			<th id="iSPC10lvl" style="font-weight: normal;"><input type="text"
				id="editSPC10" size=10
				onchange="JavaScript: updateSPCArray('editSPC10');"></input></th>
		</tr>
		<tr>
			<th><a href="#" onclick="submitSPC();">Load SPC</a></th>
			<th><a href="#" onclick="submitSPC();">Save SPC</a></th>
		</tr>
		<tr>
			<th><a href="#" onclick="submitSPC();">Apply SPC to</a></th>
			<th><?php
			print make_dropdown_list("","spc_Supplier","",$sup_list,"Supplier List","");
			?></th>
		</tr>
	</tbody>
</table>
</center>

<center><a href="#" style="width: 200px;" onclick="submitSPC();">Apply
SPC to All Supplier</a></center>
</div>
</div>
</body>

</html>
