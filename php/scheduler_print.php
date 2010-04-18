<?php
include("../backend/scheduler.php");
?>

<html>
<head>
<title></title>
<link href="../css/print_table.css" rel="stylesheet" type="text/css" />
<script src="../js/jquery.js" type="text/javascript"></script>
<script src="../js/general.js" type="text/javascript"></script>
<script language="javascript" type="text/javascript">

</script>
</head>
<body>

<?php 
list($byType,$schAmount,$schYear) = get_query_parameters($_GET);
print_data_table($byType,$schAmount,$year_list[$schYear]);
?>

</body>

</html>