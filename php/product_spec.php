<?php
include("../backend/product.php");
include("../backend/methods.php");
$sid=$_GET['sid'];
$pid=$_GET['pid'];
?>

<html>
<head>
<title></title>
<link href="../css/product_spec.css" rel="stylesheet" type="text/css" />
<script src="../js/product.js" type="text/javascript"></script>

</head>
<body>
<div id="spec_detail1">
	<?php print print_spec_detail1($sid,$pid);?>
</div>
<div id="spec_detail2">
	<?php print print_spec_detail2($sid,$pid); ?>
</div>
<div id="spec_detail3">
	<?php print print_spec_detail3($sid,$pid); ?>
</div>
<div id="spec_detail4">
<h2>Promo Note  <input type=button name="setPromp" id="setPromp" value="Set Promo" onClick="JavaScript:setPromo();"></input></h2>
<textarea rows="4" cols="80" name="promoBox" id="promoBox"><?php print get_promo_note($pid);?></textarea>
</div>
</body>

</html>