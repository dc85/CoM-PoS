<?php
include("db_methods.php");

//ini_set("display_errors", 0);

$data;

function create_table_header() {
	$row = "";
	$row .= wrap_tag("Due In","th");
	$row .= wrap_tag("Rental Invoice","th");
	$row .= wrap_tag("Product UPC","th");
	$row .= wrap_tag("Product Type","th");
	$row .= wrap_tag("Rented By","th");
	$row .= wrap_tag("Renter ID#(type)","th");		
    return wrap_tag($row,"tr");
}

function create_table_row($query) {
	global $data;
	$sql = "";
	$table = NULL;
	$keya = "";
	$keyb = "";
	$result = array();
	$headers = array('rentDue','rInvoice','rUPC','rPType','rLastN','rDID');
	if($query == "*") {
		$sql = "SELECT * FROM pos.tblRental;";
	} else {
		$sql = "SELECT * FROM pos.tblRental 
		WHERE (rDID LIKE '%$query%' or rInvoice LIKE '%$query%' or rLastN LIKE '%$query%' or rFirstN LIKE '%$query%');";
	}
	
	$data = query_database($sql);
	$n = 0;
	foreach($data as $rec) {
		$row = "";
		$id = $rec['rID'];
				
		foreach($headers as $field) {
			$fdata = $rec[$field];

			if($field == "rentDue") {
				if($rec["rIsReturned"] == "1") {
					$fdata = "Returned";
				} else {
					$today = getdate(time());
					$mdate = date("Y-m-d H:i:s",$today[0]);
					$curs = strtotime($mdate);
					$diff = strtotime($rec['rEnd']) - $curs; 
					$dd = round($diff/86400);
					if($dd == 0) {
						$fdata = "Today";
					} else if($dd < 0) {
						$fdata = "Overdue";
					} else {
						$fdata = $dd . " day(s)";
					}
				}
			}
			if($field == "rPType") {
				$fdata = get_product_type($rec['rPType']);
			}
			if($field == "rLastN") {
				$fdata = $rec['rLastN'].", ".$rec['rFirstN'];
			}
			if($field == "rDID") {
				$fdata = $rec['rDID']."(".$rec['rDIDType'].")";
			} 
			if($fdata == "") {
				$fdata = "--";
			}
			//$upc .= $fdata;
			$row .= wrap_tag($fdata,"td");
		}
		//$class = ($selected)?"selectcell":"cell" . ($n % 2);
		$table[] = wrap_tag($row,"tr",
		//"onMouseOver=\"this.bgColor='gold';\" ".
		//"onMouseOut=\"this.bgColor='#ffffe0';\" ".
		"onClick=\"show_rent_detail('$id');\"");
		$n++;
	}
	return $table;
}

function print_table($query) {
	$table = "";
	if($query) {
		if($header = create_table_header()) {
			$table .= wrap_tag($header,"thead");
			if($row = create_table_row($query)) {
				$tbody .= join($row,"\n");
				$table .= wrap_tag($tbody,"tbody","");
				$table = wrap_tag($table,"table","id=\"myTable\" class=\"tablesorter\" border=\"0\" cellpadding=\"0\" cellspacing=\"1\" title=\"Click on row to see customer details\"");
				//print "<style type=\"text/css\">\ntr.cell0 td,tr.cell1 td, tr.selectcell td { white-space:nowrap; }\n</style>\n";
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
?>