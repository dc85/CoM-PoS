<?php
include("../backend/customer.php");
include("../backend/methods.php");
$store=$_GET['store'];
$id=$_GET['id'];
?>

<html>
<head>
<title></title>
<link href="../css/customer_editspec.css" rel="stylesheet" type="text/css" />
<script src="../js/jquery.js" type="text/javascript"></script>
<script src="../js/customer.js" type="text/javascript"></script>
</head>
<body>
<div id="spec_info">
	<?php print print_editspec_info($store,$id);?>
</div>

<div id="spec_prime_contact">
	<h3 class="info">Primary Contact</h3>
	<?php print print_edithome_contact($store,$id);?>
</div>
<div id="spec_second_contact">
	<h3 class="info">Secondary Contact</h3>
	<?php print print_editsecond_contact($store,$id);?>
</div>
<div id="spec_backup_contact">
	<h3 class="info">Shipping Contact</h3>
	<?php print print_editbackup_contact($store,$id);?>
</div>
<div id="spec_additional_contact">
	<h3 class="info">Additional Contact</h3>
	<?php print print_editadditional_contact($store,$id);?>
</div>
<div id="spec_finance_info">
	<?php print print_editfinance_info($store,$id);?>
</div>

</body>

</html>