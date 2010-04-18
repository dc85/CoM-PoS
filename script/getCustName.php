<?php
ini_set("display_errors", 1);
error_reporting(E_NONE);
include("../backend/customer.php");

if(!isset($_GET['frame']) || empty($_GET['frame'])) {
	$str = $_GET['str'];
	
	$sql = "SELECT cFirstN,cLastN,cCoName,cID ".
		"FROM pos.tblCustomer ".
		"WHERE cLastN LIKE '%$str%' ".
		"OR cFirstN LIKE '%$str%' ".
		"OR cCoName LIKE '%$str%' ".
		"OR cAKA LIKE '%$str%' ".
		"AND cCardNum IS NOT NULL ".
		"AND cDelStatus=0";
	
	//print $sql;
	if($result = @mysql_query($sql)) {
		while($row = @mysql_fetch_assoc($result)) {
			$line = "";
			if(!empty($row["cLastN"]) && !empty($row["cFirstN"])) {
				$name = $row["cLastN"].",".$row["cFirstN"];
				$line .= htmlspecialchars($name, ENT_QUOTES,'UTF-8',true);
			} else {
				if($row["cLastN"] == "") {
					$name = $row["cFirstN"];
					$line .= htmlspecialchars($name, ENT_QUOTES,'UTF-8',true);
				} else if($row["cFirstN"] == "") {
					$name = $row["cLastN"];
					$line .= htmlspecialchars($name, ENT_QUOTES,'UTF-8',true);
				} else {
					$line .= "N/A";
				}
			}
			if(!empty($row["cCoName"])) {
				$coname = $row["cCoName"];
				//$line .= htmlspecialchars($coname, ENT_QUOTES,'UTF-8',true);
				$line .= " - ".htmlspecialchars($coname, ENT_QUOTES,'UTF-8',true);
			}
			print $line."%".$row["cID"]."\n";
		}
	} else {
		print $sql."\n";
		print (mysql_error())."\n";
	}
} else {
	$str = $_GET['str'];
	
	$sql = "SELECT cFirstN,cLastN,cCoName,cID ".
		"FROM pos.tblCustomer ".
		"WHERE cLastN LIKE '%$str%' ".
		"OR cFirstN LIKE '%$str%' ".
		"OR cCoName LIKE '%$str%' ".
		"OR cCardNum LIKE '%$str%' ".
		"OR cAKA LIKE '%$str%' ".
		"AND cCardNum IS NOT NULL ".
		"AND cDelStatus=0";
	
	//print $sql;
	if($result = @mysql_query($sql)) {
		$ii = 20;
		if(@mysql_num_rows($result) < $ii) {
			$ii = mysql_num_rows($result);
		}
		while($i < $ii) {
			$row = @mysql_fetch_assoc($result);
			$line = "";
			if(!empty($row["cLastN"]) && !empty($row["cFirstN"])) {
				$name = $row["cLastN"].",".$row["cFirstN"];
				$line .= htmlspecialchars($name, ENT_QUOTES,'UTF-8',true);
			} else {
				if($row["cLastN"] == "") {
					$name = $row["cFirstN"];
					$line .= htmlspecialchars($name, ENT_QUOTES,'UTF-8',true);
				} else if($row["cFirstN"] == "") {
					$name = $row["cLastN"];
					$line .= htmlspecialchars($name, ENT_QUOTES,'UTF-8',true);
				} else {
					$line .= "N/A";
				}
			}
			if(!empty($row["cCoName"])) {
				$coname = $row["cCoName"];
				//$line .= htmlspecialchars($coname, ENT_QUOTES,'UTF-8',true);
				$line .= " - ".htmlspecialchars($coname, ENT_QUOTES,'UTF-8',true);
			}
			print $line."%".$row["cID"]."\n";
			$i++;
		}
	} else {
		print $sql."\n";
		print (mysql_error())."\n";
	}
}
?>