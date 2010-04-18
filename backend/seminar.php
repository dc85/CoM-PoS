<?php
include("db_methods.php");

//ini_set("display_errors", 0);

$data;

function create_table_header() {
	$row = "";
	$row .= wrap_tag("Name","th");
	$row .= wrap_tag("Company","th");
	$row .= wrap_tag("Phone1","th");
	$row .= wrap_tag("Phone2","th");		
    return wrap_tag($row,"tr");
}

function create_table_row() {
	global $data;
	$sql = "";
	$table = NULL;
	$keya = "";
	$keyb = "";
	$result = array();
	$headers = array('Name','Company','Phone1','Phone2');
	/*if($query == "*") {
		$sql = "SELECT * FROM pos.tblRental;";
	} else {
		$sql = "SELECT tblCustomer.cFirstN as firstName,tblCustomer.cLastN as lastName,tblCustomer.cCoName as coName,
		tblCustomer.cPhone1 as phone1,tblCustomer.cPhone2 as phone2 
		FROM pos.tblCustomer,pos.tblSemnr 
		WHERE tblCustomer.cID=tblSemnr.sCustID;";
	}*/
	/*$sql = "SELECT tblCustomer.cFirstN as firstName,tblCustomer.cLastN as lastName,tblCustomer.cCoName as coName,
		tblCustomer.cPhone1 as phone1,tblCustomer.cPhone2 as phone2 
		FROM pos.tblCustomer,pos.tblSemnr 
		WHERE tblCustomer.cID=tblSemnr.sCustID AND sPaid=1;";*/
	$sql = "SELECT tblCustomer.cLastN as sLastN,".
				"tblCustomer.cFirstN as sFirstN,".
				"tblCustomer.cCoName as sCoName,".
				"tblCustomer.cPhone1 as sPhone1,".
				"tblCustomer.cPhone2 as sPhone2 ".
				"FROM pos.tblSemnr ".
				"INNER JOIN pos.tblCustomer ON tblSemnr.sCustID=tblCustomer.cID ".
				"WHERE sPaid=0;";
				//WHERE tblSemnr.sPaid=1;";
	//print $sql;
	$data = query_database($sql);
	$n = 0;
	foreach($data as $rec) {
		$row = "";				
		foreach($headers as $field) {
			if($field == "Name") {
				$fdata = $rec['sLastN'].", ".$rec['sFirstN'];
			}
			if($field == "Company") {
				$fdata = $rec['sCoName'];
			}
			if($field == "Phone1") {
				$fdata = $rec['sPhone1'];
			}
			if($field == "Phone2") {
				$fdata = $rec['sPhone2'];
			} 
			if($fdata == "") {
				$fdata = "--";
			}
			//$upc .= $fdata;
			$row .= wrap_tag($fdata,"td");
		}
		//$class = ($selected)?"selectcell":"cell" . ($n % 2);
		$table[] = wrap_tag($row,"tr","");
		$n++;
	}
	return $table;
}

function print_table() {
	$table = "";
	if(true) {
		if($header = create_table_header()) {
			$table .= wrap_tag($header,"thead");
			if($row = create_table_row()) {
				$tbody .= join($row,"\n");
				$table .= wrap_tag($tbody,"tbody","");
				$table = wrap_tag($table,"table","id=\"myTable\" class=\"tablesorter\" border=\"0\" cellpadding=\"0\" cellspacing=\"1\" title=\"Click on row to see customer details\"");
				print $table;
			} else {
				print "<table height=\"100%\" width=\"100%\"><tr><td><div style=\"font-weight:bold;text-align:center;font-size:120%;\">No matching records.</div></td></tr></table>\n";
				//print $table;
			}
		}
	}
}
?>