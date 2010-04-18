$(function() {
	Date.firstDayOfWeek = 0;
	Date.format = 'yyyy-mm-dd';
	//$('.date-pick').datePicker({startDate:'1996-01-01'})
	$('#start-date').bind(
		'dpClosed',
		function(e, selectedDates)
		{
			var d = selectedDates[0];
			if (d) {
				d = new Date(d);
				$('#end-date').dpSetStartDate(d.addDays(1).asString());
			}
		}
	);
	$('#end-date').bind(
		'dpClosed',
		function(e, selectedDates)
		{
			var d = selectedDates[0];
			if (d) {
				d = new Date(d);
				$('#start-date').dpSetEndDate(d.addDays(-1).asString());
			}
		}
	);
});

function clearAll() {
	var eles = new Array("start-date","end-date","new_rUPC","new_rPType","new_rPDescription",
			"new_cID","new_rLastN","new_rFirstN","new_rPhone","new_rDID","new_rDIDType",
			"new_rDAmt","new_rDType","new_rStore","new_rStaff");
	
	for(var i=0; i<eles.length; i++) {
		var ele = document.getElementById(eles[i]);
		if(eles[i] == "new_rStore") { 
			ele.selectedIndex = 0;
		} else if(eles[i] == "new_rStaff") {
			ele.options.length = 0;
		} else {
			ele.value = "";
		}
	}
}

function hao() {
	var doc = document.getElementById("new_rInvoice");
	alert(doc.innerHTML);
}

function submitRent() {
	var eles = new Array("new_rInvoice","start-date","end-date","new_rUPC","new_rPType","new_rPDescription",
			"new_rcID","new_rLastN","new_rFirstN","new_rPhone","new_rDID","new_rDIDType",
			"new_rDAmt","new_rDType","new_rStore","new_rStaff");
	
	var myForm = document.createElement("form");
	myForm.method="post" ;
	myForm.action = "../script/rental_addnew.php";
	myForm.target = "_blank";

	for(var i=0; i<eles.length; i++) {
		var ele = document.getElementById(eles[i]);
		if(eles[i] == "new_rStore") {
			var val = ele[ele.selectedIndex].innerHTML;
			postElement(""+eles[i]+"",val,myForm);
		} else if(eles[i] == "new_rStaff") {
			var val = ele[ele.selectedIndex].innerHTML; 
			postElement(""+eles[i]+"",val,myForm);
		} else if(eles[i] == "new_rInvoice") {
			var val = ele.innerHTML; 
			postElement(""+eles[i]+"",val,myForm);
		} else {
			postElement(""+eles[i]+"",ele.value,myForm);
		}
	}
	document.body.appendChild(myForm);
	myForm.submit() ;
	document.body.removeChild(myForm);
}

function lookup(inputString) {
	if(inputString.length == 0) {
		// Hide the suggestion box.
		$('#suggestions').hide();
	} else {
		$.post("../php/rental_getupclist.php", {queryString: ""+inputString+""}, function(data){
			if(data.length >0) {
				//alert(data);
				//var list = data.split("|");
				$('#suggestions').show();
				$('#autoSuggestionsList').html(data);
			}
		});
	}
} // lookup

function fill(thisValue) {
	$('#new_rUPC').val(thisValue);
	setTimeout("$('#suggestions').hide();", 200);
	setTimeout("fillRest()",400);
}

function fillRest() {
	var ele = document.getElementById("new_rUPC");
	$.post("../php/rental_fillitem.php", {queryString: ""+ele.value+""}, function(data){
		if(data.length >0) {
			list = data.split("|");
			var e1 = document.getElementById("new_rPType");
			var e2 = document.getElementById("new_rPDescription");
			e1.value = list[0];
			e2.value = list[1];
		}
	});
}

function show_rent_detail(id) {
	//var tt = parent.document.getElementById('rentStart');
	//alert(tt);
	//alert(tt.value);
	var selRID = parent.document.getElementById("pageRID");
	
	selRID.value = id;
	
	var titles = new Array("rID","rInvoice","rStart","rEnd",
			"rUPC","rPType","rPDescription","rRenter","rLastN",
			"rFirstN","rPhone","rDepositID","rDepositIDType",
			"rDepositAmt","rDepositType","rStaff","rStore","rStatus");
	//var itemList = new Array('rentalID','rentInvoice','rentStart','rentEnd','rentItemID','rentItem','rentItemType',
	//		'rentID','rentLName','rentFName','rentDepositID','rentDepositIDType','rentDepositAmt','rentDepositType',
	//		'rentStaff','rentStore','rentReturned');
	if(navigator.appName == "Microsoft Internet Explorer") {
		http = new ActiveXObject("Microsoft.XMLHTTP");
	} else {
		http = new XMLHttpRequest();
	}
	http.open("GET", "../php/rental_getdetail.php?id=" + id, true);
	http.onreadystatechange=function() {
		if(http.readyState == 4) {
			var str = http.responseText;
			var list = str.split("|");
			//alert(str);
			for ( var i = 0; i < (titles.length); i++) {
				var ele = parent.document.getElementById(titles[i]);
				if(titles[i] == "rStatus") {
					if(list[i] == "1") {
						ele.value = "RETURNED";
					} else if(list[i] == "0") {
						ele.value = "NOT RETURNED";
					} 
				} else if(titles[i] == "rPDescription") {
					ele.innerHTML = list[i];
				} else {
					ele.innerHTML = list[i];
				}
			}
		}
	}
	http.send(null);
}

function setReturnStatus() {
	var selRID = document.getElementById("pageRID");
	
	if(selRID.value == "-1") {
		alert("No rental record selected.");
		return;
	}
	
	id = selRID.value;
	
	var ele = parent.document.getElementById("rStatus");
	var returned;
	
	if(ele.value == "RETURNED") {
		returned = 0;
	} else {
		returned = 1;
	}
	
	var poststr = "rID=" + id + "&return=" + returned;
	//alert(poststr);
	if (navigator.appName == "Microsoft Internet Explorer") {
		http = new ActiveXObject("Microsoft.XMLHTTP");
	} else {
		http = new XMLHttpRequest();
	}
	http.open("GET", "../php/rental_setstatus.php?" + poststr, true);
	http.onreadystatechange = function() {
		if (http.readyState == 4) {
			if (http.status == 200) {
				var str = http.responseText;
				if (str == "1") {
					ele.value = "RETURNED";
				} else {
					ele.value = "NOT RETURNED";
				}
			} else {
				alert("Problem");
			}
		}
	}
	http.send(null);
}

function newRent() {
	window.open("../php/rental_new.php","_blank","resizable=0, location=0, scrollbars=1, width=617px, height=600px");
}

function loadStaff() {
	var store = document.getElementById("new_rStore").selectedIndex;
	//alert(store);
	var poststr = "store=" + store;
	//alert(poststr);
	if (navigator.appName == "Microsoft Internet Explorer") {
		http = new ActiveXObject("Microsoft.XMLHTTP");
	} else {
		http = new XMLHttpRequest();
	}
	http.open("GET", "../php/rental_getstafflist.php?" + poststr, true);
	http.onreadystatechange = function() {
		if (http.readyState == 4) {
			if (http.status == 200) {
				var str = http.responseText;
				var items = str.split("|");
				//alert(list);
				var selector = document.getElementById("new_rStaff");
				selector.options.length = 0;
				for(i=0;i<(items.length-1);i++) {
					selector.options[i+1] = new Option(items[i],items[i],true,false);
				}
			} else {
				alert("Problem");
			}
		}
	}
	http.send(null);
}