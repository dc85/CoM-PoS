<?php
include("../backend/invoice.php");

  function create_shoppingcart_rows() {
	$result = NULL;
	if($this->shopping_cart) {
		$result = array();
		$record = $this->fetch_value_if_exists($_POST,'record2');
		$n = 0;
		if(@mysql_data_seek($this->shopping_cart,0)) {
			while($rec = mysql_fetch_assoc($this->shopping_cart)) {
				$selected = ($record == $n);
				$to_mark = ($record == $n+PHPWriter::record_list_offset);
				$row = "";			
				for($i = 0; $i < sizeof($this->header_shoppingcart); ++$i) {
					$fdata = $rec[$this->header_shoppingcart[$i]];
					$fdata = htmlspecialchars($fdata);
					$fdata = preg_replace("/\n/m","<br/>\n",$fdata);
					if($to_mark) {
						$fdata .= "<a name=\"r\"></a>";
					}
					$row .= $this->wrap_tag($fdata,"td");
				}
				$class = ($selected)?"selectcell":"cell" . ($n % 2);
				$result[] = $this->wrap_tag($row,"tr","class=\"$class\" onClick=\"choose_row2(" . $n . ");\"");
				$n++;
			}
		} else {
			
		}
	}
	return $result;
}


?>