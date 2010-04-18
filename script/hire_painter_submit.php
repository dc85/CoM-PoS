<?php
ini_set("display_errors", 1);

error_reporting(E_NONE);

include("../backend/mysqldb.php");

$database = new MySQLDB;


$cE = array("cE1","cE2","cE3","cE4","cE5","cE6",
"cE7","cE8","cE9","cE10","cE11","cE12","cE13",
"cE14","cE15","cE16","cE17","cNumCols","cNumRms");

$datestr = getdate(time());
$hDateCrt = date("Y-m-d H:i:s",$datestr[0]);
$hDate = $_POST['cDate'];

$qryDate = strtotime(substr($hDate,4));
$qDate = date("Y-m-d",$qryDate);
//print $qDate;

//print $hDateCrt."\n";

$q_array = array(
	array("query" => buildInsertQuery()),
	array("query" => buildUpdateQuery())
	);
	
if($database->transaction($q_array)) {
	print "WEBPOS_MSG(02020445)";
} else {
	print "WEBPOS_ERR(02020445)";
}

function buildInsertQuery() {
	global $cE,$hDateCrt,$qDate;
	$sql = "INSERT INTO pos.tblPCHire(hContID,hCustID,".
	"cE1,cE2,cE3,cE4,cE5,cE6,cE7,cE8,cE9,cE10,cE11,cE12,".
	"cE13,cE14,cE15,cE16,cE17,cNumCols,cNumRms,cNote,".
	"hDate,hCrtDate) VALUES(".
	$_POST['cContID'].",".
	$_POST['cCustID'].",";
	foreach($cE as $e) {
		if(isset($_POST[$e]) && !empty($_POST[$e])) {
			$sql .= $_POST[$e].",";
		} else {
			$sql .= "0,";
		}
	}
	if(isset($_POST['cNote']) && !empty($_POST['cNote'])) {
		$sql .= "'".$_POST['cNote']."',";
	} else {
		$sql .= "null,";
	}
	$sql .= "'".$qDate."','".$hDateCrt."');";
	//print $sql;
	return $sql;
}

function buildUpdateQuery() {
	global $hDateCrt,$qDate;
	$sql = "UPDATE pos.tblHPainter SET pLastJob='$hDateCrt' WHERE pCustID=".$_POST['cContID'].";";
	return $sql;
}
?>