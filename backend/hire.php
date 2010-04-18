<?php
include("db_methods.php");

ini_set("display_errors", 0);

function create_hired_header() {
	$row = "";
	$row .= wrap_tag("Company","th","");
	$row .= wrap_tag("Name","th","");	
    return wrap_tag($row,"tr");
}

function create_hire_header() {
	$row = "";
	$row .= wrap_tag("Name","th","");
	$row .= wrap_tag("Company","th","");	
    return wrap_tag($row,"tr");
}

function hired_row($filter) {
	//$filter = str_replace('.','=',$filter);
	$result = NULL;
	if($filter == "hiredPainter") {
		$sql = "SELECT tb1.cLastN AS cLastN,".
			"tb1.cFirstN AS cFirstN,".
			"t1.hCrtDate AS date1,".
			"t2.hCrtDate AS date2,".
			"t1.hDate AS date,".
			"tb2.cCoName AS cCoName,".
			"tb2.cFirstN AS pFirstN,".
			"tb2.cLastN AS pLastN ".
			"FROM pos.tblPCHire as t1 ".
			"INNER JOIN pos.tblCustomer AS tb1 ON t1.hCustID=tb1.cID,".
			"pos.tblPCHire AS t2 INNER JOIN pos.tblCustomer AS tb2 ON ".
			"t2.hContID=tb2.cID WHERE t1.hCrtDate=t2.hCrtDate ".
			"ORDER BY t1.hCrtDate;";
	} else if($filter == "hiredCC") {
		$sql = "SELECT tb1.cLastN AS cLastN,".
			"tb1.cFirstN AS cFirstN,".
			"t1.hCrtDate AS date1,".
			"t2.hCrtDate AS date2,".
			"t1.hDate AS date,".
			"tb2.cCoName AS cCoName,".
			"tb2.cFirstN AS pFirstN,".
			"tb2.cLastN AS pLastN ".
			"FROM pos.tblCCHire as t1 ".
			"INNER JOIN pos.tblCustomer AS tb1 ON t1.hCustID=tb1.cID,".
			"pos.tblCCHire AS t2 INNER JOIN pos.tblCustomer AS tb2 ON ".
			"t2.hContID=tb2.cID WHERE t1.hCrtDate=t2.hCrtDate ".
			"ORDER BY t1.hCrtDate;";
	}

	//print $sql;
	$header = array("cCoName","cFirstN");
	$data = query_database($sql);
	foreach($data as $rec) {
		$date = $rec['date'];
		$row = "";			
		foreach($header as $field) {
			//$fdata = $rec[$field];
			if($field == 'cFirstN') {
				if(empty($rec['cLastN']) && empty($rec['cFirstN'])) {
					$fdata = "";
				} else if(empty($rec['cLastN'])) {
					$fdata = $rec['cFirstN'];
				} else if(empty($rec['cFirstN'])) {
					$fdata = $rec['cLastN'];
				} else {
					$fdata = $rec['cLastN'].", ".$rec['cFirstN'];
					$fdata = htmlspecialchars($fdata);
					$fdata = preg_replace("/\n/m","<br/>\n",$fdata);
				}
			} else {
				if(empty($rec['pLastN']) && empty($rec['pFirstN'])) {
					$fdata = "";
				} else if(empty($rec['pLastN'])) {
					$fdata = $rec['pFirstN'];
				} else if(empty($rec['pFirstN'])) {
					$fdata = $rec['pLastN'];
				} else {
					$fdata = $rec['pLastN'].", ".$rec['pFirstN'];
					$fdata = htmlspecialchars($fdata);
					$fdata = preg_replace("/\n/m","<br/>\n",$fdata);
				}
				$fdata .= " - ".$rec['cCoName'];
				$fdata = htmlspecialchars($fdata);
				$fdata = preg_replace("/\n/m","<br/>\n",$fdata);
			}
			$row .= wrap_tag($fdata,"td");
		}
		$result[] = wrap_tag($row,"tr","date=\"$date\" ");
	}
	return $result;
}

function hire_painter_row($filter) {
	if(!isset($filter) || empty($filter)) {
		return null;
	} else if($filter == "all") {
		$sql = "SELECT cFirstN,cLastN,cCoName,pCustID,pPaid,pOder,pID ".
		"FROM pos.tblHPainter ".
		"INNER JOIN pos.tblCustomer ON cID=pCustID ".
		"WHERE pDelStatus=0 ORDER BY pLastJob ASC;";
	} else {
		$filter = str_replace('.','=',$filter);
		$sql = "SELECT cFirstN,cLastN,cCoName,pCustID,pPaid,pOder,pID ".
		"FROM pos.tblHPainter ".
		"INNER JOIN pos.tblCustomer ON cID=pCustID ".
		"WHERE $filter ".
		"AND pDelStatus=0 ORDER BY pLastJob ASC;";
	}	
	$result = NULL;
	
	//print $sql;
	$header = array("cFirstN","cCoName");
	$data = query_database($sql);
	foreach($data as $rec) {
		$pCustID = $rec['pCustID'];
		$pPaid = $rec['pPaid'];
		$pOder = $rec['pOder'];
		$row = "";			
		foreach($header as $field) {
			//$fdata = $rec[$field];
			if($field == 'cFirstN') {
				if(empty($rec['cLastN']) && empty($rec['cFirstN'])) {
					$fdata = "";
				} else if(empty($rec['cLastN'])) {
					$fdata = $rec['cFirstN'];
				} else if(empty($rec['cFirstN'])) {
					$fdata = $rec['cLastN'];
				} else {
					$fdata = $rec['cLastN'].", ".$rec['cFirstN'];
					$fdata = htmlspecialchars($fdata);
					$fdata = preg_replace("/\n/m","<br/>\n",$fdata);
				}
			} else {
				$fdata = $rec[$field];
				$fdata = htmlspecialchars($fdata);
				$fdata = preg_replace("/\n/m","<br/>\n",$fdata);
			}
			$row .= wrap_tag($fdata,"td");
		}
		$result[] = wrap_tag($row,"tr","pCustID=\"$pCustID\" pPaid=\"$pPaid\" ".
			"pOder=\"$pOder\" ".
			"onclick=\"return choose_painter(this);\"");
	}
	return $result;
}

function hire_cc_row() {
	$result = NULL;
	$sql = "SELECT cFirstN,cLastN,cCoName,cCustID,cPaid,cOder,tblHCCnst.cID ".
		"FROM pos.tblHCCnst ".
		"INNER JOIN pos.tblCustomer ON tblCustomer.cID=cCustID ".
		"WHERE tblHCCnst.cDelStatus=0 ORDER BY tblHCCnst.cLastJob ASC;";
	//print $sql;
	$header = array("cFirstN","cCoName");
	$data = query_database($sql);
	foreach($data as $rec) {
		$cCustID = $rec['cCustID'];
		$cPaid = $rec['cPaid'];
		$cOder = $rec['cOder'];
		$row = "";			
		foreach($header as $field) {
			//$fdata = $rec[$field];
			if($field == 'cFirstN') {
				if(empty($rec['cLastN']) && empty($rec['cFirstN'])) {
					$fdata = "";
				} else if(empty($rec['cLastN'])) {
					$fdata = $rec['cFirstN'];
				} else if(empty($rec['cFirstN'])) {
					$fdata = $rec['cLastN'];
				} else {
					$fdata = $rec['cLastN'].", ".$rec['cFirstN'];
					$fdata = htmlspecialchars($fdata);
					$fdata = preg_replace("/\n/m","<br/>\n",$fdata);
				}
			} else {
				$fdata = $rec[$field];
				$fdata = htmlspecialchars($fdata);
				$fdata = preg_replace("/\n/m","<br/>\n",$fdata);
			}
			$row .= wrap_tag($fdata,"td");
		}
		$result[] = wrap_tag($row,"tr","cCustID=\"$cCustID\" ".
		"cPaid=\"$cPaid\" cOder=\"$cOder\" ".
		"onclick=\"return choose_consultant(this);\"");
	}
	return $result;	
}

function print_hire($arg,$filter) {
	if($arg == "hirePainter") {
		$header = create_hire_header();
		$table_rows = hire_painter_row($filter);
	} else if($arg == "hiredPainter") {
		$header = create_hired_header();
		$table_rows = hired_row($arg);
	} else if($arg == "hireConsultant") {
		$header = create_hire_header();
		$table_rows = hire_cc_row();
	} else if($arg == "hiredCC") {
		$header = create_hired_header();
		$table_rows = hired_row($arg);
	} else {
		$header = create_payhistory_header();
		$table_rows = create_paymenthistory_rows($var);
	}
	$table = "";
	if($header) {
		//$table .= $header;
		$table .= wrap_tag($header,"thead");
		if($table_rows) {
			$tbody .= join($table_rows,"\n");
			$table .= wrap_tag($tbody,"tbody","");
	    	if($arg == "hirePainter") {
				$table = wrap_tag($table,"table","id=\"myTable\" class=\"tablesorter\" border=\"0\" cellpadding=\"0\" cellspacing=\"1\"");
	    	} else if($arg == "hiredPainter") {
				$table = wrap_tag($table,"table","id=\"myTable\" class=\"tablesorter\" border=\"0\" cellpadding=\"0\" cellspacing=\"1\"");
	    	} else if($arg == "hireConsultant") {
				$table = wrap_tag($table,"table","id=\"myTable\" class=\"tablesorter\" border=\"0\" cellpadding=\"0\" cellspacing=\"1\"");
	    	} else if($arg == "hiredCC") {
				$table = wrap_tag($table,"table","id=\"myTable\" class=\"tablesorter\" border=\"0\" cellpadding=\"0\" cellspacing=\"1\"");
	    	} else {
				$table = wrap_tag($table,"table","id=\"myTable\" class=\"tablesorter\" border=\"0\" cellpadding=\"0\" cellspacing=\"1\" title=\"Click on row to see invoice items.\"");
			}
			print $table;
		} else {
			$table .= "<tbody></tbody>";
			$table = wrap_tag($table,"table","id=\"myTable\" class=\"tablesorter\" border=\"0\" cellpadding=\"0\" cellspacing=\"1\"");
			print $table;
		}
	}
}
?>