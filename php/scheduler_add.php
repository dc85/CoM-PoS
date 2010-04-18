<?php
include("../backend/scheduler.php");
ini_set('max_execution_time',300);

$count = 0;

$type = $_POST['type'];
$amount = $_POST['amount'];
$year = $_POST['year'];
$str = $_POST['str'];

$stuff = substr($str,0,strlen($str)-1);
$schedules = split(',',$stuff);
//$count = sizeof($schedules);
$result = array();
$shiftnames = array("","DAY OFF","FS1","FS2","MS1","MS2","BS1","BS2","IT");
//print sizeof($schedules);
//print $str;
if($type == "byWeek") {
	$result = create_week_header($amount, $year+2008);
	//print_r($result);
} else if($type == "byMonth") {
	$result = create_month_header($amount, $year+2008);
	//print_r($result);
} else {
	
}
//print $_GET['str'];
foreach($schedules as $pair) {
	$shift = split(':',$pair);
	$id_day = split('-',$shift[0]);
	//print $id_day[0];
	//$staffinfo = find_staffinfo($id_day[0],"getsid");
	//print_r($staffinfo);
	//print "Finished<br>";
	$shiftinfo = find_shiftinfo($shiftnames[$shift[1]],date("Y-m-d",$result[$id_day[1]]));
	//print_r($shiftinfo);
	//print "<br>";
	$date = getdate(time());
	$curr_date = date("Y-m-d H:i:s",$date[0]);
	$item = array();
	$item[0] = $id_day[0];
	$item[1] = date("Y-m-d",$result[$id_day[1]-1]);
	//$item[1] = date("Y-m-d",$head_date);
	$item[2] = $shiftinfo[2];
	
	//print $item[0] . " / " . $item[1] . " / " . $item[2] . " / " . $item[1].":".$shiftinfo[0] . " / " . $item[1].":".$shiftinfo[1] . "<br>";  
	
	if(shiftExists($item[0],$item[1],$item[2])) {
		$sql = "UPDATE pos.tblTimesheet SET tsStaff='$item[0]' AND tsDate='$item[1]' AND tsShift=$item[2] WHERE tsStaff=$item[0] AND tsDate='$item[1]' AND tsModDate='$curr_date';";
	} else {
		$sql = "INSERT INTO pos.tblTimesheet
		(`tsStaff`,`tsStatus`,`tsDate`,`tsShift`,`tsXin`,`tsXOut`,`tsModDate`,`tsLogIn`,`tsDelStatus`) 
		VALUES
		('$item[0]',0,'$item[1]',$item[2],'$item[1]:$shiftinfo[0]','$item[1]:$shiftinfo[1]','$curr_date',0,0);";
	}
	//print $sql . "<br>";
	if(mysql_query($sql)) {
		//print $sql . "<br>";
		$count++;
	} else {
		print mysql_error();
	}
	//}
}
print $count . " lines executed sucessfully <br>";
?>