<?php
//include("backend/db_methods.php");
//include("backend/customer.php");
?>

<html>
	<head>
		<title>Colours of Maple Stores</title>
		<link href="../css/customer.css" rel="stylesheet" type="text/css" />
		<script src="../js/jquery.js" type="text/javascript"></script>
		<script src="../js/menu.js" type="text/javascript"></script>
		<script src="../js/jquery.hotkeys.js" type="text/javascript"></script>
		<script type="text/javascript">
			Array.prototype.indexOf = function(p_val) {
				for(var i = 0, l = this.length; i < l; i++) {
					if(this[i] == p_val) {
						return i;
					}
				}
				return -1;
			};
		
			function compareStr() {
				var ary = new Array("Lala","huaren");
				var r_ary = new Array();
				
				str = document.getElementById("ttyy").innerHTML;

				if(str.indexOf(ary[i) != -1) {
					r_ary.splice();
				}
				
			}
		</script>

	</head>
	<body>
	<textarea id="ttyy"  rows="30" cols="180"></textarea>
	<input type="button" value="Submit" onclick="compareStr();"></input>
	</body>
	
</html>