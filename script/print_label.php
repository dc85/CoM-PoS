<?php
//define('FPDF_FONTPATH', 'font/');
include("../backend/customer.php");
require('PDF_Label.php');

/*------------------------------------------------
To create the object, 2 possibilities:
either pass a custom format via an array
or use a built-in AVERY name
------------------------------------------------*/

// Example of custom format
//$pdf = new PDF_Label(array('paper-size'=>'A4', 'metric'=>'mm', 'marginLeft'=>1, 'marginTop'=>1, 'NX'=>4, 'NY'=>9, 'SpaceX'=>0, 'SpaceY'=>0, 'width'=>99, 'height'=>38, 'font-size'=>14));
$sql = "";
// Standard format
if(isset($_GET['month'])) {
	$month = (int)$_GET['month'] + 1;
	$sql = "SELECT cFirstN,cLastN,cUnitH,cAdrHome,".
	"cCityH,tblProvince.pAbbr AS cProvH,cZipH,week(cDoB) AS weekNum ".
	"FROM pos.tblCustomer ".
	"INNER JOIN pos.tblProvince ON cProvH=pID ".
	"WHERE MONTH(cDoB)=$month ORDER BY weekNum;";	
} else if(isset($_GET['cids'])) {
	$cids = $_GET['cids'];
	$sql = "SELECT cFirstN,cLastN,cUnitH,cAdrHome,".
	"cCityH,tblProvince.pAbbr AS cProvH,cZipH,week(cDoB) AS weekNum ".
	"FROM pos.tblCustomer ".
	"INNER JOIN pos.tblProvince ON cProvH=pID ".
	"WHERE cID IN ($cids) ORDER BY weekNum;";
	//echo $sql;
} else if(isset($_GET['days'])) {
	$curdate = date("Y-m-d");
	$term = (int)$_GET['days'];
	$expdate = date( "Y-m-d", mktime(0, 0, 0, date("m"), date("d")-$term, date("y")) );
	//$cids = $_GET['cids'];
	$sql = "SELECT cFirstN,cLastN,cUnitH,cAdrHome,".
	"cCityH,tblProvince.pAbbr AS cProvH,cZipH,week(cDoB) AS weekNum ".
	"FROM pos.tblInvoice ".
	"INNER JOIN pos.tblCustomer ON cID=iCustID ".
	"INNER JOIN pos.tblProvince ON cProvH=pID ".
	"WHERE iCrtDate >= '$expdate' ".
	"ORDER BY weekNum;";
}  

//$month = 5;
$data = array();

//print $sql;
$weekNum = "";
if($result = @mysql_query($sql)) {
	while($row = @mysql_fetch_assoc($result)) {
		if(isset($_GET['month'])) {
			if($row['weekNum'] != $weekNum) {
				$weekNum = $row['weekNum'];
				$nextWeek = array("","Start Week -".$weekNum." -->","");
				$data[] = $nextWeek;
				$line = array();
				$line[] = $row['cLastN']." ".$row['cFirstN'];
				$adr = "";
				if(!empty($row['cUnitH']) && !empty($row['cAdrHome'])) {
					$adr .= $row['cUnitH']." ".$row['cAdrHome'];
				} else if(empty($row['cUnitH']) && !empty($row['cAdrHome'])) {
					$adr .= $row['cAdrHome'];
				} else {
					$adr .= $row['cUnitH'];
				}
				$line[] = $adr;
				$line[] = $row['cCityH']." ".$row['cProvH'].", ".$row['cZipH'];
				$data[] = $line;
			} else {
				$line = array();
				$line[] = $row['cLastN']." ".$row['cFirstN'];
				$adr = "";
				if(!empty($row['cUnitH']) && !empty($row['cAdrHome'])) {
					$adr .= $row['cUnitH']." ".$row['cAdrHome'];
				} else if(empty($row['cUnitH']) && !empty($row['cAdrHome'])) {
					$adr .= $row['cAdrHome'];
				} else {
					$adr .= $row['cUnitH'];
				}
				$line[] = $adr;
				$line[] = $row['cCityH']." ".$row['cProvH'].", ".$row['cZipH'];
				$data[] = $line;
			}
		} else if(isset($_GET['cids']) || isset($_GET['days'])) {
				$line = array();
				$line[] = $row['cLastN']." ".$row['cFirstN'];
				$adr = "";
				if(!empty($row['cUnitH']) && !empty($row['cAdrHome'])) {
					$adr .= $row['cUnitH']." ".$row['cAdrHome'];
				} else if(empty($row['cUnitH']) && !empty($row['cAdrHome'])) {
					$adr .= $row['cAdrHome'];
				} else {
					$adr .= $row['cUnitH'];
				}
				$line[] = $adr;
				$line[] = $row['cCityH']." ".$row['cProvH'].", ".$row['cZipH'];
				$data[] = $line;
		}

	}
} else {
	print mysql_error()."\n";
}

if(!isset($_GET['avery']) || empty($_GET['avery'])) {
	$l = "5160";
} else {
	$l = $_GET['avery'];
}
$pdf = new PDF_Label($l);

$pdf->AddPage();

// Print labels
foreach($data as $rec) {
	//$text = sprintf("%s\n%s\n%s\n",$rec[0],$rec[1],$rec[2]);
	//$text = $data;
	$pdf->Add_Label($rec);
}

$pdf->Output();
?>
