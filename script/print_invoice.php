<?php
require_once('../fpdf/fpdf.php');
include("../backend/invoice.php");

class PDF extends FPDF
{
	
	var $daysInMonth = array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
	var $colwidth = array(30,90,30,10,30);
	var $colwidthb = array(130,35,25);
	var $colwidthc = array(70,60,60);
	var $col=0;
	
	var $connection,$header,$result,$data,$cID,$sID,$coName,$tItem,$tPrice,$arAmt,$cSize,$gTotal,$cFName,$cLName,$session,$date,$date1,$server,$paybyString,$mpay,$payPo,$quote;
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
			$this->fetch_value_if_exists($base,'quote'),
			$this->fetch_value_if_exists($base,'inSID'),
			$this->fetch_value_if_exists($base,'inCID'),
			$this->fetch_value_if_exists($base,'inFName'),
			$this->fetch_value_if_exists($base,'inLName'),
			$this->fetch_value_if_exists($base,'inSession'),
			$this->fetch_value_if_exists($base,'inServer'),
			$this->fetch_value_if_exists($base,'inDate'),
			$this->fetch_value_if_exists($base,'coName'),
			$this->fetch_value_if_exists($base,'csize'),
			$this->fetch_value_if_exists($base,'arAmt'),
			$this->fetch_value_if_exists($base,'mpay'),
			$this->fetch_value_if_exists($base,'poStr'),
			$this->fetch_value_if_exists($base,'paybyString')
			
			//$this->fetch_value_if_exists($base,'schSubmit')
		);	
	}
	
	function SetPath($s,$d) {;
		$path = 'D:\\Invoice\\';
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
		list($this->quote,$this->sID,$this->cID,$this->cFName,$this->cLName,$this->session,
			$this->server,$cdate,$this->coName,$csize,$this->arAmt,$this->mpay,
			$this->poStr,$this->paybyString) = $this->get_parameters($_GET);
		//print $cID . "/" . $fname . "/" . $lname . "/" . $ses . "/" . $ser;
		//$time = getdate(time());
		$time = strtotime($cdate);
		//print $time;
		$date = date("D, M/d/Y",$time);
		//print $date;
		//$this->cID = $cID;
		//$this->sID = $sID;
		//$this->coName = $coName;
		//$this->arAmt = $arAmt;
		//$this->mpay = $mpay;
		//$this->paybyString = $paybyString;
		//$this->cFName = $fname;
		//$this->cLName = $lname;
		//$this->session = $ses;
		//$this->server = $ser;
		$this->date = $date;
		$this->date1 = $time;
		if($csize) {
			$this->cSize = $csize;
		}
		$this->connection = mysql_connect("localhost","SQL","pos");
		if($this->connection) {
			$sql = "SELECT tblShoppingCart.scPUPC AS upc,".
			"tblShoppingCart.scSku AS sku,".
			"tblShoppingCart.scPDescription AS description,".
			"tblShoppingCart.scColour AS colour,".
			"tblShoppingCart.scPrice AS uprice,".
			"tblShoppingCart.scTotal AS tprice,".
			"tblShoppingCart.scProdCode AS prodCode,".
			"tblShoppingCart.scTax1 AS tax1,".
			"tblShoppingCart.scTax2 AS tax2,".
			"tblShoppingCart.scEcoFee AS ecofee,".
			"tblShoppingCart.scNote AS note,".
			"tblShoppingCart.scTint AS tint,".
			"tblShoppingCart.scTintNote AS tintnote,".
			"tblShoppingCart.scTintNote AS csize,".
			"tblShoppingCart.scQty AS qty ".
			"FROM pos.tblShoppingCart WHERE scInvcNum='$this->session';";
			//print $sql;
			$this->result = mysql_query($sql,$this->connection);
			while($row = mysql_fetch_assoc($this->result)) {
				//print_r($row);
				$item = array();
				$item['upc'] = $row['upc'];
				$item['sku'] = $row['sku'];
				$item['prodCode'] = $row['prodCode'];
				$item['description'] = $row['description'];
				$item['colour'] = $row['colour'];
				$item['uprice'] = $row['uprice'];
				$item['qty'] = $row['qty'];
				$item['stotal'] = (int)$row['qty']*(float)$row['uprice'];
				$item['tprice'] = $row['tprice'];
				$item['tax1'] = $row['tax1'];
				$item['tax2'] = $row['tax2'];
				$item['ecofee'] = $row['ecofee'];
				$item['note'] = $row['note'];
				$item['tint'] = $row['tint'];
				$item['tintnote'] = $row['tintnote'];
				
				//print_r($item);
				$result[] = $item;
				$this->EF = (float)$row['ecofee'];
				//$this->PST = 100;
				//$this->GST = 100;
				$this->PST = $this->PST + (float)$row['uprice']*(int)$row['qty']*(float)$row['tax1'];
				$this->GST = $this->GST + (float)$row['uprice']*(int)$row['qty']*(float)$row['tax2'];
				//$this->PST = $this->PST +$row['uprice']."/".$row['qty']."/".$row['tax1'];
				//$this->GST = $row['uprice']."/".$row['qty']."/".$row['tax2'];
				//$this->GST += (float)$row['uprice']*(int)$row['qty']*(int)$row['tax2'];
				//$this->GST += number_format(((float)$row['uprice'])*((int)$row['qty'])*((int)$row['tax2']),2,'.','');
			}
			/*$sql2 = "SELECT cTax1,cTax2,cEcoFee FROM pos.tblCustomer WHERE cID='$this->cID';";
			//echo $sql;
			$tr = mysql_query($sql2,$this->connection);
			if($row = mysql_fetch_row($tr)) {
				
			}*/
		}
		return $result;
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
		if($this->connection && ($this->quote != "true")) {
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
		}

	    $newy = $iy;
	    $newx += 50;
		    //$newx = $newx + $colwidthc[$i];
	    $x = $this->GetX();
		$y = $this->GetY();
	    $this->SetXY(175,$y);
	    $this->SetFont('Arial','',8);
	    $this->SetTextColor(0);
	    $this->Cell(70,6,'P:'.$result['loc']['phone'].' - F:'.$result['loc']['fax'],0,1,'C',false);
	    $this->SetFillColor(255,255,255);
	    $this->SetDrawColor(102,0,0);
	    $this->SetLineWidth(1);
	    $this->Line(10,47,200,47);
	    $this->Ln(0);
	    $title = array('UPC/Sku','Product/Colour','Unit $','QTY','Price');
	    	$this->SetTextColor(0,0,255);
	    	$this->SetFont('Arial','B',11);
	    	$this->SetLineWidth(0);
	    	$this->Cell($this->colwidth[0],6,$title[0],0,0,'L',false);
	    	$this->Cell($this->colwidth[1],6,$title[1],0,0,'L',false);
	    	$this->Cell($this->colwidth[2],6,$title[2],0,0,'C',false);
	    	$this->Cell($this->colwidth[3],6,$title[3],0,0,'R',false);
	    	$this->Cell($this->colwidth[4],6,$title[4],0,0,'R',false);
	    $this->Ln(6);
	    $this->SetLineWidth(0.3);
	    $this->Line(10,53,200,53);
	}
	
	function Middle($data) {
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
		foreach($data as $row) {
			$this->Cell($this->colwidth[0],6,$row['upc'],$cellborder,0,'L',$fill);
			if($this->cSize != "-1") {
	    		$this->Cell($this->colwidth[1],6,$row['description']."[".$this->cSize."]",$cellborder,0,'L',$fill);
			} else {
				$this->Cell($this->colwidth[1],6,$row['description'],$cellborder,0,'L',$fill);
			}
	    	$this->Cell($this->colwidth[2],6,"$".$row['uprice'],$cellborder,0,'C',$fill);
	    	$this->Cell($this->colwidth[3],6,$row['qty'],$cellborder,0,'C',$fill);
	    	$this->tItem += $row['qty'];
	    	$this->Cell($this->colwidth[4],6,"$".$row['stotal'],$cellborder,1,'R',$fill);
	    	$this->tPrice += $row['stotal'];
	    	$this->Cell($this->colwidth[0],5,":".$row['prodCode'],$cellborder,0,'L',$fill);
	    	if($row['note'] != "") {
	    		$this->Cell($this->colwidth[1],5,$row['colour']."-".$row['note'],$cellborder,0,'L',$fill);
	    	} else {
	    		$this->Cell($this->colwidth[1],5,$row['colour'],$cellborder,0,'L',$fill);
	    	}
	    	$this->SetTextColor(0,153,102);
	    	$this->SetFont('Arial','B',11);
	    	$this->Cell($this->colwidth[2],5,'EcoFee(GST)',$cellborder,0,'C',$fill);
	    	$this->SetTextColor(0,0,0);
	    	$this->SetFont('Arial','',11);
	    	$this->Cell($this->colwidth[3],5,'',$cellborder,0,'C',$fill);
	    	$this->Cell($this->colwidth[4],5,"$".$row['ecofee'],$cellborder,0,'R',$fill);
	    	$this->EcoFee += $row['ecofee'];
	    	
	    	//$fill = !$fill;
	    	$this->Ln(6);
		}
	}
	
	//Page footer
	function Footer() {
		$this->SetY(-75);
		$this->SetFont('Arial','B',9);
		$this->SetTextColor(0,0,255);
		$this->Cell(20,5,"Payment By",$cellborder,0,'L');
		$this->SetTextColor(0,0,0);
		$total = $this->tPrice + $this->EF + $this->PST + $this->GST;
		if($this->mpay != "1") {
			$this->Cell(40,5,"Multiple $".number_format($total,2,'.',''),$cellborder,0,'L');
			$this->Cell(60,5,$this->paybyString,$cellborder,1,'L');
		} else {
			$this->Cell(40,5,$this->paybyString,$cellborder,1,'L');
		}
		if($this->poStr) {
			$this->Cell(140,5,"PO: ".$this->poStr,$cellborder,0,'R');
		}
		$this->SetY(-65);
		$this->SetFont('Arial','',9);
		$this->Cell(70,5,"Thank you for shopping at Colours of Maple.",$cellborder,0,'C');
		$this->Cell(20,5,"Total Items",$cellborder,0,'C');
		$this->Cell(41,5,"Picked up by",$cellborder,1,'C');
		$this->Cell(70,5,"CHANGE THE WAY YOU SEE COLOUR",$cellborder,0,'C');
		$this->Cell(32,5,"_______",$cellborder,0,'R');
		$this->SetX(125);
		$this->Cell(70,5,"___________________________________",$cellborder,0,'R');
		$this->SetY(-50);
		$this->SetFillColor(255,255,255);
	    $this->SetDrawColor(102,0,0);
	    $this->SetLineWidth(1);
	    //$this->Line(10,47,200,47);
	    $this->Line(10,247,200,247);
	    $this->Ln(2);
	    $cellborder = 0;
	    // Prints the Disclaimer
	    $disclaimer = array("Total Items $this->tItem",'The Government of Ontario implemented an Eco Fee to assist in the recycling program for Paint',
	    'Products. Only GST is applied for this fee.',
	    '',
	    '*All supplies are property of Colours of Maple until Paid in Full',
	    '2% Monthly Interest on Accounts over 30 days',
	    'Check all Colours before Applying',
	    'No Refunt or Exchange on the Tinted Paints.',
	    'For MSDS and Surface Prep Data Sheets, Visit www.coloursofmaple.com',
	    'GST #898358221RT0001');
	    $ix = $this->GetX();
	    $iy = $this->GetY();
	    $this->SetFont('Arial','B',11);
	    $i = 0;
	    $this->SetXY($ix,$iy - 2);
		foreach($disclaimer as $item) {
			if($i == 0) {
				$this->MultiCell($this->colwidthb[0],7,$item,$cellborder,'C',false);
			} else {
				$this->SetFont('Arial','',6);		
		    	$this->MultiCell($this->colwidthb[0],3,$item,$cellborder,'L',false);
			}
		    $i++;
		}
		// Prints the Totals
		$totals = array('Sub Total','Eco Fee Total','PST','GST','Total');
		$this->SetFont('Arial','B',11);
		$this->SetXY($ix+$this->colwidthb[0],$iy - 2);
		$y = $iy - 2;
		foreach($totals as $item) {
			if($item == "Eco Fee Total") {
				$this->SetTextColor(168,168,168);
			} else {
				$this->SetTextColor(0,0,0);
			}
		    $this->MultiCell($this->colwidthb[1],6,$item,$cellborder,'R',false);
		    $y = $y+6;
		    $this->SetXY($ix+$this->colwidthb[0],$y);
		}
		//Prints the Fees
		//$EF = 0.02*$this->tPrice;
		//$EF = number_format(0.02*$this->tPrice,2,'.','');
		//$GST = number_format($this->GST*$this->tPrice,2,'.','');
		//$PST = number_format($this->PST*$this->tPrice,2,'.','');
		//$PST = $this->PST*$this->tPrice;
		$total = $this->tPrice + $this->EF + $this->PST + $this->GST;
		$totals = array(number_format($this->tPrice,2,'.',''),number_format($this->EF,2,'.',''),number_format($this->PST,2,'.',''),number_format($this->GST,2,'.',''),number_format($total,2,'.',''));
		$this->SetFont('Arial','B',11);
		$this->SetXY($ix+$this->colwidthb[0]+$this->colwidthb[1],$iy - 2);
		$y = $iy - 2;
		foreach($totals as $item) {
		    $this->MultiCell($this->colwidthb[2],6,"$".$item,$cellborder,'R',false);
		    $y = $y+6;
		    $this->SetXY($ix+$this->colwidthb[0]+$this->colwidthb[1],$y);
		}
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
	    $this->Ln(6);
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
$pdf->Middle($data);
$path = $pdf->SetPath($pdf->session,$pdf->date1);
$pdf->Output($path,"F");
$pdf->Output();
?>
