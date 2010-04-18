<?php
include("../backend/methods.php");
include("../backend/db_methods.php");

$array = $_GET['array'];

$str = substr($array,0,strlen($array)-1);
//print $str;
$result = split(']',$str);

foreach($result as $pair) {
	$item = split(':',$pair);
	
	$staffinfo = find_staffinfo($item[0],"id");
	//print_r($staffinfo);
	//print "Finished<br>";
	$shiftinfo = find_shiftinfo($item[2],$item[1]);
	//print_r($shiftinfo);
	//print "<br>";
	$date = getdate(time());
	$curr_date = date("Y-m-d",$date[0]); 
	
	if(shiftExists('1',$item[0],$item[1],$item[2])) {
		$sql = "UPDATE pos.tblTimesheet SET tsID='$item[0]' AND tsDate='$item[1]' AND tsShift='$item[2]' WHERE tsStaff=$item[0] AND tsDate='$item[1]';";
	} else {
		$sql = "INSERT INTO pos.tblTimesheet
		(`sStoreID`,`tsStaff`,`tsStatus`,`tsDate`,`tsShift`,`tsXin`,`tsXOut`,`tsModDate`,`tsLogIn`,`tIsActive`)
			VALUES
			(1,$item[0],0,'$item[1]','$item[2]','$shiftinfo[0]','$shiftinfo[1]',$curr_date,'n/a',1);";
	}
	
	//print $sql . "<br>";
	
	//print $sql;
	//$arg="INSERT INTO pos.tblwrkshift(`sStoreID`, `sID`, `sSchedule`, `sIn0`, `sOut0`, `sIn1`, `sOut1`, `sIn2`, `sOut2`, `sIn3`, `sOut3`, `sIn4`, `sOut4`, `sIn5`, `sOut5`, `sIn6`, `sOut6`, `sModDate`, `sCrtDate`, `sIsActive`) VALUES(1, 1, 'DAY OFF', '0/0/0000 00:00:00', '0/0/0000 00:00:00', '0/0/0000 00:00:00', '0/0/0000 00:00:00', '0/0/0000 00:00:00', '0/0/0000 00:00:00', '0/0/0000 00:00:00', '0/0/0000 00:00:00', '0/0/0000 00:00:00', '0/0/0000 00:00:00', '0/0/0000 00:00:00', '0/0/0000 00:00:00', '0/0/0000 00:00:00', '0/0/0000 00:00:00', NULL, NULL, 1);";
	if(mysql_query($sql)) {
		print $sql . "<br>";
	} else {
		print mysql_error();
	}
}
?>