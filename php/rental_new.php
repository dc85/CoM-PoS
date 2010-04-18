<?php
// @ error reporting setting  (  modify as needed )
ini_set("display_errors", 1);
error_reporting(E_ALL);
include("../backend/methods.php");
include("../backend/db_methods.php");
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
$t = getdate(time());
//$t = strtotime($today);
$rinvoice = "T".date("Y",$t[0]).date("z",$t[0]).date("Hms",$t[0]);
?>

<html>
	<head>
		<title>New Rental</title>
		<link href="../css/rental_new.css" rel="stylesheet" type="text/css" />
		<link href="../css/datepicker.css" rel="stylesheet" type="text/css" />
		<script src="../js/jquery.js" type="text/javascript"></script>
		<script src="../js/jquery.autocomplete.js" type="text/javascript"></script>
		<script src="../js/date.js" type="text/javascript"></script>
		<script src="../js/jquery.bgiframe.min.js" type="text/javascript"></script>
		<script src="../js/jquery.datePicker.js" type="text/javascript"></script>
		<script src="../js/general.js" type="text/javascript"></script>
		<script src="../js/rental.js" type="text/javascript"></script>
		<script type="text/javascript">

		</script>
	</head>
	<body>
		<div id="sidemenu">
			<div id="logo">
				<img src="../images/CoM-vert.png" style="height:185px; width:35px;"></img>
			</div>
		</div>
		<div id="hiddenFrame">
			<iframe name="hiddenFrame">
				
			</iframe>
		</div>
		<div id="main">
			<table>
				<thead>
					<tr>
						<th class="title" style="color: #ffffff; background-color: #000000;">New
						Rental</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<th class="normal"><b>Invoice</b></th>
						<th class="normal"><label id="new_rInvoice"><?php print $rinvoice;?></label></th>
					</tr>
					<tr>
						<th class="normal"><b>Start Date</b></th>
						<th class="normal" >
						<input name="datePick" id="start-date" class="date-pick" /></th>
					</tr>
					<tr>
						<th class="normal"><b>End Date</b></th>
						<th class="normal">
						<input name="datePick" id="end-date" class="date-pick" /></th>
					</tr>
					<tr>
						<th class="normal"><b>Item UPC</b></th>
						<th class="normal"><input type="text" id="new_rUPC" size=30 onkeyup="lookup(this.value);" onblur="fill();"></input>
						<label for="new_rUPC" style="color: #ccccff;">Enter item UPC</label>
						<div class="suggestionsBox" id="suggestions" style="display: none;">
							<div class="suggestionList" id="autoSuggestionsList">
								
							</div>
						</div>
						</th>
					</tr>
					<tr>
						<th class="normal"><b>Item Type</b></th>
						<th class="normal"><input type="text" id="new_rPType" size=30></input></th>
					</tr>
					<tr>
						<th class="normal"><b>Item Description</b></th>
						<th class="normal"><input type="text" id="new_rPDescription" size=75></input></th>
					</tr>
					<tr>
						<th class="normal"><b>&nbsp;</b></th>
					</tr>
					<tr>
						<th class="normal"><b>Customer ID/Number</b></th>
						<th class="normal"><input type="text" id="new_rcID" size=25></input></th>
					</tr>
					<tr>
						<th class="normal"><b>Last Name</b></th>
						<th class="normal"><input type="text" id="new_rLastN" size=25></input></th>
					</tr>
					<tr>
						<th class="normal"><b>First Name</b></th>
						<th class="normal"><input type="text" id="new_rFirstN" size=25></input></th>
					</tr>
					<tr>
						<th class="normal"><b>Phone Number</b></th>
						<th class="normal"><input type="text" id="new_rPhone" size=25></input></th>
					</tr>
					<tr>
						<th class="normal"><b>Deposit Number</b></th>
						<th class="normal"><input type="text" id="new_rDID" size=25></input></th>
					</tr>
					<tr>
						<th class="normal"><b>Deposit ID Type</b></th>
						<th class="normal"><input type="text" id="new_rDIDType" size=25></input></th>
					</tr>
					<tr>
						<th class="normal"><b>Deposit Amount</b></th>
						<th class="normal"><input type="text" id="new_rDAmt" size=25></input></th>
					</tr>
					<tr>
						<th class="normal"><b>Deposit Type</b></th>
						<th class="normal"><input type="text" id="new_rDType" size=25></input></th>
					</tr>
					<tr>
						<th class="normal"><b>Store</b></th>
						<th class="normal">
							<?php
								$val_array = get_store_names(); 
								print make_rentaldropdown_list("","new_rStore","",$val_array,"Supplier List","onchange=\"JavaScript: alert('TEST')\"");
							?>		
						</th>
					</tr>
					<tr>
						<th class="normal"><b>Staff</b></th>
						<th class="normal">
							<select id="new_rStaff" size="1">
								<option value="opt1"></option>
								<option value="opt2">SELECT STORE</option>
							</select>
						</th>
					</tr>
				</tbody>
			</table>
			<br></br>
			<center>
				<input type="button" value="Add Rental" onclick="JavaScript: submitRent();"></input>
				<input type="button" value="Clear All" onclick="JavaScript: clearAll();"></input>
			</center>
		</div>
		<div id="footer">
			<table><tr>
				<td></td>
				
			</tr></table>
		</div>
	</body>
	
</html>