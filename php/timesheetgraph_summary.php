<?php
include("../backend/timesheet.php");
$sql_date = $_GET['date'];



$sql = "SELECT tblProduct.pCatType as typ,SUM(tTotal) as tot 
FROM pos.tblTrans 
INNER JOIN pos.tblProduct ON tblTrans.tProdID = tblProduct.pID 
WHERE tTrnsDate='$sql_date' GROUP BY(tblProduct.pCatType);";
$resultp = "1:0|2:0|3:0|4:0|5:0|6:0";
if($result = @mysql_query($sql)) {
	while($row = @mysql_fetch_assoc($result)) {		
		$index = $row['typ'];
		$total = $row['tot'];
		$front = substr($resultp,0,strpos($resultp,$index.':')+2);
		$back = substr($resultp,strpos($resultp,$index.':')+3,strlen($resultp));
		$resultp = $front.$total.$back;
	}
} else {
	print $sql;
	print (mysql_error());
}
print $resultp;
?>
