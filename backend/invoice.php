<?php
include("db_methods.php");

function create_shoppingcart_header() {
	$row = "";
	$row .= wrap_tag("Free","th");
	$row .= wrap_tag("Product","th");
	$row .= wrap_tag("QTY","th");
	$row .= wrap_tag("Unit Price","th");
	$row .= wrap_tag("Tax1","th");
	$row .= wrap_tag("Tax2","th");
	$row .= wrap_tag("EF","th");
	$row .= wrap_tag("Total","th");
	$row .= wrap_tag("Colour","th");
	$row .= wrap_tag("Note","th");
    return wrap_tag($row,"tr");
}

function create_purchasetable_header() {
	$row = "";
	$row .= wrap_tag("Age","td","class=\"cellTitle\"");
	$row .= wrap_tag("Invoice#","td","class=\"cellTitle\"");
	$row .= wrap_tag("Total","td","class=\"cellTitle\"");
	$row .= wrap_tag("Due","td","class=\"cellTitle\"");
	$row .= wrap_tag("Paid","td","class=\"cellTitle\"");
    return wrap_tag($row,"tr");
}

function create_lookup_header() {
	$row = "";
	$row .= wrap_tag("Date","th");
	$row .= wrap_tag("Age","th");
	$row .= wrap_tag("Invoice#","th");
	$row .= wrap_tag("Note","th");
	$row .= wrap_tag("Owed","th");
    return wrap_tag($row,"tr");
}

function create_outstandingtable_header() {
	$row = "";
	$row .= wrap_tag("Date","th");
	$row .= wrap_tag("Age","th");
	$row .= wrap_tag("Invoice#","th");
	$row .= wrap_tag("Note","th");
	$row .= wrap_tag("OS","th");
    return wrap_tag($row,"tr");
}

function create_outstandingitemstable_header() {
	$row = "";
	$row .= wrap_tag("Product","th");
	$row .= wrap_tag("Qty","th");
	$row .= wrap_tag("Price","th");
	$row .= wrap_tag("Total","th");
    return wrap_tag($row,"tr");
}

function create_payhistory_header() {
	$row = "";
	$row .= wrap_tag("Age","td","class=\"cellTitle\"");
	$row .= wrap_tag("Date","td","class=\"cellTitle\"");
	$row .= wrap_tag("Type","td","class=\"cellTitle\"");
	$row .= wrap_tag("Amount","td","class=\"cellTitle\"");
	$row .= wrap_tag("Note","td","class=\"cellTitle\"");
    return wrap_tag($row,"tr");
}

function create_paid_header() {
	$row = "";
	$row .= wrap_tag("Age","th");
	$row .= wrap_tag("Date","th");
	$row .= wrap_tag("Type","th");
	$row .= wrap_tag("Amount","th");
	$row .= wrap_tag("Note","th");
    return wrap_tag($row,"tr");
}

function get_shoppingcart_values() {
	return array('scIsFree','scPID','scQty','scPrice','scTax1','scTax2','scEcoFee','scTotal','scColour','scNote');
}

function get_purchasetable_values() {
	return array('iInvcdate','iInvcNum','iTotal','iPayment','iActPay');
}

function get_outstandingtable_values() {
	return array('iInvcdate','iInvcAge','iInvcNum','iNote','iDifference');
}

function get_lookuptable_values() {
	return array('iInvcdate','iInvcAge','iInvcNum','iNote','iDifference');
}

function get_outstandingitemstable_values() {
	return array('tProdID','tQty','tPrice','tTotal');
}

function get_paymenthistory_values() {
	return array('pPayDate','pPayDate','pPType','pAmount','pNote');
}

function get_paid_values() {
	return array('pPayDate','pPayDate','pPType','pAmount','pNote');
}

function create_shoppingcart_rows($session) {
	$header_shoppingcart = get_shoppingcart_values(); 
	$sql = "SELECT * FROM pos.tblShoppingCart WHERE tblShoppingCart.scInvcNum='$session';";
	//print $sql;
	$shopping_cart = @mysql_query($sql);
	$result = NULL;
	if($shopping_cart) {
		$result = array();
		$n = 0;
		if(@mysql_data_seek($shopping_cart,0)) {
			while($rec = @mysql_fetch_assoc($shopping_cart)) {
				$row = "";
				$keya = $rec['scID'];
				for($i = 0; $i < sizeof($header_shoppingcart); ++$i) {
					$fdata = $rec[$header_shoppingcart[$i]];
					if($header_shoppingcart[$i] == "scIsFree") {
						if($fdata == "0") {
							$fdata = "NO";
						} else {
							$fdata = "YES";
						}
					} else if($header_shoppingcart[$i] == "scNote") {
						//$fdata = $rec[''];
					}
					//$fdata = htmlspecialchars($fdata);
					//$fdata = preg_replace("/\n/m","<br/>\n",$fdata);
					if($to_mark) {
						$fdata .= "<a name=\"r\"></a>";
					}
					$row .= wrap_tag($fdata,"td");
				}
				//$class = ($selected)?"selectcell":"cell" . ($n % 2);
				$result[] = wrap_tag($row,"tr",
				//"class=\"$class\" ".
				"l1=\"$keya\" ".
				"title=\"".$rec['scPDescription']."\" ".
				"ondblclick=\"return remove_item(this);\"");
				$n++;
			}
		} else {
			
		}
	}
	return $result;
}

function create_purchasetable_rows($cID) {
	$result = NULL;
	//$sql = "SELECT * FROM pos.tblShoppingCart WHERE scCID='$cID' ORDER BY scSNum DESC AND scCommited=1;";
	$sql = "SELECT * FROM pos.tblInvoice WHERE iCustID='$cID' ORDER BY iInvcdate DESC;";
	
	$header_purchasetable = get_purchasetable_values();
	if($purchase_table = @mysql_query($sql)) {
		$result = array();
		//$record = fetch_value_if_exists($_POST,'record3');
		$n = 0;
		if(@mysql_data_seek($purchase_table,0)) {
			while($rec = @mysql_fetch_assoc($purchase_table)) {
				$row = "";
				$tag = 0;
				if($rec['iIsActive'] == "0") {
					$tag = 3;
				} else if($rec['iIsPending'] == "1") {
					$tag = 2;
				} else {
					
				}
				for($i = 0; $i < sizeof($header_purchasetable); ++$i) {
					if($i == 0) {
						$today = getdate(time());
						//$mdate = date("Y-m-d H:i:s",$today[0]);
						//$curs = strtotime($mdate);
						$diff = $today[0] - strtotime($rec['iInvcDate']);
						$dd = round($diff/86400);
						if($dd == 0) {
							$fdata = "Today";
						} else {
							$fdata = $dd . " day(s)";
						}
					} /*else if($i == 4) {
						$fdata = (float)$rec['iTotal'] * (float)$rec['iPayment'];
					} else*/ else {
						$fdata = $rec[$header_purchasetable[$i]];
						$fdata = htmlspecialchars($fdata);
						$fdata = preg_replace("/\n/m","<br/>\n",$fdata);
					}
					if($to_mark) {
						$fdata .= "<a name=\"r\"></a>";
					}
					$row .= wrap_tag($fdata,"td");
				}
				if($tag == 0) {
					$class = ($selected)?"selectcell":"tcell";
				} else if($tag == 2) {
					$class = ($selected)?"selectcell":"tcell0";
				} else if($tag == 3) {
					$class = ($selected)?"selectcell":"tcell1";
				} else {
					
				}
				$result[] = wrap_tag($row,"tr","class=\"$class\"");
				$n++;
			}
		}
	}
	return $result;
}

function create_lookup_rows($cid,$start,$end) {
	$result = NULL;
	$today = getdate(time());
	if($start != "" && $end != "") {
		$start = str_replace("/","-",$start);
		$end = str_replace("/","-",$end);
	} else {
		$start = date("Y-m-d",$today[0]);
		$end = date("Y-m-d",$today[0]);
	}
	//$sql = "SELECT * FROM pos.tblShoppingCart WHERE scCID='$cID' ORDER BY scSNum DESC AND scCommited=1;";
	$sql = "SELECT * FROM pos.tblInvoice WHERE iCustID=$cid AND '$start'<=iInvcDate AND iInvcDate<='$end' ORDER BY iInvcDate DESC;";
	//print $sql;
	$header_lookuptable = get_lookuptable_values();
	if($lookup_table = @mysql_query($sql)) {
		$result = array();
		//$record = fetch_value_if_exists($_POST,'record3');
		$n = 0;
		if(@mysql_data_seek($lookup_table,0)) {
			while($rec = @mysql_fetch_assoc($lookup_table)) {
				$row = "";
				$keya = $rec['iID'];
				$lAmt = "0.00";
				for($i = 0; $i < sizeof($header_lookuptable); ++$i) {
					if($i == 0) {
						$fdata = substr($rec['iInvcDate'],0,10);
					} else if($i == 1) {
						$today = getdate(time());
						//$mdate = date("Y-m-d H:i:s",$today[0]);
						//$curs = strtotime($mdate);
						$diff = $today[0] - strtotime($rec['iInvcDate']);
						$dd = round($diff/86400);
						if($dd == 0) {
							$fdata = "Today";
						} else {
							$fdata = $dd . " day(s)";
						}
					} else if($i == 4) {
						$fdata = number_format((float)$rec['iTotal'] - (float)$rec['iActPay'],2,'.','');
						$lAmt = $fdata;
						/*if($pid == "1") {
							$fdata = "YES";
						} else {
							$fdata = "NO";
						}*/
					}  else {
						$fdata = $rec[$header_lookuptable[$i]];
						$fdata = htmlspecialchars($fdata);
						$fdata = preg_replace("/\n/m","<br/>\n",$fdata);
					}
					$row .= wrap_tag($fdata,"td");
				}
				$result[] = wrap_tag($row,"tr","amt=\"$lAmt\" ind=\"$n\" ".
				"tag=\"$keya\" iID=\"".$rec['iInvcNum']."\" ".
				"click=\"false\" onclick=\"return ragItem(this)\";");
				$n++;
			}
		}
	}
	return $result;
}

function create_outstandingtable_rows($cID) {
	$result = NULL;
	//$sql = "SELECT * FROM pos.tblShoppingCart WHERE scCID='$cID' ORDER BY scSNum DESC AND scCommited=1;";
	$sql = "SELECT * FROM pos.tblInvoice WHERE iCustID=$cID AND iPIF=0 ORDER BY iInvcDate DESC;";
	
	$header_outstandingtable = get_outstandingtable_values();
	if($outstanding_table = @mysql_query($sql)) {
		$result = array();
		//$record = fetch_value_if_exists($_POST,'record3');
		$n = 0;
		if(@mysql_data_seek($outstanding_table,0)) {
			while($rec = @mysql_fetch_assoc($outstanding_table)) {
				$row = "";
				$keya = $rec['iID'];
				$lAmt = "0.00";
				for($i = 0; $i < sizeof($header_outstandingtable); ++$i) {
					if($i == 0) {
						$fdata = substr($rec['iInvcDate'],0,10);
					} else if($i == 1) {
						$today = getdate(time());
						//$mdate = date("Y-m-d H:i:s",$today[0]);
						//$curs = strtotime($mdate);
						$diff = $today[0] - strtotime($rec['iInvcDate']);
						$dd = round($diff/86400);
						if($dd == 0) {
							$fdata = "Today";
						} else {
							$fdata = $dd . " day(s)";
						}
					} else if($i == 4) {
						$fdata = number_format((float)$rec['iTotal'] - (float)$rec['iActPay'],2,'.','');
						$lAmt = $fdata;
					}  else {
						$fdata = $rec[$header_outstandingtable[$i]];
						$fdata = htmlspecialchars($fdata);
						$fdata = preg_replace("/\n/m","<br/>\n",$fdata);
					}
					$row .= wrap_tag($fdata,"td");
				}
				$result[] = wrap_tag($row,"tr","amt=\"$lAmt\" ind=\"$n\" ".
				"tag=\"$keya\" iID=\"".$rec['iInvcNum']."\" ".
				"click=\"false\" onclick=\"return tagItem(this)\";");
				$n++;
			}
		}
	}
	return $result;
}

function create_outstandingitemstable_rows($iID) {
	$result = NULL;
	$sql = "SELECT tblTrans.tQty AS tQty,". 
		"tblTrans.tPrice AS tPrice,".
		"tblTrans.tTotal AS tTotal,".
		"tblTrans.tProdID AS tProdID ".
		"FROM pos.tblTrans ".
		"WHERE tblTrans.tInvcID IN ($iID);";
	//print $sql;
	$header_outstandingitemstable = get_outstandingitemstable_values();
	if($outstandingitems_table = @mysql_query($sql)) {
		$result = array();
		//$record = fetch_value_if_exists($_POST,'record3');
		$n = 0;
		if(@mysql_data_seek($outstandingitems_table,0)) {
			while($rec = @mysql_fetch_assoc($outstandingitems_table)) {
				$row = "";
				//$keya = $rec['iID'];
				for($i = 0; $i < sizeof($header_outstandingitemstable); ++$i) {
					if($i == 0) {
						$fdata = $rec['tProdID'];
					} else if($i == 2) {
						$fdata = number_format((int)$rec['tQty']*(float)$rec['tPrice'],2,'.','');
					} else {
						$fdata = $rec[$header_outstandingitemstable[$i]];
						$fdata = htmlspecialchars($fdata);
						$fdata = preg_replace("/\n/m","<br/>\n",$fdata);
					}
					$row .= wrap_tag($fdata,"td");
				}
				$result[] = wrap_tag($row,"tr","");
				$n++;
			}
		}
	}
	return $result;
}

function create_paymenthistory_rows($cID) {
	$result = NULL;
	$sql = "SELECT * FROM pos.tblPay WHERE pCustID='$cID' ORDER BY pPayDate DESC;";
	$payM = array("","CoM AR","Cash","Cheque","Visa","Master Card","American Express","Debit","Certificate");
	$header_paymenthistory = get_paymenthistory_values();
	if($payment_history = @mysql_query($sql)) {
		$result = array();
		//$record = $this->fetch_value_if_exists($_POST,'record4');
		$n = 0;
		if(@mysql_data_seek($payment_history,0)) {
			while($rec = @mysql_fetch_assoc($payment_history)) {
				$row = "";			
				for($i = 0; $i < sizeof($header_paymenthistory); ++$i) {
					if($i == 0) {
						$today = getdate(time());
						$mdate = date("Y-m-d H:i:s",$today[0]);
						$curs = strtotime($mdate);
						$diff = $curs - strtotime($rec['pPayDate']);
						$dd = round($diff/86400);
						if($dd == 0) {
							$fdata = "Today";
						} else {
							$fdata = $dd . " day(s)";
						}
					} else {
						$fdata = $rec[$header_paymenthistory[$i]];
						if($i == 4) {
							$fdata = substr($fdata,0,40);
						} else if($i == 2) {
							$fdata = $payM[$fdata];
						}
						$fdata = htmlspecialchars($fdata);
						$fdata = preg_replace("/\n/m","<br/>\n",$fdata);
					}
					if($to_mark) {
						$fdata .= "<a name=\"r\"></a>";
					}
					$row .= wrap_tag($fdata,"td");
				}
				$class = ($selected)?"selectcell":"cell" . ($n % 2);
				$result[] = wrap_tag($row,"tr","class=\"$class\"");
				$n++;
			}
		}
	}
	return $result;
}

function create_paid_rows($cID) {
	$result = NULL;
	$sql = "SELECT * FROM pos.tblPay WHERE pCustID='$cID' AND pIsRepay=1 ORDER BY pPayDate DESC;";
	$payM = array("","CoM AR","Cash","Cheque","Visa","Master Card","American Express","Debit","Certificate");
	$header_paid = get_paid_values();
	if($payment_history = @mysql_query($sql)) {
		$result = array();
		//$record = $this->fetch_value_if_exists($_POST,'record4');
		$n = 0;
		if(@mysql_data_seek($payment_history,0)) {
			while($rec = @mysql_fetch_assoc($payment_history)) {
				$row = "";			
				for($i = 0; $i < sizeof($header_paid); ++$i) {
					if($i == 0) {
						$today = getdate(time());
						$mdate = date("Y-m-d H:i:s",$today[0]);
						$curs = strtotime($mdate);
						$diff = $curs - strtotime($rec['pPayDate']);
						$dd = round($diff/86400);
						if($dd == 0) {
							$fdata = "Today";
						} else {
							$fdata = $dd . " day(s)";
						}
					} else {
						$fdata = $rec[$header_paid[$i]];
						if($i == 4) {
							$fdata = substr($fdata,0,40);
						} else if($i == 2) {
							$fdata = $payM[$fdata];
						}
						$fdata = htmlspecialchars($fdata);
						$fdata = preg_replace("/\n/m","<br/>\n",$fdata);
					}
					$row .= wrap_tag($fdata,"td");
				}
				$class = ($selected)?"selectcell":"cell" . ($n % 2);
				$result[] = wrap_tag($row,"tr","");
				$n++;
			}
		}
	}
	return $result;
}

function print_invoice_table($arg,$var) {
	if($arg == "cart") {
		$header = create_shoppingcart_header();
		$table_rows = create_shoppingcart_rows($var);
	} else if($arg == "purchase") {
		$header = create_purchasetable_header();
		$table_rows = create_purchasetable_rows($var);
	} else if($arg == "outstanding") {
		$header = create_outstandingtable_header();
		$table_rows = create_outstandingtable_rows($var);
	} else if($arg == "outstandingitems") {
		$header = create_outstandingitemstable_header();
		$table_rows = create_outstandingitemstable_rows($var);
	} else if($arg == "paid") {
		$header = create_paid_header();
		$table_rows = create_paid_rows($var);
	} else if($arg == "lookup") {
		//print $var;
		$list = explode("@",$var);
		$header = create_lookup_header();
		$table_rows = create_lookup_rows($list[0],$list[1],$list[2]);
	} else {
		$header = create_payhistory_header();
		$table_rows = create_paymenthistory_rows($var);
	}
	if(true) {
		$table = "";
		if($header) {
			//$table .= $header;
			$table .= wrap_tag($header,"thead");
			if($table_rows) {
				$tbody .= join($table_rows,"\n");
				$table .= wrap_tag($tbody,"tbody","");
		    	if($arg == "cart") {
					$table = wrap_tag($table,"table","id=\"myTable\" class=\"tablesorter\" border=\"0\" cellpadding=\"0\" cellspacing=\"1\" title=\"Click on row to remove item from cart.\"");
		    	} else if($arg == "purchase") {
					$table = wrap_tag($table,"table","width=100% title=\"Red=Inactive Yellow=Pending\"");
		    	} else {
					$table = wrap_tag($table,"table","id=\"myTable\" class=\"tablesorter\" border=\"0\" cellpadding=\"0\" cellspacing=\"1\" title=\"Click on row to see invoice items.\"");
				}
				print $table;
			} else if($arg == "cart") { // no matching records note
				print "<table height=\"100%\" width=\"100%\"><tr><td><div style=\"font-weight:bold;text-align:center;font-size:120%;\">Shoppingcart is empty.</div></td></tr></table>\n";
			} else if($arg == "purchase") {
				print "<table height=\"100%\" width=\"100%\"><tr><td><div style=\"font-weight:bold;text-align:center;font-size:120%;\">Purchase history is empty.</div></td></tr></table>\n";
			} else if($arg == "outstandings" || $arg == "lookup") {
				///print "<style type=\"text/css\">\ntr.cell0 td,tr.cell1 td, tr.selectcell td { white-space:nowrap; }\n</style>\n";
				//$table = wrap_tag($table,"table","width=100% title=\"Click on item to select it\"");
				//print $table;
				print "<table height=\"100%\" width=\"100%\"><tr><td><div style=\"font-weight:bold;text-align:center;font-size:120%;\">Choose a date range to lookup invoices.</div></td></tr></table>\n";
			} else if($arg == "outstandingitems") {
				//$table = wrap_tag($table,"table","id=\"myTable\" class=\"tablesorter\" border=\"0\" cellpadding=\"0\" cellspacing=\"1\" title=\"\"");
				//print $table;
				print "<table height=\"100%\" width=\"100%\"><tr><td><div style=\"font-weight:bold;text-align:center;font-size:120%;\">Select invoice on left to see item.</div></td></tr></table>\n";
			} else if($arg == "paid") {
				//$table = wrap_tag($table,"table","id=\"myTable\" class=\"tablesorter\" border=\"0\" cellpadding=\"0\" cellspacing=\"1\" title=\"\"");
				//print $table;
				print "<table height=\"100%\" width=\"100%\"><tr><td><div style=\"font-weight:bold;text-align:center;font-size:120%;\">No repayments on record.</div></td></tr></table>\n";
			} else {
				print "<style type=\"text/css\">\ntr.cell0 td,tr.cell1 td, tr.selectcell td { white-space:nowrap; }\n</style>\n";
				$table = wrap_tag($table,"table","class=\"myTable\" width=100% title=\"Click on item to select it\"");
				print $table;
				//print "<table height=\"100%\" width=\"100%\"><tr><td><div style=\"font-weight:bold;text-align:center;font-size:120%;\">Payment history is empty.</div></td></tr></table>\n";
			}
		}
	}
}
?>