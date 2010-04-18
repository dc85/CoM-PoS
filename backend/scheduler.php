<?php
include("db_methods.php");
include("methods.php");

$staff;
$number;

$shiftTime = array();
	//var $month_list = array("January","February","March","April","May","June","July","August","September","October","November","December");
$shiftnames = array("","DAY OFF","FS1","FS2","MS1","MS2","BS1","BS2","IT");
$staffshiftlist = array("sSchedule0","sSchedule1","sSchedule2","sSchedule3","sSchedule4","sSchedule5","sSchedule6");
$monthn_list = array(1,2,3,4,5,6,7,8,9,10,11,12);
$week_list = array(1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,
	41,42,43,44,45,46,47,48,49,50,51,52);
$year_list = array("2008","2009","2010","2011");
$daysInMonth = array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);

function get_query_parameters($base) {
		return array(
			fetch_value_if_exists($base,'type'),
			fetch_value_if_exists($base,'amount'),
			fetch_value_if_exists($base,'year')
			//$this->fetch_value_if_exists($base,'schSubmit')
		);	
}

function print_data_table($type,$amount,$year) {
	//print $schMonth;
	//print $type."-".$amount."-".$year;
	if($type == "byWeek" && $amount != "" && $year != "") {
		$header = create_weekschedule_header($amount,$year);
		$table_rows = create_weekscheduler_lists($amount,$year);
		//$table_rows = "<p>test</p>";
	} else if($type == "byMonth" && $amount != "" && $year != "") {
		$header = create_monthschedule_header($amount,$year);
		$table_rows = create_monthscheduler_lists($amount,$year);
		//print "byday";
	} else {
		print "<table height=\"100%\" width=\"100%\"><tr><td><div style=\"font-weight:bold;text-align:center;font-size:120%;\">Please select table type.</div></td></tr></table>\n";
	}
	if(true) {
		$table = "";
		if($header) {
			$table .= $header;
			if($table_rows) {
		    	$table .= join($table_rows,"\n");
				$table = wrap_tag($table,"table","width=100% title=\"Click a record to edit it below\"");
			print $table;
			} else { // no matching records note
				print "<table height=\"100%\" width=\"100%\"><tr><td><div style=\"font-weight:bold;text-align:center;font-size:120%;\">No matching records.</div></td></tr></table>\n";
			}
		}
	}
}

/* Make tables */

function create_week_header($week,$year) {
    $result = array();
    $start = strtotime($year . "W" . $week);
    //print $start;
    //echo date("Y-m-d",$start) . "<br>";
	for ($i = 0; $i <= 6; $i++) {
		//echo date("Y-m-d",$start) . "<br>"; 
		$result[] = $start;
		$start=$start+(60*60*24);
	}
	return $result;
}

function create_weekschedule_header($week,$year) {
	$result = "";
	$result .= wrap_tag("Staff","td","class=\"cellTitle\"");
	$dates = create_week_header($week,$year);
	//$dayname = date("D",$dates);
	for ($i = 0; $i <= 6; $i++) {
		$result .= wrap_tag(date("m-d",$dates[$i]) . " (" . date("D",$dates[$i]) . ")","td","class=\"cellTitle\"");
	}
	return $result;
}

function create_weekscheduler_lists($amount,$year) {
	global $staff,$number,$shiftnames,$staffshiftlist;
	$result = NULL;
	$ids = array();
	$names = array();
	$dates = array();
	
	//if($ids = $this->get_rep_id()) {
	if($names = get_rep_name()) {
		//$numbers = $this->get_rep_num();
		$n = 0;
		//print "FAVOR" . $ids[1]. $ids[3]. $ids[6];
		$dates = create_week_header($amount,$year);
		foreach($names as $name) {
			$row = "";
			$fdata = $name[2] . ", " . $name[1];
			$row .=  wrap_tag($fdata,"td", "class=\"cellTitle\"");
			for($i = 0; $i < 7; ++$i) {
				$strdate = $dates[$i];
				$w = date("w",$strdate);
				$fdata = get_cell($name[0],$staffshiftlist[$w]);
				if($n%2 == 0) {
					$postname = $name[0] . "-" . ($i+1);
					$r = make_scheduledropdown_list("",$postname,$shiftnames[$fdata],$shiftnames,"Edit to change shifts",date("Y-m-d",$dates[$i]),$name[0]);
					$row .= wrap_tag($r,"td","bgcolor=\"#eff1f1\"");
				} else if($n%2 == 1) {
					$postname = $name[0] . "-" . ($i+1);
					$r = make_scheduledropdown_list("",$postname,$shiftnames[$fdata],$shiftnames,"Edit to change shifts",date("Y-m-d",$dates[$i]),$name[0]);
					$row .= wrap_tag($r,"td","bgcolor=\"#f8f8f8\"");
				}
				//$r = $this->make_formdropdown_list("","mSch",$schYear,$this->shift_list,"Scheduler view year");
				//$row .= $this->wrap_tag($r,"td");
			}
			$class = ($selected)?"selectcell":"cell" . ($n % 2);
			$result[] = wrap_tag($row,"tr","class=\"$class\"");
			$n++;
			$number = 7;
			$staff = sizeof($names);
			//$result[] = $this->wrap_tag($row,"tr");
		}
	}
	return $result;
}

function getDaysInMonth($month, $year) {
	global $daysInMonth;
	if ($month < 1 || $month > 12) {
    	return 0;
    }
    $d = $daysInMonth[$month - 1];
    if ($month == 2) {
    // Check for leap year
    // Forget the 4000 rule, I doubt I'll be around then...
    	if ($year%4 == 0) {
        	if ($year%100 == 0) {
            	if ($year%400 == 0) {
                	$d = 29;
                }
            } else {
                $d = 29;
            }
        }
    }
    return $d;
}

function create_month_header($month,$year) {
	global $daysInMonth;
    $result = array();
    $start = strtotime($year . "-" . $month);
	for ($i = 0; $i <= $daysInMonth[$month-1]; $i++) { 
		$result[] = $start;
		$start=$start+(60*60*24);
	}
	return $result;
}

function create_monthschedule_header($month,$year) {
    	$result = "";
		$result .= wrap_tag("Staff","td","class=\"cellTitle\"");
		$dates = create_month_header($month,$year);
		for ($i = 0; $i < getDaysInMonth($month,$year); $i++) {
			$result .= wrap_tag(date("m-d",$dates[$i]),"td","class=\"cellTitle\"");
		}
		return $result;
}

function create_monthscheduler_lists($amount,$year) {
	global $staff,$number,$shiftnames,$staffshiftlist;
	$result = NULL;
	$ids = array();
	$names = array();
	$dates = array();
	$tday = 0;
	$tstaff = 0;
	//if($ids = $this->get_rep_id()) {
	if($names = get_rep_name()) {
		$staff = sizeof($names);
		$n = 0;
		$dates = create_month_header($amount,$year);
		//print "FAVOR" . $ids[1]. $ids[3]. $ids[6];
		foreach($names as $name) {
			$row = "";
			$fdata = $name[2] . ", " . $name[1];
			$row .=  wrap_tag($fdata,"td", "class=\"cellTitle\"");
			//print $id;
			for($i = 0; $i < getDaysInMonth($amount,$year); $i++) {
				//$shifts = array();
				$day = $i+1;
				$date = $year . "-" . $amount . "-" . $day;
				$strdate = strtotime($date);
				$w = date("w",$strdate);
				//echo $date . "is a " .$w . "<br>";
				$fdata = get_cell($name[0],$staffshiftlist[$w]);
				//$shifts[] = $fdata;
				//echo $fdata;
				if($n%2 == 0) {
					$postname = $name[0] . "-" . ($i+1);
					$r = make_scheduledropdown_list("",$postname,$shiftnames[$fdata],$shiftnames,"Edit to change shifts",date("Y-m-d",$dates[$i]),$name[0]);
					$row .= wrap_tag($r,"td","bgcolor=\"#eff1f1\"");
				} else if($n%2 == 1) {
					$postname = $name[0] . "-" . ($i+1);
					$r = make_scheduledropdown_list("",$postname,$shiftnames[$fdata],$shiftnames,"Edit to change shifts",date("Y-m-d",$dates[$i]),$name[0]);
					$row .= wrap_tag($r,"td","bgcolor=\"#f8f8f8\"");
				}
				//$tday = $i++;
			}
			$number = getDaysInMonth($amount,$year);
			$class = ($selected)?"selectcell":"cell" . ($n % 2);
			$result[] = wrap_tag($row,"tr","class=\"$class\"");
			$n++;
		}
	}
	return $result;
}
?>