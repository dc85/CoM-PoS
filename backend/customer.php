<?php
include("db_methods.php");

ini_set("display_errors", 0);

$data;

function create_table_header() {
	$row = "";
	$row .= wrap_tag("Name","th","");
	$row .= wrap_tag("AKA","th","");
	$row .= wrap_tag("Company","th","");
	$row .= wrap_tag("Address","th","");
	$row .= wrap_tag("Phone","th","");
	$row .= wrap_tag("CoMNum","th","");		
    return wrap_tag($row,"tr");
}

/*function create_table_header() {
	$row = "";
	$row .= wrap_tag("Name","td","class=\"cellTitle\"");
	$row .= wrap_tag("AKA","td","class=\"cellTitle\"");
	$row .= wrap_tag("Company","td","class=\"cellTitle\"");
	$row .= wrap_tag("Address","td","class=\"cellTitle\"");
	$row .= wrap_tag("Phone","td","class=\"cellTitle\"");
	$row .= wrap_tag("CoMNum","td","class=\"cellTitle\"");		
    return wrap_tag($row,"tr");
}*/

function create_table_row($search) {
	global $data;
	$table = NULL;
	$keya = "";
	$keyb = "";
	$result = array();
	$headers = array('cLastN','cAKA','cCoName','cAdrBus','cPhone1','cCardNum');
	//$sql = "SELECT tblProduct.*,tblStore.sStoreNum AS storeID,tblStore.sName AS storeName,tblSupplier.sName AS supplyName,tblCatType.cCatType AS catType,tblInventory.*	FROM db.tblProduct,db.tblStore,db.tblSupplier,db.tblCatType,db.tblInventory WHERE pDescription LIKE '%$search%' AND tblProduct.pSupplier=tblSupplier.sID AND tblProduct.pCatType=tblCatType.ctID AND tblProduct.pID=tblInventory.iProduct AND tblProduct.pIsActive=0";
	$sql = "SELECT tblCustomer.cStoreID as keyA,".
	"tblCustomer.cID as keyB,".
	"tblCustomer.cSPC as keyC,".
	"tblCustomer.cLastN AS cLastN,".
	"tblCustomer.cFirstN AS cFirstN,".
	"tblCustomer.cAKA AS cAKA,".
	"tblCustomer.cCoName AS cCoName,".
	"tblCustomer.cAdrBus AS cAdrBus,".
	"tblCustomer.cPhone1 AS cPhone1,".
	"tblCustomer.cCardNum AS cCardNum ".
	"FROM pos.tblCustomer ".
	"WHERE (cLastN LIKE '%$search%' ". 
	"OR cFirstN LIKE '%$search%' ". 
	"OR cAKA LIKE '%$search%' ". 
	"OR cCardNum='$search') ".
	"AND cCardNum IS NOT NULL;";
	
	//print $sql;
	$data = query_database($sql);
	foreach($data as $rec) {
		$row = "";
				
		foreach($headers as $field) {
			$fdata = $rec[$field];

			if($field == "cLastN") {
				$fdata .= ", " . $rec["cFirstN"];
				//$fdata = htmlspecialchars($fdata);
				$fdata = htmlspecialchars($fdata,ENT_QUOTES);
				$fdata = preg_replace("/\n/m","<br/>\n",$fdata);
			}
			if($fdata == "") {
				$fdata = "--";
			} 
			if(strlen($fdata) > 70) {
				$fdata = substr($fdata,0,70);
				$fdata .= "...";
			} else {
				$fdata = $fdata;
			}
			//$upc .= $fdata;
			//print $fdata;
			$row .= wrap_tag($fdata,"td");
		}
		//$class = ($selected)?"selectcell":"cell" . ($n % 2);
		$table[] = wrap_tag($row,"tr",
		"c1=\"".$rec['keyA']."\" ".
		"c2=\"".$rec['keyB']."\" ".
		"c3=\"".$rec['cLastN']."\" ".
		"c4=\"".$rec['cFirstN']."\" ".
		"c5=\"".$rec['cCoName']."\" ".
		"c6=\"".$rec['cCardNum']."\" ".
		"onClick=\"return choose_cust(this);\"");
		$n++;
	}
	return $table;
}

function print_table($query) {
	$table = "";
	$tbody = "";
	if($query=="") {
		print "<table height=\"100%\" width=\"100%\"><tr><td><div style=\"font-weight:bold;text-align:center;font-size:120%;\">Please enter customer name to search.</div></td></tr></table>\n";
	} else {
		if($header = create_table_header()) {
			$table .= wrap_tag($header,"thead");
			if($row = create_table_row($query)) {
				$tbody .= join($row,"\n");
				$table .= wrap_tag($tbody,"tbody","");
				$table = wrap_tag($table,"table","id=\"myTable\" class=\"tablesorter\" border=\"0\" cellpadding=\"0\" cellspacing=\"1\" title=\"Click on row to see customer details\"");
				//print "<style type=\"text/css\">\ntr.cell0 td,tr.cell1 td, tr.selectcell td { white-space:nowrap; }\n</style>\n";
				print $table;
			} else {
				print "<table height=\"100%\" width=\"100%\"><tr><td><div style=\"font-weight:bold;text-align:center;font-size:120%;\">No matching records.</div></td></tr></table>\n";
			}
		}
	}
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