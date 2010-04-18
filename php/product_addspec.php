<?php
include("../backend/product.php");
include("../backend/methods.php");
$store=$_GET['sStoreID'];
$id=$_GET['sID'];
//print "STORE" . $store . "|id" . $id;
?>

<html>
<head>
<title></title>
<link href="../css/staff_editspec.css" rel="stylesheet" type="text/css" />
<script src="../js/jquery.js" type="text/javascript"></script>
<script src="../js/product.js" type="text/javascript"></script>
</head>
<body>
<div id="new_spec_info">
<table>
	<tr>
		<td class="new_product"><b>Store</b></td>
		<td class="new_product"><select id="new_iStoreID" size="1">
			<option value="opt1"></option>
			<option value="opt2">COLORS OF MAPLE</option>
			<option value="opt3">OAKRIDGES</option>
		</select></td>
	</tr>
	<tr>
		<td class="new_product"><b>Supplier</b></td>
		<td class="new_product">
			<?php
				$val_array = get_supplier_list(); 
				print make_dropdown_list("","new_pSupplier","",$val_array,"Supplier List");
			?>
		</td>
	</tr>
	<tr>
		<td class="new_product"><b>Product Name:</b></td>
		<td class="new_product">
			<input type="text" id="new_pName" size=25></input>
		</td>
	</tr>
	<tr>
		<td class="new_product"><b>Product Category:</b></td>
		<td class="new_product">
			<?php
				$val_array = get_product_category(); 
				print make_dropdown_list("","new_pCatType","",$val_array,"Product Category List");
			?>
		</td>
	</tr>
	<tr>
		<td class="new_product"><b>Product Description:</b></td>
		<td class="new_product">
			<input type="text" id="new_pDescription" size=25></input>
		</td>
	</tr>
	<tr>
		<td class="new_product"><b>Product UPC:</b></td>
		<td class="new_product">
			<input type="text" id="new_pUPC" size=25></input>
		</td>
	</tr>
	<tr>
		<td class="new_product"><b>Product Sku:</b></td>
		<td class="new_product">
			<input type="text" id="new_pSku" size=25></input>
		</td>
	</tr>
	<tr>
		<td class="new_product"><b>Product Code:</b></td>
		<td class="new_product">
			<input type="text" id="new_pProdCode" size=25></input>
		</td>
	</tr>
	<!-- <tr>
		<td class="new_product"><b>Product Size:</b></td>
		<td class="new_product">
			<input type="text" id="new_pSize" size=25></input>
		</td>
	</tr> -->
	<tr>
		<td class="new_product"><b>Base:</b></td>
		<td class="new_product">
			<?php 
				$val_array = get_B_sizes();
				print make_dropdown_list("","new_pBSize","",$val_array,"Product Base");
			?>
		</td>
	</tr>
	<tr>
		<td class="new_product"><b>Size:</b></td>
		<td class="new_product">
			<?php 
				$val_array = get_C_size();
				print make_dropdown_list("","new_pCSize","",$val_array,"Product C Size");
			?>
		</td>
	</tr>
	<tr>
		<td class="new_product"><b>Product Cost:</b></td>
		<td class="new_product">
			<input type="text"  id="new_iCost" size=25 onchange="JavaScript: fillPrices()"></input>
		</td>
	</tr>
	<tr>
		<td class="new_product"><b>Product MSRP:</b></td>
		<td class="new_product">
			<input type="text" id="new_iPrice" size=25></input>
		</td>
	</tr>
	<tr>
		<td class="new_product"><b>Product Tax 1:</b></td>
		<td class="new_product">
			<?php
				$val_array = get_tax_category(); 
				print make_dropdown_list("","new_iTax1","",$val_array,"Product Tax1");
			?>
		</td>
	</tr>
	<tr>
		<td class="new_product"><b>Product Tax 2:</b></td>
		<td class="new_product">
			<?php
				$val_array = get_tax_category(); 
				print make_dropdown_list("","new_iTax2","",$val_array,"Product Tax2");
			?>
		</td>
	</tr>
	<tr>
		<td class="new_product"><b>Product Min Stock:</b></td>
		<td class="new_product">
			<input type="text" id="new_iMinQty" size=25></input>
		</td>
	</tr>
	<tr>
		<td class="new_product"><b>Current Stock:</b></td>
		<td class="new_product">
			<input type="text" id="new_iQty" size=25></input>
		</td>
	</tr>
	<tr>
		<td class="new_product"><b>Product Max Stock:</b></td>
		<td class="new_product">
			<input type="text" id="new_iMaxQty" size=25></input>
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
			<th class="serie" style="color: #ffffff; background-color: #000000;">SPC+Tint+Eco</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<th class="serie" style="background-color: #ffffff;">SPC-1:</th>
			<th id="new_pSPC1lvl" style="font-weight: normal;">2.00</th>
			<th id="new_pSPC1prc" style="font-weight: normal;"></th>
			<th id="new_pSPC1fee"
				style="font-weight: normal; background-color: #99ff99;"></th>
		</tr>
		<tr>
			<th class="serie" style="background-color: #ffffff;">SPC-2:</th>
			<th id="new_pSPC2lvl" style="font-weight: normal;">1.80</th>
			<th id="new_pSPC2prc" style="font-weight: normal;"></th>
			<th id="new_pSPC2fee"
				style="font-weight: normal; background-color: #99ff99;"></th>
		</tr>
		<tr>
			<th class="serie" style="background-color: #ffffff;">SPC-3:</th>
			<th id="new_pSPC3lvl" style="font-weight: normal;">1.70</th>
			<th id="new_pSPC3prc" style="font-weight: normal;"></th>
			<th id="new_pSPC3fee"
				style="font-weight: normal; background-color: #99ff99;"></th>
		</tr>
		<tr>
			<th class="serie" style="background-color: #ffffff;">SPC-4:</th>
			<th id="new_pSPC4lvl" style="font-weight: normal;">1.60</th>
			<th id="new_pSPC4prc" style="font-weight: normal;"></th>
			<th id="new_pSPC4fee"
				style="font-weight: normal; background-color: #99ff99;"></th>
		</tr>
		<tr>
			<th class="serie" style="background-color: #ffffff;">SPC-5:</th>
			<th id="new_pSPC5lvl" style="font-weight: normal;">1.50</th>
			<th id="new_pSPC5prc" style="font-weight: normal;"></th>
			<th id="new_pSPC5fee"
				style="font-weight: normal; background-color: #99ff99;"></th>
		</tr>
		<tr>
			<th class="serie" style="background-color: #ffffff;">SPC-6:</th>
			<th id="new_pSPC6lvl" style="font-weight: normal;">1.35</th>
			<th id="new_pSPC6prc" style="font-weight: normal;"></th>
			<th id="new_pSPC6fee"
				style="font-weight: normal; background-color: #99ff99;"></th>
		</tr>
		<tr>
			<th class="serie" style="background-color: #ffffff;">SPECIAL 1:</th>
			<th id="new_pSPC7lvl" style="font-weight: normal;">1.25</th>
			<th id="new_pSPC7prc" style="font-weight: normal;"></th>
			<th id="new_pSPC7fee"
				style="font-weight: normal; background-color: #99ff99;"></th>
		</tr>
		<tr>
			<th class="serie" style="background-color: #ffffff;">SPECIAL 2:</th>
			<th id="new_pSPC8lvl" style="font-weight: normal;">1.20</th>
			<th id="new_pSPC8prc" style="font-weight: normal;"></th>
			<th id="new_pSPC8fee"
				style="font-weight: normal; background-color: #99ff99;"></th>
		</tr>
		<tr>
			<th class="serie" style="background-color: #ffffff;">SPECIAL 3:</th>
			<th id="new_pSPC9lvl" style="font-weight: normal;">1.10</th>
			<th id="new_pSPC9prc" style="font-weight: normal;"></th>
			<th id="new_pSPC9fee"
				style="font-weight: normal; background-color: #99ff99;"></th>
		</tr>
		<tr>
			<th class="serie" style="background-color: #ffffff;">SPECIAL 4:</th>
			<th id="new_pSPC10lvl" style="font-weight: normal;">1.00</th>
			<th id="new_pSPC10prc" style="font-weight: normal;"></th>
			<th id="new_pSPC10fee"
				style="font-weight: normal; background-color: #99ff99;"></th>
		</tr>
	</tbody>
</table>
</div>
<div id="new_spec_control">

</div>
</body>

</html>