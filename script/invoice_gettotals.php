<?php
include("../backend/invoice.php");

$session = $_GET['session'];

$sql = "SELECT SUM(tblShoppingCart.scTotal) AS tot FROM pos.tblShoppingCart WHERE tblShoppingCart.scInvcNum='$session' AND tblShoppingCart.scIsFree=0;";
$sql2 = "SELECT SUM(tblShoppingCart.scQty) AS tot FROM pos.tblShoppingCart WHERE tblShoppingCart.scInvcNum='$session';";

//print $sql;
$total1 = 0;
$total2 = 0;

if($result = @mysql_query($sql)) {
	if($row = @mysql_fetch_assoc($result)) {
		$total1 = $row['tot'];
	}
} else {
	print $sql."\n";
	print mysql_error()."\n";
}

if($result2 = @mysql_query($sql2)) {
	if($row = @mysql_fetch_assoc($result2)) {
		$total2 = $row['tot'];
	}
} else {
	print $sql."\n";
	print mysql_error()."\n";
}

print "GQRY&".$total1 . "&" . $total2;
?>