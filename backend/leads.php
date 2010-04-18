<?php
include("db_methods.php");

ini_set("display_errors", 0);

$data;

function create_table_header() {
	$row = "";
	$row .= wrap_tag("Company","th");
	$row .= wrap_tag("Expert In","th");
	$row .= wrap_tag("City","th");
	$row .= wrap_tag("Province","th");
	$row .= wrap_tag("Zip","th");
	$row .= wrap_tag("Phone","th");
	$row .= wrap_tag("Since","th");		
    return wrap_tag($row,"tr");
}

function create_table_row($filter,$zip,$search) {
	global $data;
	$result = NULL;
	$headers = array('cCoName','eType','cCityB','pAbbr','cZipB','cPhone1','cCrtDate');
	$sql = "SELECT cID,cStoreID,cCoName,eType,cCityB,pAbbr,cZipB,cPhone1,cCrtDate ".
		"FROM pos.tblCustomer ".
		"INNER JOIN pos.tblEType ON eID=cExpert ".
		"INNER JOIN pos.tblProvince ON cProvB=pID ".
		"WHERE (cLastN LIKE '%$search%' ". 
		"OR cFirstN LIKE '%$search%' ". 
		"OR cAKA LIKE '%$search%' ".
		"OR cCoName LIKE '%$search%' ".
		"OR eType LIKE '%$search%' ".
		"OR cZipB LIKE '%$search%' ".
		"OR cPhone1 LIKE '%$search%' ".
		"OR cCityB LIKE '%$search%') ".
		"AND cCardNum IS NULL";
	if(isset($filter) && !empty($filter)) {
		$sql .= " AND cExpert=$filter";
	}
	if(isset($zip) && !empty($zip)) {
		$sql .= " AND cZipB LIKE '%$zip%'";
	}
	//print $sql;
	$data = query_database($sql);
	foreach($data as $rec) {
		$cID = $rec['cID'];
		$sID = $rec['cStoreID'];
		$row = "";			
		foreach($headers as $field) {
			$fdata = $rec[$field];
			if($field == 'cCrtDate') {
				$today = strtotime($rec['cCrtDate']);
				$fdata = date("Y-m-d",$today);
			} else {
				$fdata = $rec[$field];
				$fdata = htmlspecialchars($fdata);
				$fdata = preg_replace("/\n/m","<br/>\n",$fdata);
			}
			$row .= wrap_tag($fdata,"td");
		}
		$result[] = wrap_tag($row,"tr","cID=\"$cID\" sID=\"$sID\" ".
			"onclick=\"return choose_lead(this);\"");
	}
	return $result;
}

function print_table($filter,$zip,$query) {
	$table = "";
	if($header = create_table_header()) {
		$table .= wrap_tag($header,"thead");
		if($row = create_table_row($filter,$zip,$query)) {
			$tbody .= join($row,"\n");
			$table .= wrap_tag($tbody,"tbody","");
			$table = wrap_tag($table,"table","id=\"myTable\" class=\"tablesorter\" border=\"0\" cellpadding=\"0\" cellspacing=\"1\" title=\"Click on row to see lead details\"");
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
?>