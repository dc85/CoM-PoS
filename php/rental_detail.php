<?php
include("../backend/rental.php");
include("../backend/methods.php");
$store=$_GET['store'];
$id=$_GET['id'];
?>

<html>
<head>
<title></title>
<link href="../css/rental_detail.css" rel="stylesheet" type="text/css" />
<script src="../js/rental.js" type="text/javascript"></script>

</head>
<body>
	<div id="main">
		<table>
			<tr>
				<td><?php make_formtext_field("Rental ID","rentalID",$result[$item],"Last Name",25,1);?></td>
			</tr>
		</table>
	</div>
</body>

</html>