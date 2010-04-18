<?php
include("../backend/invoice.php");
?>

<html>
<head>
<title></title>
<link href="../jquery.tablesorter/themes/blue/style.css" rel="stylesheet" type="text/css" />
<script src="../js/jquery.js" type="text/javascript"></script>
<script src="../js/customer.js" type="text/javascript"></script>
<script src="../jquery.tablesorter/jquery.tablesorter.js" type="text/javascript"></script>
<script language="javascript" type="text/javascript">
$(document).ready(function() { 
		//$.tablesorter.defaults.widgets = ['zebra'];
	    $("#myTable").tablesorter( {sortList: [[0,0]]} );
	    
	    $(".tablesorter tr").mouseover(function(){
	      $(this).addClass("over");
	    });
	
	    $(".tablesorter tr").mouseout(function(){
	      $(this).removeClass("over");
	    });
	} 
);
</script>
</head>
<body>

<?php
$iID = $_GET['iID'];
print_invoice_table("outstandingitems",$iID);

//print "<script type=\"text/javascript\">refreshCart();</script>";
?>

</body>

</html>