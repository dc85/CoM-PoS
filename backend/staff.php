<?php
include("db_methods.php");

//ini_set("display_errors", 0);

$data;

function create_table_header() {
	$row = "";
	$row .= wrap_tag("Store","th");
	$row .= wrap_tag("Staff ID","th");
	$row .= wrap_tag("Staff Number","th");
	$row .= wrap_tag("Name","th");
	$row .= wrap_tag("Since","th");
	$row .= wrap_tag("Active","th");		
    return wrap_tag($row,"tr");
}

function create_table_row($query,$store) {
	global $data;
	$table = NULL;
	$keya = "";
	$keyb = "";
	$result = array();
	$headers = array('sName','sID','sCNumber','sStaff','sCreated','sIsActive');
	$sql = "SELECT tblStaff.sStoreID as sStoreID,tblStore.sName as sName,".
	"tblStaff.sID as sID,tblStaff.sCNumber as sCNumber,tblStaff.sStaff as sStaff,".
	"tblStaff.sCreated as sCreated,tblStaff.sIsActive as sIsActive ".
	"FROM pos.tblStaff,pos.tblStore WHERE tblStore.sID=tblStaff.sStoreID ".
	"AND (sStaff LIKE '%$query%' OR sCNumber LIKE '%$query%')";
	if($store != "") {
		$sql .= " AND sStoreID=$store";
	}
	//print $sql;
	$data = query_database($sql);
	$n = 0;
	foreach($data as $rec) {
		$active = false;
		$row = "";
		$keya = $rec['sStoreID'];
		$keyb = $rec['sID'];
		if($rec['sIsActive'] == "1") {
			$active = true;
		} else {
			$active = false;
		}
		/*$keyc = $rec['keyC'];
		$keyd = htmlspecialchars($rec['cFirstN'],ENT_QUOTES);
		$keye = htmlspecialchars($rec['cLastN'],ENT_QUOTES);*/
				
		foreach($headers as $field) {
			$fdata = $rec[$field];

			if($field == "sStaff") {
				$fdata = htmlspecialchars($fdata,ENT_QUOTES);
				$fdata = preg_replace("/\n/m","<br/>\n",$fdata);
			}
			if($field == "sIsActive") {
				if($fdata == "1") {
					$fdata = "YES";
				} else if($fdata == "0") {
					$fdata = "NO";
				} else {
					$fdata = "N/A";
				}
			} 
			if($fdata == "") {
				$fdata = "--";
			}
			//$upc .= $fdata;
			$row .= wrap_tag($fdata,"td");
		}
		//$class = ($selected)?"selectcell":"cell" . ($n % 2);
		$class = ($active)?"":"class=\"inactive\"";
		$table[] = wrap_tag($row,"tr",
			"$class onClick=\"choose_staff('$keya','$keyb');\"");
		//$n++;
	}
	return $table;
}

function print_table($query,$store) {
	$table = "";
	if(false) {
		print "<table height=\"100%\" width=\"100%\"><tr><td><div style=\"font-weight:bold;text-align:center;font-size:120%;\">Please enter product description to search.</div></td></tr></table>\n";
	} else {
		if($header = create_table_header()) {
			$table .= wrap_tag($header,"thead");
			if($row = create_table_row($query,$store)) {
				$tbody .= join($row,"\n");
				$table .= wrap_tag($tbody,"tbody","");
				$table = wrap_tag($table,"table","id=\"myTable\" class=\"tablesorter\" border=\"0\" cellpadding=\"0\" cellspacing=\"1\" title=\"Click on row to see customer details\"");
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

function print_spec_info() {
	$table = "";
	$list_info = array('sName','end','sID','end','sCustomer','end','sCNumber','end','sStaff','end','line',
	'sPassword','end','sLevel','end','sPswdExpMM','end','sPswdExp','end','sStoreCredit','end','line','sHrRate','end','sDfHr','end','line',
	'sSchedule0','end','sSchedule1','end','sSchedule2','end','sSchedule3','end','sSchedule4','end','sSchedule5','end','sSchedule6','end','line');
	$val = array(" ","DAY OFF","FRONT SHIFT 1","FRONT SHIFT 2","MIDDLE SHIFT 1","MIDDLE SHIFT 2","BACK SHIFT 1","BACK SHIFT 2","IT");
	$stores = get_store_names();
	/*$sql = "SELECT tblStaff.sStoreID as sStoreID,tblStore.sName as sName,tblStaff.sID as sID,tblStaff.sCNumber as sCNumber,
	tblStaff.sStaff as sStaff,tblStaff.sCreated as sCreated,
	tblStaff.sSchedule0 as sSchedule0,tblStaff.sSchedule1 as sSchedule1,tblStaff.sSchedule2 as sSchedule2,
	tblStaff.sSchedule3 as sSchedule3,tblStaff.sSchedule4 as sSchedule4,tblStaff.sSchedule5 as sSchedule5,
	tblStaff.sSchedule6 as sSchedule6,tblStaff.sIsActive as sIsActive 
	FROM pos.tblStaff,pos.tblStore
	WHERE tblStore.sID=tblStaff.sStoreID;";*/
	$sql = "SELECT * FROM pos.tblStaff;";
	//print $sql;
	//$r = @query_database($sql);
	//$result = $r[0];
	//print $sql;
	//print_r($result);
	$content = "";
	//print_r($result);
	foreach($list_info as $item) {
		if($item == 'end') {
			//$content .= $title;
			$content .= $element . "&nbsp;";
			//$content .= $element;
			//$title = "";
			$element = "";
		} else if($item == 'line') {
			$content .= "<br/>";
		}  else if($item == 'sName') {
			$element = make_dropdown_list("Store Name",$item,$stores[$item],$stores,"Name of store the staff work at");	
		} else if($item == 'sID') {
			$element = make_formtext_field("Staff ID",$item,$result[$item],"Staff ID",5,1);	
		} else if($item == 'sCustomer') {
			$element = make_formtext_field("Staff cID",$item,$result[$item],"Staff's Customer Number",15,1);	
		} else if($item == 'sCNumber') {
			$element = make_formtext_field("Staff Number",$item,$result[$item],"Staff Number",15,1);	
		} else if($item == 'sStaff') {
			$element = make_formtext_field("Staff Name",$item,$result[$item],"Name of the Staff",30,1);	
		} else if($item == 'sPassword') {
			$element = make_formtext_field("Account Password",$item,$result[$item],"Password for the staff's login",25,1);	
		} else if($item == 'sLevel') {
			$element = make_formtext_field("Staff Level",$item,$result[$item],"Staff Level",5,1);	
		} else if($item == 'sPswdExpMM') {
			$element = make_formtext_field("Password Expire MM",$item,$result[$item],"Password expiray",25,1);	
		} else if($item == 'sPswdExp') {
			$element = make_formtext_field("Password Expire Date",$item,$result[$item],"Password expiry date",25,1);	
		} else if($item == 'sStoreCredit') {
			$element = make_formtext_field("Store Credit",$item,$result[$item],"Staff's store credit",25,1);	
		} else if($item == 'sHrRate') {
			$element = make_formtext_field("Staff Hour Rate($)",$item,$result[$item],"Hourly rate of the staff",25,1);	
		} else if($item == 'sDfHr') {
			$element = make_formtext_field("Default daily hour",$item,$result[$item],"Default daily hour amount",25,1);	
		} else if($item == 'sSchedule0') {
			$element = make_dropdown_list("Sunday",$item,$val[$item],$val,"Default sunday's scheduled work time");	
		} else if($item == 'sSchedule1') {
			$element = make_dropdown_list("Monday",$item,$val[$item],$val,"Default monday's scheduled work time");	
		} else if($item == 'sSchedule2') {
			$element = make_dropdown_list("Tuesday",$item,$val[$item],$val,"Default tuesday's scheduled work time");	
		} else if($item == 'sSchedule3') {
			$element = make_dropdown_list("Wednesday",$item,$val[$item],$val,"Default wednesday's scheduled work time");	
		} else if($item == 'sSchedule4') {
			$element = make_dropdown_list("Thursday",$item,$val[$item],$val,"Default thursday's scheduled work time");	
		} else if($item == 'sSchedule5') {
			$element = make_dropdown_list("Friday",$item,$val[$item],$val,"Default friday's scheduled work time");	
		} else if($item == 'sSchedule6') {
			$element = make_dropdown_list("Saturday",$item,$val[$item],$val,"Default saturday's scheduled work time");	
		} else {
		
		}
	}
	return $content;
}

function print_newspec_info() {
	$table = "";
	$list_info = array('sTitle','end','line',
	'sFirstN','end','line',
	'sLastN','end','line',
	'sStoreID','end','line',
	//'sID','end','line',
	//'sCustomer','end','line',
	'sCNumber','end','line',
	'sStaff','end','line',
	'sPassword','end','line',
	'sLevel','end','line',
	'sPswdExpMM','end','line',
	'sPswdExp','end','line',
	'sStoreCredit','end','line',
	'sHrRate','end','line',
	'sDfHr','end','line',
	'sSchedule0','end','line',
	'sSchedule1','end','line',
	'sSchedule2','end','line',
	'sSchedule3','end','line',
	'sSchedule4','end','line',
	'sSchedule5','end','line',
	'sSchedule6','end','line');
	$val = array("","DAY OFF","FRONT SHIFT 1","FRONT SHIFT 2","MIDDLE SHIFT 1","MIDDLE SHIFT 2","BACK SHIFT 1","BACK SHIFT 2","IT");
	$stores = get_store_names();
	$content = "";
	//print_r($result);
	foreach($list_info as $item) {
		if($item == 'end') {
			//$content .= $title;
			//$content .= $element . "&nbsp;";
			$content .= $element;
			//$title = "";
			$element = "";
		} else if($item == 'line') {
			//$content .= "<br/>";
		} else if($item == 'sTitle') {
			$titles = get_customer_title();
			$element = make_editstaffdropdown_list("Title","n".$item," ",$titles,"Customer Title");	
		} else if($item == 'sFirstN') {
			$element = make_editstaffformtext_field("Last Name","n".$item,"","Last Name",25);	
		} else if($item == 'sLastN') {
			$element = make_editstaffformtext_field("Last Name","n".$item,"","First Name",25);	
		} else if($item == 'sStoreID') {
			$element = make_editstaffdropdown_list("Store Name","n".$item,"",$stores,"Name of store the staff work at");	
		} else if($item == 'sID') {
			$element = make_editstaffformtext_field("Staff ID","n".$item,"","Staff ID",5);	
		} else if($item == 'sCustomer') {
			$element = make_editstaffformtext_field("Staff cID","n".$item,"","Staff's Customer Number",15);	
		} else if($item == 'sCNumber') {
			$element = make_editstaffformtext_field("Staff Number","n".$item,"","Staff Number",15);	
		} else if($item == 'sStaff') {
			$element = make_editstaffformtext_field("Staff Name","n".$item,"","Name of the Staff",30);	
		} else if($item == 'sPassword') {
			$element = make_editstaffformtext_field("Account Password","n".$item,"","Password for the staff's login",25);	
		} else if($item == 'sLevel') {
			$element = make_editstaffformtext_field("Staff Level","n".$item,"","Staff Level",5);	
		} else if($item == 'sPswdExpMM') {
			$element = make_editstaffformtext_field("Password Expire MM","n".$item,"","Password expiray",25);		
		} else if($item == 'sStoreCredit') {
			$element = make_editstaffformtext_field("Store Credit","n".$item,"","Staff's store credit",25);	
		} else if($item == 'sHrRate') {
			$element = make_editstaffformtext_field("Staff Hour Rate($)","n".$item,"","Hourly rate of the staff",25);	
		} else if($item == 'sDfHr') {
			$element = make_editstaffformtext_field("Default daily hour","n".$item,"","Default daily hour amount",25);	
		} else if($item == 'sSchedule0') {
			$element = make_editstaffdropdown_list("Sunday","n".$item,"",$val,"Default sunday's scheduled work time");	
		} else if($item == 'sSchedule1') {
			$element = make_editstaffdropdown_list("Monday","n".$item,"",$val,"Default monday's scheduled work time");	
		} else if($item == 'sSchedule2') {
			$element = make_editstaffdropdown_list("Tuesday","n".$item,"",$val,"Default tuesday's scheduled work time");	
		} else if($item == 'sSchedule3') {
			$element = make_editstaffdropdown_list("Wednesday","n".$item,"",$val,"Default wednesday's scheduled work time");	
		} else if($item == 'sSchedule4') {
			$element = make_editstaffdropdown_list("Thursday","n".$item,"",$val,"Default thursday's scheduled work time");	
		} else if($item == 'sSchedule5') {
			$element = make_editstaffdropdown_list("Friday","n".$item,"",$val,"Default friday's scheduled work time");	
		} else if($item == 'sSchedule6') {
			$element = make_editstaffdropdown_list("Saturday","n".$item,"",$val,"Default saturday's scheduled work time");	
		} else {
		
		}
	}
	return $content;
}


function print_editspec_info($store,$id) {
	$table = "";
	$list_info = array('sStoreID','end','line',
	'sID','end','line',
	'sCustomer','end','line',
	'sCNumber','end','line',
	'sStaff','end','line',
	'sPassword','end','line',
	'sLevel','end','line',
	'sPswdExMM','end','line',
	'sPswdExp','end','line',
	'sStoreCredit','end','line',
	'sHrRate','end','line',
	'sDfHr','end','line',
	'sSchedule0','end','line',
	'sSchedule1','end','line',
	'sSchedule2','end','line',
	'sSchedule3','end','line',
	'sSchedule4','end','line',
	'sSchedule5','end','line',
	'sSchedule6','end','line');
	$val = array(" ","DAY OFF","FRONT SHIFT 1","FRONT SHIFT 2","MIDDLE SHIFT 1","MIDDLE SHIFT 2","BACK SHIFT 1","BACK SHIFT 2","IT");
	$stores = get_store_names();
	/*$sql = "SELECT tblStaff.sStoreID as sStoreID,tblStore.sName as sName,tblStaff.sID as sID,tblStaff.sCNumber as sCNumber,
	tblStaff.sStaff as sStaff,tblStaff.sCreated as sCreated,
	tblStaff.sSchedule0 as sSchedule0,tblStaff.sSchedule1 as sSchedule1,tblStaff.sSchedule2 as sSchedule2,
	tblStaff.sSchedule3 as sSchedule3,tblStaff.sSchedule4 as sSchedule4,tblStaff.sSchedule5 as sSchedule5,
	tblStaff.sSchedule6 as sSchedule6,tblStaff.sIsActive as sIsActive 
	FROM pos.tblStaff,pos.tblStore
	WHERE tblStore.sID=tblStaff.sStoreID;";*/
	$sql = "SELECT * FROM pos.tblStaff WHERE sStoreID=$store AND sID=$id;";
	//print $sql;
	$r = @query_database($sql);
	$result = $r[0];
	//print $sql;
	//print_r($result);
	$content = "";
	//print_r($result);
	foreach($list_info as $item) {
		if($item == 'end') {
			//$content .= $title;
			//$content .= $element . "&nbsp;";
			$content .= $element;
			//$title = "";
			$element = "";
		} else if($item == 'line') {
			//$content .= "<br/>";
		}  else if($item == 'sStoreID') {
			$element = make_editstaffdropdown_list("Store Name","e".$item,$stores[$result[$item]],$stores,"Name of store the staff work at");	
		} else if($item == 'sID') {
			$element = make_editstaffformtext_field("Staff ID","e".$item,$result[$item],"Staff ID",5);	
		} else if($item == 'sCustomer') {
			$element = make_editstaffformtext_field("Staff cID","e".$item,$result[$item],"Staff's Customer Number",15);	
		} else if($item == 'sCNumber') {
			$element = make_editstaffformtext_field("Staff Number","e".$item,$result[$item],"Staff Number",15);	
		} else if($item == 'sStaff') {
			$element = make_editstaffformtext_field("Staff Name","e".$item,$result[$item],"Name of the Staff",30);	
		} else if($item == 'sPassword') {
			$element = make_editstaffformtext_field("Account Password","e".$item,$result[$item],"Password for the staff's login",25);	
		} else if($item == 'sLevel') {
			$element = make_editstaffformtext_field("Staff Level","e".$item,$result[$item],"Staff Level",5);	
		} else if($item == 'sPswdExMM') {
			$element = make_editstaffformtext_field("Password Expire MM","e".$item,$result[$item],"Password expiray",25);	
		} else if($item == 'sStoreCredit') {
			$element = make_editstaffformtext_field("Store Credit","e".$item,$result[$item],"Staff's store credit",25);	
		} else if($item == 'sHrRate') {
			$element = make_editstaffformtext_field("Staff Hour Rate($)","e".$item,$result[$item],"Hourly rate of the staff",25);	
		} else if($item == 'sDfHr') {
			$element = make_editstaffformtext_field("Default daily hour","e".$item,$result[$item],"Default daily hour amount",25);	
		} else if($item == 'sSchedule0') {
			$element = make_editstaffdropdown_list("Sunday","e".$item,$val[$result[$item]],$val,"Default sunday's scheduled work time");	
		} else if($item == 'sSchedule1') {
			$element = make_editstaffdropdown_list("Monday","e".$item,$val[$result[$item]],$val,"Default monday's scheduled work time");	
		} else if($item == 'sSchedule2') {
			$element = make_editstaffdropdown_list("Tuesday","e".$item,$val[$result[$item]],$val,"Default tuesday's scheduled work time");	
		} else if($item == 'sSchedule3') {
			$element = make_editstaffdropdown_list("Wednesday","e".$item,$val[$result[$item]],$val,"Default wednesday's scheduled work time");	
		} else if($item == 'sSchedule4') {
			$element = make_editstaffdropdown_list("Thursday","e".$item,$val[$result[$item]],$val,"Default thursday's scheduled work time");	
		} else if($item == 'sSchedule5') {
			$element = make_editstaffdropdown_list("Friday","e".$item,$val[$result[$item]],$val,"Default friday's scheduled work time");	
		} else if($item == 'sSchedule6') {
			$element = make_editstaffdropdown_list("Saturday","e".$item,$val[$result[$item]],$val,"Default saturday's scheduled work time");	
		} else {
		
		}
	}
	return $content;
}
?>