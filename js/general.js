var error_messages = new Array();
/* hire_painter_submit.php */
error_messages['WEBPOS_MSG(02020445)'] = 'Hire painter appointment added';
error_messages['WEBPOS_ERR(02020445)'] = 'WebPoS failed to add the hire painter appointment';
/* hire_consultant_submit.php */
error_messages['WEBPOS_MSG(02020516)'] = 'Hire consultant appointment added';
error_messages['WEBPOS_ERR(02020516)'] = 'WebPoS failed to add the hire consultant appointment';
/* invoice_repay.php */
error_messages['WEBPOS_ERR(02131845)'] = 'Important repay information are missing';
error_messages['WEBPOS_ERR(02131131)'] = 'WebPoS failed to insert repayments to table';
error_messages['WEBPOS_MSG(02131131)'] = 'Repayments inserted successful';
/* customer_addnew.php */
error_messages['WEBPOS_MSG(02201403)'] = 'New customer added';
error_messages['WEBPOS_ERR(02201403)'] = 'Add customer failed, operation rolled back';
/* staff_addnew.php */
error_messages['WEBPOS_MSG(02201520)'] = 'New staff added';
error_messages['WEBPOS_ERR(02201520)'] = 'Add staff failed, operation rolled back';
/* product_addnew.php */
error_messages['WEBPOS_MSG(02201632)'] = 'New product added';
error_messages['WEBPOS_ERR(02201632)'] = 'Add product failed, operation rolled back';
error_messages['WEBPOS_ERR(02201750)'] = 'Error initializing product search';
error_messages['WEBPOS_ERR(02201754)'] = 'Error inserting new product';

var backtocust = null;

$(document).ready(function() {
	jQuery(document).bind('keydown', 'Alt+e',
		function(evt){ 
			if(showDebug == 0) {
				$("#debug").css( {
					"opacity" : "0.9",
					"z-index" : "10"
				});
				$("#debug").slideDown("slow");
				showDebug = 1;
			} else {
				$("#debug").fadeOut("slow");
				showDebug = 0;
			}
		return false; 
	});
});
	
function round2(n) {
	ans = n * 1000;
	ans = Math.round(ans /10) + ""; 
	while (ans.length < 3) {
		ans = "0" + ans;
	} 
	len = ans.length;
	ans = ans.substring(0,len-2) + "." + ans.substring(len-2,len);
	return ans;
}

function showLoadingZone() {
	$("#loadingZone").fadeIn("fast");
}

function hideLoadingZone() {
	$("#loadingZone").fadeOut("fast");
}

function hideQueryZone() {
	$("#queryZone").fadeOut("fast");
}

function reloadPIframe() {
	$("#loadingZone").css({
		"opacity": "0.5"
	});
	$("#loadingZone").fadeIn("fast");
	var str = document.getElementById('prodSearch').value;
	var filter = document.getElementById('search_pStore').selectedIndex;
	var supplier = document.getElementById('search_Supplier').selectedIndex;
	var url = "";
	if(filter == 1) {
		var url = "../php/product_print.php?query=" + str + "&store=1";
	} else if(filter == 2) {
		var url = "../php/product_print.php?query=" + str + "&store=2";
	} else {
		var url = "../php/product_print.php?query=" + str;
	}
	if(supplier != 0) {
		url += "&supplier=" + supplier;
	}
	// alert(url);
	window.open(url,"tableFrame");
}

function alertSelected() {
	var selector = document.getElementById('paintColourSelect');
	
	alert(selector.value);
}

function reloadPIframeInInv() {
	$("#loadingZone").css({
		"opacity": "0.5"
	});
	$("#loadingZone").fadeIn("fast");
	var str = document.getElementById('prodSearch').value;
	var spc = document.getElementById('invSPC').value;
	var sid = document.getElementById('defStoreID').value;
	
	var url = "../php/invoice_product_print.php?query=" + str + "&spc=" + spc + "&store=" + sid;
	window.open(url,"invProdFrame");
}

function reloadCIframe() {
	$("#loadingZone").css({
		"opacity": "0.5"
	});
	$("#loadingZone").fadeIn("fast");
	var str = document.getElementById('custSearch').value;
	//var range = document.getElementById('custSearchRange').value;
	var url = "../php/customer_print.php?query=" + str;
	window.open(url,"tableFrame");
	window.curCustomer = null;
}

function openInvCustomer(a,b) {
	// alert(a + "/" + b);
	window.open("../php/customer.php?loadchoice=yes&sID="+a+"&cID="+b,"_self");
}

function reloadStaffIframe() {
	$("#loadingZone").css({
		"opacity": "0.5"
	});
	$("#loadingZone").fadeIn("fast");
	var str = document.getElementById('staffSearch').value;
	var store = document.getElementById('search_sStore').selectedIndex;
	var url = "../php/staff_print.php?query=" + str;
	if(store == 1) {
		url += "&store=1";
	} else if(store == 2) {
		url += "&store=2";
	} else {
		
	}
	// alert(url);
	window.open(url,"tableFrame");
}

function reloadLIframe() {
	$("#loadingZone").css({
		"opacity": "0.5"
	});
	$("#loadingZone").fadeIn("fast");
	var str = document.getElementById('leadSearch').value;
	var url = "../php/leads_print.php?query=" + str;
	window.open(url,"tableFrame");
}

function reloadTimesheetIframe() {
	$("#loadingZone").css({
		"opacity": "0.5"
	});
	$("#loadingZone").fadeIn("fast");
	var str = document.getElementById('datePick').value;
	var url = "../php/timesheet_print.php?query=" + str;
	window.open(url,"tableFrame");
	var url2 = "../php/timesheet_summary.php?date=" + str;
	window.open(url2,"totalFrame");
	var url3 = "../php/timesheetgraph.php?date=" + str;
	window.open(url3,"specFrame");
}

/* Customer functions */
var custGlobalStoreID = 0;
var custGlobalCustID = 0;
var custGlobalSPC = 0;
var custGlobalFName = 0;
var custGlobalLName = 0;

function choose_cust(cSID,cID) {
	var selSID = parent.document.getElementById('selSID');
	var selCID = parent.document.getElementById('selCID');
	
	selSID.value = cSID;
	selCID.value = cID;
	
	var poststr = "custStoreID=" + cSID + "&custID=" + cID;
	
	if(navigator.appName == "Microsoft Internet Explorer") {
		http = new ActiveXObject("Microsoft.XMLHTTP");
	} else {
		http = new XMLHttpRequest();
	}
	http.open("GET", "../php/customer_retrieve.php?" + poststr, true);
	http.onreadystatechange=function() {
		if(http.readyState == 4) {
			var str = http.responseText;
			var list = str.split('|');
			custGlobalStoreID = list[0];
			custGlobalCustID = list[1];
			custGlobalSPC = list[4];
			custGlobalFName = list[2];
			custGlobalLName = list[3];
			url = "../php/customer_spec.php?store=" + custGlobalStoreID + "&id=" + custGlobalCustID;
			window.open(url,"specFrame");
		}
	}
	http.send(null);
}

function postElement(name,value,form) {
	var myInput = document.createElement("input") ;
	myInput.setAttribute("name", name) ;
	myInput.setAttribute("value", value);
	form.appendChild(myInput);
}

function openCustomer() {
	window.open("../php/customer.php","_self");
}

function openProduct() {
	window.open("../php/product.php","_self");
}

function openScheduler() {
	window.open("../php/scheduler.php","_self");
}

function openInvoice() {
	window.open("../php/invoice.php","_self");
}

function openTimesheet() {
	window.open("../php/timesheet.php","_self");
}

function openStaff() {
	window.open("../php/staff.php","_self");
}

function openReports() {
	window.open("../php/reports.php","_self");
}

function openLeads() {
	window.open("../php/leads.php","_self");
}

function openRental() {
	window.open("../php/rental.php","_self");
}

function openSeminar() {
	window.open("../php/seminar.php","_self");
}

function openLabels() {
	window.open("../php/labels.php","_self");
}

function openCalculator() {
	window.open("../php/calculator.php","_blank","resizable=0, location=0, scrollbars=1, width=617px, height=600px");
}

/* Scheduler funtions */

var updateArray = "";

// Function will update the update list as we go
function schEnlist(str,id) {
	// var updateArray = getArray();
	var tagged = getSelected(id);
	var idx2 = updateArray.indexOf(str);
	if( idx2 != -1) {
		var front = updateArray.substring(0,idx2+str.length+1);
		var back = updateArray.substring(updateArray.indexOf(']',idx2+str.length+1));
		// alert(front + tagged + back);
		// alert('THERE');
		// alert(back);
		updateArray = front + tagged + back;
		// setArray(updateArray);
		// alert(updateArray);
	} else {

		updateArray += str + ":" + tagged + "]";
		// setArray(updateArray);
		// alert(updateArray);
	}
	alert(updateArray);
}

function updateSchedule() {
	url = "../php/scheduler_update.php?array=" + window.tableFrame.updateArray;
	window.open(url, '_blank', 'height=300px, width=700px');
}

// Function will add a new schedule
function addSchedule() {
	$("#queryZone").css({
		"opacity": "0.5"
	});
	$("#queryZone").fadeIn("fast");
	var amount = "0";
	var year = "0";
	var type = "none";

	// var selection = document.length;
	if(document.getElementById('111').checked) {
		type = "byWeek"
	} else if(document.getElementById('222').checked) {
		type="byMonth";
	}
	// var selection = document.quiz.colour;
	if(type == "byWeek") {
		amount = (document.getElementById('whichWeek').selectedIndex) + 1;
	} else if(type == "byMonth") {
		amount = (document.getElementById('whichMonth').selectedIndex) + 1;
	}
	year = document.getElementById('whichYear').selectedIndex;
	// returns the iframe window document
	var frame = document.getElementById("tableFrame").contentWindow;
	var ray = new Array();
	ray = frame.document.getElementsByTagName("select");
	var str = "";
	// alert(ray.length);
	for(i=0;i<ray.length;i++) {
		var str;
		str += ray[i].name + ":" + ray[i].selectedIndex + ",";
	}
	// alert(str);
	var myForm = document.createElement("form");
	myForm.method="post" ;
	myForm.action = "../php/scheduler_add.php";
	myForm.target = "hiddenFrame";
	postElement("str",str,myForm);
	postElement("type",type,myForm);
	postElement("amount",amount,myForm);
	postElement("year",year,myForm);
	document.body.appendChild(myForm);
	myForm.submit() ;
	document.body.removeChild(myForm);
	
	// var url = "../php/scheduler_add.php?type=" + type + "&amount=" + amount +
	// "&year=" + year + "&str=" + str;
	// window.open(url, '_blank', 'scrollbars=yes,height=300px,width=700px');
}

// 1,2,3,4,5,6,7,8,9,10,11,13,14,15,16,17,18,19,20,21,22,24,27,31,32,34,37,38,39
function printSchedule() {
	var amount = "0";
	var year = "0";
	var type = "none";
	// var selection = document.length;
	if(document.getElementById('111').checked) {
		type = "byWeek"
	} else if(document.getElementById('222').checked) {
		type="byMonth";
	}
	// var selection = document.quiz.colour;
	if(type == "byWeek") {
		// amount = (document.getElementById('whichWeek').selectedIndex) + 1;
		alert("Schedule print by week not avaliable");
		return;
	} else if(type == "byMonth") {
		amount = (document.getElementById('whichMonth').selectedIndex) + 1;
	}
	year = document.getElementById('whichYear').selectedIndex;
	year += 2008;
	var url = "../php/print_schedule.php?&ttMonth=" + amount + "&ttYear=" + year;
	window.open(url, '_blank', 'height=600px, width=1000px');
}

function printTable() {
	window.open("../php/scheduler.php","_self");
}

function checkZip(str) {
	var z = document.getElementById(str);
	var zip = z.value;
	zip = zip.split(' ').join('');
	zip = zip.toUpperCase();
	if(zip.length <= 3) {
		z.value = zip;
		return;
	} else if(zip.length == 7) {
		z.value = zip.substr(0,3) + " " + zip.substr(3,3);
	} else {
		z.value = zip.substr(0,3) + " " + zip.substr(3);
	}
	if(str != "leadZip") {
		leadsEditUpdate(str);
	}
}

function getSelected(id) {
	list = new Array();
	list[0] = "";
	list[1] = "DAY OFF";
	list[2] = "FS1";
	list[3] = "FS2";
	list[4] = "MS1";
	list[5] = "MS2";
	list[6] = "BS1";
	list[7] = "BS2";
	list[8] = "IT";
	var idx = document.getElementById(id).selectedIndex;
	
	return list[idx];
}

function reloadSIframe() {
	$("#loadingZone").css({
		"opacity": "0.5"
	});
	$("#loadingZone").fadeIn("fast");
	var amount = "0";
	var year = "0";
	var type = "none";
	// var selection = document.length;
	if(document.getElementById('111').checked) {
		type = "byWeek"
	} else if(document.getElementById('222').checked) {
		type="byMonth";
	}
	// var selection = document.quiz.colour;
	if(type == "byWeek") {
		amount = (document.getElementById('whichWeek').selectedIndex) + 1;
	} else if(type == "byMonth") {
		amount = (document.getElementById('whichMonth').selectedIndex) + 1;
	}
	year = document.getElementById('whichYear').selectedIndex;
	var url = "../php/scheduler_print.php?type=" + type + "&amount=" + amount + "&year=" + year;
	window.open(url,"tableFrame");
}

function roundNumber(num, dec) {
	var result = Math.round(num*Math.pow(10,dec))/Math.pow(10,dec);
	return result;
}

function get_tax(n) {
	if(n == "1") {
		return "0.00";
	} else if(n == "2") {
		return "0.08";
	} else if(n == "3") {
		return "0.05";
	} else {
		return "--";
	}
}

function makePOSTRequest(url, parameters) {
	http_request = false;
	if (window.XMLHttpRequest) { // Mozilla, Safari,...
		http_request = new XMLHttpRequest();
		if (http_request.overrideMimeType) {
			// set type accordingly to anticipated content type
			// http_request.overrideMimeType('text/xml');
			http_request.overrideMimeType('text/html');
		}
	} else if (window.ActiveXObject) { // IE
		try {
			http_request = new ActiveXObject("Msxml2.XMLHTTP");
		} catch (e) {
			try {
				http_request = new ActiveXObject("Microsoft.XMLHTTP");
			} catch (e) {}
		}
	}
	if (!http_request) {
		alert('Cannot create XMLHTTP instance');
		return false;
	}
	
	http_request.onreadystatechange = alertContents;
	http_request.open('POST', url, true);
	http_request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	http_request.setRequestHeader("Content-length", parameters.length);
	http_request.setRequestHeader("Connection", "close");
	http_request.send(parameters);
}

function alertContents() {
    if (http_request.readyState == 4) {
       if (http_request.status == 200) {
    	   //alert(http_request.responseText);
    	   var str = http_request.responseText;
    	   if(http_request.responseText == "ADDTOCARTLESSTHANZERO") {
    		   alert("Cannot remove that many items from cart.");
    		   // document.getElementById("debugger").value += "Cannot remove
				// that many items from cart.";
    	   } else if(str.indexOf("New customer added...") != -1) {
    		   alert("Customer Added");
    		   document.getElementById("debugger").value += str;
    	   } else if(str.indexOf("EDITDONE") != -1) {
    		   alert("Customer Edited");
    		   document.getElementById("debugger").value += str;
    		   window.editMasterArray = "";
    	   } else {
    		   // alert(str.substring(11,19));
    		   //alert(str);
    		   if(str.substring(0,6) == "WEBPOS") {
    			   
    			   document.getElementById("debugger").value += error_messages[str] + "\n";
    			   if(str.substring(11,19) == "02020445") {
    				   generate_pdf('paint')
    			   } else if (str.substring(11,19) == "02020516") {
    				   generate_pdf('cont')
    			   } else if (str.substring(11,19) == "02131131") {
    				   
    			   } else if (str.substring(11,19) == "02251024") {
    				   //alert(str.substring(1,4));
    				   //alert(str.substring(7,10));
    				   var msg = document.getElementById("lb_returnMessage");
    				   //alert();
    				   if(str.substr(7,3) == "ERR") {
    					   //alert("ERROR");
	   						$("#returnMessage").css({
	   							"backgroundColor": "#FF3300"
	   						});
	   						msg.style.color = "white";
	   						// msg.style.fontWeight = "bold";
	   						msg.innerHTML = "Staff update failed.";
	   						$("#returnMessage").slideDown(300).delay(1500).fadeOut('fast');
	   						document.getElementById("debugger").value += str + "\n";
	   					} else if(str.substr(7,3) == "MSG") {
	   						//alert("MESSAGE");
	   						$("#returnMessage").css({
	   							"backgroundColor": "#00FF00"
	   						});
	   						msg.style.color = "black";
	   						// msg.style.fontWeight = "bold";
	   						msg.innerHTML = "Staff update successful.";
	   						$("#returnMessage").slideDown(300).delay(1500).fadeOut('fast');
	   					}
    				   //return;
    				   editArray = new Array();
    			   } else {
    				   document.getElementById("debugger").value += "ERROR MESG NUM NOT FOUND\n";
    			   }
    		   } else {
    			   //alert(str);
    			   document.getElementById("debugger").value += str;
    		   }
    		   // alert(http_request.responseText.substr(21));
    	   }
          // result = http_request.responseText;
          // document.getElementById('myspan').innerHTML = result;
       } else {
    	   // alert('There was a problem with the request.\n');
    	   alert(http_request.responseText+"\n");
       }
    }
}

function getBPrice(bSize) {
	if(navigator.appName == "Microsoft Internet Explorer") {
		http = new ActiveXObject("Microsoft.XMLHTTP");
	} else {
		http = new XMLHttpRequest();
	}
	http.open("GET", "../script/getbprice.php?input=" + bSize, true);
	http.onreadystatechange=function() {
		if(http.readyState == 4) {
			var str = http.responseText;
			var bp = document.getElementById("new_pBPrice");
			bp.value = str;
		}
	}
	http.send(null);
}

function getCPrice(cSize) {
	if(navigator.appName == "Microsoft Internet Explorer") {
		http = new ActiveXObject("Microsoft.XMLHTTP");
	} else {
		http = new XMLHttpRequest();
	}
	http.open("GET", "../script/getcprice.php?input=" + cSize, true);
	http.onreadystatechange=function() {
		if(http.readyState == 4) {
			var str = http.responseText;
			var bp = document.getElementById("new_pCPrice");
			bp.value = str;
		}
	}
	http.send(null);
}

function logout() {
	window.open("../src/index.php?logoff=1","_self");
}