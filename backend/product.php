<?php
include("db_methods.php");

ini_set("display_errors", 0);
/*if(!empty($_GET['logoff'])) { $_SESSION = array(); }

//@ is authorized?
if(empty($_SESSION['exp_user']) || @$_SESSION['exp_user']['expires'] < time()) {
header("location:../src/login.html");	//@ redirect
} else {
$_SESSION['exp_user']['expires'] = time()+(30*60);	//@ renew 45 minutes
}*/
$data;


function create_promo_header() {
	$row = "";
	$row .= wrap_tag("Item","th");
	$row .= wrap_tag("Photo","th");
	$row .= wrap_tag("Promo Note","th");	
   return wrap_tag($row,"tr");
}

function create_promo_row() {
	$table = NULL;
	$upc = "";
	$result = array();
	$headers = array('item','photo','note');
	$sql = "SELECT tblProduct.pID AS pID,
			tblSupplier.sName AS SupplierName,
			tblProduct.pUPC AS pUPC,
			tblProduct.pDescription AS pDescription,
			tblProduct.pPromo AS pPromo
			FROM pos.tblProduct 
			INNER JOIN pos.tblSupplier ON tblSupplier.sID=tblProduct.pSupplier 
			WHERE pPromo IS NOT NULL";
	$data = query_database($sql);
	$n = 0;
	foreach($data as $rec) {
		$row = "";
		foreach($headers as $field) {
			if($field == "item") {
				$fdata = "[".$rec['SupplierName']."] <br /> ".$rec['pUPC']; 
			} else if($field == "photo") {
				$fname = "default.jpg";
				$fdata = "<img src=\"../promo_pictures/$fname\" ".
				"alt=\"Missing image\" width=\"60\" height=\"60\"/>";
			} else if($field == "note") {
				$fdata = $rec['pPromo'];
			}
			$row .= wrap_tag($fdata,"td");
		}
		$table[] = wrap_tag($row,"tr",
		//"class=\"$class\" ".
		"p1=\"".$rec['pID']."\" ".
		"p2=\"".$rec['SupplierName']."\" ".
		"p3=\"".$rec['pUPC']."\" ".
		"p4=\"".$rec['pDescription']."\" ".
		"p5=\"".$rec['pPromo']."\" ".
		"title=\"".$rec['pDescription']."\" ".
		"onclick=\"return alert(this);\"");
		$n++;
	}
	return $table;
}

function create_table_header() {
	$row = "";
	$row .= wrap_tag("Store","th");
	$row .= wrap_tag("Supplier","th");
	$row .= wrap_tag("UPC","th");
	$row .= wrap_tag("pSku","th");
	$row .= wrap_tag("ProdCode","th");
	$row .= wrap_tag("Description","th");
	$row .= wrap_tag("Size","th");
	$row .= wrap_tag("Qty","th");
	$row .= wrap_tag("Cost","th");
	$row .= wrap_tag("MSRP","th");
	$row .= wrap_tag("Base","th");
	$row .= wrap_tag("Tint","th");		
   return wrap_tag($row,"tr");
}

function create_table_row($search,$store,$supplier) {
	//print "TEST:" . $supplier . "\n";
	global $data;
	$table = NULL;
	$upc = "";
	$result = array();
	$headers = array('StoreName','SupplierName','pUPC',
	'pSku','pProdCode','pDescription','pSize','pQty',
	'pCost','pMSRP','pBase','pTint');
	
	if($search == "PROMOITEMS") {
		$sql = "SELECT tblStore.sName AS StoreName,
			tblInventory.iStoreID AS sID,
			tblInventory.iProduct AS pID,
			tblSupplier.sName as SupplierName,
			tblProduct.pUPC as pUPC,
			tblProduct.pSku as pSku,
			tblProduct.pProdCode as pProdCode,
			tblProduct.pDescription as pDescription,
			tblProduct.pSize as pSize, 
			tblInventory.iQty as pQty, 
			tblInventory.iCost as pCost, 
			tblInventory.iPrice as pMSRP 
			FROM pos.tblInventory	
			INNER JOIN pos.tblProduct ON tblProduct.pID=tblInventory.iProduct 
			INNER JOIN pos.tblStore	ON tblStore.sID=tblInventory.iStoreID 
			INNER JOIN pos.tblSupplier ON tblSupplier.sID=tblProduct.pSupplier 
			WHERE pPromo IS NOT NULL";
	} else {
		if($store != "") {
			$sql = "SELECT tblStore.sName AS StoreName,
			tblInventory.iStoreID AS sID,
			tblInventory.iProduct AS pID,
			tblSupplier.sName as SupplierName,
			tblProduct.pUPC as pUPC,
			tblProduct.pSku as pSku,
			tblProduct.pProdCode as pProdCode,
			tblProduct.pDescription as pDescription,
			tblProduct.pSize as pSize,
			tblInventory.iQty as pQty, 
			tblInventory.iCost as pCost, 
			tblInventory.iPrice as pMSRP  
			FROM pos.tblInventory	
			INNER JOIN pos.tblProduct ON tblProduct.pID=tblInventory.iProduct 
			INNER JOIN pos.tblStore	ON tblStore.sID=tblInventory.iStoreID 
			INNER JOIN pos.tblSupplier ON tblSupplier.sID=tblProduct.pSupplier  
			WHERE tblInventory.iStoreID=$store 
			AND (tblProduct.pDescription LIKE '%$search%' 
			OR tblProduct.pProdCode LIKE '%$search%'
			OR tblProduct.pSku LIKE '%$search%'
			OR tblProduct.pName LIKE '%$search%'
			OR tblProduct.pUPC LIKE '%$search%')";
			if($supplier != "") {
				$sql .= " AND pSupplier=$supplier";
			}
		} else {
			$sql = "SELECT tblStore.sName AS StoreName,
			tblInventory.iStoreID AS sID,
			tblInventory.iProduct AS pID,
			tblSupplier.sName as SupplierName,
			tblProduct.pUPC as pUPC,
			tblProduct.pSku as pSku,
			tblProduct.pProdCode as pProdCode,
			tblProduct.pDescription as pDescription,
			tblProduct.pSize as pSize, 
			tblInventory.iQty as pQty, 
			tblInventory.iCost as pCost, 
			tblInventory.iPrice as pMSRP 
			FROM pos.tblInventory	
			INNER JOIN pos.tblProduct ON tblProduct.pID=tblInventory.iProduct 
			INNER JOIN pos.tblStore	ON tblStore.sID=tblInventory.iStoreID 
			INNER JOIN pos.tblSupplier ON tblSupplier.sID=tblProduct.pSupplier 
			WHERE (tblProduct.pDescription LIKE '%$search%' 
			OR tblProduct.pProdCode LIKE '%$search%'
			OR tblProduct.pSku LIKE '%$search%'
			OR tblProduct.pName LIKE '%$search%'
			OR tblProduct.pUPC LIKE '%$search%')";
			if($supplier != "") {
				$sql .= " AND pSupplier=$supplier";
			}
		}
	}
	//AND tblProduct.pCatType=tblCatType.ctID
	//INNER JOIN pos.tblCatType
	//print $sql;
	$data = query_database($sql);
	$n = 0;
	foreach($data as $rec) {
		$row = "";
		$sID = $rec['sID'];
		$pID = $rec['pID'];
		foreach($headers as $field) {
			$fdata = $rec[$field];
			if($field == "pUPC") {
				$upc = $fdata; 
			}
			if($fdata == "") {
				$fdata = "--";
			} 
			if(strlen($fdata) > 90) {
				$fdata = substr($fdata,0,70);
				$fdata .= "...";
			} else {
				$fdata = $fdata;
			}
			//$upc .= $fdata;
			$row .= wrap_tag($fdata,"td");
		}
		$table[] = wrap_tag($row,"tr",
		//"class=\"$class\" ".
		"p1=\"".$rec['sID']."\" ".
		"p2=\"".$rec['pID']."\" ".
		"p3=\"".$rec['pUPC']."\" ".
		"p4=\"".$rec['SupplierName']."\" ".
		"onclick=\"return choose_product(this);\"");
		$n++;
	}
	return $table;
}

function create_invoicetable_row($search,$spc,$store) {
	$spci = "iSPC" . $spc;
	global $data;
	$table = NULL;
	$upc = "";
	$result = array();
	//$store = $_SESSION['exp_user']['localSID'];
	$headers = array('StoreName','SupplierName','pUPC',
	'pSku','pProdCode','pDescription','pSize','pQty',
	'pCost','pMSRP','pBase','pTint');
	//$sql = "SELECT tblProduct.*,tblStore.sStoreNum AS storeID,tblStore.sName AS storeName,tblSupplier.sName AS supplyName,tblCatType.cCatType AS catType,tblInventory.*	FROM pos.tblProduct,pos.tblStore,pos.tblSupplier,pos.tblCatType,pos.tblInventory WHERE pDescription LIKE '%$search%' AND tblProduct.pSupplier=tblSupplier.sID AND tblProduct.pCatType=tblCatType.ctID AND tblProduct.pID=tblInventory.iProduct AND tblProduct.pIsActive=0";
	$sql = "SELECT tblStore.sName AS StoreName,
		tblInventory.iStoreID AS sID,
		tblInventory.iProduct AS pID,
		tblSupplier.sName AS SupplierName,
		tblProduct.pSupplier AS SupplierID,
		tblProduct.pUPC as pUPC,
		tblProduct.pSku as pSku,
		tblProduct.pProdCode as pProdCode,
		tblProduct.pDescription as pDescription,
		tblProduct.pSize as pSize,
		tblProduct.pCatType as category,
		tblInventory.iQty as pQty, 
		tblInventory.iCost as pCost, 
		tblInventory.iPrice as pMSRP,
		tblInventory.$spci AS iSPCc,
		tblProduct.pCSize AS pCSize,
		tblInventory.iTax1 AS iTax1, 
		tblInventory.iTax2 AS iTax2
		FROM pos.tblInventory	
		INNER JOIN pos.tblProduct ON tblProduct.pID=tblInventory.iProduct  
		INNER JOIN pos.tblStore	ON  tblStore.sID=tblInventory.iStoreID 
		INNER JOIN pos.tblSupplier ON tblProduct.pSupplier=tblSupplier.sID
		WHERE tblInventory.iStoreID=$store  
		AND (tblProduct.pDescription LIKE '%$search%' 
		OR tblProduct.pProdCode LIKE '%$search%'
		OR tblProduct.pSku LIKE '%$search%'
		OR tblProduct.pName LIKE '%$search%'
		OR tblProduct.pUPC LIKE '%$search%');";
	$data = query_database($sql);
	//print $sql;
	$n = 0;
	$record = fetch_value_if_exists($_GET,'record');
	foreach($data as $rec) {
		//$selected = ($record == $n);
		//$to_mark = ($record == $n+6);
		$row = "";		
		foreach($headers as $field) {
			$fdata = $rec[$field];
			if($fdata == "") {
				$fdata = "--";
			} 
			if(strlen($fdata) > 90) {
				$fdata = substr($fdata,0,70);
				$fdata .= "...";
			} else {
				$fdata = $fdata;
			}
			//$upc .= $fdata;
			if($to_mark) {
				$fdata .= "<a name=\"r\"></a>";
			}
			$row .= wrap_tag($fdata,"td");
		}
		//$class = ($selected)?"selectcell":"cell" . ($n % 2);
		$table[] = wrap_tag($row,"tr",
		//"class=\"$class\" ".
		"i1=\"".$rec['StoreName']."\" ".
		"i2=\"".$rec['sID']."\" ".
		"i3=\"".$rec['pID']."\" ".
		"i4=\"".$rec['SupplierID']."\" ".
		"i5=\"".$rec['pUPC']."\" ".
		"i6=\"".$rec['pSku']."\" ".
		"i7=\"".$rec['pProdCode']."\" ". 
		"i8=\"".$rec['pDescription']."\" ". 
		"i9=\"".$rec['pSize']."\" ".
		"i10=\"".$rec['category']."\" ". 
		"i11=\"".$rec['pQty']."\" ".
		"i12=\"".$rec['pCost']."\" ".
		"i13=\"".$rec['pMSRP']."\" ".
		"i14=\"".$rec['pCSize']."\" ".
		"i15=\"".$spci."\" ".
		"i16=\"".$rec['iTax1']."\" ".
		"i17=\"".$rec['iTax2']."\" ".
		"i18=\"".$rec['iSPCc']."\" ".
		"onMouseOver=\"this.bgColor='#99FF66';\" ".
		"onMouseOut=\"this.bgColor='#FFFFE0';\" ".
		"onclick=\"return choose_item(this);\"");
		$n++;
	}
	return $table;
}

function print_table($query,$store,$supplier) {
	$table = "";
	if($query=="") {
		print "<table height=\"100%\" width=\"100%\"><tr><td><div style=\"font-weight:bold;text-align:center;font-size:120%;\">Please enter product description to search.</div></td></tr></table>\n";
	} else {
		if($header = create_table_header()) {
			$table .= wrap_tag($header,"thead");
			//$table .= $header;
			if($row = create_table_row($query,$store,$supplier)) {
				$tbody .= join($row,"\n");
				$table .= wrap_tag($tbody,"tbody","");
				$table = wrap_tag($table,"table","id=\"myTable\" class=\"tablesorter\" border=\"0\" cellpadding=\"0\" cellspacing=\"1\" title=\"Click on row to see product details\"");
				print $table;
				/*$table .= join($row,"\n");
				$table = wrap_tag($table,"table","width=100% title=\"Select record to see details\"");
				print "<style type=\"text/css\">\ntr.cell0 td,tr.cell1 td, tr.selectcell td { white-space:nowrap; }\n</style>\n";
				print $table;*/
			} else {
				print "<table height=\"100%\" width=\"100%\"><tr><td><div style=\"font-weight:bold;text-align:center;font-size:120%;\">No matching records.</div></td></tr></table>\n";
			}
		}
	}
}

function print_table_invoice($query,$spc,$store) {
	$table = "";
	if($query=="") {
		print "<table height=\"100%\" width=\"100%\"><tr><td><div style=\"font-weight:bold;text-align:center;font-size:120%;\">Please enter product description to search.</div></td></tr></table>\n";
	} else {
		if($header = create_table_header()) {
			$table .= wrap_tag($header,"thead");
			//$table .= $header;
			if($row = create_invoicetable_row($query,$spc,$store)) {
				$tbody .= join($row,"\n");
				$table .= wrap_tag($tbody,"tbody","");
				$table = wrap_tag($table,"table","id=\"myTable\" class=\"tablesorter\" border=\"0\" cellpadding=\"0\" cellspacing=\"1\" title=\"Click on row to see product details\"");
				print $table;
				/*$table .= join($row,"\n");
				$table = wrap_tag($table,"table","width=100% title=\"Click item to select\"");
				print "<style type=\"text/css\">\ntr.cell0 td,tr.cell1 td, tr.selectcell td { white-space:nowrap; }\n</style>\n";
				print $table;*/
			} else {
				print "<table height=\"100%\" width=\"100%\"><tr><td><div style=\"font-weight:bold;text-align:center;font-size:120%;\">No matching records.</div></td></tr></table>\n";
			}
		}
	}
}

function print_promo_table() {
	$table = "";
	if($header = create_promo_header()) {
		$table .= wrap_tag($header,"thead");
		//$table .= $header;
		if($row = create_promo_row()) {
			$tbody .= join($row,"\n");
			$table .= wrap_tag($tbody,"tbody","");
			$table = wrap_tag($table,"table","id=\"myTable\" class=\"tablesorter\" border=\"0\" cellpadding=\"0\" cellspacing=\"1\" title=\"Click on row to see product details\"");
			print $table;
			/*$table .= join($row,"\n");
			$table = wrap_tag($table,"table","width=100% title=\"Select record to see details\"");
			print "<style type=\"text/css\">\ntr.cell0 td,tr.cell1 td, tr.selectcell td { white-space:nowrap; }\n</style>\n";
			print $table;*/
		} else {
			print "<table height=\"100%\" width=\"100%\"><tr><td><div style=\"font-weight:bold;text-align:center;font-size:120%;\">No matching records.</div></td></tr></table>\n";
		}
	}
}

function get_details($n) {
	global $data;
	$result = $data[0]['pUPC'];
	//$d = "";
	//,tblProduct.pSize AS psPSize,tblInventory.iCost AS psPCost,tblInventory.iPrice AS psPMSRP,tblTax.tName AS psPTax1,tblTax.tName AS psPTax2,tblCatType.cCatType AS psPCat
	//AND tblProduct.pCatType=tblCatType.ctID 
	// AND tblTax.tName=tblInventory.iTax1 AND tblTax.tName=tblInventory.iTax2
	//$sql = "SELECT tblStore.sName AS psName,tblSupplier.sName as psSupplier,tblProduct.pName AS pName,tblProduct.pDescription AS psDescription,tblProduct.pUPC AS psUPC,tblProduct.pSku AS psSku,tblProduct.pProdCode AS psPCode FROM pos.tblProduct,pos.tblInventory,pos.tblStore,pos.tblSupplier WHERE pUPC=$upc AND tblProduct.pSupplier=tblSupplier.sID AND tblProduct.pID=tblInventory.iProduct;";
	//$result = query_database($sql);
	//return $data[$n];
	//return "DANIEL";
	return $result;
	//return "TEST TEST";
}

function find_tax($t) {
	if($t == '1') {
		return "EXCEMPT";
	} else if($t == '2') {
		return "PST";
	} else if($t == '3') {
		return "GST";
	} else {
		
	}
}
?>