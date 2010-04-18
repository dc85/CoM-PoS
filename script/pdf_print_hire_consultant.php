<?php
require_once('../fpdf/fpdf.php');
//require('../fpdf/fpdf_htmlforms.php');

include("../backend/invoice.php");

define('FPDF_FONTPATH', 'font/');

class PDF extends FPDF
{
	var $daysInMonth = array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
	var $colwidth = array(30,90,30,10,30);
	var $colwidthb = array(130,35,25);
	var $colwidthc = array(70,60,60);
	var $col=0;
	
	var $consultant = array();
	var $customer = array();
	
	var $connection,$header,$result,$data,$date2,$date1,$sID;
	var $EF = 0.00;
	var $GST = 0.00;
	var $PST = 0.00;
	
	function fetch_value_if_exists($base,$name,$default = '') {
		$result = NULL;
		if($base && array_key_exists($name,$base) && sizeof($base[$name]) > 0) {
			$result = $base[$name];
		}
		else if ($default && strlen($default) > 0) {
			$result = $default;
		}
		else if($this->old_values && array_key_exists($name,$this->old_values) && strlen($this->old_values[$name]) > 0) {
			$result = $this->old_values[$name];
		}
		return $result;
	}
	
	function SetPath($s,$d) {
		$hContID = $_GET['hContID'];
		$hCustID = $_GET['hCustID'];
		$hDate = $_GET['hDate'];
		$path = 'D:\\Contracts\\';
		if(file_exists($path)) {

		} else {
			//echo $path;
		    mkdir($path,0775);
		}
		$path .= $hCustID."H".$hContID."O".str_replace("-","",$hDate).".pdf";
		return $path;
	}
	/*
	 * if($this->mpay == "2") {
			$this->Cell(80,5,$this->mpay." ".$this->paybyString,$cellborder,0,'L');
		} else if($this->mpay == "1") {
			$this->Cell(40,5,$this->paybyString,$cellborder,0,'L');
		}
	 */
	function buildQuery() {
		//$today = getdate(time());
		$time = getdate(time());
		$date = date("D, M/d/Y H:i:s",$time[0]);
		$date3 = date("D, M/d/Y",$time[0]);
		$date2 = date("D, M/d/Y",$time[0]);
		$this->date2 = $date2;
		$this->date1 = $date;
		$this->date3 = $date3;
		$this->sID = $_GET['sID'];
		$hContID = $_GET['hContID'];
		$hCustID = $_GET['hCustID'];
		$hDate = $_GET['hDate'];
		$qryDate = strtotime(substr($hDate,4));
		$qDate = date("Y-m-d",$qryDate);
		$this->AddFont('Comic','B','comic.php');

		$this->connection = mysql_connect("localhost","SQL","pos");
		if($this->connection) {
			$cont_sql = "SELECT cFirstN,cLastN,cCoName,cUnitB,cAdrBus,".
				"cCityB,tblProvince.pAbbr AS cProvB,cZipB,cpType1,cPhone1,cpType2,cPhone2,cpType3,".
				"cPhone3,cpType4,cPhone4,cpType5,cPhone5,cE1,cE2,cE3,cE4,cE5,".
				"cE6,cE7,cE8,cE9,cE10 ".
				"FROM pos.tblCustomer ".
				"INNER JOIN pos.tblProvince ON tblCustomer.cProvB=tblProvince.pID ".
				"WHERE tblCustomer.cID=$hContID";
			
			$cust_sql = "SELECT tblCCHire.*,cFirstN,".
				"cLastN,cCoName,cUnitB,".
				"cAdrBus,cCityB,tblProvince.pAbbr AS cProvB,".
				"cZipB,cpType1,cPhone1,".
				"cpType2,cPhone2,cpType3,".
				"cPhone3,cpType4,cPhone4,".
				"cpType5,cPhone5 ".
				"FROM pos.tblCCHire,pos.tblCustomer ".
				"INNER JOIN pos.tblProvince ON tblCustomer.cProvB=tblProvince.pID ".
				"WHERE hCustID=$hCustID ".
				"AND hContID=$hContID ".
				"AND hDate='$qDate' ".
				"AND tblCustomer.cID=hCustID;";
			//print $cont_sql."\n";
			//print $cust_sql;
			$cont = mysql_query($cont_sql,$this->connection);
			$cust = mysql_query($cust_sql,$this->connection);
			$this->consultant = mysql_fetch_assoc($cont);
			$this->customer = mysql_fetch_assoc($cust);
		}
	}
	
	//Page header
	function Header() {
	    $this->Image('..\images\logo_final.gif',20,5,170,15);
	    $this->Ln(13);
	    $this->SetFont('Arial','',15);
		$ix = $this->GetX();
		$newx = $ix;
		$iy = $this->GetY();
		$newy = $iy;
		if($this->connection) {
			$sql = "SELECT tblStore.sUnit AS unit,".
			"tblStore.sAddress1 AS address,".
			"tblStore.sCity AS city,".
			"tblProvince.pAbbr AS prov,".
			"tblStore.sZip AS zip,".
			"tblStore.sWeb AS url,".
			"tblStore.sPhone AS phone,".
			"tblStore.sFax AS fax ".
			"FROM pos.tblStore ".
			"INNER JOIN pos.tblProvince ON tblProvince.pID=tblStore.sProv ".
			"WHERE tblStore.sID='$this->sID';";
			//print $sql;
			$this->result = mysql_query($sql,$this->connection);
			while($row = mysql_fetch_assoc($this->result)) {
				//print_r($row);
				$item = array();
				$item['addr'] = $row['unit']."-".$row['address'];
				$item['city'] = $row['city'].", ".$row['prov']." ".$row['zip'];
				$item['url'] = $row['url'];
				$item['phone'] = $row['phone'];
				$item['fax'] = $row['fax'];
				//print_r($item);
				$result['loc'] = $item;
			}
		}
	    $ary = array($result['loc']['addr'],$result['loc']['city'],$result['loc']['url']);
	    $ary2 = array("","","");
	    $ary3 = array("",$this->date2,"");
	   	$this->SetTextColor(0);
	    foreach($ary as $cel) {
	    	if($cel == 'Invoice to' || substr($cel,0,9) == 'Invoice #') {
	    		$this->SetFont('Arial','B',12);
	    		$this->SetXY($newx,$newy);
		    	$this->MultiCell(70,6,$cel,0,'C',false);
		    	$newy += 6;
	    	} else {
	    		$this->SetFont('Arial','',12);
		    	$this->SetXY($newx,$newy);
		    	$this->MultiCell(70,6,$cel,0,'C',false);
		    	$newy += 6;
	    	}
	    }
	    $newy = $iy;
	    $newx += 70;
	    foreach($ary2 as $cel) {
	    	if($cel == 'INVOICE TO' || substr($cel,0,9) == 'INVOICE #') {
	    		$this->SetFont('Arial','B',12);
	    		$this->SetXY($newx,$newy);
		    	$this->MultiCell(55,6,$cel,0,'C',false);
		    	$newy += 6;
	    	} else {
	    		$this->SetFont('Arial','',12);
		    	$this->SetXY($newx,$newy);
		    	$this->MultiCell(55,6,$cel,0,'C',false);
		    	$newy += 6;
	    	}
	    }
	    $newy = $iy;
	    $newx += 55;
	    foreach($ary3 as $cel) {
	    	if($cel == 'Invoice to' || substr($cel,0,9) == 'Invoice #') {
	    		$this->SetFont('Arial','B',12);
	    		$this->SetXY($newx,$newy);
		    	$this->MultiCell(70,6,$cel,0,'C',false);
		    	$newy += 6;
	    	} else {
	    		$this->SetFont('Arial','',12);
		    	$this->SetXY($newx,$newy);
		    	$this->MultiCell(70,6,$cel,0,'C',false);
		    	$newy += 6;
	    	}
	    }
	    $newy = $iy;
	    $newx += 50;
		    //$newx = $newx + $colwidthc[$i];
	    $x = $this->GetX();
		 $y = $this->GetY();
	    $this->SetXY($x,$y);
	    $this->SetFont('Arial','',8);
	    $this->SetTextColor(0);
	    $this->Cell(70,6,'P:'.$result['loc']['phone'].' - F:'.$result['loc']['fax'],0,1,'C',false);
	    $this->SetFillColor(255,255,255);
	    $this->SetDrawColor(102,0,0);
	    $this->SetLineWidth(1);
	    $this->Line(10,47,200,47);

	    $this->SetLineWidth(1);
	    $this->Line(105,47,105,285);
	    $this->Line(10,285,200,285);
	}
	
	function Middle() {
		$this->Ln(2);
		$this->SetX(50);
		$this->SetFont('Comic','B',15);
		$this->SetTextColor(0,0,255);
		$this->Cell(10,10,"Specialist",0,0,'C');
		$this->SetX(150);
		$this->Cell(10,10,"Customer",0,1,'C');
		$this->SetFont('Arial','',10);
		$this->SetTextColor(0,0,0);
		$this->Cell(90,4,$this->consultant['cLastN'].", ".$this->consultant['cFirstN']." - ".$this->consultant['cCoName'],0,0,'C');
		$this->SetX(110);
		$this->Cell(90,4,$this->customer['cLastN'].", ".$this->customer['cFirstN']." - ".$this->customer['cCoName'],0,1,'C');
		
		$this->Cell(90,4,$this->consultant['cUnitB']." ".$this->consultant['cAdrBus'].", ".$this->consultant['cProvB'],0,0,'C');
		$this->SetX(110);
		$this->Cell(90,4,$this->customer['cUnitB'].", ".$this->customer['cAdrBus'].", ".$this->customer['cProvB'],0,1,'C');
		
		$this->Cell(90,4,$this->consultant['cZipB'],0,0,'C');
		$this->SetX(110);
		$this->Cell(90,4,$this->customer['cZipB'],0,1,'C');
		
		$this->Cell(90,4,$this->findPhone($this->consultant['cpType1'])." ".$this->consultant['cPhone1'],0,0,'C');
		$this->SetX(110);
		$this->Cell(90,4,$this->findPhone($this->customer['cpType1'])." ".$this->customer['cPhone1'],0,0,'C');
		
		$this->Cell(90,4,$this->findPhone($this->consultant['cpType2'])." ".$this->consultant['cPhone2'],0,1,'C');
		$this->SetX(110);
		$this->Cell(90,4,$this->findPhone($this->customer['cpType2'])." ".$this->customer['cPhone2'],0,1,'C');
		
		$this->Cell(90,4,$this->findPhone($this->consultant['cpType3'])." ".$this->consultant['cPhone3'],0,1,'C');
		$this->SetX(110);
		$this->Cell(90,4,$this->findPhone($this->customer['cpType3'])." ".$this->customer['cPhone3'],0,1,'C');
		
		$this->Cell(90,4,$this->findPhone($this->consultant['cpType4'])." ".$this->consultant['cPhone4'],0,1,'C');
		$this->SetX(110);
		$this->Cell(90,4,$this->findPhone($this->customer['cpType4'])." ".$this->customer['cPhone4'],0,1,'C');
		
		$this->Cell(90,4,$this->findPhone($this->consultant['cpType5'])." ".$this->consultant['cPhone5'],0,1,'C');
		$this->SetX(110);
		$this->Cell(90,4,$this->findPhone($this->customer['cpType5'])." ".$this->customer['cPhone5'],0,1,'C');
		
		$this->Ln(5);
		$this->SetFont('Comic','B',12);
		$this->SetTextColor(0,0,255);
		$this->Cell(90,6,"",0,0,'L');
		$this->SetX(110);
		$this->Cell(90,6,"Request work on the following",0,1,'L');
		
		$this->SetFont('Arial','',10);
		$this->SetTextColor(0,0,0);

		$this->Cell(90,4,"",0,0,'L');
		$this->SetX(110);
		if($this->customer['cE1'] != "0") {
			$this->Cell(90,4,"[ Y ] Interior",0,1,'L');
		} else {
			$this->Cell(90,4,"[ N ] Interior",0,1,'L');
		}
		
		$this->Cell(90,4,"",0,0,'L');
		$this->SetX(110);
		if($this->customer['cE2'] != "0") {
			$this->Cell(90,4,"[ Y ] Exterior",0,1,'L');
		} else {
			$this->Cell(90,4,"[ N ] Exterior",0,1,'L');
		}
		
		$this->Cell(90,4,"",0,0,'L');
		$this->SetX(110);
		if($this->customer['cE3'] != "0") {
			$this->Cell(90,4,"[ Y ] Residential",0,1,'L');
		} else {
			$this->Cell(90,4,"[ N ] Residential",0,1,'L');
		}
		
		$this->Cell(90,4,"",0,0,'L');
		$this->SetX(110);
		if($this->customer['cE4'] != "0") {
			$this->Cell(90,4,"[ Y ] Commercial",0,1,'L');
		} else {
			$this->Cell(90,4,"[ N ] Commercial",0,1,'L');
		}		

		$this->Cell(90,4,"",0,0,'L');
		$this->SetX(110);
		if($this->customer['cE5'] != "0") {
			$this->Cell(90,4,"[ Y ] Stain Work",0,1,'L');
		} else {
			$this->Cell(90,4,"[ N ] Stain Work",0,1,'L');
		}
		
		$this->Ln(2);
		
		$this->Cell(90,4,"",0,0,'L');
		$this->SetX(110);
		$this->Cell(90,4,"#Colours ".$this->customer['cNumCols'],0,1,'L');
		
		$this->Cell(90,4,"",0,0,'L');
		$this->SetX(110);
		$this->Cell(90,4,"#Rooms (w.c./kitch) ".$this->customer['cNumRms'],0,1,'L');
		
		$this->Ln(2);
		$this->Cell(90,4,"",0,0,'L');
		$this->SetX(110);
		$this->SetFont('Comic','B',10);
		$this->SetTextColor(0,0,255);
		$this->Cell(45,6,"Prefer Start Date*",0,0,'L');
		$this->SetTextColor(0,0,0);
		$this->Cell(45,6,$this->date3,0,1,'L');
		
		$this->Cell(90,4,"",0,0,'L');
		$this->SetX(110);
		$this->SetFont('Comic','B',10);
		$this->SetTextColor(0,0,255);
		$this->Cell(45,6,"Additional Notes",0,0,'L');
		$this->SetTextColor(0,0,0);
		$this->Cell(45,6,$this->customer['cNote'],0,1,'L');
		
		$this->SetY(-50);
		$this->SetTextColor(255,0,0);
		$this->SetFont('Arial','B',10);
		$this->Cell(90,4,"Please note there is a FEE for consultant visitation.",0,1,'L');
		$this->Cell(90,4,"However, you could be eligible to recieve free paint",0,1,'L');
		$this->Cell(90,4,"samples. Speak to staff for more information.",0,1,'L');
		
		$this->SetY(-30);
		$this->SetTextColor(255,0,0);
		$this->SetFont('Arial','',6);
		$this->Cell(90,4,"*Actual Start Date/Time and Paint Colours will be decided by Customer and Specialist.",0,1,'L');
		$this->Cell(90,4,"Colours of Maple has Expert Painters to Assist with Painting.",0,1,'L');
	}
	
	//Page footer
	function Footer() {
		$this->SetY(-15);
		$this->SetFont('Arial','BI',7);
		$this->SetTextColor(0,0,0);
	   $this->SetX(20);
	   $this->Cell(10,10,$this->date1,0,0,'C');
	   $this->SetX(190);
	   $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
	}
	
	function findPhone($id) {
		switch($id) {
			case 1:
				return "BUSINESS";
				//break;
			case 2:
				return "FAX";
				//break;
			case 3:
				return "HOME";
			case 4:
				return "CELL";
			default:
				return "";
				//break;
		}
	}
}

$pdf=new PDF();
$pdf->buildQuery();

$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Times','',12);
/*for($i=1;$i<=5;$i++)
    $pdf->Cell(0,10,'ITEM STRING'.$i,0,1);*/
$pdf->Middle();
$path = $pdf->SetPath($pdf->session,$pdf->date1);
$pdf->Output($path,"F");
$pdf->Output();
?>
