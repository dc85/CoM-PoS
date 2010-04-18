<?php
ini_set("display_errors", 1);
error_reporting(E_NONE);
include("../backend/product.php");
$str = $_GET['str'];
$infoPairs = explode("|",$str);
$count = 0;

foreach($infoPairs as $info) {
	$pair = explode("@",$info);
	$col = "tblSPCType.slvl";
	$loc = substr($pair[0],strlen($pair[0])-1);
	$sql = "UPDATE pos.tblSPCType SET $col=$pair[1] WHERE sID=$loc;";
	//print $sql;
	if(@mysql_query($sql)) {
		$count++;	
	} else {
		print (mysql_error());
	}
}
print "[$count] record(s) in [tblSPCType] has been updated.";
?>