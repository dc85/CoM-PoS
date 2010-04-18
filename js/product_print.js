function choose_row() {
	var arguments = choose_row.arguments;
	
	url = "../php/product_spec.php?upc=" + arguments[0];
	window.open(url,"specFrame");
}