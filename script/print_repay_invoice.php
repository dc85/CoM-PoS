<?php
require_once('../fpdf/fpdf.php');
include("../backend/invoice.php");

class PDF extends FPDF
{
	var $daysInMonth = array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
	var $colwidth = array(30,90,30,10,30);
	var $colwidthb = array(130,35,25);
	var $colwidthc = array(70,60,60);
	var $colwidthR = array(150,40);
	var $col=0;
	
	var $connection,$amount,$header,$result,$data,$cID,$sID,$coName,$tItem,$tPrice,$gTotal,$cFName,$cLName,$session,$date,$date1,$server,$paybyString,$mpay,$payPo,$items;
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

	function get_parameters($base) {
		return array(
			$this->fetch_value_if_exists($base,'inSID'),
			$this->fetch_value_if_exists($base,'inCID'),
			$this->fetch_value_if_exists($base,'inFName'),
			$this->fetch_value_if_exists($base,'inLName'),
			$this->fetch_value_if_exists($base,'inSession'),
			$this->fetch_value_if_exists($base,'inServer'),
			$this->fetch_value_if_exists($base,'inDate'),
			$this->fetch_value_if_exists($base,'coName'),
			$this->fetch_value_if_exists($base,'inItems'),
			$this->fetch_value_if_exists($base,'mpay'),
			$this->fetch_value_if_exists($base,'poStr'),
			$this->fetch_value_if_exists($base,'amount'),
			$this->fetch_value_if_exists($base,'paybyString')
			
			//$this->fetch_value_if_exists($base,'schSubmit')
		);	
	}
	
	function SetPath($s,$d) {
		$date = date("Y-M",$d);
		$path = 'D:\\Invoice\\'.$date.'\\';
		if(file_exists($path)) {

		} else {
			//echo $path;
		    mkdir($path,0775);
		}
		$path .= $s . ".pdf";
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
		list($this->sID,
		$this->cID,
		$this->cFName,
		$this->cLName,
		$this->session,
		$this->server,
		$cdate,
		$this->coName,
		$this->items,
		$this->mpay,
		$this->poStr,
		$this->amount,
		$this->paybyString) = $this->get_parameters($_GET);
		//print $cID . "/" . $fname . "/" . $lname . "/" . $ses . "/" . $ser;
		//$time = getdate(time());
		$time = strtotime($cdate);
		//print $time;
		$date = date("D, M/d/Y",$time);
		$this->date = $date;
		$this->date1 = $time;
		$this->connection = mysql_connect("localhost","SQL","pos");
		if($this->connection) {
			$this->PST = $this->PST + (float)$row['uprice']*(int)$row['qty']*(float)$row['tax1'];
			$this->GST = $this->GST + (float)$row['uprice']*(int)$row['qty']*(float)$row['tax2'];
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
	    $ary2 = array('INVOICE TO',"$this->cFName, $this->cLName",$this->coName);
	    $ary3 = array("INVOICE # $this->session",$this->date,"Staff - $this->server");
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
	    $this->Ln(0);
	    $title = array('Payment By','Payment $');
    	$this->SetTextColor(0,0,255);
    	$this->SetFont('Arial','B',11);
    	$this->SetLineWidth(0);
    	$this->Cell($this->colwidthR[0],6,$title[0],0,0,'L',false);
    	$this->Cell($this->colwidthR[1],6,$title[1],0,1,'L',false);
    	$this->SetTextColor(0,0,0);
    	$this->SetFont('Arial','',11);
    	if($this->mpay == "S") {
    		$this->SetFont('Arial','B',11);
    		$tag = $this->paybyString." for Invoice(s) - ";
    		$this->Cell($this->colwidthR[0],6,$tag,0,0,'L',false);
    		$this->Cell($this->colwidthR[1],6,$this->amount,0,1,'L',false);
    		$this->SetFont('Arial','',11);
    		$string = $this->items;
    		while(strlen($string) > 60) {
    			$line = substr($string,0,60);
    			$this->Cell($this->colwidthR[0],6,$line,0,1,'L',false);
    			$string = substr($string,60);
    		}
    		$this->Cell($this->colwidthR[0],6,$string,0,0,'L',false);
    		
    	} else if($this->mpay == "M") {
    		$this->SetFont('Arial','B',11);
    		$tag = "MULTIPLE: ".$this->paybyString." for Invoice(s) - ";
    		$this->Cell($this->colwidthR[0],6,$tag,0,0,'L',false);
    		$this->Cell($this->colwidthR[1],6,$this->amount,0,1,'L',false);
    		$this->SetFont('Arial','',11);
    		$string = $this->items;
    		while(strlen($string) > 60) {
    			$line = substr($string,0,60);
    			$this->Cell($this->colwidthR[0],6,$line,0,1,'L',false);
    			$string = substr($string,60);
    		}
    		$this->Cell($this->colwidthR[0],6,$string,0,0,'L',false);
    		
    	}
	    $this->Ln(6);
	    $this->SetLineWidth(0.3);
	    $this->Line(10,53,200,53);
	}
	
	function Middle() {
		//print "TEST";
		//print_r($data);
		$this->SetFont('Arial','',11);
	    $this->SetLineWidth(0);
	   	//$this->SetFillColor(190,190,190);
	   	//$this->SetFillColor(256,256,256);
	   	$this->tItem = 0;
	   	$this->tPrice = 0.00;
	   	$cellborder = 0;
	   	//$fill = true;
		$this->Cell($this->colwidthR[0],6,$row['upc'],$cellborder,0,'L',$fill);
		$this->Cell($this->colwidthR[1],6,$row['upc'],$cellborder,0,'L',$fill);
	}
	
	//Page footer
	function Footer() {
		$this->SetY(-75);
		$d = getdate(time());
		$date = date("F/d/Y",$d[0]);
		$paydate = "Aug/17/2009";
		$final = array("         AS of $date Total OutStanding (incl. previous balance &AR)Pay by $payday",
		"$");
		$this->SetFont('Arial','B',8);
		//$this->SetXY($ix+$this->colwidthb[0]+$this->colwidthb[1]+$this->colwidthb[2],$iy - 2);
		$this->Ln(3);
		$this->SetX($ix);
		//$y = $iy - 2;
		$this->Cell(165,7,$final[0],$cellborder,0,'L',false);
		$this->Cell(25,7,$final[1],$cellborder,0,'R',false);
		// Page end
	    $this->SetY(-15);
	    $this->SetFont('Arial','BI',6);
	    $this->SetX(12);
	    $this->Cell(10,10,"$date",0,0,'C');
	    $this->SetX(190);
	    $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
	}
}

$pdf=new PDF();
$data = $pdf->buildQuery();
$pdf->data = $data;
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Times','',12);
/*for($i=1;$i<=5;$i++)
    $pdf->Cell(0,10,'ITEM STRING'.$i,0,1);*/
//$pdf->Middle();
$path = $pdf->SetPath($pdf->session,$pdf->date1);
$pdf->Output($path,"F");
$pdf->Output();
?>
