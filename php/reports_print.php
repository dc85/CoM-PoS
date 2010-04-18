<?php
include("../backend/reports.php");
?>

<html>
<head>
<title></title>
<link href="../css/print_table.css" rel="stylesheet" type="text/css" />
<script src="../js/jquery.js" type="text/javascript"></script>
<script src="../js/general.js" type="text/javascript"></script>
<script src="../js/report.js" type="text/javascript"></script>
<script language="javascript" type="text/javascript">

</script>
</head>
<body>

<?php 
$list = $_GET['list'];
$start = $_GET['start'];
$end = $_GET['end'];
print_table($list,$start,$end);
?>

</body>

</html>