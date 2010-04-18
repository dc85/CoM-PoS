<?php 
$date = $_GET['date'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-us" lang="en-us">
 	<head>
	    <title>Test graphs</title>
	    <link href="../css/timesheetgraph.css" rel="stylesheet" type="text/css" />
	 	<script src="../js/jquery.js" type="text/javascript"></script>
	 	<script src="../js/timesheetgraph.js" type="text/javascript"></script> 
		<script src="../jgcharts/jgcharts.js" type="text/javascript"></script>
		<script src="../jgcharts/lib/metadata/jquery.metadata.min.js" type="text/javascript"></script>  
		<script src="../jgcharts/plugins/jgtable/jgtable.js" type="text/javascript"></script>
		<script type="text/javascript">
		
		</script> 
	</head>
	<body onload="JavaScript: getTotals();">
	
		<div id="graph">
		<center>
			<table> 
			  <thead> 
			    <tr> 
			        <th></th> 
			        <th class="serie">Sales</th> 
			        <th class="serie">Target</th> 
			        <th class="serie">S/T %</th> 
			    </tr> 
			  </thead> 
			  <tbody> 
			    <tr> 
			        <th class="serie" style="background-color: #ff0000;">SUNDRIE</th> 
			        <td id="sundrietot">$0.00</td> 
			        <td>$985.00</td> 
			        <td id="sundriepec">0.00%</td> 
			    </tr> 
			    <tr> 
			        <th class="serie" style="background-color: #ff9900;">PAINT</th> 
			        <td id="painttot">$0.00</td> 
			        <td>$6575.00</td> 
			        <td id="paintpec">0.00%</td> 
			    </tr> 
			    <tr> 
			        <th class="serie" style="background-color: #ffff00;">DRAPERY</th> 
			        <td id="draperytot">$0.00</td> 
			        <td>$300.00</td> 
			        <td id="draperypec">0.00%</td> 
			    </tr>
			    <tr> 
			        <th class="serie" style="background-color: #33ff00;">WALLPAPER</th> 
			        <td id="wallpapertot">$0.00</td> 
			        <td>$50.00</td> 
			        <td id="wallpaperpec">0.00%</td> 
			    </tr>
			    <tr> 
			        <th class="serie" style="background-color: #0000ff;">SERVICE</th> 
			        <td id="servicetot">$0.00</td> 
			        <td>$300.00</td> 
			        <td id="servicepec">0.00%</td> 
			    </tr>
			    <tr> 
			        <th class="serie" style="background-color: #cc00cc;">NOCATG</th> 
			        <td id="nocatgtot">$0.00</td> 
			        <td>$1.00</td> 
			        <td id="nocatgpec">0.00%</td> 
			    </tr>
			  </tbody> 
			  </table>
		  </center> 
		</div>
	</body>
</html>