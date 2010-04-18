<?php
include("../backend/product.php");
?>

<html>
<head>
<title></title>
<link href="../jquery.tablesorter/themes/blue/style.css" rel="stylesheet" type="text/css" />
<script src="../js/jquery.js" type="text/javascript"></script>
<script src="../js/product.js" type="text/javascript"></script>
<script src="../jquery.tablesorter/jquery.tablesorter.js" type="text/javascript"></script>
<script language="javascript" type="text/javascript">
var $val = null;
$(document).ready(function() { 
		//$.tablesorter.defaults.widgets = ['zebra'];
    $("#myTable").tablesorter();
    
    $(".tablesorter tr").mouseover(function(){
      $(this).addClass("over");
    });

    $(".tablesorter tr").mouseout(function(){
      $(this).removeClass("over");
    });
    $(".tablesorter tr").click(function(){
        if($val != null) {
        	$val.removeClass("clicked");
        }
	    $val = $(this);
	    if($(this).attr("selected") !== undefined) {
	    	if($(this).attr("selected") == "f") {
		    	$(this).attr("selected","t");
				$(this).addClass("removed");
				//$("#parent").val.removeClass("clicked");
		    } else if($(this).attr("selected") == "t") {
		    	$(this).attr("selected","f");
				$(this).removeClass("clicked");
		    }
	    } else {
	    	$(this).attr("selected","t");
			$(this).addClass("clicked");
	    }		    
	});
});
</script>
</head>
<body>

<?php
//$session = $_GET['session'];
print_promo_table();

//print "<script type=\"text/javascript\">refreshCart();</script>";
?>

</body>

</html>