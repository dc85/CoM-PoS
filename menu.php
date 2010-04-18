<html>
	<head>
		<title>Colours of Maple Stores</title>
		<link href="css/menu.css" rel="stylesheet" type="text/css" />
		<script src="js/jquery.js" type="text/javascript"></script>
		<script src="js/menu.js" type="text/javascript"></script>
		<script src="js/jquery.hotkeys.js" type="text/javascript"></script>
		<script type="text/javascript">
			function loadCheck() {
				var wshell = new ActiveXObject("WScript.Shell");
				var sID = wshell.ExpandEnvironmentStrings("%pos%");
				//alert(sID);
				if(sID == "%pos%") {
					document.getElementById('tb_si').disabled = true;
					document.getElementById('focus_si').disabled = true;
					document.getElementById('submit_si').disabled = true;
					document.getElementById('tb_oo').disabled = true;
					document.getElementById('focus_oo').disabled = true;
					document.getElementById('submit_oo').disabled = true;
					document.getElementById('tb_so').disabled = true;
					document.getElementById('focus_so').disabled = true;
					document.getElementById('submit_so').disabled = true;
					document.getElementById('mSignin').disabled = true;
					document.getElementById('clock_msg').innerHTML = "System Error #10-00: If problem presist, please contact network administrator.";
				}

				/*alert("- Reset on invoce and customer added.\n"+
						"- after select product in invoice qty tab focus and highlighted.\n"+
						"- changed to a more detailed upc and brand display + descriptioln when highlight.\n"+
						"- changed to double click remove.\n"+
						"- changed msg select a tint to 'Missing colour'.\n"+
						"- change label note to tint detail/formula' maake sure a paint Co is selected.\n"+
						"- removed calculate button.\n"+
						"- removed popup on invoice");*/
			}
		</script>
	</head>
	<body onload="loadCheck();">
	<div id="main">
	<div id="div_si">
		<a class="controls" href="#" style="height:20%;width:100%;" id="focus_si" onclick="focus_si();">Clock In</a>
		<br>
		<center><input type="text" style="font-size:15pt;" id="tb_si" size=16></input></center>
		<br>
		<a class="controls" href="#" style="height:20%;width:100%;" id="submit_si" onclick="submit_si();">Submit</a>
	</div>
		
	<div id="div_oo">
		<a class="controls" href="#" style="height:20%;width:100%;font-size:10pt;" id="focus_oo" onclick="focus_oo();">Out of Office<br />Return to Office</a>
		<br>
		<center><input type="text" style="font-size:15pt;" id="tb_oo" size=16></input></center>
		<br>
		<a class="controls" href="#" style="height:20%;width:100%;" id="submit_oo" onclick="submit_oo();">Submit</a>
	</div>
	
	<div id="div_so">
		<a class="controls" href="#" style="height:20%;width:100%;" id="focus_so" onclick="focus_so();">Clock Out</a>
		<br>
		<center><input type="text" style="font-size:15pt;" id="tb_so" size=16></input></center>
		<br>
		<a class="controls" href="#" style="height:20%;width:100%;" id="submit_so" onclick="submit_so();">Submit</a>
	</div>
	
	<div id="div_msg">
		<center>
			<label id="clock_msg" style="height:100%;width:100%;color:red;font-size:large;">MAR 07 - Leads panel updates</label>
		</center>
	</div>
	</div>
	<div id="backgroundPopup"></div>	
	
	<div id="menu">
		<div id="logo">
			<img src="images/CoM.png" style="height:35px; width:185px;"></img>
		</div>
	</div>
	<div id="footer">
      	<a href="#" onClick="JavaScript:openFileList();" id="mSignin" title="Signin")><img src="images/menu_login.png" border=0></img> Login</a>
	</div>
	</body>
</html>