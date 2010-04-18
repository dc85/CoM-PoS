<?php
// @ error reporting setting  (  modify as needed )
ini_set("display_errors", 1);
error_reporting(E_ALL);
/*
//@ explicity start session  ( remove if needless )
session_start();

//@ if logoff
if(!empty($_GET['logoff'])) { $_SESSION = array(); }

//@ is authorized?
if(empty($_SESSION['exp_user']) || @$_SESSION['exp_user']['expires'] < time()) {
	header("location:../src/login.html");	//@ redirect 
} else {
	$_SESSION['exp_user']['expires'] = time()+(30*60);	//@ renew 45 minutes
}*/	
?>

<html>
	<head>
		<title>Calculator</title>
		<link href="../css/calculator.css" rel="stylesheet" type="text/css" />
		<script src="../js/jquery.js" type="text/javascript"></script>
		<script src="../js/menu.js" type="text/javascript"></script>
		<script src="../js/jquery.hotkeys.js" type="text/javascript"></script>
		<script src="../js/general.js" type="text/javascript"></script>
		<script src="../js/calculator.js" type="text/javascript"></script>
		<script type="text/javascript">
			function setFocus() {
				document.getElementById('wproomorwall').focus();
			}
		</script>
	</head>
	<body onLoad="setFocus();">
		<div id="sidemenu">
			<div id="logo">
				<img src="../images/CoM-vert.png" style="height:185px; width:35px;"></img>
			</div>
		</div>
		<div id="main">
			<div id="wallpaper">
				<h1>Calculate Wallpaper Rolls</h1>
				<table>
					<tr>
						<td><b>Room/Wall</b></td>
						<td>
							<select name="wproomorwall" size="1">
								<option value="def1">Wall</option>
								<option value="def2">Room</option>
							</select>
						</td>
					</tr>
					<tr>
						<td><b>Wall Width ft.</b></td>
						<td><input type="text" name="wpwallwidth" size="10" value="0.00"></input></td>
					</tr>
					<tr>
						<td><b>Wall Length ft.</b></td>
						<td><input type="text" name="wpwalllength" size="10" value="0.00"></input></td>
					</tr>
					<tr>
						<td><b>Wall Height ft.</b></td>
						<td><input type="text" name="wpwallheight" size="10" value="0.00"></input></td>
					</tr>
					<tr>
						<td><b>Doors</b></td>
						<td>
							<select name="wpdoors" size="1">
								<option value="1">1</option>
								<option value="2">2</option>
								<option value="3">3</option>
								<option value="4">4</option>
								<option value="5">5</option>
							</select>
						</td>
					</tr>
					<tr>
						<td><b>Windows</b></td>
						<td>
							<select name="wpwindows" size="1">
								<option value="1">1</option>
								<option value="2">2</option>
								<option value="3">3</option>
								<option value="4">4</option>
								<option value="5">5</option>
								<option value="6">6</option>
								<option value="7">7</option>
								<option value="8">8</option>
								<option value="9">9</option>
								<option value="10">10</option>
								<option value="11">11</option>
								<option value="12">12</option>
								<option value="13">13</option>
								<option value="14">14</option>
								<option value="15">15</option>
							</select>
						</td>
					</tr>
					<tr>
						<td><b>Pattern Repeat</b></td>
						<td>
							<select name="wppattern" size="1">
								<option value="pattern1">0-6</option>
								<option value="pattern2">7-12</option>
								<option value="pattern3">13-18</option>
								<option value="pattern4">19-23</option>
							</select>
						</td>
					</tr>
					<tr>
						<td><b>Leeway</b></td>
						<td>
							<select name="wpleeway" size="1">
								<option value="leeway0">None</option>
								<option value="leeway1">5%</option>
								<option value="leeway2">10%</option>
								<option value="leeway3">20%</option>
								<option value="leeway4">30%</option>
								<option value="leeway5">40%</option>
								<option value="leeway6">50%</option>
							</select>
						</td>
					</tr>
					<tr>
						<td><br></br></td>
						<td><br></br></td>
					</tr>
					<tr>
						<td><b>Room SqFt</b></td>
						<td><input type="text" name="wproomsqft" size="10"></input></td>
					</tr>
					<tr>
						<td><b>Rolls Needed</b></td>
						<td><input type="text" name="wprollsneeded" size="10"></input></td>
					</tr>
					<tr>
						<td><input type="button" name="wpresetwallpaper" value="Reset" style="width=100;" onclick="JavaScript:resetWallpaper();"></input></td>
						<td><input type="button" name="wpcalculatewallpaper" value="Calculate" style="width=100;" onclick="JavaScript:calculateWallpaper();"></input></td>
					</tr>
				</table>
			</div>
			<div id="paint">
				<h1>Calculate Paint (Gal)</h1>
				<table>
					<tr>
						<td><b>Room/Wall</b></td>
						<td>
							<select name="paintroomorwall" size="1">
								<option value="def1">Wall</option>
								<option value="def2">Room</option>
							</select>
						</td>
					</tr>
					<tr>
						<td><b>Wall Width ft.</b></td>
						<td><input type="text" name="paintwallwidth" size="10" value="0.00"></input></td>
					</tr>
					<tr>
						<td><b>Wall Length ft.</b></td>
						<td><input type="text" name="paintwalllength" size="10" value="0.00"></input></td>
					</tr>
					<tr>
						<td><b>Wall Height ft.</b></td>
						<td><input type="text" name="paintwallheight" size="10" value="0.00"></input></td>
					</tr>
					<tr>
						<td><b>Doors</b></td>
						<td>
							<select name="paintdoors" size="1">
								<option value="1">1</option>
								<option value="2">2</option>
								<option value="3">3</option>
								<option value="4">4</option>
								<option value="5">5</option>
							</select>
						</td>
					</tr>
					<tr>
						<td><b>Windows</b></td>
						<td>
							<select name="paintwindows" size="1">
								<option value="1">1</option>
								<option value="2">2</option>
								<option value="3">3</option>
								<option value="4">4</option>
								<option value="5">5</option>
								<option value="6">6</option>
								<option value="7">7</option>
								<option value="8">8</option>
								<option value="9">9</option>
								<option value="10">10</option>
								<option value="11">11</option>
								<option value="12">12</option>
								<option value="13">13</option>
								<option value="14">14</option>
								<option value="15">15</option>
							</select>
						</td>
					</tr>
					<tr>
						<td><b>Pattern Repeat</b></td>
						<td>
							<select name="paintpattern" size="1">
								<option value="pattern1">0-6</option>
								<option value="pattern2">7-12</option>
								<option value="pattern3">13-18</option>
								<option value="pattern4">19-23</option>
							</select>
						</td>
					</tr>
					<tr>
						<td><b>Leeway</b></td>
						<td>
							<select name="paintleeway" size="1">
								<option value="leeway0">None</option>
								<option value="leeway1">5%</option>
								<option value="leeway2">10%</option>
								<option value="leeway3">20%</option>
								<option value="leeway4">30%</option>
								<option value="leeway5">40%</option>
								<option value="leeway6">50%</option>
							</select>
						</td>
					</tr>
					<tr>
						<td><br></br></td>
						<td><br></br></td>
					</tr>
					<tr>
						<td><b>Room SqFt</b></td>
						<td><input type="text" name="paintroomsqft" size="10"></input></td>
					</tr>
					<tr>
						<td><b>Gallons Needed</b></td>
						<td><input type="text" name="paintgallonsneeded" size="10"></input></td>
					</tr>
					<tr>
						<td><b>Primer Needed</b></td>
						<td><input type="text" name="paintprimerneeded" size="10"></input></td>
					</tr>
					<tr>
						<td><input type="button" name="paintresetwallpaper" value="Reset" style="width=100;" onclick="JavaScript:resetPaint();"></input></td>
						<td><input type="button" name="paintcalculatewallpaper" value="Calculate" style="width=100;" onclick="JavaScript:calculatePaint();"></input></td>
					</tr>
				</table>
			</div>
		</div>
		<div id="footer">
			<table><tr>
				<td></td>
				<!-- <td><a style="border: 0; width:110px;" href="#" onClick="JavaScript:saveChanges();" title="Add a new staff into the database"><img src="../images/menu_savechanges.png"  border=0></img> Save Changes</a></td> -->
			</tr></table>
		</div>
	</body>
	
</html>