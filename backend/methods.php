<?php 
function make_formtext_field($title,$name,$value,$tip, $width = PHPWriter::textfield_width,$disabled) {
	$btitle = wrap_tag($title,"b");
	//$result = $this->wrap_tag($btitle,"td");
	$result = $btitle;
	$input = "";
	if($disabled == 1) {
		$input = "<input class=\"inputfield\" type=\"text\" title=\"$tip\" size=\"$width\" name=\"$name\" value=\"$value\" readonly/>";
	} else {
		$input = "<input class=\"inputfield\" type=\"text\" title=\"$tip\" size=\"$width\" name=\"$name\" value=\"$value\"/>";
	}
	//$result .= $this->wrap_tag($input,"td");
	$result .= $input;
	return $result;
}

function make_formtext_field_green($title,$name,$value,$tip, $width = PHPWriter::textfield_width,$disabled) {
	$btitle = wrap_tag($title,"b");
	//$result = $this->wrap_tag($btitle,"td");
	$result = $btitle;
	$input = "";
	if($disabled == 1) {
		$input = "<input class=\"inputfield\" type=\"text\" title=\"$tip\" size=\"$width\" name=\"$name\" value=\"$value\" style=\"background-color: #99FF66;\" readonly/>";	
	} else {
		$input = "<input class=\"inputfield\" type=\"text\" title=\"$tip\" size=\"$width\" name=\"$name\" value=\"$value\" style=\"background-color: #99FF66;\"/>";
	}
	
	//$result .= $this->wrap_tag($input,"td");
	$result .= $input;
	return $result;
}

function make_dropdown_list($title,$name,$value,$val_array,$tip,$method) {
	$btitle = wrap_tag($title,"b");
	//$result = $this->wrap_tag($btitle,"td","width=5px");
	$result = $btitle;
	$list = "";
	if($val_array) {
		foreach ($val_array as $item) {
			$sel = ($item == $value)?"selected":"";
			$list .= wrap_tag($item,"option",$sel);
		}
	}
	$substr = "name = \"$name\" title=\"$tip\" onchange=\"$method\"";
	$list = wrap_tag($list,"select",$substr);
	//$result .= $this->wrap_tag($list,"td");
	$result .= $list;
	return $result;
}

function make_dropdown_listd($title,$name,$value,$val_array,$tip,$method,$disabled) {
	$btitle = wrap_tag($title,"b");
	//$result = $this->wrap_tag($btitle,"td","width=5px");
	$result = $btitle;
	$list = "";
	if($val_array) {
		foreach ($val_array as $item) {
			$sel = ($item == $value)?"selected":"";
			$list .= wrap_tag($item,"option",$sel);
		}
	}
	if($disabled == 0) {
		$substr = "name = \"$name\" title=\"$tip\" onchange=\"$method\"";
	} else if($disabled == 1) {
		$substr = "name = \"$name\" title=\"$tip\" onchange=\"$method\" disabled";
	}
	$list = wrap_tag($list,"select",$substr);
	//$result .= $this->wrap_tag($list,"td");
	$result .= $list;
	return $result;
}

function make_rentaldropdown_list($title,$name,$value,$val_array,$tip) {
	$btitle = wrap_tag($title,"b");
	//$result = $this->wrap_tag($btitle,"td","width=5px");
	$result = $btitle;
	$list = "";
	if($val_array) {
		foreach ($val_array as $item) {
			$sel = ($item == $value)?"selected":"";
			$list .= wrap_tag($item,"option",$sel);
		}
	}
	$substr = "name = \"$name\" title=\"$tip\" onchange=\"JavaScript: loadStaff();\"";
	$list = wrap_tag($list,"select",$substr);
	//$result .= $this->wrap_tag($list,"td");
	$result .= $list;
	return $result;
}

function make_editstaffformtext_field($title,$name,$value,$tip, $width = PHPWriter::textfield_width) {
	$btitle = wrap_tag($title,"b");
	//$result = $this->wrap_tag($btitle,"td");
	$result = wrap_tag($btitle,"td");
	$input = "<input class=\"inputfield\" type=\"text\" title=\"$tip\" size=\"$width\" name=\"$name\" value=\"$value\" onChange=\"JavaScript:addtoEditStaffArray('$name')\";/>";
	//$result .= $this->wrap_tag($input,"td");
	$result .= wrap_tag($input,"td");
	return wrap_tag($result,"tr");
}

function make_editstaffdropdown_list($title,$name,$value,$val_array,$tip) {
	$btitle = wrap_tag($title,"b");
	//$result = $this->wrap_tag($btitle,"td","width=5px");
	$result = wrap_tag($btitle,"td");
	$list = "";
	if($val_array) {
		foreach ($val_array as $item) {
			$sel = ($item == $value)?"selected":"";
			$list .= wrap_tag($item,"option",$sel);
		}
	}
	$substr = "name = \"$name\" title=\"$tip\" onChange=\"JavaScript:addtoEditStaffArray('$name')\"";
	$list = wrap_tag($list,"select",$substr);
	//$result .= $this->wrap_tag($list,"td");
	$result .= wrap_tag($list,"td");
	return wrap_tag($result,"tr");
}

function make_editcustformtext_field($title,$name,$value,$tip, $width = PHPWriter::textfield_width) {
	$btitle = wrap_tag($title,"b");
	//$result = $this->wrap_tag($btitle,"td");
	$result = $btitle;
	$input = "<input class=\"inputfield\" type=\"text\" title=\"$tip\" size=\"$width\" name=\"$name\" value=\"$value\" onChange=\"JavaScript:addtoEditCustArray('$name')\";/>";
	//$result .= $this->wrap_tag($input,"td");
	$result .= $input;
	return $result;
}

function make_editcustdropdown_list($title,$name,$value,$val_array,$tip) {
	$btitle = wrap_tag($title,"b");
	//$result = $this->wrap_tag($btitle,"td","width=5px");
	$result = $btitle;
	$list = "";
	if($val_array) {
		foreach ($val_array as $item) {
			$sel = ($item == $value)?"selected":"";
			$list .= wrap_tag($item,"option",$sel);
		}
	}
	$substr = "name = \"$name\" title=\"$tip\" onChange=\"JavaScript:addtoEditCustArray('$name')\"";
	$list = wrap_tag($list,"select",$substr);
	//$result .= $this->wrap_tag($list,"td");
	$result .= $list;
	return $result;
}

function make_paydropdown_list($title,$name,$value,$val_array,$tip) {
	$btitle = wrap_tag($title,"b");
	//$result = $this->wrap_tag($btitle,"td","width=5px");
	$result = $btitle;
	$list = "";
	if($val_array) {
		foreach ($val_array as $item) {
			$sel = ($item == $value)?"selected":"";
			$list .= wrap_tag($item,"option",$sel);
		}
	}
	$substr = "name = \"$name\" title=\"$tip\" onChange=\"JavaScript:uppOp('$name');\"";
	$list = wrap_tag($list,"select",$substr);
	//$result .= $this->wrap_tag($list,"td");
	$result .= $list;
	return $result;
}

function make_formdropdown_list($title,$name,$value,$val_array,$tip,$disabled) {
	$btitle = wrap_tag($title,"b");
	//$result = $this->wrap_tag($btitle,"td","width=5px");
	$result = $btitle;
	$list = "";
	if($val_array) {
		foreach ($val_array as $item) {
			$sel = ($item == $value)?"selected":"";
			$list .= wrap_tag($item,"option",$sel);
		}
	}
	$substr ="";
	if($disabled == 1) {
		$substr = "name = \"$name\" title=\"$tip\" disabled";	
	} else {
		$substr = "name = \"$name\" title=\"$tip\"";
	}
	
	$list = wrap_tag($list,"select",$substr);
	//$result .= $this->wrap_tag($list,"td");
	$result .= $list;
	return $result;
}

function make_controldropdown_list($title,$name,$value,$val_array,$tip) {
	$btitle = wrap_tag($title,"b","class=\"control\"");
	//$result = $this->wrap_tag($btitle,"td","width=5px");
	$result = $btitle;
	$list = "";
	if($val_array) {
		foreach ($val_array as $item) {
			$sel = ($item == $value)?"selected":"";
			$list .= wrap_tag($item,"option",$sel);
		}
	}
	$substr = "name = \"$name\" title=\"$tip\" onChange=\"submit();\"";
	$list = wrap_tag($list,"select",$substr);
	//$result .= $this->wrap_tag($list,"td");
	$result .= $list;
	return $result;
}


function make_scheduledropdown_list($title,$name,$value,$val_array,$tip,$date,$id) {
	//list($byType,$schYear1,$schWeek,$schYear2,$schMonth) = $this->get_query_parameters($_POST);
	$btitle = wrap_tag($title,"b");
	//$result = $this->wrap_tag($btitle,"td","width=5px");
	$result = $btitle;
	$list = "";
	if($val_array) {
		foreach ($val_array as $item) {
			$sel = ($item == $value)?"selected":"";
			$list .= wrap_tag($item,"option",$sel);
		}
	}
	//$substr = "name = \"$name\" title=\"$tip\" onChange=\"submit();\"";
	 //$substr =  "name = \"$name\" title=\"$tip\" onChange=\"window.open('/scheduler.php?sch=$name&byType=$byType&schYear1=$schYear1&schWeek=$schWeek&schYear2=$schYear2&schMonth=$schMonth','_self');\"";
	 //$substr = "name = \"$name\" title=\"$tip\"";
	$substr = "name = \"$name\" title=\"$tip\" onChange=\"schEnlist('$id:$date','$name');\"";
	//$substr = "name = \"$name\" title=\"$tip\" onChange=\"getSelected('$name');\"";
	$list = wrap_tag($list,"select",$substr);
	//$result .= $this->wrap_tag($list,"td");
	$result .= $list;
	return $result;
}
?>