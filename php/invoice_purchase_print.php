<?php
include("../backend/invoice.php");
?>

<html>
<head>
<title></title>
<link href="../css/print_table.css" rel="stylesheet" type="text/css" />
<script src="../js/jquery.js" type="text/javascript"></script>
<script language="javascript" type="text/javascript">

</script>
</head>
<body>

<?php
$cID = $_GET['cID'];
print_invoice_table("purchase",$cID);

//print "<script type=\"text/javascript\">refreshCart();</script>";
?>

</body>

</html>