<?php
include("../backend/invoice.php");

$session = $_GET['session'];

$sql = "SELECT scQty FROM pos.tblShoppingCart WHERE tblShoppingCart.scSNum='$session';";
$result = @mysql_query($sql);
//print $sql;
$total = 0;

while($row = @mysql_fetch_assoc($result)) {
	//print "value " . $row['scQty'] . "<br>";
	$total += (int)$row['scQty'];
}

print $total;
?>