<?php
include("../backend/methods.php");
include("../backend/rental.php");

$eles = array("new_rInvoice","start-date","end-date","new_rUPC","new_rPType",
	"new_rPDescription","new_rcID","new_rLastN","new_rFirstN","new_rPhone",
	"new_rDID","new_rDIDType","new_rDAmt","new_rDType","new_rStore",
	"new_rStaff");
$sql = "INSERT INTO pos.tblRental(`rInvoice`,`rStart`,`rEnd`,`rUPC`,`rPType`,
	`rPDescription`,`rCID`,`rLastN`,`rFirstN`,`rPhone`,
	`rDID`,`rDIDType`,`rDAmt`,`rDType`,`rStore`,
	`rStaff`,`rIsReturned`) VALUES('".$_POST['new_rInvoice']."','".$_POST['start-date']."',
		'".$_POST['end-date']."','".$_POST['new_rUPC']."','".$_POST['new_rPType']."',
		'".$_POST['new_rPDescription']."','".$_POST['new_rcID']."',
		'".htmlspecialchars($_POST['new_rLastN'],ENT_QUOTES)."',
		'".htmlspecialchars($_POST['new_rFirstN'],ENT_QUOTES)."',
		'".$_POST['new_rPhone']."','".$_POST['new_rDID']."',
		'".htmlspecialchars($_POST['new_rDIDType'],ENT_QUOTES)."',
		".$_POST['new_rDAmt'].",'".$_POST['new_rDType']."',
		'".$_POST['new_rStore']."','".$_POST['new_rStaff']."',0);";
if($result = @mysql_query($sql)) {
	print "Rent Success.";
} else {
	print (mysql_error());
}

?>