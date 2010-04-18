<?php
include("../backend/customer.php");
include("../backend/methods.php");
$store=$_GET['store'];
$id=$_GET['id'];
?>

<html>
<head>
<title></title>
<link href="../css/customer_spec.css" rel="stylesheet" type="text/css" />

</head>
<body>
<div id="spec_info">
	<?php print print_spec_info($store,$id);?>
</div>

<div id="spec_prime_contact">
	<h3 class="info">Primary Contact</h3>
	<?php print print_home_contact($store,$id);?>
</div>
<div id="spec_second_contact">
	<h3 class="info">Secondary Contact</h3>
	<?php print print_second_contact($store,$id);?>
</div>
<div id="spec_backup_contact">
	<h3 class="info">Shipping Contact</h3>
	<?php print print_backup_contact($store,$id);?>
</div>
<div id="spec_additional_info">
	<h3 class="info">Addition Contacts</h3>
	<?php print print_additional_contact($store,$id);?>
</div>
<div id="spec_finance_info">
	<?php print print_finance_info($store,$id);?>
</div>
</body>

</html>