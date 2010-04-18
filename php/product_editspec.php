<?php
include("../backend/product.php");
include("../backend/methods.php");
$sid=$_GET['sid'];
$pid=$_GET['pid'];
//print "STORE" . $store . "|id" . $id;
?>

<html>
<head>
<title></title>
<link href="../css/staff_editspec.css" rel="stylesheet" type="text/css" />
<script src="../js/jquery.js" type="text/javascript"></script>
<script src="../js/general.js" type="text/javascript"></script>
<script src="../js/product.js" type="text/javascript"></script>
</head>
<body onload=<?php print "\"JavaScript: loadEditInfo('$sid','$pid')\"";?>>
<div id="new_spec_info">
<input type="hidden" id="edit_pBPrice" value="-1"></input>
<table>
	<tr>
		<td class="edit_product"><b>Store</b></td>
		<td class="edit_product">
		<select id="edit_iStoreID" size="1" onchange="JavaScript: prodEditUpdate('edit_iStoreID');" disabled>
			<option value="0"></option>
			<option value="1">COLORS OF MAPLE</option>
			<option value="2">OAKRIDGES</option>
		</select></td>
	</tr>
	<tr>
		<td class="edit_product"><b>Supplier</b></td>
		<td class="edit_product">
			<?php
				$val_array = get_supplier_list(); 
				print make_dropdown_list("","edit_pSupplier","",$val_array,"Supplier List", "JavaScript: prodEditUpdate('edit_pSupplier')");
			?>
		</td>
	</tr>
	<tr>
		<td class="edit_product"><b>Product Name:</b></td>
		<td class="edit_product">
			<input type="text" id="edit_pName" size=25 onchange="JavaScript: prodEditUpdate('edit_pName');"></input>
		</td>
	</tr>
	<tr>
		<td class="edit_product"><b>Product Category:</b></td>
		<td class="edit_product">
			<?php
				$val_array = get_product_category(); 
				print make_dropdown_list("","edit_pCatType","",$val_array,"Product Category List","JavaScript: prodEditUpdate('edit_pCatType')");
			?>
		</td>
	</tr>
	<tr>
		<td class="edit_product"><b>Product Description:</b></td>
		<td class="edit_product">
			<input type="text" id="edit_pDescription" size=25 onchange="JavaScript: prodEditUpdate('edit_pDescription');"></input>
		</td>
	</tr>
	<tr>
		<td class="edit_product"><b>Product UPC:</b></td>
		<td class="edit_product">
			<input type="text" id="edit_pUPC" size=25 onchange="JavaScript: prodEditUpdate('edit_pUPC');"></input>
		</td>
	</tr>
	<tr>
		<td class="edit_product"><b>Product Sku:</b></td>
		<td class="edit_product">
			<input type="text" id="edit_pSku" size=25 onchange="JavaScript: prodEditUpdate('edit_pSku');"></input>
		</td>
	</tr>
	<tr>
		<td class="edit_product"><b>Product Code:</b></td>
		<td class="edit_product">
			<input type="text" id="edit_pProdCode" size=25 onchange="JavaScript: prodEditUpdate('edit_pProdCode');"></input>
		</td>
	</tr>
	<!-- <tr>
		<td class="edit_product"><b>Product Size:</b></td>
		<td class="edit_product">
			<input type="text" id="edit_pSize" size=25 onchange="JavaScript: prodEditUpdate('edit_pSize');"></input>
		</td>
	</tr> -->
	<tr>
		<td class="new_product"><b>Base:</b></td>
		<td class="new_product">
			<?php 
				$val_array = get_B_sizes();
				print make_dropdown_list("","edit_pBSize","",$val_array,"Product Base","JavaScript: prodEditUpdate('edit_pBSize')");
			?>
		</td>
	</tr>
	<tr>
		<td class="new_product"><b>Size:</b></td>
		<td class="new_product">
			<?php 
				$val_array = get_C_size();
				print make_dropdown_list("","edit_pCSize","",$val_array,"Product C Size","JavaScript: prodEditUpdate('edit_pCSize')");
			?>
		</td>
	</tr>
	<tr>
		<td class="edit_product"><b>Product Cost:</b></td>
		<td class="edit_product">
			<input type="text"  id="edit_iCost" size=25 onchange="JavaScript: prodEditUpdate('edit_iCost')"></input>
		</td>
	</tr>
	<tr>
		<td class="edit_product"><b>Product MSRP:</b></td>
		<td class="edit_product">
			<input type="text" id="edit_iPrice" size=25 onchange="JavaScript: prodEditUpdate('edit_iPrice');"></input>
		</td>
	</tr>
	<tr>
		<td class="edit_product"><b>Product Tax 1:</b></td>
		<td class="edit_product">
			<?php
				$val_array = get_tax_category(); 
				print make_dropdown_list("","edit_iTax1","",$val_array,"Product Category List","JavaScript: prodEditUpdate('edit_iTax1')");
			?>
		</td>
	</tr>
	<tr>
		<td class="edit_product"><b>Product Tax 2:</b></td>
		<td class="edit_product">
			<?php
				$val_array = get_tax_category(); 
				print make_dropdown_list("","edit_iTax2","",$val_array,"Product Category List","JavaScript: prodEditUpdate('edit_iTax2')");
			?>
		</td>
	</tr>
	<tr>
		<td class="edit_product"><b>Product Min Stock:</b></td>
		<td class="edit_product">
			<input type="text" id="edit_iMinQty" size=25 onchange="JavaScript: prodEditUpdate('edit_iMinQty');"></input>
		</td>
	</tr>
	<tr>
		<td class="edit_product"><b>Current	Quantity:</b></td>
		<td class="edit_product">
			<input type="text" id="edit_iQty" size=25 onchange="JavaScript: prodEditUpdate('edit_iQty');"></input>
		</td>
	</tr>
	<tr>
		<td class="edit_product"><b>Product Max Stock:</b></td>
		<td class="edit_product">
			<input type="text" id="edit_iMaxQty" size=25 onchange="JavaScript: prodEditUpdate('edit_iMaxQty');"></input>
		</td>
	</tr>
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
			<th class="serie" id="editTemp" style="color: #ffffff; background-color: #000000;">SPC+Tint+Eco</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<th class="serie" style="background-color: #ffffff;">SPC-1:</th>
			<th id="edit_pSPC1lvl" style="font-weight: normal;">2.00</th>
			<th id="edit_pSPC1prc" style="font-weight: normal;"></th>
			<th id="edit_pSPC1fee"
				style="font-weight: normal; background-color: #99ff99;"></th>
		</tr>
		<tr>
			<th class="serie" style="background-color: #ffffff;">SPC-2:</th>
			<th id="edit_pSPC2lvl" style="font-weight: normal;">1.80</th>
			<th id="edit_pSPC2prc" style="font-weight: normal;"></th>
			<th id="edit_pSPC2fee"
				style="font-weight: normal; background-color: #99ff99;"></th>
		</tr>
		<tr>
			<th class="serie" style="background-color: #ffffff;">SPC-3:</th>
			<th id="edit_pSPC3lvl" style="font-weight: normal;">1.70</th>
			<th id="edit_pSPC3prc" style="font-weight: normal;"></th>
			<th id="edit_pSPC3fee"
				style="font-weight: normal; background-color: #99ff99;"></th>
		</tr>
		<tr>
			<th class="serie" style="background-color: #ffffff;">SPC-4:</th>
			<th id="edit_pSPC4lvl" style="font-weight: normal;">1.60</th>
			<th id="edit_pSPC4prc" style="font-weight: normal;"></th>
			<th id="edit_pSPC4fee"
				style="font-weight: normal; background-color: #99ff99;"></th>
		</tr>
		<tr>
			<th class="serie" style="background-color: #ffffff;">SPC-5:</th>
			<th id="edit_pSPC5lvl" style="font-weight: normal;">1.50</th>
			<th id="edit_pSPC5prc" style="font-weight: normal;"></th>
			<th id="edit_pSPC5fee"
				style="font-weight: normal; background-color: #99ff99;"></th>
		</tr>
		<tr>
			<th class="serie" style="background-color: #ffffff;">SPC-6:</th>
			<th id="edit_pSPC6lvl" style="font-weight: normal;">1.35</th>
			<th id="edit_pSPC6prc" style="font-weight: normal;"></th>
			<th id="edit_pSPC6fee"
				style="font-weight: normal; background-color: #99ff99;"></th>
		</tr>
		<tr>
			<th class="serie" style="background-color: #ffffff;">SPECIAL 1:</th>
			<th id="edit_pSPC7lvl" style="font-weight: normal;">1.25</th>
			<th id="edit_pSPC7prc" style="font-weight: normal;"></th>
			<th id="edit_pSPC7fee"
				style="font-weight: normal; background-color: #99ff99;"></th>
		</tr>
		<tr>
			<th class="serie" style="background-color: #ffffff;">SPECIAL 2:</th>
			<th id="edit_pSPC8lvl" style="font-weight: normal;">1.20</th>
			<th id="edit_pSPC8prc" style="font-weight: normal;"></th>
			<th id="edit_pSPC8fee"
				style="font-weight: normal; background-color: #99ff99;"></th>
		</tr>
		<tr>
			<th class="serie" style="background-color: #ffffff;">SPECIAL 3:</th>
			<th id="edit_pSPC9lvl" style="font-weight: normal;">1.10</th>
			<th id="edit_pSPC9prc" style="font-weight: normal;"></th>
			<th id="edit_pSPC9fee"
				style="font-weight: normal; background-color: #99ff99;"></th>
		</tr>
		<tr>
			<th class="serie" style="background-color: #ffffff;">SPECIAL 4:</th>
			<th id="edit_pSPC0lvl" style="font-weight: normal;">1.00</th>
			<th id="edit_pSPC0prc" style="font-weight: normal;"></th>
			<th id="edit_pSPC0fee"
				style="font-weight: normal; background-color: #99ff99;"></th>
		</tr>
	</tbody>
</table>
</div>
<div id="new_spec_control">
<center>
<table>
	<tr>
		<td><a href="#" id="editPAdd" onClick="JavaScript: editProduct();">Confirm Edit</a></td>
		<td><a href="#" id="editPClear"
			onClick="JavaScript: clearEditProduct();">Clear Fields</a></td>
	</tr>
</table>
</center>
</div>
</body>

</html>