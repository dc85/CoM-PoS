<?php
include("../backend/invoice.php");
?>

<html>
<head>
<title></title>
<link href="../jquery.tablesorter/themes/blue/style.css" rel="stylesheet" type="text/css" />
<script src="../js/jquery.js" type="text/javascript"></script>
<script src="../js/invoice.js" type="text/javascript"></script>
<script src="../jquery.tablesorter/jquery.tablesorter.js" type="text/javascript"></script>
<script language="javascript" type="text/javascript">
var $val = null;
$(document).ready(function() { 
		//$.tablesorter.defaults.widgets = ['zebra'];
    $("#myTable").tablesorter( {sortList: [[0,0]]} );
    
    $(".tablesorter tr").mouseover(function(){
      $(this).addClass("overcart");
    });

    $(".tablesorter tr").mouseout(function(){
      $(this).removeClass("overcart");
    });
    $(".tablesorter tr").click(function(){
        if($val != null) {
        	$val.removeClass("removed");
        }
	    $val = $(this);
	    if($(this).attr("selected") !== undefined) {
	    	if($(this).attr("selected") == "f") {
		    	$(this).attr("selected","t");
				$(this).addClass("removed");
				//$("#parent").val.removeClass("clicked");
		    } else if($(this).attr("selected") == "t") {
		    	$(this).attr("selected","f");
				$(this).removeClass("removed");
		    }
	    } else {
	    	$(this).attr("selected","t");
			$(this).addClass("removed");
	    }		    
	});
});
</script>
</head>
<body>

<?php
$session = $_GET['session'];
print_invoice_table("cart",$session);

//print "<script type=\"text/javascript\">refreshCart();</script>";
?>

</body>

</html>