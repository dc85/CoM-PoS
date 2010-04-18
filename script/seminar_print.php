<?php
include("../backend/seminar.php");
?>

<html>
<head>
<title></title>
<link href="../jquery.tablesorter/themes/blue/style.css" rel="stylesheet" type="text/css" />
<script src="../js/jquery.js" type="text/javascript"></script>
<script src="../js/general.js" type="text/javascript"></script>
<script src="../js/seminar.js" type="text/javascript"></script>
<script src="../jquery.tablesorter/jquery.tablesorter.js" type="text/javascript"></script>
<script language="javascript" type="text/javascript">
var $val = null;
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
        if($val != null) {
        	$val.removeClass("clicked");
        }
	    $val = $(this);
	    if($(this).attr("selected") !== undefined) {
	    	if($(this).attr("selected") == "f") {
		    	$(this).attr("selected","t");
				$(this).addClass("clicked");
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
print_table();
?>

</body>

</html>