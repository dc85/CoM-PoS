<?php

$DATABASE = "localhost";
$USERNAME = "SQL";
$PASSWORD = "pos";

ini_set("memory_limit",100 ."M");

mysql_connect("$DATABASE", "$USERNAME", "$PASSWORD")or die("Connection refused");

//mysql_select_db("$db_name")or die("cannot select DB");

function query_database($sql) {
	$sql_result;
	//print $sql;
	$result = array();
	if($sql_result = @mysql_query($sql)) {
		while($row = @mysql_fetch_assoc($sql_result)) {
			$result[] = $row;
		}
	} else {
		print mysql_error();
	}
	return $result;
}

function get_product_category() {
	$result = array('');
	$sql = "SELECT * FROM pos.tblCatType WHERE cIsActive=1 ORDER BY(ctID);";
	if($sql_result = @mysql_query($sql)) {
		while($row = @mysql_fetch_assoc($sql_result)) {
			$result[] = $row['cCatType'];
		}
	} else {
		print mysql_error();
	}
	return $result;
}

function get_product_type($d) {
	$result = "";
	$sql = "SELECT * FROM pos.tblCatType WHERE ctID=$d;";
	if($sql_result = @mysql_query($sql)) {
		if($row = @mysql_fetch_assoc($sql_result)) {
			$result = $row['cCatType'];
		}
	} else {
		print mysql_error();
	}
	return $result;
}


function get_supplier($id) {
	$result = "";
	$sql = "SELECT sName FROM pos.tblSupplier WHERE sID=$id;";
	if($sql_result = @mysql_query($sql)) {
		if($row = @mysql_fetch_assoc($sql_result)) {
			$result = $row['sName'];
		}
	} else {
		print mysql_error();
	}
	return $result;
}

function get_tax_category() {
	$result = array('');
	$sql = "SELECT * FROM pos.tblTax WHERE tIsActive=1;";
	if($sql_result = @mysql_query($sql)) {
		while($row = @mysql_fetch_assoc($sql_result)) {
			$result[] = $row['tName'];
		}
	} else {
		print mysql_error();
	}
	return $result;
}

function get_psrchsupplier_list() {
	$result = array('ALL SUPPLIER');
	$sql = "SELECT sName FROM pos.tblSupplier ORDER BY(sID);";
	if($sql_result = @mysql_query($sql)) {
		while($row = @mysql_fetch_assoc($sql_result)) {
			$result[] = $row['sName'];
		}
	} else {
		print mysql_error();
	}
	return $result;
}

function get_supplier_list() {
	$result = array('');
	$sql = "SELECT sName FROM pos.tblSupplier ORDER BY(sID);";
	if($sql_result = @mysql_query($sql)) {
		while($row = @mysql_fetch_assoc($sql_result)) {
			$result[] = $row['sName'];
		}
	} else {
		print mysql_error();
	}
	return $result;
}

function get_shirt_list() {
	$result = array('');
	$sql = "SELECT cSize FROM pos.tblCSize;";
	if($sql_result = @mysql_query($sql)) {
		while($row = @mysql_fetch_assoc($sql_result)) {
			$result[] = $row['cSize'];
		}
	} else {
		print mysql_error();
	}
	return $result;
}

function get_tax_category2() {
	$result = array('');
	$sql = "SELECT * FROM pos.tblTax WHERE tIsActive=1;";
	if($sql_result = @mysql_query($sql)) {
		while($row = @mysql_fetch_assoc($sql_result)) {
			$result[$row['tTax']] = $row['tName'];
		}
	} else {
		print mysql_error();
	}
	return $result;
}

function get_spc_level() {
	$result = array();
	$sql = "SELECT * FROM pos.tblspctype WHERE sIsActive=1;";
	if($sql_result = @mysql_query($sql)) {
		while($row = @mysql_fetch_assoc($sql_result)) {
			$result[] = $row['sLvl'];
		}
	} else {
		print mysql_error();
	}
	return $result;
}

function get_spc_level2() {
	$result = array('');
	$sql = "SELECT * FROM pos.tblspctype WHERE sIsActive=1;";
	if($sql_result = @mysql_query($sql)) {
		while($row = @mysql_fetch_assoc($sql_result)) {
			$result[] = $row['sSPC'] . ": [" . $row['sLvl'] . "]";
		}
	} else {
		print mysql_error();
	}
	return $result;
}

function get_account_level() {
	$result = array('');
	$sql = "SELECT tblAccLvl.aDescription as aLvl FROM pos.tblAccLvl WHERE aIsActive=1;";
	if($sql_result = @mysql_query($sql)) {
		while($row = @mysql_fetch_assoc($sql_result)) {
			$result[] = $row['aLvl'];
		}
	} else {
		print mysql_error();
	}
	return $result;
}

function get_customer_title() {
	$result = array('');
	$sql = "SELECT * FROM pos.tblTitle WHERE tIsActive=1;";
	if($sql_result = @mysql_query($sql)) {
		while($row = @mysql_fetch_assoc($sql_result)) {
			$result[] = $row['tTitle'];
		}
	} else {
		print mysql_error();
	}
	return $result;
}

function get_customer_type() {
	$result = array('');
	$sql = "SELECT * FROM pos.tblCustType WHERE cIsActive=1;";
	if($sql_result = @mysql_query($sql)) {
		while($row = @mysql_fetch_assoc($sql_result)) {
			$result[] = $row['cCustType'];
		}
	} else {
		print mysql_error();
	}
	return $result;
}


function get_shifts() {
	$result = array('');
	$sql = "SELECT tblWrkShift.sSchedule as sSch FROM pos.tblWrkShift WHERE sIsActive=1;";
	if($sql_result = @mysql_query($sql)) {
		while($row = @mysql_fetch_assoc($sql_result)) {
			$result[] = $row['sSch'];
		}
	} else {
		print mysql_error();
	}
	return $result;
}

function get_customer_expert() {
	$result = array('');
	$sql = "SELECT * FROM pos.tblEType WHERE eIsActive=1;";
	if($sql_result = @mysql_query($sql)) {
		while($row = @mysql_fetch_assoc($sql_result)) {
			$result[] = $row['eType'];
		}
	} else {
		print mysql_error();
	}
	return $result;
}

function get_product_code($upc) {
	$result = "";
	$sql = "SELECT tblProduct.pID as pID FROM pos.tblProduct WHERE pUPC='$upc';";
	if($sql_result = @mysql_query($sql)) {
		while($row = @mysql_fetch_assoc($sql_result)) {
			$result = $row['pID'];
		}
	} else {
		print mysql_error();
	}
	return $result;
}

function get_product_cost($upc,$store) {
	$result = "";
	$pID = get_product_code($upc);
	$sql = "SELECT tblInventory.iCost as iCost FROM pos.tblInventory WHERE iProduct=$pID AND iStoreID=$store;";
	if($sql_result = @mysql_query($sql)) {
		while($row = @mysql_fetch_assoc($sql_result)) {
			$result = $row['iCost'];
		}
	} else {
		print mysql_error();
	}
	return $result;
}

/*function get_store_name($sid) {
	$sql = "SELECT tblStore.sName AS sName 
		FROM pos.tblStore 
		WHERE tblStore.sID=$sid;";
	if($sql_result = @mysql_query($sql)) {
		$row = @mysql_fetch_assoc($sql_result);
		return $row["sName"];
	}
}*/

function get_rep_name() {
	$result = array();
	$sql = "SELECT * FROM pos.tblStaff WHERE sIsActive=1;";
	if($rlist = @mysql_query($sql)) {
		while($row = @mysql_fetch_row($rlist)) {
			$staff = array();
			$staff[] = $row[1];
			$staff[] = $row[3];
			$staff[] = $row[4];
			$result[] = $staff;
			//echo $row[2];
		}
	}
	return $result;
}

function get_staff_list() {
	$result = array(" ");
	$sql = "SELECT tblStaff.sStaff AS staff FROM pos.tblStaff ORDER BY(sID);";
	if($rlist = @mysql_query($sql)) {
		while($row = @mysql_fetch_assoc($rlist)) {
			$result[] = $row['staff'];
			//echo $row[2];
		}
	} else {
		print mysql_error();
	}
	return $result;
}

function get_store_names() {
	$result = array(" ");
	$sql = "SELECT tblStore.sName AS sName FROM pos.tblStore ORDER BY(sID);";
	if($sql_result = @mysql_query($sql)) {
		while($row = @mysql_fetch_assoc($sql_result)) {
			$result[] = $row['sName'];
		}
	} else {
		print mysql_error();
	}
	return $result;
}

function getAllStores() {
	$result = array("ALL STORES");
	$sql = "SELECT tblStore.sName AS sName FROM pos.tblStore ORDER BY(sID);";
	if($sql_result = @mysql_query($sql)) {
		while($row = @mysql_fetch_assoc($sql_result)) {
			$result[] = $row['sName'];
		}
	} else {
		print mysql_error();
	}
	return $result;
}

function getStock($store,$prod) {
	$sql = "SELECT ";
}

function get_linetype() {
	$result = array(" ");
	$sql = "SELECT tblLineType.lType AS lType FROM pos.tblLineType ORDER BY(lsID);";
	if($sql_result = @mysql_query($sql)) {
		while($row = @mysql_fetch_assoc($sql_result)) {
			$result[] = $row['lType'];
		}
	} else {
		print mysql_error();
	}
	return $result;
}

function getProvList() {
	$result = array(" ");
	$sql = "SELECT tblProvince.pAbbr AS prov FROM pos.tblProvince ORDER BY(pID);";
	if($sql_result = @mysql_query($sql)) {
		while($row = @mysql_fetch_assoc($sql_result)) {
			$result[] = $row['prov'];
		}
	} else {
		print mysql_error();
	}
	return $result;
}

function get_store_names_report() {
	$result = array();
	$sql = "SELECT tblStore.sName as sName FROM pos.tblStore;";
	if($sql_result = @mysql_query($sql)) {
		while($row = @mysql_fetch_assoc($sql_result)) {
			$result[] = $row['sName'];
		}
	} else {
		print mysql_error();
	}
	return $result;
}

function get_product_ids_report($start,$end) {
	$result = array();
	$sql = "SELECT DISTINCT tblTrans.tProdID as pID FROM pos.tblTrans WHERE tTrnsDate BETWEEN '$start' AND '$end';";
	if($sql_result = @mysql_query($sql)) {
		while($row = @mysql_fetch_assoc($sql_result)) {
			$result[] = $row['pID'];
		}
	} else {
		print mysql_error();
	}
	return $result;
}

function get_product_category_reports() {
	$result = array();
	$sql = "SELECT tblCatType.ctID as ctID FROM pos.tblCatType;";
	if($sql_result = @mysql_query($sql)) {
		while($row = @mysql_fetch_assoc($sql_result)) {
			$result[] = $row['ctID'];
		}
	} else {
		print mysql_error();
	}
	return $result;
}

function get_product_catname_reports($id) {
	$result = "";
	$sql = "SELECT tblCatType.cCatType as cat FROM pos.tblCatType WHERE tblCatType.ctID=$id;";
	if($sql_result = @mysql_query($sql)) {
		while($row = @mysql_fetch_assoc($sql_result)) {
			$result = $row['cat'];
		}
	} else {
		print mysql_error();
	}
	return $result;
}

function get_store_name($id) {
	$sql = "SELECT tblStore.sName as sName 
		FROM pos.tblStore 
		WHERE tblStore.sID=$id;";
	if($sql_result = @mysql_query($sql)) {
		while($row = @mysql_fetch_assoc($sql_result)) {
			$result = $row['sName'];
		}
	} else {
		print $sql."\n";
		print mysql_error()."\n";
	}
	return $result;
}

function get_product_description($id) {
	$result = "";
	$sql = "SELECT tblProduct.pDescription as pDesc FROM pos.tblProduct WHERE tblProduct.pID=$id;";
	if($sql_result = @mysql_query($sql)) {
		while($row = @mysql_fetch_assoc($sql_result)) {
			$result = $row['pDesc'];
		}
	} else {
		print mysql_error();
	}
	return $result;
}

function get_B_sizes() {
	$result = array();
	$sql = "SELECT tblBSize.bSize as bSize FROM pos.tblBSize;";
	if($sql_result = @mysql_query($sql)) {
		while($row = @mysql_fetch_assoc($sql_result)) {
			$result[] = $row['bSize'];
		}
	} else {
		print mysql_error();
	}
	return $result;
}

function get_C_sizes() {
	$result = array();
	$sql = "SELECT tblDSize.dSize as dSize,tblDSize.dID as dID FROM pos.tblDSize;";
	if($sql_result = @mysql_query($sql)) {
		while($row = @mysql_fetch_assoc($sql_result)) {
			$result[$row['dID']] = $row['dSize'];
		}
	} else {
		print mysql_error();
	}
	return $result;
}

function get_C_size() {
	$result = array();
	$sql = "SELECT tblDSize.dSize as dSize FROM pos.tblDSize;";
	if($sql_result = @mysql_query($sql)) {
		while($row = @mysql_fetch_assoc($sql_result)) {
			$result[] = $row['dSize'];
		}
	} else {
		print mysql_error();
	}
	return $result;
}

function get_cell($id, $dayofweek) {
	$result = "";
	//$result = array();
	$sql = "SELECT tblTimesheet.tsShift FROM pos.tblTimesheet WHERE sID='$id';";
	if($rlist = @mysql_query($sql)) {
		if($row = @mysql_fetch_row($rlist)) {
			$result = $row[0];
		} else {
			print (mysql_error());
		}
		return $result;	
	} else {
		$arg = "SELECT tblStaff." . $dayofweek . " FROM pos.tblStaff WHERE sID='$id';";
		$rlist = @mysql_query($arg);
		if($row = @mysql_fetch_row($rlist)) {
			$result = $row[0];
		} else {
			print (mysql_error());
		}
		return $result;
	}
}
	
function get_shirt_size() {
	/*$result = array('');
	$sql = "SELECT * FROM pos.tblCSize;";
	if($sql_result = @mysql_query($sql)) {
		while($row = @mysql_fetch_assoc($sql_result)) {
			$result[] = $row['cSize'];
		}
	} else {
		print mysql_error();
	}*/
	$shirt_list = array(" ","S","M","L","XL");
	return $shirt_list;
}

function get_pant_size() {
	$pants_list = array(" ","28X30","30X28","30X30","30X32","32X34","34X34","36X32","38X32","42X32");
	return $pants_list;
}

function get_contact_method() {
	$phone_list = array(" ","Business","Fax","Home","Cell");
	return $phone_list;
}

function get_promo_note($pid) {
	$result = "";
	$sql = "SELECT tblProduct.pPromo AS pPromo FROM pos.tblProduct WHERE tblProduct.pID='$pid';";
	if($sql_result = @mysql_query($sql)) {
		while($row = @mysql_fetch_assoc($sql_result)) {
			$result = $row['pPromo'];
		}
	} else {
		print mysql_error();
	}
	return $result;
}

function shiftExists($id,$date,$shift) {
	$arg = "SELECT * FROM pos.tblTimesheet WHERE tsDate='$date' AND tsStaff=$id AND tsShift=$shift;";
	//print $arg . "<br>";
	if($rlist = @mysql_query($arg)){
		if($row = @mysql_fetch_row($rlist)) {
			//print strtotime($row[6]) . "==" . strtotime($in) . "&&" . strtotime($row[8]) . "==" . strtotime($out) . "<br>"; 
			if($row[4] == $shift) {
				//print "true<br>";
				return true;
			} else {
				//print "false<br>";
				return false;
			}
		} else {
			print (mysql_error());
		}
	} else {
		//print "maybe<br>";
		return false;
	}
}

function find_shiftinfo($shift,$strdate) {
	$w = date("w",strtotime($strdate));
	$qin = "sIn" . $w;
	$qout = "sOut" . $w;
	
	switch($shift) {
		case 'MS1':
			$tag = "MIDDLE SHIFT1";
			$lag = 4;
			break;
		case 'MS2':
			$tag = "MIDDLE SHIFT2";
			$lag = 5;
			break;
		case 'FS1':
			$tag = "FRONT SHIFT1";
			$lag = 2;
			break;
		case 'FS2':
			$tag = "FRONT SHIFT2";
			$lag = 3;
			break;
		case 'BS1':
			$tag = "BACK SHIFT1";
			$lag = 6;
			break;
		case 'BS2':
			$tag = "BACK SHIFT2";
			$lag = 7;
			break;
		case 'DAY OFF':
			$tag = "DAY OFF";
			$lag = 1;
			break;
		case 'IT':
			$tag = "IT";
			$lag = 8;
			break;
		default:
			$tag = $row[1];
			break;
	}
	
	$sql = "SELECT tblWrkShift.$qin,tblWrkShift.$qout FROM pos.tblWrkShift WHERE sSchedule='$tag';";
	//print $sql . "<br>";

	if($rlist = mysql_query($sql)){
		if($row = mysql_fetch_row($rlist)) {
			$t = array();
			$u = split(" ",$row[0]);
			$v = split(" ",$row[1]);
			$t[] = $u[1];
			$t[] = $v[1];
			$t[] = $lag;
			return $t;
		} else {
			print (mysql_error());
		}
	} else {
		print (mysql_error());
	}
}

function find_staffinfo($var,$mode) {
	$sql = "";
	
	if($mode == "id") {
		$sql = "SELECT tblStaff.sCNumber,tblStaff.sStaff FROM pos.tblStaff WHERE tblStaff.sID='$var';";
	} else if($mode == "snumber") {
		$sql = "SELECT tblStaff.sID,tblStaff.sStaff FROM pos.tblStaff WHERE tblStaff.sCNumber='$var';";
	} else if($mode == "snumbers") {
		$sql = "SELECT tblStaff.sID,tblStaff.sStaff FROM pos.tblStaff WHERE tblStaff.sCNumber LIKE '%$var%';";
	} else if($mode == "sname") {
		$sql = "SELECT tblStaff.sID,tblStaff.sCNumber FROM pos.tblStaff WHERE tblStaff.sStaff='$var';";
	} else {
		$sql = "SELECT tblStaff.sID FROM pos.tblStaff WHERE tblStaff.sCNumber='$var';";
	}
	
	if($rlist = mysql_query($sql)){
		if($row = mysql_fetch_row($rlist)) {
			return $row;
		} else {
			print (mysql_error());
		}
	} else {
		print (mysql_error());
	}
}

/* HELPER METHODS */
function wrap_tag($data,$tag,$extra = "") {
	return "<$tag $extra>$data</$tag>\n";
}
	
function fetch_value_if_exists($base,$name,$default = '') {
	$result = NULL;
	if($base && array_key_exists($name,$base) && sizeof($base[$name]) > 0) {
		$result = $base[$name];
	}
	else if ($default && strlen($default) > 0) {
		$result = $default;
	}
	else if($old_values && array_key_exists($name,$old_values) && strlen($old_values[$name]) > 0) {
		$result = $this->old_values[$name];
	}
	return $result;
}
	
	
function fetchDBRow() {
		
}

?>