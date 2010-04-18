<?php
$str = $_GET['str'];
$sID = $_GET['esStoreID'];
$sSID = $_GET['esID'];
print var_dump($_GET);
$intvars = array('sID','sCustomer','sLevel','sPswdExpMM',
'sSchedule0','sSchedule1','sSchedule2','sSchedule3','sSchedule4','sSchedule5','sSchedule6');
$shift_list = array(" ","DAY OFF","FRONT SHIFT 1","FRONT SHIFT 2","MIDDLE SHIFT 1","MIDDLE SHIFT 2","BACK SHIFT 1","BACK SHIFT 2","IT");;
$infoPairs = explode("|",$str);
print_r($infoPairs);
foreach($infoPairs as $info) {
	$pair = explode("@",$info);
	$col = "tblStaff.".substr($pair[0],1);
	/*if($col == "cShirtSize") {
		$dex = (int)$col;
		$pair[1] = $shift_list[$dex];
	}
	if($col == "cPantSize") {
		$dex = (int)$col;
		$pair[1] = $pants_list[$dex];
	}*/
	if(in_array(substr($pair[0],1),$intvars)) {
		$sql = "UPDATE pos.tblStaff SET $col=$pair[1] WHERE sStoreID=$sID AND sID=$sSID;";	
	} else {
		$sql = "UPDATE pos.tblStaff SET $col='$pair[1]' WHERE sStoreID=$sID AND sID=$sSID;";
	}
	
	print $sql . "<br>";
}
?>