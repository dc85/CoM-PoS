<?php
include("../backend/staff.php");
include("../backend/methods.php");
$store=$_GET['sStoreID'];
$id=$_GET['sID'];
//print "STORE" . $store . "|id" . $id;
?>

<html>
<head>
<title></title>
<link href="../css/staff_editspec.css" rel="stylesheet" type="text/css" />
<script src="../js/jquery.js" type="text/javascript"></script>
<script src="../js/staff.js" type="text/javascript"></script>
</head>
<body>
<div id="edit_spec_info">
	<table>
		<?php print print_editspec_info($store,$id);?>
	</table>
</div>

<div id="edit_spec_control">
<center>
<table><tr>
	<td><a href="#" id="editSEdit" onClick="JavaScript:editStaff();">Update Staff</a></td>
	<td><a href="#" id="editSClear" onClick="JavaScript:clearEditStaff();">Clear Fields</a></td>
</tr></table>
</center>
</div>
</body>

</html>