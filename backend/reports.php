<?php
include("db_methods.php");

//ini_set("display_errors", 0);

$data;

function employee_table_header($list,$date) {
	$row = "";
	if($list == "Timesheet") {
		$row .= wrap_tag("TS Date","td","class=\"cellTitle\"");
		$row .= wrap_tag("Name","td","class=\"cellTitle\"");
		$row .= wrap_tag("Shift","td","class=\"cellTitle\"");
		$row .= wrap_tag("Clock In","td","class=\"cellTitle\"");
		$row .= wrap_tag("Expect In","td","class=\"cellTitle\"");
		$row .= wrap_tag("Clock Out","td","class=\"cellTitle\"");
		$row .= wrap_tag("Expect Out","td","class=\"cellTitle\"");
		$row .= wrap_tag("Work Hours","td","class=\"cellTitle\"");
	}
	return wrap_tag($row,"tr");
}

function employee_table_row($list,$date) {
	$table = NULL;
	$headers = array("tsDate","name","shift","cIn","eIn","cOut","eOut","wHr");
	$result = array();
	$data = array();
	
	$sql = "";
	
	if($list == "Timesheet") {
		$sql = "SELECT tblTimesheet.tsDate as tsDate, 
			tblCustomer.cFirstN as tsFirstN,
			tblCustomer.cLastN as tsLastN,
			tblStaff.sCNumber as tsSNum,
			tblWrkShift.sSchedule as tsShift,
			tblTimesheet.tsIn as tsIn,
			tblTimesheet.tsXIn as tsXIn,
			tblTimesheet.tsOut as tsOut,
			tblTimesheet.tsXOut as tsXOut 
			FROM pos.tblTimeSheet 
			INNER JOIN pos.tblCustomer ON tblTimesheet.tsStaff=tblCustomer.cID
			INNER JOIN pos.tblStaff ON tblCustomer.cID=tblStaff.sCustomer  
			INNER JOIN pos.tblWrkShift ON tblTimesheet.tsShift=tblWrkShift.sID 
			WHERE tblTimesheet.tsDate='$date';";
		//print $sql;
		$res = query_database($sql);
		foreach($res as $rec) {
			//$data[0] = $rec[0];
			$data[] = $rec;
		}
	}
	
	foreach($data as $rec) {
		$row = "";				
		foreach($headers as $field) {
			$fdata = $rec[$field];
			if($field == "name") {
				$fdata = $rec['tsLastN'].", ".$rec['tsFirstN']." (".$rec['tsSNum'].")"; 
			} else if($field == "cIn") {
				$fdata = substr($rec['tsIn'],10);
			} else if($field == "eIn") {
				$fdata = substr($rec['tsXIn'],10);
			} else if($field == "cOut") {
				$fdata = substr($rec['tsOut'],10);
			} else if($field == "eOut") {
				$fdata = substr($rec['tsXOut'],10);
			} else if($field == "tsDate") {
				$fdata = substr($rec['tsDate'],0,10);
			} else if($field == "wHr") {
				$sec = strtotime(substr($rec['tsOut'],10))- strtotime(substr($rec['tsIn'],10));
				$fdata = $sec/60/60;
			} else {
				$fdata = $rec[$field];
			}
			if($fdata == "") {
				$fdata = "--";
			} 
			//$upc .= $fdata;
			$row .= wrap_tag($fdata,"td");
		}
		$class = ($selected)?"selectcell":"cell" . ($n % 2);
		$table[] = wrap_tag($row,"tr","class=\"$class\"");
		$n++;
	}
	return $table;
}

function print_employee_table($list,$date) {
	$table = "";
	if($list=="") {
		print "<table height=\"100%\" width=\"100%\"><tr><td><div style=\"font-weight:bold;text-align:center;font-size:120%;\">Please select report and date.</div></td></tr></table>\n";
	} else {
		if($header = employee_table_header($list,$date)) {
			$table .= $header;
			if($row = employee_table_row($list,$date)) {
				$table .= join($row,"\n");
				$table = wrap_tag($table,"table","width=100% title=\"Select record to see details\"");
				print "<style type=\"text/css\">\ntr.cell0 td,tr.cell1 td, tr.selectcell td { white-space:nowrap; }\n</style>\n";
				print $table;
			} else {
				$table = wrap_tag($table,"table","width=100% title=\"Select record to see details\"");
				print "<style type=\"text/css\">\ntr.cell0 td,tr.cell1 td, tr.selectcell td { white-space:nowrap; }\n</style>\n";
				print $table;
				//print "<table height=\"100%\" width=\"100%\"><tr><td><div style=\"font-weight:bold;text-align:center;font-size:120%;\">No matching records.</div></td></tr></table>\n";
			}
		}
	}
}

function create_table_header($list,$start, $end) {
	$row = "";
	$s = strtotime($start." 00:00:00");
	$e = strtotime($end." 00:00:00");
	if($list == "By Store") {
		$row .= wrap_tag("Store","td","class=\"cellTitle\"");
	} else if($list == "By Employee") {
		$row .= wrap_tag("Employee","td","class=\"cellTitle\"");
	} else if($list == "By Product") {
		$row .= wrap_tag("Product","td","class=\"cellTitle\"");
	} else if($list == "By Product Type") {
		$row .= wrap_tag("Product Type","td","class=\"cellTitle\"");
	}
	
	while($s<=$e) {
		$date = date("Y-m-d",$s);
		$row .= wrap_tag($date,"td","class=\"cellTitle\"");
		$s = $s + 24*60*60;
	}
	return wrap_tag($row,"tr");
}

function create_table_row($list,$start, $end) {
	$table = NULL;
	$result = array();
	$dates = array();
	$data = array();
	
	$s = strtotime($start." 00:00:00");
	$e = strtotime($end." 00:00:00");
	while($s<=$e) {
		$date = date("Y-m-d",$s);
		$dates[] = $date;
		$row .= wrap_tag($date,"td","class=\"cellTitle\"");
		$s = $s + 24*60*60;
	}
	
	$sql = "";
	if($list == "By Store") {
		$storeN = get_store_names_report();
		foreach($storeN as $storeName) {
			$data[$storeName][] = $storeName;
			foreach($dates as $sql_date) {
				$data[$storeName][$sql_date] = "--";
			}
		}
		foreach($dates as $sql_date) {
			$sql = "SELECT tblStore.sName as sName,SUM(tTotal) as storeTot 
				FROM pos.tblTrans INNER JOIN pos.tblStore 
				WHERE tTrnsDate='$sql_date' AND tblStore.sID = tblTrans.tStoreID 
				GROUP BY(tStoreID);";
			$res = query_database($sql);
			foreach($res as $rec) {
				//$data[0] = $rec[0];
				$data[$rec['sName']][$sql_date] = $rec['storeTot'];
			}		
		}
	} else if($list == "By Employee") {
		$sql = "SELECT tblTrans.tStoreID as sID,SUM(tTotal) as storeTot 
		FROM pos.tblTrans WHERE tTrnsDate='$sql_date' GROUP BY(tStoreID);";
	} else if($list == "By Product") {
		$pIDs = get_product_ids_report($start,$end);
		foreach($pIDs as $pID) {
			$data[$pID][] = substr(get_product_description($pID),0,15) . " [". $pID ."]";
			foreach($dates as $sql_date) {
				$data[$pID][$sql_date] = "--";
			}
		}
		foreach($dates as $sql_date) {
			$sql = "SELECT tblProduct.PDescription as pDes,tblTrans.tProdID as pID,SUM(tTotal) as prodTot 
				FROM pos.tblTrans INNER JOIN pos.tblProduct 
				WHERE tTrnsDate='$sql_date' AND tblProduct.pID = tblTrans.tProdID 
				GROUP BY(tProdID);";
			$res = query_database($sql);
			foreach($res as $rec) {
				//$data[0] = $rec[0];
				$data[$rec['pID']][$sql_date] = $rec['prodTot'];
			}
		}
	} else if($list == "By Product Type") {
		$catIDs = get_product_category_reports();
		foreach($catIDs as $catID) {
			$data[$catID][] = get_product_catname_reports($catID) . " [". $catID ."]";
			foreach($dates as $sql_date) {
				$data[$catID][$sql_date] = "--";
			}
		}
		foreach($dates as $sql_date) {
			$sql = "SELECT tblProduct.pCatType as catgID,SUM(tTotal) as catgTot 
				FROM pos.tblTrans INNER JOIN pos.tblProduct 
				WHERE tTrnsDate='$sql_date' AND tblTrans.tProdID = tblProduct.pID 
				GROUP BY(tblProduct.pCatType);";
			$res = query_database($sql);
			foreach($res as $rec) {
				//$data[0] = $rec[0];
				$data[$rec['catgID']][$sql_date] = $rec['catgTot'];
			}
		}
	}
	
	foreach($data as $rec) {
		$row = "";				
		foreach($rec as $field) {
			$fdata = $field;

			//$upc .= $fdata;
			$row .= wrap_tag($fdata,"td");
		}
		$class = ($selected)?"selectcell":"cell" . ($n % 2);
		$table[] = wrap_tag($row,"tr","class=\"$class\" onMouseOver=\"this.bgColor='gold';\" onMouseOut=\"this.bgColor='#FFFFFF';\" onClick=\"choose_Invoice('$keya');\"");
		$n++;
	}
	return $table;
}

function print_table($list,$start,$end) {
	$table = "";
	if($list=="") {
		print "<table height=\"100%\" width=\"100%\"><tr><td><div style=\"font-weight:bold;text-align:center;font-size:120%;\">Please select report and date.</div></td></tr></table>\n";
	} else {
		if($header = create_table_header($list,$start,$end)) {
			$table .= $header;
			if($row = create_table_row($list,$start,$end)) {
				$table .= join($row,"\n");
				$table = wrap_tag($table,"table","width=100% title=\"Select record to see details\"");
				print "<style type=\"text/css\">\ntr.cell0 td,tr.cell1 td, tr.selectcell td { white-space:nowrap; }\n</style>\n";
				print $table;
			} else {
				$table = wrap_tag($table,"table","width=100% title=\"Select record to see details\"");
				print "<style type=\"text/css\">\ntr.cell0 td,tr.cell1 td, tr.selectcell td { white-space:nowrap; }\n</style>\n";
				print $table;
				//print "<table height=\"100%\" width=\"100%\"><tr><td><div style=\"font-weight:bold;text-align:center;font-size:120%;\">No matching records.</div></td></tr></table>\n";
			}
		}
	}
}

?>