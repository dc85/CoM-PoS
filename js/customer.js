/*
 * Functions for the add new customer popup.
 */

var cur_page = 0;
var addCustomerWindowOpen = 0;
var outstandingWindowOpen = 0;
var lookupWindowOpen = 0;
var hirePainterWindowOpen = 0;
var hireConsultantWindowOpen = 0;
var showDebug = 0;
var repaymentOptions = 1;
var fullRepay = 0;
var editMasterArray = "";
var tags = new Array();
var invList = new Array();
var repayStr = new Array();
var repayList = new Array();
var curCustomer = null;
var payOpt ="";

$(document).ready(function(){

	$("#addCustomerPopupContactClose").click(function(){
		disableAddCustomerPopup();
	});
	
	$("#outstandingPopupContactClose").click(function() {
		disableOutstandingPopup();
	});
	
	$("#lookupPopupContactClose").click(function() {
		disableLookupPopup();
	});
	
	$("#hirePainterPopupContactClose").click(function() {
		disableHirePainterPopup();
	});
	
	$("#hireConsultantPopupContactClose").click(function() {
		disableHireConsultantPopup();
	});

	//Click out event!
	$("#backgroundPopup").click(function(){
		if(addCustomerWindowOpen == 1) {
			disableAddCustomerPopup();
		} else if(outstandingWindowOpen == 1) {
			disableOutstandingPopup();
		} else if(lookupWindowOpen == 1) {
			disableLookupPopup();
		} else if(hirePainterWindowOpen == 1) {
			disableHirePainterPopup();
		} else if(hireConsultantWindowOpen == 1) {
			disableHireConsultantPopup();
		}

	});
	//Press Escape event!
	$(document).keypress(function(e){
		if(e.keyCode==27 && (addCustomerWindowOpen==1)){
			disableAddCustomerPopup();
		}
		if (e.keyCode == 27 && (outstandingWindowOpen == 1)) {
			disableOutstandingPopup();
		}
		if (e.keyCode == 27 && (parent.outstandingWindowOpen == 1)) {
			parent.disableOutstandingPopup();
		}
		if (e.keyCode == 27 && (lookupWindowOpen == 1)) {
			disableLookupPopup();
		}
		if (e.keyCode == 27 && (hirePainterWindowOpen == 1)) {
			disableHirePainterPopup();
		}
		if (e.keyCode == 27 && (hireConsultantWindowOpen == 1)) {
			disableHireConsultantPopup();
		}
	});
});

function showAddCustomerPopup(){
	if(addCustomerWindowOpen==0){
		$("#backgroundPopup").css({
			"opacity": "0.7"
		});
		$("#backgroundPopup").fadeIn("fast");
		$("#addCustomerPopupContact").fadeIn("fast");
		$("#add2").hide();
		$("#add3").hide();
		$("#add1").show();
		addCustomerWindowOpen = 1;
		cur_page = 1;
		document.getElementById('new_scanRIN').focus();
	}
}

function disableAddCustomerPopup(){
	//disables popup only if it is enabled
	if(addCustomerWindowOpen = 1){
		$("#backgroundPopup").fadeOut("fast");
		$("#addCustomerPopupContact").fadeOut("fast");
		addCustomerWindowOpen = 0;
		cur_page = 0;
	}
	document.getElementById("custSearch").focus();
}

function cancelAddCustomerPopup() {
	// disables popup only if it is enabled
	setTimeout("clearAddCustomer()",50);
	if(addCustomerWindowOpen = 1){
		$("#backgroundPopup").fadeOut("fast");
		$("#addCustomerPopupContact").fadeOut("fast");
		addCustomerWindowOpen = 0;
		cur_page = 0;
	}
	document.getElementById("custSearch").focus();
}

function showOutstandingPopup() {
	var selSID = document.getElementById('selSID').value;
	var selCID = document.getElementById('selCID').value;
	if(selSID == "-1" || selCID == "-1") {
		alert("Please select customer to see outstanding invoices");
		return;	
	}
	if (outstandingWindowOpen == 0) {
		invOutstandingFrame.window.location.href = "../script/invoice_outstanding_print.php?cID=" + selCID;
		invOSItemsFrame.window.location.href = "../script/invoice_outstandingitems_print.php";
		invRepaidFrame.window.location.href = "../script/invoice_outstanding_pay_print.php?cID=" + selCID;
		window.tags = new Array();
		window.invList = new Array();
		window.repayList = new Array();
		setTimeout("clearRepay()",10);
		$("#backgroundPopup").css( {
			"opacity" : "0.7"
		});
		$("#backgroundPopup").fadeIn("fast");
		$("#outstandingPopupContact").fadeIn("fast");
		outstandingWindowOpen = 1;
	}
}

function showLookupPopup() {
	var selSID = document.getElementById('selSID').value;
	var selCID = document.getElementById('selCID').value;
	if(selSID == "-1" || selCID == "-1") {
		alert("Please select customer to lookup invoices");
		return;	
	}
	if (lookupWindowOpen == 0) {
		var today = new Date();
		//$("#start-date").datePicker().dpSetSelected(new Date().asString());
		//$("#end-date").datePicker().dpSetSelected(new Date().asString());
		//document.getElementById("date-end").value = today.asString("yyyy-mm-dd");
		//document.getElementById("date-start").value = today.asString("yyyy-mm-dd");
		lookupInvoiceFrame.window.location.href = "../script/lookup_invc_print.php?cid=" + curCustomer.c2;
		lookupProductFrame.window.location.href = "../script/invoice_outstandingitems_print.php";
		lookupPaymentFrame.window.location.href = "../script/invoice_outstanding_pay_print.php";
		$("#backgroundPopup").css( {
			"opacity" : "0.7"
		});
		$("#backgroundPopup").fadeIn("fast");
		$("#lookupPopupContact").fadeIn("fast");
		lookupWindowOpen = 1;
	}
}

function showHirePainterPopup(){
	if(hirePainterWindowOpen==0){
		var now = new Date();
		var today = now.toDateString();
		//var today = dateFormat(now,"dddd, mmmm Ds yyyy");
		document.getElementById("hpContID").value = "-1";
		document.getElementById("hpDate").value = today.substr(0,3) + "," + today.substr(3);
		painterFrame.window.location.href = "../script/hirePainter_print.php";
		hiredPainterFrame.window.location.href = "../script/hired_print.php?who=hiredPainter";
		$("#backgroundPopup").css({
			"opacity": "0.7"
		});
		$("#backgroundPopup").fadeIn("fast");
		$("#hirePainterPopupContact").fadeIn("fast");
		hirePainterWindowOpen = 1;
	}
}

function showHireConsultantPopup(){
	if(hireConsultantWindowOpen==0){
		var now = new Date();
		var today = now.toDateString();
		document.getElementById("hccContID").value = "-1";
		document.getElementById("hccDate").value = today.substr(0,3) + "," + today.substr(3);
		consultantFrame.window.location.href = "../script/hireConsultant_print.php?filter=";
		hiredConsultantFrame.window.location.href = "../script/hired_print.php?who=hiredCC";
		$("#backgroundPopup").css({
			"opacity": "0.7"
		});
		$("#backgroundPopup").fadeIn("fast");
		$("#hireConsultantPopupContact").fadeIn("fast");
		hireConsultantWindowOpen = 1;
	}
}

function disableOutstandingPopup() {
	// disables popup only if it is enabled
	if (outstandingWindowOpen = 1) {
		$("#backgroundPopup").fadeOut("fast");
		$("#outstandingPopupContact").fadeOut("fast");
		outstandingWindowOpen = 0;
	}
}

function disableLookupPopup() {
	// disables popup only if it is enabled
	if (lookupWindowOpen = 1) {
		$("#backgroundPopup").fadeOut("fast");
		$("#lookupPopupContact").fadeOut("fast");
		lookupWindowOpen = 0;
	}
}

function disableHirePainterPopup() {
	// disables popup only if it is enabled
	if (lookupWindowOpen = 1) {
		$("#backgroundPopup").fadeOut("fast");
		$("#hirePainterPopupContact").fadeOut("fast");
		hirePainterWindowOpen = 0;
	}
}

function disableHireConsultantPopup() {
	// disables popup only if it is enabled
	if (lookupWindowOpen = 1) {
		$("#backgroundPopup").fadeOut("fast");
		$("#hireConsultantPopupContact").fadeOut("fast");
		hireConsultantWindowOpen = 0;
	}
}

function resetCustSearch() {
	document.getElementById("custSearch").value = "";
	document.getElementById("custSearchRange").selectedIndex = 0;
	document.getElementById("custSearch").focus();
	//var today = new Date();
	//alert(today.toString('yyyy'));
}

function checkRIN(str) {
	
}

function checkInput(eleName) {
	var str = document.getElementById(eleName).value;
	if(eleName == "new_cRIN") {
		var regEx = /[a-zA-Z]\d{14}/;
		var reg = str.match(regEx);
		if(reg == null || str.length > 15) {
			document.getElementById(eleName).style.backgroundColor = "orange";
		} else if(reg != null) {
			document.getElementById(eleName).style.backgroundColor = "white";
			document.getElementById(eleName).value = str.toUpperCase();
		}
	} else if(eleName == "new_cCardNum") {
		var regEx = /\d{5}/;
		var reg = str.match(regEx);
		if(reg == null || str.length > 5) {
			document.getElementById(eleName).style.backgroundColor = "orange";
		} else if(reg != null) {
			document.getElementById(eleName).style.backgroundColor = "white";
		}
	} else if(eleName == "new_cPantSize") {
		var regEx = /\d{2}x\d{2}/;
		var reg = str.match(regEx);
		if(reg == null || str.length > 5) {
			document.getElementById(eleName).style.backgroundColor = "orange";
		} else if(reg != null) {
			document.getElementById(eleName).style.backgroundColor = "white";
		}
	} else if(eleName == "new_cPantSize") {
		var regEx = /\d{2}x\d{2}/;
		var reg = str.match(regEx);
		if(reg == null || str.length > 5) {
			document.getElementById(eleName).style.backgroundColor = "orange";
		} else if(reg != null) {
			document.getElementById(eleName).style.backgroundColor = "white";
		}
	} else if(eleName == "new_cUnitB" || eleName == "new_cUnitH" || eleName == "new_cUnitS") {
		if(isNaN(str)) {
			document.getElementById(eleName).style.backgroundColor = "orange";
		} else {
			document.getElementById(eleName).style.backgroundColor = "white";
		}
	} else if(eleName == "new_cZipB" || eleName == "new_cZipH" || eleName == "new_cZipS") {
		var regEx = /[a-zA-Z]\d[a-zA-Z]\s?\d[a-zA-Z]\d/;
		var reg = str.match(regEx);
		if(reg == null || str.length > 7) {
			document.getElementById(eleName).style.backgroundColor = "orange";
		} else if(reg != null) {
			document.getElementById(eleName).style.backgroundColor = "white";
		}
	} else if(eleName == "new_cPhone1" || eleName == "new_cPhone2" || eleName == "new_cPhone3" || eleName == "new_cPhone4" || eleName == "new_cPhone5") {
		var regEx = /\d{10,11}/;
		var regEx2 = /\d{3}-\d{3}-\d{4}/;
		var reg = str.match(regEx);
		var reg2 = str.match(regEx2);
		if((reg == null && reg2 == null) || str.length > 12) {
			document.getElementById(eleName).style.backgroundColor = "orange";
		} else if(reg != null || reg2 != null) {
			document.getElementById(eleName).style.backgroundColor = "white";
		}
	} else if(eleName == "new_cCredit") {
		if(isNaN(str)) {
			document.getElementById(eleName).style.backgroundColor = "orange";
		} else {
			document.getElementById(eleName).value = round2(str);
			document.getElementById(eleName).style.backgroundColor = "white";
		}
	} 
}

function addNewCustomerHelper() {
	var complete = true;
	var panel = 3;
	var mia = "";
	
	var eleNames = new Array("cFirstN","cLastN","cCoName","cDoB","cRIN",
			"cCardNum","cCustType","cExpert","cUnitB","cAdrBus","cCityB",
			"cProvB","cZipB","cpType1","cPhone1");
	
	for(var i=0;i<eleNames.length;i++) {
		var ele = document.getElementById("new_"+eleNames[i]);
		if(ele.type == "text") {
			if(eleNames[i] == "cRIN") {
				if(ele.value == "") {
					complete = false;
					if(mia == "") {
						mia = "new_" + eleNames[i];
					}
					ele.style.backgroundColor = "red";
					if(panel > 1) {
						panel = 1;
					}
				} else {
					var rinStr = document.getElementById("new_cRIN").value;
					var reRIN = /[A-Z]\d{14}/;
					var rin = rinStr.match(reRIN);
					if(rin != null) {
						document.getElementById("new_cRIN").style.backgroundColor = "orange";
					}
				}
			} else if(ele.value == "") {
				complete = false;
				if(mia == "") {
					mia = "new_" + eleNames[i];
				}
				ele.style.backgroundColor = "red";
				if(panel > 1) {
					panel = 1;
				}
			}
		} else if(ele.type == "select-one") {
			if(ele.selectedIndex == 0) {
				complete = false;
				if(mia == "") {
					mia = "new_" + eleNames[i];
				}
				ele.style.backgroundColor = "red";
				if(panel > 1) {
					panel = 1;
				}
			}
		} else {
			if(ele.value == "") {
				complete = false;
				if(mia == "") {
					mia = "new_" + eleNames[i];
				}
				ele.style.backgroundColor = "red";
				if(panel > 1) {
					panel = 1;
				}
			}
		}
	}
	
	/*var eleNames = new Array("cFirstN","cLastN","cDoB","cCustType",
			"cUnitB","cAdrBus","cCityB","cProvB","cZipB","cpType1","cPhone1");
	for ( var i = 0; i < eleNames.length; i++) {
		var ele = document.getElementById("new_" + eleNames[i]);
		if (ele.type == "text" && ele.value == "") {
			ele.style.backgroundColor = "red";
			if(mia == "") {
				mia = "new_" + eleNames[i];
			}
			complete = false;
		} else if (ele.type == "select-one" && ele.selectedIndex == 0) {
			ele.style.backgroundColor = "red";
			if(mia == "") {
				mia = "new_" + eleNames[i];
			}
			complete = false;
		}
	}
	
	var eleNames = new Array("cRIN","cCardNum","cCoName","cExpert");
	
	for(var i=0;i<eleNames.length;i++) {
		var ele = document.getElementById("new_"+eleNames[i]);
		if(ele.type == "text") {
			if(eleNames[i] == "cRIN") {
				if(ele.value == "") {
					complete = false;
					if(mia == "") {
						mia = "new_" + eleNames[i];
					}
					ele.style.backgroundColor = "red";
					if(panel > 1) {
						panel = 1;
					}
				} else {
					var rinStr = document.getElementById("new_cRIN").value;
					var reRIN = /[A-Z]\d{14}/;
					var rin = rinStr.match(reRIN);
					if(rin != null) {
						document.getElementById("new_cRIN").style.backgroundColor = "orange";
					}
				}
			} else if(ele.value == "") {
				complete = false;
				if(mia == "") {
					mia = "new_" + eleNames[i];
				}
				ele.style.backgroundColor = "red";
				if(panel > 1) {
					panel = 1;
				}
			}
		} else if(ele.type == "select-one") {
			if(ele.selectedIndex == 0) {
				complete = false;
				if(mia == "") {
					mia = "new_" + eleNames[i];
				}
				ele.style.backgroundColor = "red";
				if(panel > 1) {
					panel = 1;
				}
			}
		} else {
			if(ele.value == "") {
				complete = false;
				if(mia == "") {
					mia = "new_" + eleNames[i];
				}
				ele.style.backgroundColor = "red";
				if(panel > 1) {
					panel = 1;
				}
			}
		}
	}*/

	if (!complete) {
		if(panel == 1) {
			$("#add1").fadeIn("fast");
			$("#add2").fadeOut("fast");
			$("#add3").fadeOut("fast");
			$("#add4").fadeOut("fast");
		} else if(panel == 2) {
			$("#add1").fadeOut("fast");
			$("#add2").fadeIn("fast");
			$("#add3").fadeOut("fast");
			$("#add4").fadeOut("fast");
		}
		document.getElementById(mia).focus();
		document.getElementById("nc_warning").innerHTML = "One or more required field are not filled in.";
		return;
	}
	var answer = confirm("Confirm add new customer");
	if (answer) {
		//setTimeout("fillPrices()", 500);
		setTimeout("addNewCustomer()", 100);
	} else {
		return;
	}
}

function addNewCustomer() {
	var eleNames = new Array("cTitle","cFirstN","cLastN",
			"cAKA","cCoName","cDoB","cRIN","cCardNum","cCustType","cExpert",
			"cShirtSize","cPantSize","cE1","cE2","cE3","cE4","cE5","cE6",
			"cE7","cE8","cE9","cE10","cUnitB","cAdrBus","cCityB","cProvB",
			"cZipB","cpType1","cPhone1","cEmail1","cUnitH","cAdrHome",
			"cCityH","cProvH","cZipH","cpType2","cPhone2","cEmail2",
			"cUnitS","cAdrShip","cCityS","cProvS","cZipS","cpType3",
			"cPhone3","cEmail3","cpType4","cPhone4",
			"cpType5","cPhone5","cTax1","cTax2","cSPC","cEcoFee",
			"cCustRep","cExpNum","cExpDate","cCredit");
	var poststr = "";
	for(var i=0;i<eleNames.length;i++) {
		var eleName = "new_"+eleNames[i];
		var ele = document.getElementById(eleName);
		if(ele == null) {
			alert(eleNames[i]);
		}
		if(ele.type == "text") {
			if(poststr.length == 0) {
				poststr += eleNames[i] + "=" + document.getElementById(eleName).value;
			} else {
				poststr += "&" + eleNames[i] + "=" + document.getElementById(eleName).value;
			}
		} else if(ele.type == "select-one") {
			if(poststr.length == 0) {
				poststr += eleNames[i] + "=" + document.getElementById(eleName).selectedIndex;
			} else {
				poststr += "&" + eleNames[i] + "=" + document.getElementById(eleName).selectedIndex;
			}
		} else if(ele.type == "checkbox") {
			if(ele.checked == true) {
				if(poststr.length == 0) {
					poststr += eleNames[i] + "=1";
				} else {
					poststr += "&" + eleNames[i] + "=1";
				}
			} else {
				if(poststr.length == 0) {
					poststr += eleNames[i] + "=0";
				} else {
					poststr += "&" + eleNames[i] + "=0";
				}
			}
		} else {
			if(poststr.length == 0) {
				poststr += eleNames[i] + "=" + document.getElementById(eleName).value;
			} else {
				poststr += "&" + eleNames[i] + "=" + document.getElementById(eleName).value;
			}
		}
	}
	//alert(poststr);
	makePOSTRequest('../script/customer_addnew.php',poststr);
}

function nextClick() {
	if(cur_page == 1) {
		$("#add1").fadeOut("fast");
		$("#add2").fadeIn("fast");
		cur_page = 2;
	} else if(cur_page == 2) {
		$("#add2").fadeOut("fast");
		$("#add3").fadeIn("fast");
		cur_page = 3;
	} else {
		return;
	}
}

function prevClick() {
	if(cur_page == 3) {
		$("#add3").fadeOut("fast");
		$("#add2").fadeIn("fast");
		cur_page = 2;
	} else if(cur_page == 2) {
		$("#add2").fadeOut("fast");
		$("#add1").fadeIn("fast");
		cur_page = 1;
	} else {
		return;
	}
}

function clearAddCustomer() {
	var eleNames = new Array("scanRIN","cTitle","cFirstN","cLastN",
			"cAKA","cCoName","cDoB","cRIN","cCardNum","cCustType","cExpert",
			"cShirtSize","cPantSize","cE1","cE2","cE3","cE4","cE5","cE6",
			"cE7","cE8","cE9","cE10","cUnitB","cAdrBus","cCityB","cProvB",
			"cZipB","cpType1","cPhone1","cEmail1","cUnitH","cAdrHome",
			"cCityH","cProvH","cZipH","cpType2","cPhone2","cEmail2",
			"cUnitS","cAdrShip","cCityS","cProvS","cZipS","cpType3",
			"cPhone3","cEmail3","cpType4","cPhone4",
			"cpType5","cPhone5","cTax1","cTax2","cSPC","cEcoFee",
			"cCustRep","cExpNum","cExpDate","cCredit");
	for(i=0;i<eleNames.length;i++) {
		var eleName = "new_"+eleNames[i];
		if(document.getElementById(eleName) == null) {
			alert(eleName);
		} else if(document.getElementById(eleName).type == "select-one") {
			document.getElementById(eleName).selectedIndex = 0;
		} else if(document.getElementById(eleName).type == "text") {
			document.getElementById(eleName).value = "";
		} else if(document.getElementById(eleName).type == "checkbox") {
			document.getElementById(eleName).checked = false;
		} else {
			document.getElementById(eleName).innerHTML = "";
		}
	}
}

function custEditUpdate(eleName) {
	var ele = document.getElementById(eleName);
	//alert(eleName);
	if(ele.type == "text") {
		if(editMasterArray.indexOf(eleName) != -1) {
			//alert("STRING EXISTS");
			//var subs = editMasterArray.substring(editMasterArray.indexOf(eleName) + eleName.length + 1, editMasterArray.indexOf("|",editMasterArray.indexOf(eleName) + eleName.length + 1));
			var before = editMasterArray.substring(0,editMasterArray.indexOf(eleName) + eleName.length + 1);
			var after = editMasterArray.substring(editMasterArray.indexOf("&",editMasterArray.indexOf(eleName) + eleName.length + 1),editMasterArray.length);
			editMasterArray = before + ele.value + after;
		} else {
			editMasterArray += eleName + "=" + ele.value;
			editMasterArray += "&";
		}
	} else if(ele.type == "select-one") {
		if(editMasterArray.indexOf(eleName) != -1) {
			var before = editMasterArray.substring(0,editMasterArray.indexOf(eleName) + eleName.length + 1);
			var after = editMasterArray.substring(editMasterArray.indexOf("&",editMasterArray.indexOf(eleName) + eleName.length + 1),editMasterArray.length);
			editMasterArray = before + ele.selectedIndex + after;
		} else {
			editMasterArray += eleName + "=" + ele.selectedIndex;
			editMasterArray += "&";
		}
	} else if(ele.type == "checkbox"){
		var checkd = "";
		//alert(eleName);
		if(editMasterArray.indexOf(eleName) != -1) {
			if(ele.checked == true) {
				checkd = "1";
			} else if(ele.checked == false) {
				checkd = "0";
			}
			var before = editMasterArray.substring(0,editMasterArray.indexOf(eleName) + eleName.length + 1);
			var after = editMasterArray.substring(editMasterArray.indexOf("&",editMasterArray.indexOf(eleName) + eleName.length + 1),editMasterArray.length);
			editMasterArray = before + checkd + after;
		} else {
			if(ele.checked == true) {
				checkd = "1";
			} else if(ele.checked == false) {
				checkd = "0";
			}
			editMasterArray += eleName + "=" + checkd;
			editMasterArray += "&";
		}
	}
	//alert(editMasterArray);
}

function editCustomerHelp() {
	var answer = confirm("Confirm edit product");
	if(answer)
		setTimeout("editCustomer()",100);
	else
		return;
}

function refreshCustomer() {
	var selSID = document.getElementById('selSID').value;
	var selCID = document.getElementById('selCID').value;
	if(selSID == "-1" || selCID == "-1") {
		return;
	} else {
		//var fun = "choose_cust(" + selSID + "," + selCID + ")";
		//setTimeout(fun,100);
		choose_cust(curCustomer);
	}
}

function editCustomer() {
	var selSID = document.getElementById('selSID').value;
	var selCID = document.getElementById('selCID').value;
	if(selSID == "-1" || selCID == "-1") {
		alert("Please select customer to edit");
		return;	
	}
	if(window.editMasterArray.length <= 0) {
		alert("No changes has been made to the record");
		return;
	}
	var str = window.editMasterArray.substring(0,window.editMasterArray.length-1);
	var poststr = str + "&ecStoreID=" + selSID + "&ecID=" + selCID;
	//alert(poststr);
	makePOSTRequest('../script/customer_edit.php',poststr);
}

function scanRIN() {
	var scan = document.getElementById("new_scanRIN").value;
	try {
		var list = scan.split('^');
		var names = list[1].split('$');
		var adr = list[2].split('$');
		var loc = adr[1].split(',');
		var reZip = /[A-Z]\d[A-Z][\s]\d[A-Z]\d/;
		var reRIN = /[A-Z]\d{14}/;
		var rin = list[3].match(reRIN);
		var zip = list[3].match(reZip);
	} catch(err) {
		document.getElementById("debugger").value+="There was one or more errors with the scan code.\n";
		document.getElementById("debugger").value+=err.description+"\n";
		return;
	}
	
	var firstName = "";
	var lastName = names[names.length-1];
	for(var i=0;i<names.length-1;i++) {
		firstName += trim(names[i]) + ",";
	}
	document.getElementById("new_cFirstN").value = firstName.substr(0,firstName.length-1);
	document.getElementById("new_cLastN").value = lastName;
	document.getElementById("new_cAdrBus").value = trim(adr[0]);
	document.getElementById("new_cCityB").value = trim(loc[0]);
	var p;
	switch(trim(loc[1])) {
		case "AB":
			p=1;
			break;
		case "BC":
			p=2;
			break;
		case "MB":
			p=3;
			break;
		case "NB":
			p=4;
			break;
		case "NL":
			p=5;
			break;
		case "NT":
			p=6;
			break;
		case "NS":
			p=7;
			break;
		case "NU":
			p=8;
			break;
		case "ON":
			p=9;
			break;
		case "PE":
			p=10;
			break;
		case "QC":
			p=11;
			break;
		case "SK":
			p=12;
			break;
		case "YT":
			p=13;
			break;
			
	}
	document.getElementById("new_cProvB").selectedIndex = p;
	if(rin) {
		document.getElementById("new_cRIN").value = rin;
	}
	if(zip) {
		document.getElementById("new_cZipB").value = zip;
	}
}

function trim(str) {
    if(!str || typeof str != 'string')
        return null;

    return str.replace(/^[\s]+/,'').replace(/[\s]+$/,'').replace(/[\s]{2,}/,' ').replace(/,/,'');
}

/*
 * Functions for selecting a new customer, takes two parameters
 * cSID	: the store ID that the customer belongs to (not in use yet)
 * cID	: the customer ID that is assigned to the account
 */

function choose_cust(obj) {
	//window.prevObj = obj;
	parent.curCustomer = obj;
	//window.backtocust = obj;
	//alert(window.backtocust);
	editMasterArray = "";
	var titles = new Array("cIsActive","cTitle","cFirstN","cLastN",
			"cAKA","cDoB","cRIN","cCardNum","cCustType","cCoName",
			"cShirtSize","cPantSize","cExpert","cpType4","cPhone4",
			"cpType5","cPhone5","cE1","cE2","cE3","cE4","cE5","cE6",
			"cE7","cE8","cE9","cE10","cUnitB","cAdrBus","cCityB","cProvB",
			"cZipB","cpType1","cPhone1","cEmail1","cUnitH","cAdrHome",
			"cCityH","cProvH","cZipH","cpType2","cPhone2","cEmail2",
			"cUnitS","cAdrShip","cCityS","cProvS","cZipS","cpType3",
			"cPhone3","cEmail3","cTax1","cTax2","cSPC","cEcoFee",
			"cCustRep","cExpNum","cExpDate","cCredit","cCBal",
			"cBalance","cNote");
	
	var selSID = parent.document.getElementById('selSID');
	var selCID = parent.document.getElementById('selCID');
	var selSPC = parent.document.getElementById('selSPC');
	var selFirstN = parent.document.getElementById('selFirstN');
	var selLastN = parent.document.getElementById('selLastN');
	//var selCoName = parent.document.getElementById('selCoName');
	
	selSID.value = obj.c1;
	selCID.value = obj.c2;
	
	var poststr = "custSID=" + obj.c1 + "&custCID=" + obj.c2;
	
	if(navigator.appName == "Microsoft Internet Explorer") {
		http = new ActiveXObject("Microsoft.XMLHTTP");
	} else {
		http = new XMLHttpRequest();
	}
	http.open("GET", "../script/customer_retrieve.php?" + poststr, true);
	http.onreadystatechange=function() {
		if(http.readyState == 4) {
			var str = http.responseText;
			//parent.document.getElementById("debugger").value += str;
			var list = str.split('|');
			//alert(list);
			for(var i = 0; i < (titles.length); i++) {
				var ele = parent.document.getElementById(titles[i]);
				if(ele == null) {
					alert(titles[i]);
				}
				if(list[i] == "") {
					if (titles[i] == "pPromo") {
						ele.value = "";;
					} else {
						ele.innerText = "";
						ele.style.backgroundColor = "#FF6633";
					}
					if(titles[i] != "cpType1" ||
							titles[i] != "cpType2" || titles[i] != "cpType1" ||
							titles[i] != "cpType4" || titles[i] != "cpType5") {
						ele.style.backgroundColor = "#FF6633";
					}
					if(ele.type == "text") {
						ele.value = "";
					} else if(ele.type == "select-one") {
						ele.selectedIndex = 0;
					} else if(ele.type == "checkbox") {
						ele.checked = false;
					} else {
						//do nothing
					}
				} else {
					ele.style.backgroundColor = "#ffffff";
					if(titles[i] == "cIsActive") {
						if(list[i] == "0") {
							ele.value = "ACTIVE";
						} else {
							ele.value = "INACTIVE";
						}
					} else if(titles[i] == "cPromo") {
						//alert(ele.type);
					} else if(titles[i] == "cSPC") {
						selSPC.value = list[i];
						ele.selectedIndex = list[i];
					} else if(titles[i] == "cLastN") {
						selLastN.value = list[i];
						ele.value = list[i];
					} else if(titles[i] == "cFirstN") {
						selFirstN.value = list[i];
						ele.value = list[i];
					} else if(titles[i] == "cCustRep") {
						//alert(list[i]);
						ele.selectedIndex = list[i];
					} else if(titles[i] == "cEcoFee") {
						if(list[i] == "0") {
							ele.selectedIndex = 2;
							ele.style.backgroundColor = "#FF6633";
						} else {
							ele.selectedIndex = 1;
							ele.style.backgroundColor = "#99FF00";
						}
					} else if(titles[i] == "cE1" ||
							titles[i] == "cE2" || titles[i] == "cE3" ||
							titles[i] == "cE4" || titles[i] == "cE5" ||
							titles[i] == "cE6" || titles[i] == "cE7" ||
							titles[i] == "cE8" || titles[i] == "cE9" ||
							titles[i] == "cE10") {
						//alert(ele.type);
						if(list[i] != "0") {
							ele.checked = true;
						} else {
							ele.checked = false;
						}
					} else if(titles[i] == "cpType1" ||
							titles[i] == "cpType2" || titles[i] == "cpType1" ||
							titles[i] == "cpType4" || titles[i] == "cpType5") {
						//alert(list[i]);
						if(list[i] == "1") {
							ele.src = "../images/phone_business.png";
							ele.title = "Business Number";
						} else if(list[i] == "2") {
							ele.src = "../images/phone_fax.png";
							ele.title = "Fax Number";
						} else if(list[i] == "3") {
							ele.src = "../images/phone_home.png";
							ele.title = "Home Number";
						} else if(list[i] == "4") {
							ele.src = "../images/phone_cell.png";
							ele.title = "Cell Number";
						} else {
							ele.src = "../images/phone_contact.png";
							ele.title = "Contact Number";
						}	
					} else {
						if(ele.type == "text") {
							ele.value = list[i];
						} else if(ele.type == "select-one") {
							ele.selectedIndex = list[i];
						}
					}
				}
			}
		}
	}
	http.send(null);
	setTimeout("getARs()",500);
}

/*function choose_rcust(sid,cid) {
	//window.prevObj = obj;
	//parent.curCustomer = obj;
	//alert(sid + "/"+cid);
	editMasterArray = "";
	var titles = new Array("cIsActive","cTitle","cFirstN","cLastN",
			"cAKA","cDoB","cRIN","cCardNum","cCustType","cCoName",
			"cShirtSize","cPantSize","cExpert","cpType4","cPhone4",
			"cpType5","cPhone5","cE1","cE2","cE3","cE4","cE5","cE6",
			"cE7","cE8","cE9","cE10","cUnitB","cAdrBus","cCityB","cProvB",
			"cZipB","cpType1","cPhone1","cEmail1","cUnitH","cAdrHome",
			"cCityH","cProvH","cZipH","cpType2","cPhone2","cEmail2",
			"cUnitS","cAdrShip","cCityS","cProvS","cZipS","cpType3",
			"cPhone3","cEmail3","cTax1","cTax2","cSPC","cEcoFee",
			"cCustRep","cExpNum","cExpDate","cCredit","cCBal",
			"cBalance","cNote");
	
	var selSID = document.getElementById('selSID');
	var selCID = document.getElementById('selCID');
	//var selSPC = document.getElementById('selSPC');
	//var selFirstN = parent.document.getElementById('selFirstN');
	//var selLastN = parent.document.getElementById('selLastN');
	//var selCoName = parent.document.getElementById('selCoName');
	
	selSID.value = sid;
	selCID.value = cid;
	
	var poststr = "custSID=" + sid + "&custCID=" + cid;
	//alert(poststr);
	
	if(navigator.appName == "Microsoft Internet Explorer") {
		http = new ActiveXObject("Microsoft.XMLHTTP");
	} else {
		http = new XMLHttpRequest();
	}
	http.open("GET", "../script/customer_retrieve.php?" + poststr, true);
	http.onreadystatechange=function() {
		if(http.readyState == 4) {
			var str = http.responseText;
			alert(str);
			parent.document.getElementById("debugger").value += str;
			var list = str.split('|');
			//alert(list);
			for(var i = 0; i < (titles.length); i++) {
				var ele = parent.document.getElementById(titles[i]);
				if(ele == null) {
					alert(titles[i]);
				}
				if(list[i] == "") {
					if (titles[i] == "pPromo") {
						ele.value = "";;
					} else {
						ele.innerText = "";
						ele.style.backgroundColor = "#FF6633";
					}
					if(titles[i] != "cpType1" ||
							titles[i] != "cpType2" || titles[i] != "cpType1" ||
							titles[i] != "cpType4" || titles[i] != "cpType5") {
						ele.style.backgroundColor = "#FF6633";
					}
					if(ele.type == "text") {
						ele.value = "";
					} else if(ele.type == "select-one") {
						ele.selectedIndex = 0;
					} else if(ele.type == "checkbox") {
						ele.checked = false;
					} else {
						//do nothing
					}
				} else {
					ele.style.backgroundColor = "#ffffff";
					if(titles[i] == "cIsActive") {
						if(list[i] == "0") {
							ele.value = "ACTIVE";
						} else {
							ele.value = "INACTIVE";
						}
					} else if(titles[i] == "cPromo") {
						//alert(ele.type);
					} else if(titles[i] == "cSPC") {
						selSPC.value = list[i];
						ele.selectedIndex = list[i];
					} else if(titles[i] == "cLastN") {
						selLastN.value = list[i];
						ele.value = list[i];
					} else if(titles[i] == "cFirstN") {
						selFirstN.value = list[i];
						ele.value = list[i];
					} else if(titles[i] == "cCustRep") {
						//alert(list[i]);
						ele.selectedIndex = list[i];
					} else if(titles[i] == "cEcoFee") {
						if(list[i] == "0") {
							ele.selectedIndex = 2;
							ele.style.backgroundColor = "#FF6633";
						} else {
							ele.selectedIndex = 1;
							ele.style.backgroundColor = "#99FF00";
						}
					} else if(titles[i] == "cE1" ||
							titles[i] == "cE2" || titles[i] == "cE3" ||
							titles[i] == "cE4" || titles[i] == "cE5" ||
							titles[i] == "cE6" || titles[i] == "cE7" ||
							titles[i] == "cE8" || titles[i] == "cE9" ||
							titles[i] == "cE10") {
						//alert(ele.type);
						if(list[i] != "0") {
							ele.checked = true;
						} else {
							ele.checked = false;
						}
					} else if(titles[i] == "cpType1" ||
							titles[i] == "cpType2" || titles[i] == "cpType1" ||
							titles[i] == "cpType4" || titles[i] == "cpType5") {
						//alert(list[i]);
						if(list[i] == "1") {
							ele.src = "../images/phone_business.png";
							ele.title = "Business Number";
						} else if(list[i] == "2") {
							ele.src = "../images/phone_fax.png";
							ele.title = "Fax Number";
						} else if(list[i] == "3") {
							ele.src = "../images/phone_home.png";
							ele.title = "Home Number";
						} else if(list[i] == "4") {
							ele.src = "../images/phone_cell.png";
							ele.title = "Cell Number";
						} else {
							ele.src = "../images/phone_contact.png";
							ele.title = "Contact Number";
						}	
					} else {
						if(ele.type == "text") {
							ele.value = list[i];
						} else if(ele.type == "select-one") {
							ele.selectedIndex = list[i];
						}
					}
				}
			}
		}
	}
	http.send(null);
	setTimeout("getARs()",500);
}*/

function getARs() {
	var owning = false;
	var owningAmt = 0;
	var selCID = parent.document.getElementById('selCID').value;
	if(navigator.appName == "Microsoft Internet Explorer") {
		http = new ActiveXObject("Microsoft.XMLHTTP");
	} else {
		http = new XMLHttpRequest();
	}
	http.open("GET", "../script/customer_getAR.php?cID=" + selCID, true);
	http.onreadystatechange=function() {
		if(http.readyState == 4) {
			var str = http.responseText;
			var list = str.split(",");
			parent.document.getElementById("debugger").value += str+"\n";
			parent.document.getElementById("cCurrent").value = round2(list[0]);
			if(parseFloat(list[0]) > 0) {
				parent.document.getElementById("cCurrent").style.background = "#FF0000";
				owning = true;
				owningAmt += parseFloat(list[0]); 
			}
			parent.document.getElementById("c30").value = round2(list[1]);
			parent.document.getElementById("c60").value = round2(list[2]);
			parent.document.getElementById("c90").value = round2(list[3]);
			if(parseFloat(list[1]) > 0) {
				parent.document.getElementById("c30").style.background = "#FF0000";
				owning = true;
				owningAmt += parseFloat(list[1]); 
			}
			
			if(parseFloat(list[2]) > 0) {
				parent.document.getElementById("c60").style.background = "#FF0000";
				owning = true;
				owningAmt += parseFloat(list[2]);
			}
			
			if(parseFloat(list[3]) > 0) {
				parent.document.getElementById("c90").style.background = "#FF0000";
				owning = true;
				owningAmt += parseFloat(list[3]);
			}
			if(owning) {
				alert("Customer has outstanding balance that is 30 days or older: " + owningAmt);
			}
		}
	}
	http.send(null);
}

function openCustomerInvoice() {
	
	var selSID = parent.document.getElementById('selSID');
	var selCID = parent.document.getElementById('selCID');
	var selSPC = parent.document.getElementById('selSPC');
	var selFirstN = parent.document.getElementById('selFirstN');
	var selLastN = parent.document.getElementById('selLastN');
	var selCoName = document.getElementById('cCoName');
	if(selSID.value == "-1" && selCID.value == "-1" &&
			selSPC.value == "-1" && selFirstN.value == "-1" &&
			selLastN.value == "-1") {
		alert("No customer selected");
		return;
	}
	//alert(window.tableFrame.custGlobalStoreID);
	//alert(window.tableFrame.custGlobalCustID);
	//url =  "../php/invoice.php?";
	//window.open(url,"_self");
	//window.backtocust = curCustomer;
	var myForm = document.createElement("form");
	myForm.method="post" ;
	myForm.action = "../php/invoice.php";
	myForm.target = "_self";
	postElement("a",selSID.value,myForm);
	postElement("b",selCID.value,myForm);
	postElement("c",selSPC.value,myForm);
	postElement("d",selFirstN.value,myForm);
	postElement("e",selLastN.value,myForm);
	postElement("f",selCoName.value,myForm);
	var today = new Date();
	var jan1st = new Date();
	jan1st.setFullYear(today.getFullYear(),0,1);
	jan1st.setHours(0);
	jan1st.setMinutes(0);
	jan1st.setSeconds(0);
	//alert(jan1st);
	t = today.getTime();
	j = jan1st.getTime();
	var diff = t - j;
	var year = today.getFullYear();
	var days = Math.floor(diff/1000/60/60/24);
	var d = "";
	if(days < 10) {
		d = "00" + days;
	} else if(10 < days < 100) {
		d = "0" + days;
	} else {
		d = days;
	}
	var hours = today.getHours();
	if(hours < 10) {
		h = "0" + hours;
	} else {
		h = hours;
	}
	var minutes = today.getMinutes();
	if(minutes < 10) {
		m = "0" + minutes;
	} else {
		m = minutes;
	}
	var seconds = today.getSeconds();
	if(seconds < 10) {
		s = "0" + seconds;
	} else {
		s = seconds;
	}
	var ses = "M" + year+d+h+m+s;
	//var ses = "M" + year+d+hours+minutes+seconds;
	//alert(ses);
	postElement("ts",ses,myForm);
	
	document.body.appendChild(myForm) ;
	myForm.submit() ;
	document.body.removeChild(myForm) ;
}

function sendMail(dom) {
	var ele = parent.document.getElementById(dom);
}

function showLoadingZone() {
	$("#loadingZone").fadeIn("fast");
}

function hideLoadingZone() {
	$("#loadingZone").fadeOut("fast");
}

function reloadIframe(url) {
	$("#loadingZone").css({
		"opacity": "0.5"
	});
	$("#loadingZone").fadeIn("fast");
	window.open("../php/customer_print.php","tableFrame");
}

/* Customer page methods */
/*var editMasterArray = "";

function editCustomer() {
	if(window.editSpecFrame.editMasterArray.length <= 0) {
		alert("No changes has been made to the record");
		return;
	}
	var selSID = document.getElementById('selSID').value;
	var selCID = document.getElementById('selCID').value;
	//var getstr = window.editSpecFrame.editMasterArray.substring(0,window.editSpecFrame.editMasterArray.length-1);
	var getstr = window.editSpecFrame.editMasterArray + "ecStoreID@" + selSID + "|ecID@" + selCID; 
	alert(getstr);
	if(navigator.appName == "Microsoft Internet Explorer") {
		http = new ActiveXObject("Microsoft.XMLHTTP");
	} else {
		http = new XMLHttpRequest();
	}
	http.open("GET", "../php/customer_edit.php?str=" + getstr, true);
	http.onreadystatechange=function() {
		if(http.readyState == 4) {
			var str = http.responseText;
			alert(str);
		}
	}
	http.send(null);
}

function addtoEditCustArray(eleName) {
	var ele = document.getElementById(eleName);
	if(ele.type == "text") {
		if(editMasterArray.indexOf(eleName) != -1) {
			//alert("STRING EXISTS");
			//var subs = editMasterArray.substring(editMasterArray.indexOf(eleName) + eleName.length + 1, editMasterArray.indexOf("|",editMasterArray.indexOf(eleName) + eleName.length + 1));
			var before = editMasterArray.substring(0,editMasterArray.indexOf(eleName) + eleName.length + 1);
			var after = editMasterArray.substring(editMasterArray.indexOf("|",editMasterArray.indexOf(eleName) + eleName.length + 1),editMasterArray.length);
			editMasterArray = before + ele.value + after;
		} else {
			editMasterArray += eleName + "@" + ele.value;
			editMasterArray += "|";
		}
	} else if(ele.type == "select-one") {
		if(editMasterArray.indexOf(eleName) != -1) {
			var before = editMasterArray.substring(0,editMasterArray.indexOf(eleName) + eleName.length + 1);
			var after = editMasterArray.substring(editMasterArray.indexOf("|",editMasterArray.indexOf(eleName) + eleName.length + 1),editMasterArray.length);
			editMasterArray = before + ele.selectedIndex + after;
		} else {
			editMasterArray += eleName + "@" + ele.selectedIndex;
			editMasterArray += "|";
		}
	} else if(ele.type == "checkbox"){
		var checkd = "";
		if(editMasterArray.indexOf(eleName) != -1) {
			if(ele.checked == true) {
				checkd = "1";
			} else {
				checkd = "0";
			}
			var before = editMasterArray.substring(0,editMasterArray.indexOf(eleName) + eleName.length + 1);
			var after = editMasterArray.substring(editMasterArray.indexOf("|",editMasterArray.indexOf(eleName) + eleName.length + 1),editMasterArray.length);
			editMasterArray = before + checkd + after;
		} else {
			if(ele.checked == true) {
				checkd = "1";
			} else {
				checkd = "0";
			}
			editMasterArray += eleName + "@" + checkd;
			editMasterArray += "|";
		}
	}
}*/

/* Edit Customer */

/*function editCustomerHelp() {
	var answer = confirm("Confirm edit customer");
	if(answer)
		setTimeout("saveCustomer()",100);
	else
		return;
}

function custEditUpdate(eleName) {
	var selSID = document.getElementById('selSID').value;
	var selCID = document.getElementById('selCID').value;
	if(selSID == "-1" || selCID == "-1") {
		alert("Please select product to edit");
		return;	
	}
	if(editMasterArray.indexOf(eleName) != -1) {
		return;
	} else {
		editMasterArray += eleName + ",";
	}
	//editMasterArray += eleName + ",";
	//alert(editMasterArray);
}*/

function saveCustomer() {
	var selSID = document.getElementById('selSID').value;
	var selCID = document.getElementById('selCID').value;
	var poststr = "cID=" + selCID;
	//alert(poststr);
	if(selSID == "-1" || selCID == "-1") {
		alert("Please select customer to edit");
		return;	
	}
	if(editMasterArray.length <= 0) {
		alert("No changes has been made to the record");
		return;
	}
	editMasterArray = editMasterArray.substr(0,editMasterArray.length-1);
	//alert(editMasterArray);
	var list = editMasterArray.split(",");
	for(var i=0;i<(list.length);i++) {
		var ele = document.getElementById(list[i]);
		if(ele.type == "text") {
			if(poststr.length == 0) {
				poststr += list[i] + "=" + document.getElementById(list[i]).value;
			} else {
				poststr += "&" + list[i] + "=" + document.getElementById(list[i]).value;
			}
		} else if(ele.type == "select-one") {
			if(poststr.length == 0) {
				poststr += list[i] + "=" + document.getElementById(list[i]).selectedIndex;
			} else {
				poststr += "&" + list[i] + "=" + document.getElementById(list[i]).selectedIndex;
			}
		} else if(ele.type == "checkbox") {
			if(ele.checked == true) {
				if(poststr.length == 0) {
					poststr += list[i] + "=1";
				} else {
					poststr += "&" + list[i] + "=1";
				}
			} else {
				if(poststr.length == 0) {
					poststr += list[i] + "=0";
				} else {
					poststr += "&" + list[i] + "=0";
				}
			}
		} else {
			if(poststr.length == 0) {
				poststr += list[i] + "=" + document.getElementById(list[i]).value;
			} else {
				poststr += "&" + list[i] + "=" + document.getElementById(list[i]).value;
			}
		}
	}
	//alert(poststr);
	makePOSTRequest('../script/customer_edit.php',poststr);
	editMasterArray = "";

}

function addCustomer() {
	var eleName = new Array('newCTitle','newCLastN','newCFirstN','newCAKA','newCDoB','newCRIN','newCCardNum',
			'newCIsActive','newCCustType','newCCoName','newCShirtSize','newCPantSize','newCeType',
			'newCE1','newCE2','newCE3','newCE4','newCE5','newCE6','newCE7','newCE8','newCE9','newCE10',
			'newCUnitH','newCAdrHome','newCCityH','newCProvH','newCZipH','newCpType1','newCPhone1','newCEmail1',
			'newCUnitB','newCAdrBus','newCCityB','newCProvB','newCZipB','newCpType2','newCPhone2','newCEmail2',
			'newCUnitS','newCAdrShip','newCCityS','newCProvS','newCZipS','newCpType3','newCPhone3','newCEmail3');
	var postValue = new Array();
	var i;
	var poststr = "";
	var x = 0;
	//alert(document.getElementById(eleName).value);
	for(i=0;i<eleName.length;i++) {
		//alert(document.getElementById(eleName[i]).type);
		if(document.getElementById(eleName[i]).type == "select-one") {
			//alert(document.getElementById(eleName[i]).options[document.getElementById(eleName[i]).selectedIndex].innerHTML);
			//alert(document.getElementById(eleName[i]).selectedIndex);
			if(document.getElementById(eleName[i])[document.getElementById(eleName[i]).selectedIndex].innerHTML != "") {
				if(x == 0) {
					poststr += eleName[i] + "=" + encodeURI(document.getElementById(eleName[i])[document.getElementById(eleName[i]).selectedIndex].innerHTML);
				} else {
					poststr += "&" + eleName[i] + "=" + encodeURI(document.getElementById(eleName[i])[document.getElementById(eleName[i]).selectedIndex].innerHTML);
				}
				x++;
			}
		} else {
			if(document.getElementById(eleName[i]).value != "") {
				if(x == 0) {
					poststr += eleName[i] + "=" + encodeURI(document.getElementById(eleName[i]).value);
				} else {
					poststr += "&" + eleName[i] + "=" + encodeURI(document.getElementById(eleName[i]).value);
				}
				x++;
			}
		}
	}
	makePOSTRequest('../php/customer_addnew.php',poststr);
}

/*
 * Repayments
 */

function checkRepayAuth(eleName) {
	var t = document.getElementById("repayMethod" + eleName).selectedIndex;
	var auth = document.getElementById("repayAuth" + eleName);
	if (t==3 || t == 4 || t == 5) {
		auth.disabled = false;
		auth.focus();
	} else {
		auth.disabled = true;
	}
}

function choose_repay() {
	var txt = "";
	var i;
	var payments = new Array();
	var payIndex = document.getElementById("repaySize").selectedIndex;
	//var payNum = parseInt(document.getElementById("paySize")[payIndex].value);
	var repayTotal = document.getElementById("repayAmt").value;
	if (payIndex == 2) {
		repaypop1(repayTotal.substr(1));
		window.payOpt = "M";
	} else if(payIndex == 1) {
		document.getElementById("repayAmt1").value = repayTotal.substr(1);
		document.getElementById("repayAmt2").value = "0.00";
		document.getElementById("repayAmt3").value = "0.00";
		window.payOpt = "S";
	}
}

function repaypop1(n) {
	//alert(window.paymentOptions);
	txt = 'Enter First Amount'
			+ '<div class="field"><label for="firstAmount">Amount</label>'
			+ '<input type="hidden" id="tag" name="tag" value="1st">'
			+ '<input type="text" id="firstAmount" name="firstAmount" value="'
			+ n + '" />'
			+ '<input type="hidden" id="leftover" name="leftover" value="' + n
			+ '" /></div>';
	$.prompt(txt, {
		callback : repaypop2,
		buttons : {
			Ok : true,
			Cancel : false
		},
		prefix : 'cleanblue'
	});
}

function repaypop2(v, m, f) {
	// var leftover = document.getElementById("leftover");
	// var firstPay = document.getElementById("firstAmount");
	//window.paymentOptions = 1;
	var leftover = f.leftover;
	var firstPay = f.firstAmount;
	var lefto = round2(parseFloat(leftover) - parseFloat(firstPay));
	var amt = document.getElementById("repayAmt1");
	//var remain = document.getElementById("payRemain");
	amt.value = f.firstAmount;
	var v = f.leftover;
	//remain.value = lefto;
	v.value = lefto;
	//alert(lefto);
	//reupdate_val(v, m, f);
	//alert(window.paymentOptions);
	if (lefto > 0) {
		txt = 'Enter Second Amount'
				+ '<div class="field"><label for="secondAmount">Amount</label>'
				+ '<input type="hidden" id="tag" name="tag" value="2nd">'
				+ '<input type="text" id="secondAmount" name="secondAmount" value="'
				+ lefto + '" />'
				+ '<input type="hidden" id="leftover" name="leftover" value="'
				+ lefto + '" /></div>';

		$.prompt(txt, {
			//callback : repaypop3,
			callback : refinal_update,
			buttons : {
				Ok : true,
				Cancel : false
			},
			prefix : 'cleanblue'
		});
	}
}

function refinal_update(v, m, f) {
	//var remain = document.getElementById("payRemain").value;
	var amt2 = document.getElementById("repayAmt2");
	var amt3 = document.getElementById("repayAmt3");
	amt2.value = f.secondAmount;
	amt3.value = round2(parseFloat(f.leftover) - parseFloat(f.secondAmount));
	window.repaymentOptions = 3;
	//alert(window.paymentOptions);
	//update_val(v, m, f)
	//var lefto = round2(parseFloat(remain) - parseFloat(f.thirdAmount));
	//remain.value = leftto;
}

function clearRepay() {
	var eleNames = new Array("repayAmt","repayFull","repayFlat","repaySize",
			"repayAmt1","repayMethod1","repayAuth1",
			"repayAmt2","repayMethod2","repayAuth2",
			"repayAmt3","repayMethod3","repayAuth3");
	for(i=0;i<eleNames.length;i++) {
		if(document.getElementById(eleNames[i]) == null) {
			alert(eleNames[i]);
		} else if(eleNames[i] == "repayAmt") {
			document.getElementById(eleNames[i]).value = "$0.00";
		} else if(eleNames[i] == "repayAmt1" || eleNames[i] == "repayAmt2" || eleNames[i] == "repayAmt3") {
			document.getElementById(eleNames[i]).value = "0.00";
		} else if(document.getElementById(eleNames[i]).type == "select-one") {
			document.getElementById(eleNames[i]).selectedIndex = 0;
		} else if(document.getElementById(eleNames[i]).type == "text") {
			document.getElementById(eleNames[i]).value = "";
		} else if(document.getElementById(eleNames[i]).type == "checkbox") {
			document.getElementById(eleNames[i]).checked = false;
		} else {
			document.getElementById(eleNames[i]).innerHTML = "";
		}
	}
}

function checkRepayCB(obj) {
	if(obj.checked == true) {
		if(obj.id == "repayFull") {
			document.getElementById("repayFlat").checked = false;
			var def = document.getElementById("repayAmt");
			//window.fullRepay = def.substr(1);
			def.value = "$"+window.fullRepay;
		} else {
			document.getElementById("repayFull").checked = false;
			var def = document.getElementById("repayAmt").value;
			paypop(def.substr(1));
		}
	}
}

function paypop(n) {
	//alert(window.paymentOptions);
	txt = 'Enter Flat Amount'
			+ '<div class="field"><label for="repayAmount">Amount </label>'
			+ '<input type="hidden" id="tag" name="tag" value="1st">'
			+ '<input type="text" id="repayAmount" name="repayAmount" value="'
			+ n + '" /></div>';
	$.prompt(txt, {
		callback : repayAmtUpdate,
		buttons : {
			Ok : true,
			Cancel : false
		},
		prefix : 'cleanblue'
	});
}

function repayAmtUpdate(v, m, f) {
	var repayAmt = document.getElementById("repayAmt");
	if(parseFloat((repayAmt.value).substr(1)) >= parseFloat(f.repayAmount)) {
		repayAmt.value = "$"+round2(f.repayAmount);
	} else {
		return;
	}
	document.getElementById("repaySize").selectedIndex == 0;
}

function tryval() {
	alert(document.getElementById("firstAmount").value);
	alert(document.getElementById("secondAmount").value);
	alert(document.getElementById("thirdAmount").value);
}

/*
 * outstanding popup methods
 */
function tagItem(obj) {
	var ra = parent.document.getElementById("repayAmt");
	var tags = window.tags;
	var invList = window.invList;
	var repayList = window.repayList;
	var repayStr = obj.iID+"$"+obj.amt;
	if(obj.click == "false") {
		obj.click = "true";
		var i = tags.indexOf(obj.tag);
		if(i != -1) {
			tags.splice(i,1);
		} else {
			tags.splice(tags.length,0,obj.tag);
		}
		
		var j = invList.indexOf(obj.iID);		
		if(j != -1) {
			invList.splice(j,1);
		} else {
			invList.splice(invList.length,0,obj.iID);
		}
		
		var k = repayList.indexOf(repayStr);
		//alert(k);
		if(k != -1) {
			//alert("delete");
			repayList.splice(k,1);
		} else {
			//alert("add");
			repayList.splice(repayList.length,0,repayStr);
		}
		//alert((ra.value).substr(1) + "+" + obj.amt);
		var val = parseFloat((ra.value).substr(1)) + parseFloat(obj.amt);
	} else if(obj.click == "true") {
		obj.click = "false";
		var i = tags.indexOf(obj.tag);
		if(i != -1) {
			tags.splice(i,1);
		} else {
			tags.splice(tags.length,0,obj.tag);
		}
		var j = invList.indexOf(obj.iID);
		if(i != -1) {
			invList.splice(i,1);
		} else {
			invList.splice(invList.length,0,obj.iID);
		}
		//alert((ra.value).substr(1) + "-" + obj.amt);
		var k = repayList.indexOf(repayStr);		
		if(k != -1) {
			repayList.splice(k,1);
		} else {
			repayList.splice(repayList.length,0,repayStr);
		}
		//alert((ra.value).substr(1) + "+" + obj.amt);
		var val = parseFloat((ra.value).substr(1)) - parseFloat(obj.amt);
	} 
	//alert(invList);
	//alert(repayList);
	ra.value = "$"+round2(val);
	parent.window.fullRepay = round2(val);
	setTimeout("loadItems()",100);
}

function selectAllRepay() {
	var ra = parent.document.getElementById("repayAmt");
	var trs = invOutstandingFrame.document.getElementsByTagName("tr");
	var but = document.getElementById('selAllRepay');
	if(but.value == "Select All Invoices") {
		var val = ra.value.substr(1);
		//alert(val);
		for(var i=0;i<trs.length;i++) {
			if(trs[i].click == "false") {
				alert(trs[i].click);
				tagItem(trs[i]);
			}
		}
		ra.value = "$"+round2(val);
		parent.window.fullRepay = round2(val);
		but.value = "Unselect All Invoices";
	} else if("Unselect All Invoices") {
		//alert(invList.length);
		for(var i=0;i<trs.length;i++) {
			if(trs[i].click == "true") {
				alert(trs[i].click);
				tagItem(trs[i]);
			}
		}
		ra.value = "$0.00";
		parent.window.fullRepay = "0.00";
		but.value = "Select All Invoices";
	}
	setTimeout("loadItems()",100);
}

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


function loadItems() {
	//alert("loaditems");
	//var tags = invOutstandingFrame.tags;
	var items="";
	for(var i=0;i<window.tags.length;i++) {
		items += window.tags[i] + ",";
	}
	//loadItemsHelp(items);
	//alert(window.tags);
	var url = "../script/invoice_outstandingitems_print.php?iID="+items.substr(0,items.length-1);
	parent.invOSItemsFrame.location.href = url;
}

function loadLookupTables() {
	
}

Array.prototype.indexOf = function(p_val) {
	for(var i = 0, l = this.length; i < l; i++) {
		if(this[i] == p_val) {
			return i;
		}
	}
	return -1;
};

function confirmRepay() {
	//Check repay total
	var repayDate = document.getElementById("datePick2").value;
	//repayStr.splice(repayStr.length,0,"repayDate="+repayDate);
	var amt1 = document.getElementById("repayAmt1").value;
	var amt2 = document.getElementById("repayAmt2").value;
	var amt3 = document.getElementById("repayAmt3").value;
	var tot = parseFloat(amt1) + parseFloat(amt2) + parseFloat(amt3);
	//alert(tot);
	var et = document.getElementById("repayAmt").value;
	//alert(tot + "," + et);
	if(round2(tot) != parseFloat(et.substr(1))) {
		alert("Total repaid amount does not equal to expected repay amount.");
		return;
	}
	//repayStr.splice(repayStr.length,0,"repayTotal="+et);
	if(document.getElementById("repaySize").selectedIndex == 0) {
		alert("Please select payment option.");
		document.getElementById("repaySize").focus();
		return;
	}
	//repayStr.splice(repayStr.length,0,"repayOpt="+document.getElementById("repaySize").selectedIndex);
	if(document.getElementById("repayAmt1").value != "0.00" && document.getElementById("repayMethod1").selectedIndex != 0) {
		if(document.getElementById("repayAuth1").disabled == false && document.getElementById("repayAuth1").value == "") {
			alert("Payment authorization #1 not set.");
			document.getElementById("repayAuth1").focus();
			return
		}
	} else if(document.getElementById("repayAmt1").value != "0.00" && document.getElementById("repayMethod1").selectedIndex == 0) {
		alert("Payment method #1 not set.");
		document.getElementById("repayMethod1").focus();
		return;
	}
	
	if(document.getElementById("repayAmt2").value != "0.00" && document.getElementById("repayMethod2").selectedIndex != 0) {
		if(document.getElementById("repayAuth2").disabled == false && document.getElementById("repayAuth2").value == "") {
			alert("Payment authorization #2 not set.");
			document.getElementById("repayAuth2").focus();
			return;
		}
	} else if(document.getElementById("repayAmt2").value != "0.00" && document.getElementById("repayMethod2").selectedIndex == 0) {
		alert("Payment method #2 not set.");
		document.getElementById("repayMethod2").focus();
		return;
	}
	
	if(document.getElementById("repayAmt3").value != "0.00" && document.getElementById("repayMethod3").selectedIndex != 0) {	
		if(document.getElementById("repayAuth3").disabled == false && document.getElementById("repayAuth3").value == "") {
			alert("Payment authorization #3 not set.");
			document.getElementById("repayAuth3").focus();
			return;
		}
	} else if(document.getElementById("repayAmt3").value != "0.00" && document.getElementById("repayMethod3").selectedIndex == 0) {
		alert("Payment method #3 not set.");
		document.getElementById("repayMethod3").focus();
		return;
	}
	
	var answer = confirm("Confirm payment?");
	if (answer) {
		repayStr.splice(repayStr.length,0,"repayTeller=" + document.getElementById("repayTeller").selectedIndex);
		if(document.getElementById("repayPO").value != "") {
			repayStr.splice(repayStr.length,0,"repayPO=" + document.getElementById("repayPO").selectedIndex);
		}
		setTimeout("confirmRepayHelp()", 100);
	} else {
		return;
	}
}

function confirmRepayHelp() {
	//var custID = document.getElementById('custID').value;
	//var cFirstN = document.getElementById('cFirstN').value;
	//var cLastN = document.getElementById('cLastN').value;
	var cDate = document.getElementById('datePick2').value;
	var today = new Date();
	var jan1st = new Date();
	var pbStr = "";
	var today = new Date();
	var jan1st = new Date();
	jan1st.setFullYear(today.getFullYear(),0,1);
	jan1st.setHours(0);
	jan1st.setMinutes(0);
	jan1st.setSeconds(0);
	//alert(jan1st);
	t = today.getTime();
	j = jan1st.getTime();
	var diff = t - j;
	var year = today.getFullYear();
	var days = Math.floor(diff/1000/60/60/24);
	var d = "";
	if(days < 10) {
		d = "00" + days;
	} else if(10 < days < 100) {
		d = "0" + days;
	} else {
		d = days;
	}
	var hours = today.getHours();
	if(hours < 10) {
		h = "0" + hours;
	} else {
		h = hours;
	}
	var minutes = today.getMinutes();
	if(minutes < 10) {
		m = "0" + minutes;
	} else {
		m = minutes;
	}
	var seconds = today.getSeconds();
	if(seconds < 10) {
		s = "0" + seconds;
	} else {
		s = seconds;
	}
	var ses = "R" + year+d+h+m+s;
	var teller = document.getElementById('repayTeller')[document.getElementById('repayTeller').selectedIndex].innerHTML;
	//var teller = document.getElementById('invTeller')[document.getElementById('invTeller').selectedIndex].innerHTML;
	var repayAmount = document.getElementById('repayAmt').value;
	var invItems = "";
	//alert(invOutstandingFrame.invList);
	for(var i=0;i<invOutstandingFrame.invList.length;i++) {
		invItems += invOutstandingFrame.invList[i] + ",";
	}
	var repayItems = "";
	for(var i=0;i<invOutstandingFrame.repayList.length;i++) {
		repayItems += invOutstandingFrame.repayList[i] + ",";
	}
	
	if(document.getElementById('repayMethod1').selectedIndex != 0 && document.getElementById('repayAmt1').value != "0.00") {
		pbStr += document.getElementById('repayMethod1')[document.getElementById('repayMethod1').selectedIndex].innerHTML
			+ " $" + document.getElementById('repayAmt1').value;
		repayStr.splice(repayStr.length,0,
				"repayAmt1="+document.getElementById('repayAmt1').value,
				"repayMethod1="+document.getElementById('repayMethod1').selectedIndex);
		if(document.getElementById('repayAuth1').value != "") {
			repayStr.splice(repayStr.length,0,"repayAuth1="+document.getElementById('repayAuth1').value)
		}
	}
	if(document.getElementById('repayMethod2').selectedIndex != 0 && document.getElementById('repayAmt2').value != "0.00") {
		pbStr += ":"+document.getElementById('repayMethod2')[document.getElementById('repayMethod2').selectedIndex].innerHTML
		+ " $" + document.getElementById('repayAmt2').value;
		repayStr.splice(repayStr.length,0,
				"repayAmt2="+document.getElementById('repayAmt2').value,
				"repayMethod2="+document.getElementById('repayMethod2').selectedIndex);
		if(document.getElementById('repayAuth2').value != "") {
			repayStr.splice(repayStr.length,0,"repayAuth2="+document.getElementById('repayAuth2').value)
		}
	}
	if(document.getElementById('repayMethod3').selectedIndex != 0 && document.getElementById('repayAmt3').value != "0.00") {
		pbStr += ":"+document.getElementById('repayMethod3')[document.getElementById('repayMethod3').selectedIndex].innerHTML
		+ " $" + document.getElementById('repayAmt3').value;
		repayStr.splice(repayStr.length,0,
				"repayAmt3="+document.getElementById('repayAmt3').value,
				"repayMethod3="+document.getElementById('repayMethod3').selectedIndex);
		if(document.getElementById('repayAuth3').value != "") {
			repayStr.splice(repayStr.length,0,"repayAuth3="+document.getElementById('repayAuth3').value)
		}
	}
	var url = "../script/print_repay_invoice.php?"
		+ "inCID=" + curCustomer.c2 
		+ "&inSID=" + curCustomer.c1 
		+ "&paybyString=" + pbStr
		+ "&mpay=" + window.payOpt 
		+ "&amount=" + repayAmount
		+ "&poStr=" + document.getElementById('repayPO').value
		+ "&coName=" + curCustomer.c5 
		+ "&inFName=" + curCustomer.c4 
		+ "&inLName=" + curCustomer.c3 
		+ "&inSession="	+ ses 
		+ "&inServer=" + teller 
		+ "&inItems=" + invItems.substr(0,invItems.length-1)
		+ "&inDate=" + (cDate.split(' '))[0];
	repayStr.splice(repayStr.length,0,
		"repayOpt=" + document.getElementById("repaySize").selectedIndex,
		"inCID=" + curCustomer.c2,
		"&inSID=" + curCustomer.c1,
		"&amount=" + repayAmount,
		"&inDate=" + (cDate.split(' '))[0],
		"&inSession=" + ses,
		"&repayItems=" + repayItems.substr(0,repayItems.length-1));
	//alert(url);
	window.open(url, "_blank", "height=595px,width=842px");
	//alert(url);
	//window.open(url, "_blank", "height=595px,width=842px"); robert montesano
	var fun = "updateRepayTables()";
	setTimeout(fun, 1000);
}

function updateRepayTables() {
	//var tags = invOutstandingFrame.tags;
	//var tags = window.tags;
	//alert(ses);
	//alert(repayStr);
	//var invItems = "";
	var repay = "";
	for(var i=0;i<repayStr.length;i++) {
		repay += repayStr[i] + "&";
	}
	//alert(repay);
	makePOSTRequest("../script/invoice_repay.php",repay.substring(0,repay.length-1));
	repayStr = new Array();
}

/*LOOKUP FUNCTIONS*/

function lookup_range() {
	//alert("TEST");
	var start = new Date(document.getElementById("date-s").value);
	var end = new Date(document.getElementById("date-e").value);
	//alert(start + " to " + end);
	if(end >= start) {
		urlInvc = "../script/lookup_invc_print.php?cid=" + curCustomer.c2 
			+ "&start=" + document.getElementById("date-start").value
			+ "&end=" + document.getElementById("date-end").value;
		lookupInvoiceFrame.window.location.href = urlInvc;
		//alert("OH YEAH ITS ALIVE");
	} else {
		return;
	}
}

function ragItem(obj) {
	//alert(invList);
	if(obj.click == "false") {
		/*obj.style.background = '#000000';
		obj.style.color = '#ffffff';*/
		obj.click = "true";
		var url = "../script/invoice_outstandingitems_print.php?iID="+obj.tag;
	} else if(obj.click == "true") {
		obj.click = "false";
		var url = "../script/invoice_outstandingitems_print.php?";
	}
	parent.lookupProductFrame.location.href = url;
}

/**/

function popLPN(obj) {
	var selector;
	if(obj.id=="hpCustSearch") {
		selector = document.getElementById('hpCustID');
	} else if(obj.id=="hccCustSearch") {
		selector = document.getElementById('hccCustID');
	}
	//selector.options = null;
	if(navigator.appName == "Microsoft Internet Explorer") {
		http = new ActiveXObject("Microsoft.XMLHTTP");
	} else {
		http = new XMLHttpRequest();
	}
	http.open("GET", "../script/getCustName.php?frame=cust&str=" + obj.value, true);
	http.onreadystatechange=function() {
		if(http.readyState == 4) {
			document.getElementById("debugger").value += (http.responseText);
			selector.options.length = 0;
			var items = http.responseText.split("\n");
			for (i = 0; i < items.length-1; i++) {
				var p = items[i].split('%');
				selector.options[i] = new Option(p[0], p[0],
						true, false);
				selector.options[i].value = p[1];
			}
		} else {
			//alert("There was problem with the request.");
			return;
		}
	}
	http.send(null);
}

function resetDiv(div) {
//	/alert(div);
	var i = 0;
	var input = document.getElementById(div).getElementsByTagName('input');
	var sel = document.getElementById(div).getElementsByTagName('select');
	var ta = document.getElementById(div).getElementsByTagName('textarea');
	var now = new Date();
	var today = now.toDateString();

	document.getElementById("hpDate").value = "TEST TEST WTF";
	for(i=0;i<input.length;i++) {
		if(input[i].type=="text") {
			if(input[i].id == "hpDate" || input[i].id == "hccDate") {
				input[i].value = today.substr(0,3) + "," + today.substr(3);
				if(input[i].id == "hpDate") {
					$("#hireP-date").dpSetSelected(now.asString());
				} else {
					$("#hireCC-date").dpSetSelected(now.asString());
				}
			} else {
				input[i].value = "";
			}
		} else if(input[i].type=="checkbox") {
			input[i].checked = false;
		}
	}
	
	for(i=0;i<ta.length;i++) {
		ta[i].value = "";
	}
	
	for(i=0;i<sel.length;i++) {
		if(sel[i].id == "hpCustID" || sel[i].id == "hccCustID") {
			sel[i].options.length = 0;
		} else {
			sel[i].selectedIndex = 0;
		}
	}
}

function findHire() {
	//var eleNames = new Array('hpE1','hpE2','hpE3','hpE4','hpE5','hpE6','hpE7','hpE8',
	//	'hpE9','hpE10','hpE11','hpE12','hpE13','hpE14','hpE15','hpE16','hpE17');
	var eleNames = new Array('hpE1','hpE2','hpE3','hpE4','hpE5','hpE6','hpE7','hpE8','hpE9','hpE10')
	var str = "";
	for(var i=0;i<eleNames.length;i++) {
		var ele = document.getElementById(eleNames[i]);
		if(ele.checked == true) {
			str += eleNames[i].replace("hp","c") + ".-1" + " AND ";
		} else {
			//str += eleNames[i].replace("hp","c") + ".0" + " AND ";
		}
	}
	if(str == "") {
		str = "all";
	} else {
		str = str.substring(0,str.length-5);
	}
	
	//alert(str);
	painterFrame.window.location.href = "../script/hirePainter_print.php?filter=" + str;
	//hiredPainterFrame.window.location.href = "../script/hired_print.php?who=hiredPainter";
}

function saveHire(obj) {
	//alert(obj.name);
	var postAry = new Array();
	if(obj.name == "savP") {
		if(document.getElementById("hpContID").value == "-1") {
			alert("Please select a painter first.");
			document.getElementById("findPainter").focus();
			return;
		}
		
		if(document.getElementById("hpCustID").selectedIndex == "-1") {
			alert("Customer not selected.");
			document.getElementById("hpCustSearch").focus();
			return;
		}
		
		var answer = confirm("Hire Painter?");
		if(!answer)
			return;
		
		var eleNames = new Array('hpContID','hpCustID','hpE1','hpE2',
				'hpE3','hpE4','hpE5','hpE6','hpE7','hpE8','hpE9',
				'hpE10','hpE11','hpE12','hpE13','hpE14','hpE15',
				'hpE16','hpE17','hpNumCols','hpNumRms','hpDate','hpNote');
		for(var i=0;i<eleNames.length;i++) {
			var ele = document.getElementById(eleNames[i]);
			if(ele.type == "text" || ele.type == "textarea" || ele.type == "hidden") {
				if(ele.value != "") {
					postAry.splice(postAry.length,0,eleNames[i].replace("hp","c") + "=" + ele.value);
				}
			} else if(ele.type == "checkbox") {
				if(ele.checked == true) {
					postAry.splice(postAry.length,0,eleNames[i].replace("hp","c") + "=" + "-1");
				}
			} else if(ele.type == "select-one") {
				if(ele.selectedIndex != -1) {
					postAry.splice(postAry.length,0,eleNames[i].replace("hp","c") + "=" + ele.options[ele.selectedIndex].value);
				}
			}
		}
		//alert(postAry);
		var poststr = "";
		for(var j=0;j<postAry.length;j++) {
			poststr += postAry[j] + "&";
		} 
		makePOSTRequest('../script/hire_painter_submit.php',poststr.substring(0,poststr.length-1));
	} else if(obj.name == "savCC") {
		if(document.getElementById("hccContID").value == "-1") {
			alert("Please select a consultant first.");
			return;
		}
		
		if(document.getElementById("hccCustID").selectedIndex == "-1") {
			alert("Customer not selected.");
			document.getElementById("hccCustSearch").focus();
			return;
		}
		
		var answer = confirm("Hire Consultant?");
		if(!answer)
			return;
		
		var eleNames = new Array('hccContID','hccCustID','hccE1','hccE2',
				'hccE3','hccE4','hccE5','hccNumCols','hccNumRms','hccDate','hccNote');
		for(var i=0;i<eleNames.length;i++) {
			var ele = document.getElementById(eleNames[i]);
			if(ele.type == "text" || ele.type == "textarea" || ele.type == "hidden") {
				if(ele.value != "") {
					postAry.splice(postAry.length,0,eleNames[i].replace("hcc","c") + "=" + ele.value);
				}
			} else if(ele.type == "checkbox") {
				if(ele.checked == true) {
					postAry.splice(postAry.length,0,eleNames[i].replace("hcc","c") + "=" + "-1");
				}
			} else if(ele.type == "select-one") {
				if(ele.selectedIndex != -1) {
					postAry.splice(postAry.length,0,eleNames[i].replace("hcc","c") + "=" + ele.options[ele.selectedIndex].value);
				}
			}
		}
		//alert(postAry);
		var poststr = "";
		for(var j=0;j<postAry.length;j++) {
			poststr += postAry[j] + "&";
		}
		makePOSTRequest('../script/hire_consultant_submit.php',poststr.substring(0,poststr.length-1));		
	}
}

function choose_painter(obj) {
	parent.document.getElementById("hpContID").value = obj.pCustID;
	//parent.document.getElementById("hpDate").value = obj.pCustID;
}

function choose_consultant(obj) {
	parent.document.getElementById("hccContID").value = obj.cCustID;
}

function generate_pdf(d) {
	if(d == "paint") {
		var url = "hContID=" + document.getElementById("hpContID").value +
		"&hCustID=" + document.getElementById("hpCustID").value +
		"&hDate=" + document.getElementById("hpDate").value +
		"&sID=" + document.getElementById("defStoreID").value;
		window.open("../script/pdf_print_hire_painter.php?" + url, "_blank", "height=595px,width=842px");
	} else if(d == "cont") {
		var url = "hContID=" + document.getElementById("hccContID").value +
		"&hCustID=" + document.getElementById("hccCustID").value +
		"&hDate=" + document.getElementById("hccDate").value +
		"&sID=" + document.getElementById("defStoreID").value; 
		window.open("../script/pdf_print_hire_consultant.php?" + url, "_blank", "height=595px,width=842px");
	}
}

function printLabel() {
	if (curCustomer == null) {
		alert("No customer selected.");
		return;
	}
	var cID = curCustomer.c2;
	var avery = "5160";
	var url = "../script/print_label.php?"
		+ "cids=" + cID
		+ "&avery=" + avery;
	// alert(url);
	window.open(url, "_blank", "height=595px,width=842px");
}

function addToSeminar() {
	if (curCustomer == null) {
		alert("No customer selected.");
		return;
	}

	var paid = "";
	var cid = curCustomer.c2;
	var cnum = curCustomer.c6;
	
	txt = 'Did the customer pay for the seminar'
		+ '<div class="field"></div>';
	$.prompt(txt, {
		buttons : {
			YES : true,
			NO : false
		},
		prefix : 'cleanblue',
		submit : function(v,m,f) {
			if(v) {
				paid = "0";
				var msg = parent.document.getElementById("lb_returnMessage");
				if(navigator.appName == "Microsoft Internet Explorer") {
					http = new ActiveXObject("Microsoft.XMLHTTP");
				} else {
					http = new XMLHttpRequest();
				}
				//alert("cID" + pageCID + "comNum" + semCID + "paid" + semPaid + "ta" + semTA);
				http.open("GET", "../script/seminar_addCust.php?cID=" + cid +
						"&comNum=" + cnum +
						"&paid=" + paid +
						"&ta=Added on customer page", true);
				http.onreadystatechange=function() {
					if(http.readyState == 4) {
						var str = http.responseText;
						$("#returnMessage",window.parent.document).css({
							"backgroundColor": "#00FF00"
						});
						msg.style.color = "black";
						msg.style.fontWeight = "bold";
						msg.innerHTML =str;
						$("#returnMessage",window.parent.document).slideDown(300).delay(1500).fadeOut('fast');
						parent.document.getElementById("debugger").value += str.substr(2) + "\n";
					}
				}
				http.send(null);
			} else {
				paid = "-1";
				var msg = parent.document.getElementById("lb_returnMessage");
				if(navigator.appName == "Microsoft Internet Explorer") {
					http = new ActiveXObject("Microsoft.XMLHTTP");
				} else {
					http = new XMLHttpRequest();
				}
				//alert("cID" + pageCID + "comNum" + semCID + "paid" + semPaid + "ta" + semTA);
				http.open("GET", "../script/seminar_addCust.php?cID=" + cid +
						"&comNum=" + cnum +
						"&paid=" + paid +
						"&ta=Added on customer page", true);
				http.onreadystatechange=function() {
					if(http.readyState == 4) {
						var str = http.responseText;
						//alert(str);
						$("#returnMessage",window.parent.document).css({
							"backgroundColor": "#00FF00"
						});
						msg.style.color = "black";
						msg.style.fontWeight = "bold";
						msg.innerHTML =str;
						$("#returnMessage",window.parent.document).slideDown(300).delay(1500).fadeOut('fast');
						parent.document.getElementById("debugger").value += str.substr(2) + "\n";
					}
				}
				http.send(null);
			}
		}
	});		
}