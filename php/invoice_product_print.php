<?php
include("../backend/product.php");

/*if(!empty($_GET['logoff'])) { $_SESSION = array(); }

//@ is authorized?
if(empty($_SESSION['exp_user']) || @$_SESSION['exp_user']['expires'] < time()) {
header("location:../src/login.html");	//@ redirect
} else {
$_SESSION['exp_user']['expires'] = time()+(30*60);	//@ renew 45 minutes
}*/
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
      $(this).addClass("overinvc");
    });

    $(".tablesorter tr").mouseout(function(){
      $(this).removeClass("overinvc");
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
$query = $_GET['query'];
$spc = $_GET['spc'];
$store = $_GET['store'];

print_table_invoice($query,$spc,$store);
?>

</body>

</html>