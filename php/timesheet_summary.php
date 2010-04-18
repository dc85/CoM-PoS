<?php
include("../backend/timesheet.php");
?>

<html>
<head>
<title></title>
<link href="../css/print_table.css" rel="stylesheet" type="text/css" />
<script src="../js/jquery.js" type="text/javascript"></script>
<script src="../js/general.js" type="text/javascript"></script>
<script src="../js/timesheet.js" type="text/javascript"></script>
<script language="javascript" type="text/javascript">

</script>
</head>
<body>
	<?php
		$date = $_GET['date'];
		print_sales_table($date);
		//print print_sales_table($date);
	?>
</html>