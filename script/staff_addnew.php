<?php
ini_set("display_errors", 1);
error_reporting(E_NONE);
include("../backend/customer.php");

$d = getdate(time());
$today = date("Y-m-d H:i:s",$d[0]);
//print "TIMESTAMP: ".$today ."...\n";
$custID = "";
$staffID = "";
//var_dump($_POST);
/*
 * Variable tag arrays
 */
$staff_eleNames = array("sStoreID","sCNumber","sCustomer","sStaff",
	"sLevel","sPassword","sPswdExpMM","sPswdExp","sStoreCredit",
	"sHrRate","sDfHr","sSchedule0","sSchedule1","sSchedule2",
	"sSchedule3","sSchedule4","sSchedule5","sSchedule6");
$staff_varchars = array("sCNumber","sStaff","sPassword","sPswdExp");

$cust_eleNames = array("sStoreID","cTitle","cFirstN","cLastN",	"cAKA","cDoB",
	"cRIN","cCardNum","cCustType","cCoName","cShirtSize","cPantSize",
	"cExpert","cpType4","cPhone4","cpType5","cPhone5","cE1","cE2",
	"cE3","cE4","cE5","cE6","cE7","cE8","cE9","cE10","cUnitB",
	"cAdrBus","cCityB","cProvB","cZipB","cpType1","cPhone1","cEmail1",
	"cUnitH","cAdrHome","cCityH","cProvH","cZipH","cpType2","cPhone2",
	"cEmail2","cUnitS","cAdrShip","cCityS","cProvS","cZipS","cpType3",
	"cPhone3","cEmail3","cTax1","cTax2","cSPC","cEcoFee","cCustRep",
	"cExpNum","cExpDate","cCredit");
$cust_varchars = array("cFirstN","cLastN","cAKA","cDoB","cCoName",
	"cUnitB","cAdrBus","cCityB","cZipB","cUnitH","cAdrHome","cCityH",
	"cZipH","cUnitS","cAdrShip","cCityS","cZipS","cPhone1","cPhone2",
	"cPhone3","cPhone4","cPhone5","cEmail1","cEmail2","cEmail3","cRIN",
	"cDL","cCardNum","cExpNum","cExpDate","cPantSize","cNote","cAware",
	"cModDate","cCrtDate");
$cust_expert = array("cE1","cE2","cE3","cE4","cE5","cE6","cE7","cE8",
	"cE9","cE10","cTax1","cTax2","cEcoFee");
//$eleArray = var_dump($_POST);
//print_r($eleArray);
@mysql_query("BEGIN");
if($tblCustomer_Insert = build_tblCustomerInsert()) {
	if(exec_tblCustomerInsert($tblCustomer_Insert)) {
		if($cID = get_tblCustomerID()) {
			if($tblStaff_Insert = build_tblStaffInsert($cID)) {
				if(exec_tblCustomerInsert($tblStaff_Insert)) {
					$sID = get_tblStaffID();
					@mysql_query("COMMIT");
					print "WEBPOS_MSG(02201520)";
				} else {
					@mysql_query("ROLLBACK");
					print "WEBPOS_ERR(02201520)";
					return;
				}
			} else {
				@mysql_query("ROLLBACK");
				print "WEBPOS_ERR(02201520)";
				return;
			}
		} else {
			@mysql_query("ROLLBACK");
			print "WEBPOS_ERR(02201520)";
			return;
		}
	} else {
		@mysql_query("ROLLBACK");
		print "WEBPOS_ERR(02201520)";
		return;
	}
} else {
	@mysql_query("ROLLBACK");
	print "WEBPOS_ERR(02201520)";
	return;
}

/*
 * SQLs that will be used
 */
function build_tblCustomerInsert() {
	global $today,$cust_eleNames,$cust_varchars,$cust_expert;
	//print "Building tblCustomer insert query...\n";
	$tblCustomer_Insert = "INSERT INTO pos.tblCustomer(cStoreID,cTitle,cFirstN,
		cLastN,cAKA,cDoB,cRIN,cCardNum,cCustType,cCoName,cShirtSize,
		cPantSize,cExpert,cpType4,cPhone4,cpType5,cPhone5,cE1,cE2,cE3,
		cE4,cE5,cE6,cE7,cE8,cE9,cE10,cUnitB,cAdrBus,cCityB,cProvB,
		cZipB,cpType1,cPhone1,cEmail1,cUnitH,cAdrHome,cCityH,cProvH,
		cZipH,cpType2,cPhone2,cEmail2,cUnitS,cAdrShip,cCityS,cProvS,
		cZipS,cpType3,cPhone3,cEmail3,cTax1,cTax2,cSPC,cEcoFee,
		cCustRep,cExpNum,cExpDate,cCredit,cNote,cAware,cCBal,cBalance,
		cModBy,cModDate,cCrtDate,cDelStatus,cDND) VALUES(";
	try {
		foreach($cust_eleNames as $eleName) {
			//print $eleName."=".$_POST[$eleName]."\n";
			if(in_array($eleName,$cust_varchars)) {
				if(!empty($_POST[$eleName])) {
					if($eleName == "cExpDate") {
						$eTime = strtotime($today);
						if($_POST[$eleName] == "3") {
							$eDate = date("Y-m-d H:i:s",
								strtotime('+1 year',$eTime));
						} else if($_POST[$eleName] == "2") {
							$eDate = date("Y-m-d H:i:s",
								strtotime('+6 months',$eTime));
						} else if($_POST[$eleName] == "1") {
							$eDate = date("Y-m-d H:i:s",
								strtotime('+1 month',$eTime));
						}
						$tblCustomer_Insert .= "'".$eDate."',";
					} else {
						$tblCustomer_Insert .= "'".$_POST[$eleName]."',";
					}
				} else {
					$tblCustomer_Insert .= "null,";
				}
			} else {
				if(!empty($_POST[$eleName])) {
					$tblCustomer_Insert .= $_POST[$eleName].",";
				} else {
					if(in_array($eleName,$cust_expert)) {
						$tblCustomer_Insert .= "0,";
					} else {
						$tblCustomer_Insert .= "null,";
					}
				}
			}
		}
		$tblCustomer_Insert .= "'','',null,null,null,'$today','$today',
		0,0);";
	} catch(Exception $err) {
		//print "ERROR white generating staff table insert sql.\n";
		//print "Caught exception: ,".  $e->getMessage(). "\n";
		return false;
	}
	return $tblCustomer_Insert;
}

function exec_tblCustomerInsert($sql) {
	global $today;
	if(@mysql_query($sql)) {
		//print "Customer insert query executed successfully...\n";
		return true;
	} else {
		//print "Customer Insert SQL:".$sql."\n";
		//print mysql_error()."\n";
		return false;
	}
}

function get_tblCustomerID() {
	global $today;
	global $custID;
	//print  "Retrieving customer ID...\n";
	$sql = "SELECT tblCustomer.cID AS cID 
		FROM pos.tblCustomer 
		WHERE tblCustomer.cCrtDate='$today'";
	if($result = @mysql_query($sql)) {
		$data = @mysql_fetch_assoc($result);
		$custID = $data["cID"];
		return $data["cID"];
	} else {
		//print "Customer ID SQL: ".$sql."\n";
		//print mysql_error()."\n";
		return false;
	}
}

function build_tblStaffInsert($cID) {
	global $today,$staff_eleNames,$staff_varchars;
	//print "Building tblStaff insert query...\n";
	$sql = "INSERT INTO pos.tblStaff(sStoreID,sCNumber,sCustomer,sStaff,
		sLevel,sPassword,sPswdExpMM,sPswdExp,sStoreCredit,sHrRate,sDfHr,
		sSchedule0,sSchedule1,sSchedule2,sSchedule3,sSchedule4,sSchedule5,
		sSchedule6,sCreated,sIsActive)	VALUES(";
	try {
		foreach($staff_eleNames as $eleName) {
			if($eleName == "sCustomer") {
				$sql .= $cID.",";
			} else {
				if(in_array($eleName,$staff_varchars)) {
					if(!empty($_POST[$eleName])) {
						$sql .= "'".$_POST[$eleName]."',";
					} else {
						$sql .= "null,";
					}
				} else {
					if(!empty($_POST[$eleName])) {
						$sql .= $_POST[$eleName].",";
					} else {
						if($eleName == "sStoreID") {
							$sql .= "0,";
						} else {
							$sql .= "null,";
						}
					}
				}
			}
		}
		$sql .= "'$today',1);";
	} catch(Exception $err) {
		//print "ERROR white generating staff table insert sql.\n";
		//print "Caught exception: ,".  $e->getMessage(). "\n";
		return false;
	}
	return $sql;
}

function exec_tblStaffInsert($sql) {
	global $today;
	if(@mysql_query($sql)) {
		//print "Staff insert query executed successfully...\n";
		return true;
	} else {
		//print "Staff Insert SQL:".$sql."\n";
		//print mysql_error()."\n";
		return false;
	}
}

function get_tblStaffID() {
	global $today;
	global $staffID;
	//print  "Retrieving staff ID...\n";
	$sql = "SELECT tblStaff.sID AS sID 
		FROM pos.tblStaff 
		WHERE tblStaff.sCreated='$today'";
	if($result = @mysql_query($sql)) {
		$data = @mysql_fetch_assoc($result);
		$staffID = $data["sID"];
		return $data["sID"];
	} else {
		//print "Staff ID SQL: ".$sql."\n";
		//print mysql_error()."\n";
		return false;
	}
}
?>