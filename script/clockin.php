<?php
/* Clock in successful: Staff [Name] Clocked In @ HH:mm:ss - Expected In @ HH:mm:ss
 * if day off: Staff [Name] Clocked In @ HH:mm:ss - Expected In @ Day Off
 * Clock out successful: Staff [Name] Clocked Out @ HH:mm:ss - Expected Out @ HH:mm:ss
 * Total Hours Worked: HH.mm
 * OOF succesful: 
*/
include("../backend/methods.php");
include("../backend/db_methods.php");

$type = $_GET['type'];
$id = "-1";
$curtime = getdate(time());
$date = date("Y-m-d",$curtime[0]) . " 00:00:00";
$date2 = date("Y-m-d",$curtime[0]);
$time = date("Y-m-d H:i:s",$curtime[0]);
$time2 = date("H:i:s",$curtime[0]);

$sql_id = "SELECT tblStaff.sID as sID,tblStaff.sStaff AS name 
	FROM pos.tblStaff WHERE 
	tblStaff.sCNumber='".$_GET["id"]."'";

if($result = @mysql_query($sql_id)) {
	if($row = @mysql_fetch_assoc($result)) {
		$id = $row['sID'];
		$name = $row['name'];
	} else {
		print (mysql_error());
		print "\n";
	}
} else {
	print (mysql_error());
	print "\n";
}

$sql_id = "SELECT * FROM pos.tblTimesheet 
			WHERE tsDate='$date' AND tsStaff=$id;";

if($result = @mysql_query($sql_id)) {
	if(@mysql_num_rows($result) == 0) {
		print "No active schedule for [$name] on [$date2].\n";
		return;
	}
} else {
	print (mysql_error());
}

if($id != "-1") {
	if($type == "si") {
		if(!testIn($id,$date)) {
			print "User [". $name ."] already signed in.\n";
			return;
		} else {
			$sql_test_si = "SELECT tblTimesheet.tsShift AS shift 
			FROM pos.tblTimesheet 
			WHERE tsDate='$date' AND tsStaff=$id;";
			if($result = @mysql_query($sql_test_si)) {
				if($row = @mysql_fetch_assoc($result)) {
					$shift = $row['shift'];
				} else {
					print (mysql_error());
					print "\n";
				}
			} else {
				print (mysql_error());
				print "\n";
			}
			if($shift != "1") {
				$sql = "UPDATE pos.tblTimesheet SET tsIn='$time',tsLogIn=1,tsStatus=1 
					WHERE tsDate='$date' AND tsStaff=$id;";
				if(@mysql_query($sql)) {
					$sql_si_msg = "SELECT tblTimesheet.tsXIn AS xIn 
					FROM pos.tblTimesheet 
					WHERE tsDate='$date' AND tsStaff=$id;";
					if($result=mysql_query($sql_si_msg)) {
						$row = @mysql_fetch_assoc($result);
						print "User [".$name."] Signin @ ".$time2."</br> Expected @ ".$row['xIn'].".\n";
					} else {
						print mysql_error();
						print "\n";
					}
				} else {
					print mysql_error();
					print "\n";
				}
			} else {
				$sql = "UPDATE pos.tblTimesheet SET tsIn='$time',tsLogIn=1,tsStatus=1 
					WHERE tsDate='$date' AND tsStaff=$id;";
				if($result=mysql_query($sql)) {
					print "User [".$name."] Signin @ ".$time2."</br> Expected @ DAY OFF.\n";
				} else {
					print mysql_error();
					print "\n";
				}
			}
		}
	} else if($type == "so") {
		if(testIn($id,$date)) {
			print "User [". $name ."] is not signed in.\n";
			return;
		} else {
			$sql_in = "SELECT tblTimesheet.tsIn AS tsIn,
				tblTimesheet.tsHrsWrk as tsHrsWrk 
				FROM pos.tblTimesheet 
				WHERE tsDate='$date' AND tsStaff=$id;";
			if($in = @mysql_query($sql_in)) {
				if($row = @mysql_fetch_assoc($in)) {
					$in_time = $row['tsIn'];
					$wrk_time = $row['tsHrsWrk'];
				}
			}
			$diff = $curtime[0] - strtotime($in_time);
			/*$sql_wrk = "SELECT tblTimesheet.tsHrsWrk as hrs 
				FROM pos.tblTimesheet 
				WHERE tsDate='$date' AND tsStaff=$id;";
			if($wrk = @mysql_query($sql_wrk)) {
				if($row = @mysql_fetch_assoc($wrk)) {
					$wrk_time = $row['hrs'];
				}
			}*/
			$tot_wrk = $wrk_time + $diff;
			$sql_so = "UPDATE pos.tblTimesheet 
				SET tsOut='$time',tsLogIn=0,tsStatus=0,tsHrsWrk=$tot_wrk 
				WHERE tsDate='$date' AND tsStaff=$id;";
			//print $sql_so;
			if(@mysql_query($sql_so)) {
				$sql_so_msg = "SELECT tblTimesheet.tsXOut AS xOut 
					FROM pos.tblTimesheet 
					WHERE tsDate='$date' AND tsStaff=$id;";
				if($result=mysql_query($sql_so_msg)) {
					$row = @mysql_fetch_assoc($result);
					print "User [".$name."] Signout @ ".$time2."</br> Expected @ ".$row['xOut']."</br> Worked For:".date("H:i:s",1255147200+$tot_wrk)."Hrs.\n";
				} else {
					print mysql_error();
					print "\n";
				}
			} else {
				print mysql_error();
				print "\n";
			}
		}
	} else {
		if(testIn($id,$date)) {
			print "User [". $name ."] is not signed in.\n";
			return;
		} else {
			$sql_test_oo = "SELECT tblTimesheet.tsStatus as status 
			FROM pos.tblTimesheet 
			WHERE tsDate='$date' AND tsStaff=$id;";
			if($result = @mysql_query($sql_test_oo)) {
				if($row = @mysql_fetch_assoc($result)) {
					$status = $row['status'];
				} else {
					print (mysql_error());
					print "\n";
				}
			} else {
				print (mysql_error());
				print "\n";
			}
			if($status == "1") {
				$sql = "UPDATE pos.tblTimesheet SET tsStatus=2 
					WHERE tsDate='$date' AND tsStaff=$id;";
				if(@mysql_query($sql)) {
					print "User [". $name ."] Set to Out Of Office @ " . $time2.".\n";
				} else {
					print mysql_error();
					print "\n";
				}
			} else {
				$sql = "UPDATE pos.tblTimesheet SET tsStatus=1 
					WHERE tsDate='$date' AND tsStaff=$id;";
				if(@mysql_query($sql)) {
					print "User [". $name ."] Set to Back To Office @ " . $time2.".\n";
				} else {
					print mysql_error();
					print "\n";
				}
			}
		}	
	}
} else {
	print "ID [".$_GET["id"]."] does not exist.\n";
}

function testIn($id,$date) {
	$sql = "SELECT tblTimesheet.tsLogIn AS tsLogIn 
		FROM pos.tblTimesheet 
		WHERE tsStaff=$id AND tsDate='$date';";
	//print $sql . "<br>";
	if($row = query_database($sql)) {
		if($row[0]['tsLogIn'] == "1") {
			return false;
		} else {
			return true;
		}
	} else {
		print mysql_error();
		print "\n";
	}	
}
?>