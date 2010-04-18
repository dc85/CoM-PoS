<?php
require_once('../fpdf/fpdf.php');
include('../backend/db_methods.php');
include('../backend/methods.php');

class PDF extends FPDF {
	var $daysInMonth = array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
	var $col=0;
	
	var $tablewidths;
	var $connection;
	var $header;
	var $staffid;
	var $staff;
	var $data;
	var $year;
	var $month;
	var $days;
	var $totals = array();
		
	function create_timetable_header() {
		$year = $_GET['ttYear'];
		$month = $_GET['ttMonth'];
		$day = $_GET['ttDay'];
		$days = $this->getDaysInMonth($year,$month);
		$this->days=$days;
		$this->month=$month;
		$this->year=$year;
		$result = array();
    	$result[] = "Staff";
		for($i=1;$i<$days;$i++) {
			$result[] = $year . "-" . $month . "-" . $i;
		}
		return $result;
	}
	
    function getDaysInMonth($y,$m) {
    	$year = (int)$y;
    	$month = (int)$m;
    	//list($year, $month) = $this->LoadParameter($_POST);
        if ($month < 1 || $month > 12) {
            return 0;
        }
   
        $d = $this->daysInMonth[$month - 1];
   
        if ($month == 2) {
            // Check for leap year
            // Forget the 4000 rule, I doubt I'll be around then...
        
            if ($year%4 == 0) {
                if ($year%100 == 0) {
                    if ($year%400 == 0) {
                        $d = 29;
                    }
                } else {
                    $d = 29;
                }
            }
        }
        return $d;
    }
    
    function get_staffid() {
    	if($this->connection) {
			$result = array();
			$arg = "SELECT * FROM pos.tblStaff";
			$rlist = mysql_query($arg,$this->connection);
			while($row = mysql_fetch_row($rlist)) {
				$result[] = $row[0];
				//echo $row[2];
			}
		}
		return $result;
    }
    
    function get_staff() {
    	//echo "GETTING STAFF...";
		$this->connection = mysql_connect("localhost","root","c3chensi");
		if($this->connection) {
			$result = array();
			//$result[] = " ";
			$arg = "SELECT * FROM pos.tblStaff";
			$rlist = mysql_query($arg,$this->connection);
			while($row = mysql_fetch_row($rlist)) {
				$staff = array();
				$staff[] = $row[0];
				$staff[] = $row[3];
				//echo $staff[0] . "," . $staff[1];
				$result[] = $staff;
				//echo $row[2];
			}
		}
		return $result;
    }
    
    function load_ind_totals() {
    	if($this->connection) {
 			$result = array();
 			$result[] .= "Ind. Total";
 			$staff = $this->staff;
 			foreach($staff as $s) {
 				$temp = 0.0;
 				//$date=$year . "-" . $month . "-" . $i;
 				$arg = "SELECT tsShift FROM pos.tblTimeSheet WHERE tsStaff=\"$s[0]\" AND tsStatus IS NULL;";
 				//echo $arg . "<br>";
 				$rlist = mysql_query($arg,$this->connection);
 				while($row = mysql_fetch_row($rlist)) {
 					//echo $row[0];
 					//echo $row[1];
 					//$r = $row[0]+0.0;
 					//echo $r;
 					//$temp = $temp + (float)$row[0];
 					$temp = $temp + $row[0];
 					//echo $row[0];
 					//echo "<br>" . $temp . "<br>";
 					//echo "break<br>";
 				}
 				$result[] .= $temp; 
 			}
 		}
 		return $result;
    }
    
    function load_totals($year,$month,$days) {
 		if($this->connection) {
 			$result = array();
 			$result[] .= "TOTAL";
 			$staff = $this->staff;
 			for($i=1;$i<=$days;$i++) {
 				$temp = 0.0;
 				$date=$year . "-" . $month . "-" . $i;
 				$arg = "SELECT tsShift FROM pos.tblTimeSheet WHERE tsDate=\"$date\" AND tsStatus IS NULL;";
 				//echo $arg . "<br>";
 				$rlist = mysql_query($arg,$this->connection);
 				while($row = mysql_fetch_row($rlist)) {
 					//echo $row[0];
 					//echo $row[1];
 					//$r = $row[0]+0.0;
 					//echo $r;
 					//$temp = $temp + (float)$row[0];
 					$temp = $temp + $row[0];
 					//echo "<br>" . $temp . "<br>";
 					//echo "break<br>";
 				}
 				$result[] .= $temp; 
 			}
 		}
 		return $result;
    }
    
    function LoadStaffSchedule($staff,$year,$month,$days) {
    	if($this->connection) {
			$result = array();
			$r = "";
			//echo $staff[0] . "," . $staff[1];
			$result[] = $staff[1];
			//$r .= $staff[1] . ":";
			$id = $staff[0];
			for($i=1;$i<=$days;$i++) {
				$date=$year . "-" . $month . "-" . $i;
				$arg = "SELECT tsDate,tsXIn,tsXOut FROM pos.tblTimeSheet 
				WHERE tsStaff=$id AND tsDate=\"$date\" AND tsStatus IS NULL;";
				//echo $arg;
				$rlist = mysql_query($arg,$this->connection);
				if($row = mysql_fetch_row($rlist)) {
					//echo "ITEM exists";
					//echo $row[0];
					$result[] = substr($row[1],-8,5) . "-" . substr($row[2],-8,5);
					//$result[] = "X";
					//$r .= "X,";
				} else {
					$result[] = "--";
					//$r .= "--,";
				}
			}
		}
		//echo $r;
		//echo $result[0] . "," . $result[1] . "," . $result[30];
		return $result;
    }
    
	function LoadInfo() {
		$this->connection = mysql_connect('localhost','SQL','pos');
		$this->header = $this->create_timetable_header();
		$this->staff = $this->get_staff();
		//$this->staffid = $this->get_staffid();
		$this->year = $_GET['ttYear'];
		$this->month = $_GET['ttMonth'];
		$this->days = $this->getDaysInMonth($this->year,$this->month);
		$this->totals = $this->load_totals($this->year,$this->month,$this->days);
		$this->itotals = $this->load_ind_totals();
	}
	
	
	function LoadTable() {
		$year = $this->year;
		$month = $this->month;
		$days = $this->days;
		$staff = $this->staff;
		$data = array();
		foreach($staff as $s) {
			//echo $s . "," . $year . "," . $month . "," . $days;
			//echo $s . "," . $s[1];
			$data[] = $this->LoadStaffSchedule($s,$year,$month,$days);
		}
		return $data;
	}
	
	//Simple table
	
	function FormatData($header,$data,$bot,$end) {
		$result = array();
		$width = sizeof($header);
	    $length = sizeof($data);
	    //echo "WIDTH: " . $width . " LENGTH: " . $length;
	    for($i=0;$i<=$width;$i++) {
	    	$temp = array();
	    	$ts = "";
	    	$temp[] = $header[$i];
	    	$ts .= $header[$i];
	    	for($j=0;$j<=$length;$j++) {
	 			$temp[] = $data[$j][$i];
	 			$ts .= $data[$j][$i];
	    	}
	    	$ts.
	    	$temp[] = $bot[$i];
	    	$ts .= $bot[$i] . "<br>";
	    	//echo $ts;
	    	$result[] = $temp;
	    }
	    $result[] = $end;
	    return $result;
	}
	
	function PrintTable($data) {
	    $width = 19;
	    $height = 6;
	    $iy = $this->GetY();
		$ix = $this->GetX();
		$w = $width*2;
	    $h = $height;
	    $this->SetFontSize(9.5);
	    foreach($data as $col) {
	    	//echo $col[0] . "," . $col[1] . "," . $col[2] . ",";
	    	if(($this->GetX()+40)>300) {
		    		$this->SetXY($ix,$iy);
			        $this->AddPage();
			}
	    	for($i=0;$i<sizeof($col);$i++) {
	    		if($i == 0) {
	    			$this->SetFillColor(255,0,0);
				   	$this->SetTextColor(255);
				    $this->SetDrawColor(128,0,0);
				    $this->SetLineWidth(.3);
				    $this->SetFont('','B');
				    $fill=true;
	    		} else if($i%2 == 1) {
	    			$this->SetFillColor(224,235,255);
	    			$this->SetTextColor(0);
	    			$this->SetFont('');
	    			$fill=true;
	    		} else {
	    			$fill=false;
	    		}
	    		$this->Cell($w,$h,$col[$i],1,0,'C',$fill);
			    $y = $this->GetY();
			    $x = $this->GetX();
			    $newy = $y + $h;
			    $newx = $x - $w; 
			    $this->SetY($newy);
			    $this->SetX($newx);
	
	    	}
	    	$x = $this->GetX();
	    	$newx = $x + $w;
	    	$this->SetXY($newx,$iy);
	    		    			$w = $width;
	    			$h = $height;
	    }
	}
	
	function BasicTable($header,$data,$bot)	{
		//echo "GOGOGOGOGO";
		//echo $data[0][0];
	    //Header
	   	$this->SetFillColor(255,0,0);
	   	$this->SetTextColor(255);
	    $this->SetDrawColor(128,0,0);
	    $this->SetLineWidth(.3);
	    $this->SetFont('','B');
	    foreach($header as $col){
	        $this->Cell(40,7,$col,1,0,'C',true);
	    }
	    $this->Ln();
	    //Data
	    $this->SetFillColor(224,235,255);
	    $this->SetTextColor(0);
	    $this->SetFont('');
	    foreach($data as $row) {
	        foreach($row as $col) {
	        	//echo $col . "<br>";
	            $this->Cell(40,6,$col,1,0,'C',$fill);
	    	}
	    	$fill=!$fill;
	        $this->Ln();
	    }
	    foreach($bot as $c) {
	    	$this->Cell(40,6,$c,1,0,'C',$fill);
	    }
	    $this->Ln();
	}
}

$pdf=new PDF('L','mm','A4');
$pdf->AliasNbPages();
$pdf->SetAutoPageBreak(auto,1);
//$pdf->SetAutoPageBreak(TRUE,1);
//Column titles
//$header=array('Country','Capital','Area (sq km)','Pop. (thousands)');
$pdf->LoadInfo();
//$header = $pdf->create_header();
$header = $pdf->header;
$mid = $pdf->LoadTable();
$bot = $pdf->totals;
$end = $pdf->itotals;
$data = $pdf->FormatData($header,$mid,$bot,$end);
$pdf->SetFont('Arial','',14);
$pdf->AddPage();
//$pdf->BasicTable($header,$data,$bot);
$pdf->PrintTable($data);
$pdf->Output('timetable.pdf','I');
?>