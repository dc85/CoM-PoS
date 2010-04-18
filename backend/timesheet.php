<?php
include("db_methods.php");

//ini_set("display_errors", 0);

$data;

function create_table_header() {
	$row = "";
	$row .= wrap_tag("Name","th");
	$row .= wrap_tag("Shift","th");
	$row .= wrap_tag("Status","th");
	$row .= wrap_tag("Date","th");
	$row .= wrap_tag("Actual In","th");
	$row .= wrap_tag("Expected In","th");
	$row .= wrap_tag("Actual Out","th");
	$row .= wrap_tag("Expected Out","th");		
    return wrap_tag($row,"tr");
}

function create_sales_header($date) {
	$dates = create_week_header($date);
	$row = "";
	$row .= wrap_tag("Category","td");
	$row .= wrap_tag(date("l",$dates[0][0]),"td");
	$row .= wrap_tag(date("l",$dates[1][0]),"td");
	$row .= wrap_tag(date("l",$dates[2][0]),"td");
	$row .= wrap_tag(date("l",$dates[3][0]),"td");
	$row .= wrap_tag("Today","td");
	$row .= wrap_tag("Total","td");		
    return wrap_tag($row,"tr");
}

function create_table_row($date) {
	global $data;
	$table = NULL;
	$keya = "";
	$keyb = "";
	$result = array();
	$headers = array('tsName','tsShift','tsStatus','tsDate','tsIn','tsXIn','tsOut','tsXOut');
	/*$sql = "SELECT tblTimesheet.tsStaff AS tsName,
	tblTimesheet.tsShift AS tsShift,
	tblTimesheet.tsStatus AS tsStatus,
	tblTimesheet.tsDate AS tsDate,
	tblTimesheet.tsIn AS tsIn,
	tblTimesheet.tsXIn AS tsXIn,
	tblTimesheet.tsOut AS tsOut,
	tblTimesheet.tsXOut AS tsXOut
	FROM pos.tblTimesheet
	WHERE tblTimesheet.tsDate='$date';";*/
	$sql = "SELECT tblStaff.sStaff AS tsName,
	tblWrkShift.sSchedule AS tsShift,
	tblTimesheet.tsStatus AS tsStatus,
	tblTimesheet.tsDate AS tsDate,
	tblTimesheet.tsIn AS tsIn,
	tblTimesheet.tsXIn AS tsXIn,
	tblTimesheet.tsOut AS tsOut,
	tblTimesheet.tsXOut AS tsXOut
	FROM pos.tblTimesheet 
	INNER JOIN pos.tblStaff ON tblStaff.sID=tblTimesheet.tsStaff 
	INNER JOIN pos.tblWrkShift ON tblTimesheet.tsShift=tblWrkShift.sID 
	WHERE tblTimesheet.tsDate='$date';";
//	print $sql;
	$data = query_database($sql);
	$n = 0;
	foreach($data as $rec) {
		$row = "";
		$keya = $rec['sStoreID'];
		$keyb = $rec['sID'];
		/*$keyc = $rec['keyC'];
		$keyd = htmlspecialchars($rec['cFirstN'],ENT_QUOTES);
		$keye = htmlspecialchars($rec['cLastN'],ENT_QUOTES);*/
				
		foreach($headers as $field) {
			$fdata = $rec[$field];

			if($field == "tsStatus") {
				switch($fdata) {
					case '0':
						$fdata = "LEFT WORK";
						break;
					case '1':
						$fdata = "AT WORK";
						break;
					case '2':
						$fdata = "OUT OF OFFICE";
						break;
					default:
						$fdata = "MIA";
						break;
				}
			}
			if($field == "tsDate") {
				$strdate = strtotime($fdata);
				$fdata = date("D F/d/Y",$strdate);
			}
			if($field == "tsIn" || $field == "tsXIn" || $field == "tsOut" || $field == "tsXOut") {
				$fdata = substr($fdata,-8);
			}
			if($fdata == "") {
				$fdata = "--";
			}
			//$upc .= $fdata;
			$row .= wrap_tag($fdata,"td");
		}
		if($rec["tsStatus"] == "0") {
			$class = "tsLeft";
		} else if($rec["tsStatus"] == "1") {
			$class = "tsWork";
		} else if($rec["tsStatus"] == "2") {
			$class = "tsOut";
		} else {
			$class = "tsMia";
		}
		$table[] = wrap_tag($row,"tr",
			"class=\"$class\"");
		$n++;
	}
	return $table;
}

function print_table($date) {
	$table = "";
	if($query!="") {
		print "<table height=\"100%\" width=\"100%\"><tr><td><div style=\"font-weight:bold;text-align:center;font-size:120%;\">Please select a date above.</div></td></tr></table>\n";
	} else {
		if($header = create_table_header()) {
			$table .= wrap_tag($header,"thead");
			if($row = create_table_row($date)) {
				$tbody .= join($row,"\n");
				$table .= wrap_tag($tbody,"tbody","");
				$table = wrap_tag($table,"table","id=\"myTable\" class=\"tablesorter\" border=\"0\" cellpadding=\"0\" cellspacing=\"1\" title=\"Refresh(F5) to see the updates.\"");
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

function create_week_header($date) {
    $result = array();
    $end = strtotime($date);
    $start = $end - 4*(60*60*24);
    //print "startstr:" . date("Y-m-d H:i:s",$start) . "<br>";
    //print $start;
    //echo date("Y-m-d",$start) . "<br>";
	for ($i = 0; $i <= 4; $i++) {
		//echo date("Y-m-d",$start) . "<br>";
		$double = array(); 
		$double[] = $start;
		$double[] = date("l",$start);
		$result[] = $double;
		$start=$start+(60*60*24);
	}
	return $result;
}

function create_sales_table($date) {
	//print "Start" . $date . "<br>";
	$data = array();
	$dates = create_week_header($date);
	$table = NULL;
	$result = array();
	$expected_sales = array('DRAPERY'=>300.00,'NOCATGORY'=>1.00,'PAINT'=>6575.00,'SERVICES'=>300.00,'SUNDRIE'=>985.00,'WALLPAPER'=>50.00,'TOTAL'=>8211.00);
	$headers = array('DRAPERY','NOCATGORY','PAINT','SERVICES','SUNDRIE','WALLPAPER','TOTAL');
	$inner_headers = array(' ','','','','','','','');
	foreach($headers as $header) {
		$t_array = array();
		$tot = "0.00";
		$index = 1;
		foreach($dates as $day) {
			$sql_date = date("Y-m-d H:i:s",$day[0]);
			$sql = "SELECT
				tblTrans.tInvcID,
				tblTrans.tProdID,
				tblTrans.tQty,
				tblTrans.tPrice,
				tblTrans.tTotal,
				tblTrans.tTCost,
				tblProduct.pCatType,
				tblCatType.cCatType
				FROM pos.tbltrans
				Inner Join pos.tblproduct ON tbltrans.tProdID = tblproduct.pID
				Inner Join pos.tblCatType ON tblProduct.pCatType = tblCatType.ctID
				WHERE tIsFree=0 AND tTrnsDate='$sql_date' AND cCatType LIKE '%$header%';";
			if($sql_data = query_database($sql)) {
				$sales = 0.00;
				foreach($sql_data as $rec) {
					//$sales += (int)$rec['tQty']*(float)$rec['tPrice'];
					$sales += (float)$rec['tTotal'];
				}
				//print date("Y-m-d",$day) . ":" . $sales . "<br>";
				$tot = (double)$tot + $sales;
				/*foreach($sql_data as $rec) {
					$sales = (double)$rec['tTotal'];
				}*/
				//$t_array[date("Y-m-d",$day[0])] = $sales; 
				$t_array[$index] = $sales;
				$index++;
			} else {
				//$t_array[date("Y-m-d",$day[0])] = "0.00";
				$t_array[$index] = "0.00";
				$index++;
			}
		}
		$t_array['Total'] = $tot;
		$data[$header] = $t_array;
	}
	$cat_total = array();
	foreach($headers as $cat) {
		if($cat == "TOTAL") {
			$row = "";
			for($i=0;$i<7;$i++) {
				if($i==0) {
					$fdata = $cat;
				} else {
					$fdata = "$".$cat_total[$i]." (".number_format((double)$cat_total[$i]/$expected_sales['TOTAL']*5,2)."%)";
				}
				$row .= wrap_tag($fdata,"td");
			}
		} else {
			$row = "";
			for($i=0;$i<7;$i++) {
				$fdata = "";
				if($i==0) {
					$fdata = $cat;
				} else if($i==6) {
					$fdata = $data[$cat]['Total'];
					$cat_total[$i] += $fdata;
					$fdata = "$".$fdata." (".number_format((double)$data[$cat]['Total']/($expected_sales[$cat]*5),2)."%)";
				} else {
					$fdata = $data[$cat][$i];
					$cat_total[$i] = $fdata;
					$fdata = "$".$fdata." (".number_format((double)$data[$cat][$i]/($expected_sales[$cat]),2)."%)";
				}
				$row .= wrap_tag($fdata,"td");
			}
		}
		$class = ($selected)?"selectcell":"cell" . ($n % 2);
		$table[] = wrap_tag($row,"tr","class=\"$class\" onMouseOver=\"this.bgColor='gold';\" onMouseOut=\"this.bgColor='#FFFFFF';\"");
		$n++;
	}
	
	return $table;
}

function print_sales_table($date) {
	$table = "";
	if($query!="") {
		print "<table height=\"100%\" width=\"100%\"><tr><td><div style=\"font-weight:bold;text-align:center;font-size:120%;\">Please enter product description to search.</div></td></tr></table>\n";
	} else {
		if($header = create_sales_header($date)) {
			$table .= $header;
			if($row = create_sales_table($date)) {
				$table .= join($row,"\n");
				$table = wrap_tag($table,"table","width=100% title=\"Select record to see details\"");
				print "<style type=\"text/css\">\ntr.cell0 td,tr.cell1 td, tr.selectcell td { white-space:nowrap; }\n</style>\n";
				print $table;
			} else {
				print "<table height=\"100%\" width=\"100%\"><tr><td><div style=\"font-weight:bold;text-align:center;font-size:120%;\">No matching records.</div></td></tr></table>\n";
			}
		}
	}
}
?>