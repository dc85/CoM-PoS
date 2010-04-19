/* Invoice page methods */
var paymentWindowOpen = 0;
var outstandingWindowOpen = 0;
var paymentOptions = 1;
var payOpt = 1;
var repaymentOptions = 1;
var fullRepay = 0;
var colourSelection = 0;
var showDebug = 0;
var option1 = -1;
var option2 = -1;
var option3 = -1;
var tags = new Array();
var prevObj = null;

$(document).ready(function() {

	$("#checkoutPopupContactClose").click(function() {
		disablePopup();
	});

	$("#outstandingPopupContactClose").click(function() {
		disableOutstandingPopup();
	});
	// Click out event!
	$("#backgroundPopup").click(function() {
		if(outstandingWindowOpen == 1) {
			disableOutstandingPopup();
		} else if(paymentWindowOpen == 1) {
			disablePopup();
		} else {
			
		}

	});
	// Press Escape event!
	$(document).keypress(function(e) {
		if (e.keyCode == 27 && (paymentWindowOpen == 1)) {
			disablePopup();
		}
		if (e.keyCode == 27 && (outstandingWindowOpen == 1)) {
			disableOutstandingPopup();
		}
		if (e.keyCode == 27 && (parent.outstandingWindowOpen == 1)) {
			parent.disableOutstandingPopup();
		}
	});
});

function disablePopup() {
	// disables popup only if it is enabled
	if (paymentWindowOpen = 1) {
		$("#backgroundPopup").fadeOut("fast");
		$("#checkoutPopupContact").fadeOut("fast");
		paymentWindowOpen = 0;
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

function showOutstandingPopup() {
	if (outstandingWindowOpen == 0) {
		invOutstandingFrame.window.location.reload();
		invOSItemsFrame.window.location.href = "../script/invoice_outstandingitems_print.php";
		invRepaidFrame.window.location.reload();
		setTimeout("clearRepay()",10);
		$("#backgroundPopup").css( {
			"opacity" : "0.7"
		});
		$("#backgroundPopup").fadeIn("fast");
		$("#outstandingPopupContact").fadeIn("fast");
		outstandingWindowOpen = 1;
	}
}

function showPaymentPopup() {
	// loads popup only if it is disabled
	updateTotals();
	setTimeout("showPaymentPopupHelp()", 500);
}

function showPaymentPopupHelp() {
	if (paymentWindowOpen == 0) {
		$("#backgroundPopup").css( {
			"opacity" : "0.7"
		});
		$("#backgroundPopup").fadeIn("fast");
		$("#checkoutPopupContact").fadeIn("fast");
		paymentWindowOpen = 1;
	}
	var total = document.getElementById('invoiceTotal').value;
	var totalbox = document.getElementById('payTotal');
	//var remainbox = document.getElementById('payRemain');
	totalbox.value = "$"+round2(total);
	//remainbox.value = "$"+round2(total);
}

function resetCart() {
	var session = document.getElementById('session').value;
	//alert(session);

	if (navigator.appName == "Microsoft Internet Explorer") {
		http = new ActiveXObject("Microsoft.XMLHTTP");
	} else {
		http = new XMLHttpRequest();
	}
	http.open("GET", "../script/invoice_resetcart.php?session="+session, true);
	http.onreadystatechange = function() {
		if (http.readyState == 4) {
			var str = http.responseText;
			//alert(str);
			document.getElementById("debugger").value += str + "\n";
			refreshCart();
			updateTotals();
		}
	}
	http.send(null);
}

function resetInvSearch() {
	document.getElementById("prodSearch").value = "";
	document.getElementById("prodSearch").focus();
}


function resetInvoiceAll() {
	document.getElementById("prodSearch").value = "";
	reloadPIframeInInv();
	resetCart();
	document.getElementById("invFree").checked=false;
	document.getElementById("invUPC").value="";
	document.getElementById("invSku").value="";
	document.getElementById("invUPrice").value="";
	document.getElementById("invQty").value="";
	document.getElementById("invTotal").value="";
	document.getElementById("invTax1").selectedIndex=0;
	document.getElementById("invTax2").selectedIndex=0;
	document.getElementById("paintFilter").value="";
	document.getElementById("paintFilter").disabled = true;
	document.getElementById("paintColourSelect").options.length = 0;
	document.getElementById("paintColourSelect").disabled = true;
	document.getElementById("paintBrandSelect").selectedIndex = 0;
	document.getElementById("paintBrandSelect").disabled = true;
	document.getElementById("paintFilterNote").value = "";
	document.getElementById("paintFilterNote").disabled = true;
}

function getFilterList() {
	var value = document.getElementById('paintFilter').value;
	// alert(value);

	if (navigator.appName == "Microsoft Internet Explorer") {
		http = new ActiveXObject("Microsoft.XMLHTTP");
	} else {
		http = new XMLHttpRequest();
	}
	http.open("GET", "../php/invoice_getFilterList.php?key=" + value, true);
	http.onreadystatechange = function() {
		if (http.readyState == 4) {
			var selector = document.getElementById('paintColourSelect');
			selector.options.length = 0;
			var str = http.responseText;
			if (str == "null") {
				alert("Empty search result");
				return;
			} else {
				var items = str.split('/');
				var i;
				for (i = 0; i < items.length; i++) {
					selector.options[i + 1] = new Option(items[i], items[i],
							true, false);
				}
			}
		}
	}
	http.send(null);
}

function resetPaymentPopup() {
	var totalbox = document.getElementById('payTotal');
	//var remainbox = document.getElementById('payRemain');
	//remainbox.value = totalbox.value;

	var paysel1 = document.getElementById('payMethod1');
	var paysel2 = document.getElementById('payMethod2');
	var paysel3 = document.getElementById('payMethod3');
	var abox1 = document.getElementById('payAmount1');
	var abox2 = document.getElementById('payAmount2');
	var abox3 = document.getElementById('payAmount3');
	var pobox1 = document.getElementById('payAuth1');
	var pobox2 = document.getElementById('payAuth2');
	var pobox3 = document.getElementById('payAuth3');
	var notebox1 = document.getElementById('payNote1');
	var notebox2 = document.getElementById('payNote2');
	var notebox3 = document.getElementById('payNote3');
	var pobox = document.getElementById('payPO');

	paysel1.selectedIndex = 0;
	paysel2.selectedIndex = 0;
	paysel3.selectedIndex = 0;
	abox1.value = "0.00";
	abox2.value = "0.00";
	abox3.value = "0.00";
	pobox1.value = "";
	pobox1.disabled = true;
	pobox2.value = "";
	pobox1.disabled = true;
	pobox3.value = "";
	pobox1.disabled = true;
	notebox1.value = "";
	notebox2.value = "";
	notebox3.value = "";
	pobox.value = "";
}

/*function confirmPayHelp() {
	var answer = confirm("Confirm payment?");
	if (answer)
		setTimeout("confirmPay()", 100);
	else
		return;
}*/

function confirmPay() {
	//Check repay total
	var amt1 = document.getElementById("payAmount1").value;
	var amt2 = document.getElementById("payAmount2").value;
	var amt3 = document.getElementById("payAmount3").value;
	var tot = parseFloat(amt1) + parseFloat(amt2) + parseFloat(amt3);
	var et = document.getElementById("payTotal").value;
	
	if(tot != parseFloat(et.substr(1))) {
		alert("Total repaid amount does not equal to expected repay amount.");
		return;
	}
	
	if(document.getElementById("payAmount1").value != "0.00" && document.getElementById("payMethod1").selectedIndex != 0) {
		if(document.getElementById("payAuth1").disabled == false && document.getElementById("payAuth1").value == "") {
			alert("Payment authorization #1 not set.");
			document.getElementById("payAuth1").focus();
			return
		}
	} else if(document.getElementById("payAmount1").value != "0.00" && document.getElementById("payMethod1").selectedIndex == 0) {
		alert("Payment method #1 not set.");
		document.getElementById("payMethod1").focus();
		return;
	}
	
	if(document.getElementById("payAmount2").value != "0.00" && document.getElementById("payMethod2").selectedIndex != 0) {
		if(document.getElementById("payAuth2").disabled == false && document.getElementById("payAuth2").value == "") {
			alert("Payment authorization #2 not set.");
			document.getElementById("payAuth2").focus();
			return;
		}
	} else if(document.getElementById("payAmount2").value != "0.00" && document.getElementById("payMethod2").selectedIndex == 0) {
		alert("Payment method #2 not set.");
		document.getElementById("payMethod2").focus();
		return;
	}
	
	if(document.getElementById("payAmount3").value != "0.00" && document.getElementById("payMethod3").selectedIndex != 0) {	
		if(document.getElementById("payAuth3").disabled == false && document.getElementById("payAuth3").value == "") {
			alert("Payment authorization #3 not set.");
			document.getElementById("payAuth3").focus();
			return;
		}
	} else if(document.getElementById("payAmount3").value != "0.00" && document.getElementById("payMethod3").selectedIndex == 0) {
		alert("Payment method #3 not set.");
		document.getElementById("payMethod3").focus();
		return;
	}
	
	var answer = confirm("Confirm payment?");
	if (answer)
		setTimeout("confirmPayHelp()", 100);
	else
		return;
	
}

function confirmPayHelp() {
	var arAmt = 0.00;
	var pbStr = "";
	var custID = document.getElementById('custID').value;
	var sID = document.getElementById('defStoreID').value;
	var cFirstN = document.getElementById('cFirstN').value;
	var cLastN = document.getElementById('cLastN').value;
	var cDate = document.getElementById('datePick').value;
	var session = document.getElementById('session').value;
	var coName = document.getElementById('coName').value;
	// var server =
	// document.getElementById('invServer')[document.getElementById('invServer').selectedIndex].innerHTML;
	// var teller =
	// document.getElementById('invTeller')[document.getElementById('invTeller').selectedIndex].innerHTML;
	//var server = document.getElementById('invServer').selectedIndex;
	var teller = document.getElementById('invTeller')[document.getElementById('invTeller').selectedIndex].innerHTML;
	if(document.getElementById('payAuth1').selectedIndex == 1) {
		arAmt += parseFloat(document.getElementById('payAmount1').value);
	} else if(document.getElementById('payAuth2').selectedIndex == 2) {
		arAmt += parseFloat(document.getElementById('payAmount2').value);
	} else if(document.getElementById('payAuth3').selectedIndex == 3) {
		arAmt += parseFloat(document.getElementById('payAmount3').value);
	}
	if(document.getElementById('payMethod1').selectedIndex != 0 && document.getElementById('payAmount1').value != "0.00") {
		pbStr += document.getElementById('payMethod1')[document.getElementById('payMethod1').selectedIndex].innerHTML
			+ " $" + document.getElementById('payAmount1').value;
	}
	if(document.getElementById('payMethod2').selectedIndex != 0 && document.getElementById('payAmount2').value != "0.00") {
		pbStr += ":"+document.getElementById('payMethod2')[document.getElementById('payMethod2').selectedIndex].innerHTML
		+ " $" + document.getElementById('payAmount2').value;
	}
	if(document.getElementById('payMethod3').selectedIndex != 0 && document.getElementById('payAmount3').value != "0.00") {
		pbStr += ":"+document.getElementById('payMethod3')[document.getElementById('payMethod3').selectedIndex].innerHTML
		+ " $" + document.getElementById('payAmount3').value;
	}
	//alert(pbStr);
	var url = "../script/print_invoice.php?"
		+ "inCID=" + custID 
		+ "&csize=" + document.getElementById('invCSize').value
		+ "&prodCode=" + document.getElementById('invProdCode').value
		+ "&inSID=" + sID 
		+ "&arAmt=" + arAmt
		+ "&paybyString=" + pbStr
		+ "&mpay=" + payOpt
		+ "&poStr=" + document.getElementById('payPO').value
		+ "&coName=" + coName 
		+ "&inFName=" + cFirstN 
		+ "&inLName=" + cLastN 
		+ "&inSession="	+ session 
		+ "&inServer=" + teller 
		+ "&inDate=" + (cDate.split(' '))[0];
	// alert(url);
	window.open(url, "_blank", "height=595px,width=842px");
	var fun = "updatePayTables('"+session+"')";
	setTimeout(fun, 500);
}

function showQuote() {
	//alert("test");
	var arAmt = 0.00;
	var pbStr = "";
	var custID = document.getElementById('custID').value;
	var sID = document.getElementById('defStoreID').value;
	var cFirstN = document.getElementById('cFirstN').value;
	var cLastN = document.getElementById('cLastN').value;
	var cDate = document.getElementById('datePick').value;
	var session = document.getElementById('session').value;
	var coName = document.getElementById('coName').value;
	//alert(pbStr);
	var url = "../script/print_quote.php?"
		+ "inCID=" + custID 
		+ "&csize=" + document.getElementById('invCSize').value
		+ "&prodCode=" + document.getElementById('invProdCode').value
		+ "&inSID=" + sID 
		+ "&arAmt=" + arAmt
		+ "&paybyString=" + pbStr
		+ "&mpay=" + payOpt
		+ "&poStr=" + document.getElementById('payPO').value
		+ "&coName=" + coName 
		+ "&inFName=" + cFirstN 
		+ "&inLName=" + cLastN 
		+ "&inSession="	+ session 
		+ "&inDate=" + (cDate.split(' '))[0];
	// alert(url);
	window.open(url, "_blank", "height=595px,width=842px");
	//var fun = "updatePayTables('"+session+"')";
	//setTimeout(fun, 500);
}

function updatePayTables(ses) {
	var numpay = 0;
	var actPay = 0;
	var custID = document.getElementById('custID').value;
	var storeID = document.getElementById('defStoreID').value;
	//var storeID = 1;
	var total = document.getElementById('payTotal').value;
	var server = document.getElementById('invServer').selectedIndex;
	var teller = document.getElementById('invTeller').selectedIndex;
	var po = document.getElementById('payPO').value;

	var url = "payOp=" + paymentOptions
			+ "&totalDue=" + total.substr(1) 
			+ "&inSession="	+ ses 
			+ "&inCID=" + custID 
			+ "&inSID=" + storeID 
			+ "&inServer=" + server
			+ "&inTeller=" + teller 
			+ "&payPO=" + po;
	// alert(url);
	if((document.getElementById("payMethod1").selectedIndex > 0) && ((document.getElementById("payAmount1").value.length) != "0.00")) {
		var pm1 = document.getElementById("payMethod1").selectedIndex;
		var pay1 = document.getElementById('payAmount1').value;
		var note1 = document.getElementById('payNote1').value;
		if(pm1 != 1) {
			actPay += parseFloat(pay1);
		}
		var auth1 = document.getElementById('payAuth1');
		if(auth1.value.length > 0) {
			url += "&pm1=" + pm1 + "&note1=" + note1 + "&pay1=" + pay1 + "&auth1=" + auth1.value;
		} else {
			url += "&pm1=" + pm1 + "&note1=" + note1 + "&pay1=" + pay1;
		}
		numpay = 1;
		// alert(url);
	}
	if((document.getElementById("payMethod2").selectedIndex > 0) && ((document.getElementById("payAmount2").value.length) != "0.00")) {
		var pm2 = document.getElementById("payMethod2").selectedIndex;
		var pay2 = document.getElementById('payAmount2').value;
		var note2 = document.getElementById('payNote3').value;
		if(pm2 != 1) {
			actPay += parseFloat(pay2);
		}
		var auth2 = document.getElementById('payAuth2');
		if(auth2.value.length > 0) {
			url += "&pm2=" + pm2 + "&note2=" + note2 + "&pay2=" + pay2 + "&auth2=" + auth2.value;
		} else {
			url += "&pm2=" + pm2 + "&note2=" + note2 + "&pay2=" + pay2;
		}
		numpay = 2;
		// alert(url);
	}
	if((document.getElementById("payMethod3").selectedIndex > 0) && ((document.getElementById("payAmount3").value.length) != "0.00")) {
		var pm3 = document.getElementById("payMethod3").selectedIndex;
		var pay3 = document.getElementById('payAmount3').value;
		var note3 = document.getElementById('payNote3').value;
		if(pm3 != 1) {
			actPay += parseFloat(pay3);
		}
		var auth3 = document.getElementById('payAuth3');
		if(auth3.value.length > 0) {
			url += "&pm3=" + pm3 + "&note3=" + note3 + "&pay3=" + pay3 + "&auth3=" + auth3.value;
		} else {
			url += "&pm3=" + pm3 + "&note3=" + note3 + "&pay3=" + pay3;
		}
		numpay = 3;
		// alert(url);
	}
	makePOSTRequest("../script/invoice_pay.php",url+"&numPay="+numpay+"&actPay="+actPay);
}




function getTaxNum(index) {
	if(index == 2)
		return 0.08;
	else if(index == 3)
		return 0.05;
	else
		return 0.00;
}

function addToCart(opt) {
	var selType = document.getElementById('selType');
	var upcbox = document.getElementById('invUPC');
	var qtybox = document.getElementById('invQty');
	if (!upcbox.value) {
		alert("Please select a product to add to cart first.");
		upcbox.focus();
		return;
	} else if (qtybox.value == "") {
		alert("Please indicate a quantity for this product.");
		qtybox.focus();
		return;
	} else {

	}
	var paintFilter = document.getElementById('paintFilter');
	var paintColourSelect = document.getElementById('paintColourSelect');
	var paintFilterNote = document.getElementById('paintFilterNote');
	var paintBrandSelect = document.getElementById('paintBrandSelect');
	if (selType.value == 2) {

		if (paintColourSelect.selectedIndex == 0) {
			alert("Select a tint for the paint");
			paintColourSelect.focus();
			return;
		}
		if (paintBrandSelect.selectedIndex == 0) {
			alert("Select a brand for the paint");
			paintBrandSelect.focus();
			return;
		}
		var colour = paintColourSelect[paintColourSelect.selectedIndex].innerHTML;
		var note = paintFilterNote.value;
		var brand = paintBrandSelect[paintBrandSelect.selectedIndex].innerHTML;
	} else {
		var colour = "";
		var note = "";
		var brand = "";
	}
	
	if(opt == "add") {
		var qty = qtybox.value;
	} else if(opt == "remove") {
		var qty = parseInt(qtybox.value)*(-1);
	}
	// var filter = document.getElementById('paintColourSelect').value;
	// var value = document.getElementById('paintFilter').value;
	//alert(qtybox.value);
	var poststr = "sessionID=" + document.getElementById('session').value 
		+ "&selPID=" + prevObj.i3
		+ "&supplier=" + prevObj.i4
		+ "&coName=" + document.getElementById('coName').value
		+ "&unitCost=" + document.getElementById('invUCost').value
		+ "&upc=" + prevObj.i5 
		+ "&spc=" + document.getElementById('invSPC').value 
		+ "&sku=" + document.getElementById('invSku').value 
		+ "&unitPrice=" + document.getElementById('invUPrice').value 
		+ "&csize=" + document.getElementById('invCSize').value 
		+ "&qty=" + qty 
		+ "&cID=" + document.getElementById('custID').value 
		+ "&totalPrice=" + document.getElementById('invTotal').value 
		+ "&tax1=" + getTaxNum(document.getElementById('invTax1').selectedIndex) 
		+ "&tax2=" + getTaxNum(document.getElementById('invTax2').selectedIndex) 
		+ "&free=" + document.getElementById('invFree').checked 
		+ "&prod=" + document.getElementById('invProd').value 
		+ "&date=" + document.getElementById('scDate').value 
		+ "&colour=" + colour 
		+ "&note=" + note 
		+ "&brand="	+ brand;
	//alert(poststr);
	//makePOSTRequest('../script/invoice_addtocart.php',poststr);
	if (navigator.appName == "Microsoft Internet Explorer") {
		http = new ActiveXObject("Microsoft.XMLHTTP");
	} else {
		http = new XMLHttpRequest();
	}
	http.open("GET", "../script/invoice_addtocart.php?" + poststr, true);
	http.onreadystatechange = function() {
		if (http.readyState == 4) {
			if (http.status == 200) {
				var str = http.responseText;
				//alert(str);
				var msg = document.getElementById("lb_returnMessage");
				   //alert();
				if(str.substr(0,1) == "0") {
					$("#returnMessage").css({
						"backgroundColor": "#FF3300"
					});
					msg.style.color = "white";
					msg.style.fontWeight = "bold";
					msg.innerHTML = "Failed to add item(s) to cart.";
					$("#returnMessage").slideDown(300).delay(1500).fadeOut('fast');
					document.getElementById("debugger").value += str.substr(2) + "\n";
				} else if(str.substr(0,1) == "1") {
					//alert("MESSAGE");
					$("#returnMessage").css({
						"backgroundColor": "#00FF00"
					});
					msg.style.color = "black";
					// msg.style.fontWeight = "bold";
					if(opt == "add") {
						msg.innerHTML = "Item(s) added to cart.";
					} else if(opt == "remove") {
						msg.innerHTML = "Item(s) removed from cart.";
					}
					$("#returnMessage").slideDown(300).delay(1500).fadeOut('fast');
					//editArray = new Array();
				} else if(str.substr(0,2) == "-1") {
					$("#returnMessage").css({
						"backgroundColor": "#FF3300"
					});
					msg.style.color = "white";
					msg.style.fontWeight = "bold";
					msg.innerHTML = "Not enough item(s) in cart to be removed.";
					$("#returnMessage").slideDown(300).delay(1500).fadeOut('fast');
					document.getElementById("debugger").value += str.substr(2) + "\n";
				}
				//return;
				
			} else {
				alert("ERROR @ addToCart");
			}
		}
	}
	http.send(null);
	setTimeout("refreshCart()",500);
	setTimeout("updateTotals()",500);
	//refreshCart();
	//updateTotals();
}

function choose_item(obj) {
	parent.prevObj = obj;
	
	var selPID = parent.document.getElementById('selPID');
	selPID.value = "-1";
	// var selSID = parent.document.getElementById('selSID');
	var selType = parent.document.getElementById('selType');
	selType.value = "-1";
	var cStoreID = parent.document.getElementById('cStoreID').value;
	//alert(type + " AND " + selType.value + " SELECTION " +parent.colourSelection);
	var paintFilter = parent.document.getElementById('paintFilter');
	paintFilter.value = "";
	var paintColourSelect = parent.document.getElementById('paintColourSelect');
	paintColourSelect.options.length=0;
	var paintFilterNote = parent.document.getElementById('paintFilterNote');
	paintFilterNote.value = "";
	var paintBrandSelect = parent.document.getElementById('paintBrandSelect');
	paintBrandSelect.selectedIndex = 0;

	selPID.value = obj.i3;
	// selSID.value = store;
	selType.value = obj.i10;

	if (obj.i10 == "2" && parent.colourSelection == 0) {
		// alert("paint");
		paintFilter.disabled = false;
		paintColourSelect.disabled = false;
		paintFilterNote.disabled = false;
		paintBrandSelect.disabled = false;
		parent.colourSelection = 1;
	} else if (obj.i10 != "2" && parent.colourSelection == 1) {
		// alert("no paint");
		paintFilter.disabled = true;
		paintColourSelect.disabled = true;
		paintFilterNote.disabled = true;
		paintBrandSelect.disabled = true;
		parent.colourSelection = 0;
	} else if (obj.i10 == "2" && parent.colourSelection == 1) {
		paintFilter.value = "";
		paintColourSelect.options[0] = new Option("", "", true, false);
		paintColourSelect.options[1] = new Option("Fill in filter",
				"Fill in filter", true, false);
		paintFilterNote.value = "";
		paintColourSelect.options[0] = new Option("", "", true, false);
		paintColourSelect.options[1] = new Option("Para", "Para", true, false);
		paintColourSelect.options[2] = new Option("Pratt", "Pratt", true, false);
	}

	/* tooltip methods */
	if(obj.i10 != "2") {
		parent.document.getElementById("qty_tooltip").style.display = "block";
		parent.document.getElementById("color_tooltip").style.display = "none";
		parent.document.getElementById("tint_tooltip").style.display = "none";
	} else if(obj.i10 == "2") {
		parent.document.getElementById("qty_tooltip").style.display = "block";
		parent.document.getElementById("color_tooltip").style.display = "block";
		parent.document.getElementById("tint_tooltip").style.display = "block";
		//parent.document.getElementById("brand_tooltip").style.display = "block";
	}
		
	
	//var poststr = "pUPC=" + obj.i5 + "&pSPC=" + obj.i15 + "&cStoreID=" + obj.i2;
	parent.document.getElementById('invUPC').value = obj.i5;
	parent.document.getElementById('invSku').value = obj.i6;
	parent.document.getElementById('invProdCode').value = obj.i7;
	parent.document.getElementById('invUPrice').value = obj.i18;
	parent.document.getElementById('invUCost').value = obj.i12;
	parent.document.getElementById('invTax1').selectedIndex = obj.i16;
	parent.document.getElementById('invTax2').selectedIndex = obj.i17;
	parent.document.getElementById('invProd').value = obj.i8;
	parent.document.getElementById('invCSize').value = obj.i14;
	parent.document.getElementById('invQty').value = 0;
	parent.document.getElementById('invQty').focus();
	parent.document.getElementById('invQty').select();
}

function addInvoice() {
	var invSID = document.getElementById('sessionID').value;
	var invCID = document.getElementById('custID').value;
	var invTeller = document.getElementById('invTeller')[document
			.getElementById('invTeller').selectedIndex].innerHTML;
	var invServer = document.getElementById('invServer')[document
			.getElementById('invServer').selectedIndex].innerHTML;
	var invTotal = document.getElementById('payTotal').value;
	var invTotal = document.getElementById('payTotal').value;
}

/*function updatePay() {
	//alert("update pay");
	var totalbox = document.getElementById('payTotal');
	//var remainbox = document.getElementById('payRemain');
	var abox1 = document.getElementById('payAmount1');
	var abox2 = document.getElementById('payAmount2');
	var abox3 = document.getElementById('payAmount3');

	var remain = roundNumber(totalbox.value - abox1.value - abox2.value
			- abox3.value, 2);
	remainbox.value = remain;
}*/

function checkPayAuth(eleName) {
	var t = document.getElementById("payMethod" + eleName).selectedIndex;
	var auth = document.getElementById("payAuth" + eleName);
	if (t==3 || t == 4 || t == 5 || t == 6 || t == 7) {
		auth.disabled = false;
		auth.focus();
	} else {
		auth.disabled = true;
	}
}

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

function updateCartItem() {
	var uprice = document.getElementById('invUPrice').value;
	var qty = document.getElementById('invQty');
	if(isNaN(qty.value)) {
		//alert("Not a Number");
		qty.focus();
		qty.style.background = "#FF3300";
		return;
	}
	var tp = document.getElementById('invTotal');

	tp.value = (roundNumber(uprice * qty.value, 2)).toFixed(2);
}

function updateTotals() {
	if (navigator.appName == "Microsoft Internet Explorer") {
		http = new ActiveXObject("Microsoft.XMLHTTP");
	} else {
		http = new XMLHttpRequest();
	}
	var sessionID = parent.document.getElementById('session').value;
	if(sessionID.length < 5) {
		var sessionID = document.getElementById('session').value;
	}
	// alert(sessionID.innerHTML.substring(12,sessionID.innerHTML.length-4));
	http.open("GET","../script/invoice_gettotals.php?session=" + sessionID, true);
	http.onreadystatechange = function() {
		if (http.readyState == 4) {
			var str = http.responseText;
			//alert(str);
			if(str.substr(0,4) == "GQRY") {
				var texts = str.split('&');
				if(texts[2] != "") {
					document.getElementById('invoiceItems').value = parseInt(texts[2]);
				} else {
					document.getElementById('invoiceItems').value = "0";
				}
				if(texts[1] != "") {
					document.getElementById('invoiceTotal').value = round2(parseFloat(texts[1]));
				} else {
					document.getElementById('invoiceItems').value = "0.00";
				}
				
			} else {
				document.getElementById("debugger").value += str;
			}
		}
	}
	http.send(null);
}

function remove_item(obj) {
	//var sessionID = parent.document.getElementById('session').value;
	var cID = parent.document.getElementById('custID');

	if (navigator.appName == "Microsoft Internet Explorer") {
		http = new ActiveXObject("Microsoft.XMLHTTP");
	} else {
		http = new XMLHttpRequest();
	}
	http.open("GET", "../script/invoice_removefromcart.php?id=" + obj.l1);
	http.onreadystatechange = function() {
		if (http.readyState == 4) {
			if (http.responseText != "0") {
				parent.document.getElementById("debugger").value += http.responseText;
		
				//updateTotals();
				//refreshCart();
			} else {
				parent.document.getElementById("debugger").value += "Cannot remove item from cart.";
			}
		}
	}
	http.send(null);
	
	parent.document.getElementById('invoiceItems').value = "0";
	parent.document.getElementById('invoiceTotal').value = "0.00";
	//setTimeout("updateTotals()",500);
	setTimeout("refreshCart()",500);
	//setTimeout("updateTotal()",500);
}

function refreshCart() {
	var f = parent.document.getElementById('invCartFrame');
	f.contentWindow.location.reload(true);
}

function uppOp(list) {
	// alert(list.substring(list.length-1,list.length));
	if (list.substring(list.length - 1, list.length) == "1") {
		option1 = parent.document.getElementById("payMethod1").selectedIndex;
	}
	if (list.substring(list.length - 1, list.length) == "2") {
		option2 = parent.document.getElementById("payMethod2").selectedIndex;
	}
	if (list.substring(list.length - 1, list.length) == "3") {
		option3 = parent.document.getElementById("payMethod3").selectedIndex;
	}
	// option1 = parent.document.getElementById("payMethod1").selectedIndex;
}

/*
 * Pay popup
 */

function choose_payment() {
	var txt = "";
	var i;
	var payments = new Array();
	var payIndex = document.getElementById("paySize").selectedIndex;
	//var payNum = parseInt(document.getElementById("paySize")[payIndex].value);
	var payTotal = document.getElementById("payTotal").value;
	if (payIndex == 2) {
		payOpt = "2";
		paypop1(payTotal.substr(1));
	} else if(payIndex == 1) {
		payOpt = "1";
		document.getElementById("payAmount1").value = "0.00";
		document.getElementById("payAuth1").value = "";
		document.getElementById("payAuth1").disabled = true;
		document.getElementById("payMethod1").selectedIndex = 0;
		document.getElementById("payAmount2").value = "0.00";
		document.getElementById("payAuth2").value = "";
		document.getElementById("payAuth2").disabled = true;
		document.getElementById("payMethod2").selectedIndex = 0;
		document.getElementById("payAmount3").value = "0.00";
		document.getElementById("payAuth3").value = "";
		document.getElementById("payAuth3").disabled = true;
		document.getElementById("payMethod3").selectedIndex = 0;
		document.getElementById("payAmount1").value = payTotal.substr(1);
	}
}

function paypop1(n) {
	//alert(window.paymentOptions);
	txt = 'Enter First Amount'
			+ '<div class="field"><label for="firstAmount">Amount</label>'
			+ '<input type="hidden" id="tag" name="tag" value="1st">'
			+ '<input type="text" id="firstAmount" name="firstAmount" value="'
			+ n + '" />'
			+ '<input type="hidden" id="leftover" name="leftover" value="' + n
			+ '" /></div>';
	$.prompt(txt, {
		callback : paypop2,
		buttons : {
			Ok : true,
			Cancel : false
		},
		prefix : 'cleanblue'
	});
}

function paypop2(v, m, f) {
	// var leftover = document.getElementById("leftover");
	// var firstPay = document.getElementById("firstAmount");
	//window.paymentOptions = 1;
	var leftover = f.leftover;
	var firstPay = f.firstAmount;
	var lefto = round2(parseFloat(leftover) - parseFloat(firstPay));
	var amt = document.getElementById("payAmount1");
	//var rm = document.getElementById("payRemain");
	//var remain = document.getElementById("payRemain");
	amt.value = f.firstAmount;
	var v = f.leftover;
	//remain.value = lefto;
	v.value = lefto;
	//update_val(v, m, f);
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
			callback : final_update,
			buttons : {
				Ok : true,
				Cancel : false
			},
			prefix : 'cleanblue'
		});
	}
}


/*function update_val(v, m, f) {
	var payTotal = document.getElementById("payTotal");
	//var payRemain = document.getElementById("payRemain");
	if (f.tag == "1st") {
		var box = document.getElementById("payAmount1");
		box.value = f.firstAmount;
		var diff = round2(parseFloat((payRemain.value).substr(1)) - f.firstAmount);
		payRemain.value = "$"+diff;
		// alert(payTotal.value);
	} else if (f.tag == "2nd") {
		var box = document.getElementById("payAmount2");
		box.value = f.secondAmount;
		var diff = round2(parseFloat((payRemain.value).substr(1)) - f.secondAmount);
		payRemain.value = "$"+diff;
		// alert(payTotal.value);
	} else if (f.tag == "3rd") {
		var box = document.getElementById("payAmount3");
		box.value = f.thirdAmount;
		var diff = round2(parseFloat((payRemain.value).substr(1)) - f.thirdAmount);
		payRemain.value = "$"+diff;
		// alert(payTotal.value);
	} else {

	}
}*/

/*function paypop3(v, m, f) {
	window.paymentOptions = 2;
	var leftover = f.leftover;
	var secondPay = f.secondAmount;
	var lefto = round2(parseFloat(leftover) - parseFloat(secondPay));
	var amt = document.getElementById("payAmount2");
	//var remain = document.getElementById("payRemain");
	amt.value = f.secondAmount;
	// var v = document.getElementById("leftover");
	var v = f.leftover;
	//remain.value = lefto;
	v.value = lefto;
	update_val(v, m, f);
	//alert(window.paymentOptions);
	if (lefto > 0) {
		txt = 'Enter Third Amount'
				+ '<div class="field"><label for="thirdAmount">Amount</label>'
				+ '<input type="hidden" id="tag" name="tag" value="3rd">'
				+ '<input type="text" id="thirdAmount" name="thirdAmount" value="'
				+ lefto + '" />'
				+ '<input type="hidden" id="leftover" name="leftover" value="'
				+ lefto + '" /></div>';
		$.prompt(txt, {
			callback : final_update,
			buttons : {
				Ok : true,
				Cancel : false
			},
			prefix : 'cleanblue'
		});
	}
}*/

function final_update(v, m, f) {
	//var remain = document.getElementById("payRemain").value;
	var amt2 = document.getElementById("payAmount2");
	var amt3 = document.getElementById("payAmount3");
	amt2.value = f.secondAmount;
	amt3.value = round2(parseFloat(f.leftover) - parseFloat(f.secondAmount));
	window.paymentOptions = 3;
	//alert(window.paymentOptions);
	//update_val(v, m, f)
	//var lefto = round2(parseFloat(remain) - parseFloat(f.thirdAmount));
	//remain.value = leftto;
}

/*
 * Repay popup
 */

function choose_repay() {
	var txt = "";
	var i;
	var payments = new Array();
	var payIndex = document.getElementById("repaySize").selectedIndex;
	//var payNum = parseInt(document.getElementById("paySize")[payIndex].value);
	var repayTotal = document.getElementById("repayAmt").value;
	if (payIndex == 1) {
		repaypop1(repayTotal.substr(1));
	} else {
		document.getElementById("repayAmt1").value = repayTotal.substr(1);
		document.getElementById("repayAmt2").value = "0.00";
		document.getElementById("repayAmt3").value = "0.00";
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
	//alert(amt.value);
	if(obj.click == "false") {
		obj.style.background = '#000000';
		obj.style.color = '#ffffff';
		obj.click = "true";
		var i = tags.indexOf(obj.tag);
		if(i != -1) {
			tags.splice(i,1);
		} else {
			tags.splice(tags.length,0,obj.tag);
		}
		var val = parseFloat((ra.value).substr(1)) + parseFloat(obj.amt);
	} else if(obj.click == "true") {
		obj.style.background = '#ffffe0';
		obj.style.color = '#000000';
		obj.click = "false";
		var i = tags.indexOf(obj.tag);
		if(i != -1) {
			tags.splice(i,1);
		} else {
			tags.splice(tags.length,0,obj.tag);
		}
		var val = parseFloat((ra.value).substr(1)) - parseFloat(obj.amt);
	}
	ra.value = "$"+round2(val);
	parent.window.fullRepay = round2(val);
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
	//alert(tags);
	var items="";
	for(var i=0;i<tags.length;i++) {
		items += tags[i] + ",";
	}
	var url = "../script/invoice_outstandingitems_print.php?iID="+items.substr(0,items.length-1);
	//alert(url);
	parent.invOSItemsFrame.location.href = url;
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
	var amt1 = document.getElementById("repayAmt1").value;
	var amt2 = document.getElementById("repayAmt2").value;
	var amt3 = document.getElementById("repayAmt3").value;
	var tot = parseFloat(amt1) + parseFloat(amt2) + parseFloat(amt3);
	var et = document.getElementById("repayAmt").value;
	
	if(tot != parseFloat(et.substr(1))) {
		alert("Total repaid amount does not equal to expected repay amount.");
		return;
	}
	
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
	if (answer)
		setTimeout("confirmRepayHelp()", 100);
	else
		return;
}

function confirmRepayHelp() {
	var custID = document.getElementById('custID').value;
	var cFirstN = document.getElementById('cFirstN').value;
	var cLastN = document.getElementById('cLastN').value;
	var cDate = document.getElementById('datePick2').value;
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
	var minutes = today.getMinutes();
	var seconds = today.getSeconds();
	var ses = "M" + year+d+hours+minutes+seconds;
	// var server =
	// document.getElementById('invServer')[document.getElementById('invServer').selectedIndex].innerHTML;
	// var teller =
	// document.getElementById('invTeller')[document.getElementById('invTeller').selectedIndex].innerHTML;
	var server = document.getElementById('repayServer').selectedIndex;
	var teller = document.getElementById('repayTeller').selectedIndex;
	var url = "../script/print_invoice.php?" 
		+ "inCID=" + custID 
		+ "&inFName=" + cFirstN 
		+ "&inLName=" + cLastN 
		+ "&inSession=" + ses 
		+ "&inServer=" + server 
		+ "&inDate=" + (cDate.split(' '))[0];
	alert(url);
	//window.open(url, "_blank", "height=595px,width=842px"); robert montesano
	var fun = "updateRepayTables('"+ses+"')";
	setTimeout(fun, 1000);
}

function updateRepayTables(ses) {
	alert(ses);
	/*var numpay = 0;
	var actPay = 0;
	var sessionID = document.getElementById('sessionID');
	var custID = document.getElementById('custID').value;
	var storeID = document.getElementById('defStoreID').value;
	//var storeID = 1;
	var total = document.getElementById('payTotal').value;
	var server = document.getElementById('invServer').selectedIndex;
	var teller = document.getElementById('invTeller').selectedIndex;
	var po = document.getElementById('payPO').value;

	var url = "payOp=" + paymentOptions
			+ "&totalDue=" + total + "&inSession="
			+ sessionID.innerHTML.substring(12, sessionID.innerHTML.length - 4)
			+ "&inCID=" + custID + "&inSID=" + storeID + "&inServer=" + server
			+ "&inTeller=" + teller + "&payPO=" + po;
	// alert(url);
	if (document.getElementById("payMethod1").selectedIndex > 0) {
		var pm1 = document.getElementById("payMethod1").selectedIndex;
		var pay1 = document.getElementById('payAmount1').value;
		var note1 = document.getElementById('payNote1').value;
		if(pm1 != 1) {
			actPay += parseFloat(pay1);
		}
		var auth1 = document.getElementById('payAuth1');
		if(auth1.value.length > 0) {
			url += "&pm1=" + pm1 + "&note1=" + note1 + "&pay1=" + pay1 + "&auth1=" + auth1.value;
		} else {
			url += "&pm1=" + pm1 + "&note1=" + note1 + "&pay1=" + pay1;
		}
		numpay = 1;
		// alert(url);
	}
	if (document.getElementById("payMethod2").selectedIndex > 0) {
		var pm2 = document.getElementById("payMethod2").selectedIndex;
		var pay2 = document.getElementById('payAmount2').value;
		var note2 = document.getElementById('payNote3').value;
		if(pm2 != 1) {
			actPay += parseFloat(pay2);
		}
		var auth2 = document.getElementById('payAuth2');
		if(auth2.value.length > 0) {
			url += "&pm2=" + pm2 + "&note2=" + note2 + "&pay2=" + pay2 + "&auth2=" + auth2.value;
		} else {
			url += "&pm2=" + pm2 + "&note2=" + note2 + "&pay2=" + pay2;
		}
		numpay = 2;
		// alert(url);
	}
	if (document.getElementById("payMethod3").selectedIndex > 0) {
		var pm3 = document.getElementById("payMethod3").selectedIndex;
		var pay3 = document.getElementById('payAmount3').value;
		var note3 = document.getElementById('payNote3').value;
		if(pm3 != 1) {
			actPay += parseFloat(pay3);
		}
		var auth3 = document.getElementById('payAuth3');
		if(auth3.value.length > 0) {
			url += "&pm3=" + pm3 + "&note3=" + note3 + "&pay3=" + pay3 + "&auth3=" + auth3.value;
		} else {
			url += "&pm3=" + pm3 + "&note3=" + note3 + "&pay3=" + pay3;
		}
		numpay = 3;
		// alert(url);
	}
	makePOSTRequest("../script/invoice_pay.php",url+"&numPay="+numpay+"&actPay="+actPay);*/
}