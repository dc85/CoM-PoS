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

	    $(".tablesorter tr").click(function(){
		    //var val = $(this).attr("selected");
		    //alert("val="+val);
		    if($(this).attr("selected") !== undefined) {
		    	if($(this).attr("selected") == "f") {
			    	$(this).attr("selected","t");
					$(this).addClass("clicked");
			    } else if($(this).attr("selected") == "t") {
			    	$(this).attr("selected","f");
					$(this).removeClass("clicked");
			    }
		    } else {
		    	$(this).attr("selected","t");
				$(this).addClass("clicked");
		    }		    
		});
		/*$(".tablesorter tr").mouseout(function(){
		      $(this).removeClass("clicked");
		});*/
	} 
);
function reload() {
	parent.invList = document.getElementsByTagName("tr");
	//alert(parent.invList.length);
}
</script>
</head>
<body>

<?php
$cid = $_GET['cid'];
$start = $_GET['start'];
$end = $_GET['end'];
print_invoice_table("lookup",$cid."@".$start."@".$end);

//print "<script type=\"text/javascript\">refreshCart();</script>";
?>

</body>

</html>