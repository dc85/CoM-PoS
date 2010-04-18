var addProductWindowOpen = 0;
var editProductWindowOpen = 0;
var editSPCWindowOpen = 0;
var fullPictureOpen = 0;
var editMasterArray = "";
var newMasterArray = "";
var editSPCArray = "";
var showDebug = 0;
var curProduct = null;
var prevProduct = null;
var editArray = new Array();

$(document).ready(function() {
	$("#addProductPopupContactClose").click(function() {
		disableAddProductPopup();
	});

	$("#editProductPopupContactClose").click(function() {
		disableEditProductPopup();
	});

	$("#editSPCPopupContactClose").click(function() {
		disableEditSPCPopup();
	});

	// Click out event!
		$("#backgroundPopup").click(function() {
			if (addProductWindowOpen == 1) {
				disableAddProductPopup();
			}
			if (editProductWindowOpen = 1) {
				disableEditProductPopup();
			}
			if (editSPCWindowOpen = 1) {
				disableEditSPCPopup();
			}
		});
		// Press Escape event!
		$(document).keypress(function(e) {
			if (e.keyCode == 27 && (addProductWindowOpen == 1)) {
				disableAddProductPopup();
			}
			if (e.keyCode == 27 && (editProductWindowOpen == 1)) {
				disableEditProductPopup();
			}
			if (e.keyCode == 27 && (editSPCWindowOpen == 1)) {
				disableEditSPCPopup();
			}
			if (e.keyCode == 27 && (fullPictureOpen == 1)) {
				restoreImage();
			}
		});
	});

function resetPFields() {
	var id = document.getElementById('defStoreID').value;
	document.getElementById("search_pStore").selectedIndex = id;
	document.getElementById("search_Supplier").selectedIndex = 0;
	document.getElementById("prodSearch").value = "";
	document.getElementById('prodSearch').focus();
	setTimeout("reset_row()", 100);
}

function prodEditUpdate(eleName) {
	if (eleName == "iCost") {
		var ele_cost = document.getElementById("iCost");
		var bprice = document.getElementById("pBPrice").value;
		// alert(bprice);
		if (isNaN(ele_cost.value)) {
			alert("Please enter a valid NUMBER value for Product Cost");
			ele_cost.value = "";
			return;
		} else {
			var box_list = new Array("iSPC1prc", "iSPC2prc", "iSPC3prc",
					"iSPC4prc", "iSPC5prc", "iSPC6prc", "iSPC7prc", "iSPC8prc",
					"iSPC9prc", "iSPC10prc");
			for ( var i = 0; i < box_list.length; i++) {
				var multi = box_list[i].replace("prc", "lvl");
				var multiplier = document.getElementById(multi).value;
				var price = document.getElementById(box_list[i]);
				// alert(ele_cost.value+"*"+multiplier);
				var result = round2(parseFloat(ele_cost.value)
						* parseFloat(multiplier));
				price.value = result;
				var tip = "";
				var index = box_list[i].replace("prc", "fee");
				var fee = document.getElementById(index);
				var feed = 0.00;
				var catg = document.getElementById("pCatType").selectedIndex;
				var csize = document.getElementById("pCSize").selectedIndex;
				if (csize == "0.25" || csize == "0.00" || catg != "2") {
					feed = parseFloat(result) + 0.00;
					tip = tip + "Tint: $" + "0.00" + "\n";
				} else {
					feed = parseFloat(result) + parseFloat(bprice)
							* parseFloat(csize);
					tip = tip + "Tint: $"
							+ round2(parseFloat(bprice) * parseFloat(csize))
							+ "\n";
				}
				if (csize == "NA") {
					feed = feed + 0.00;
					tip = tip + "Eco: $" + "0.00" + "\n";
				} else {
					feed = feed + parseFloat(csize) * 0.4;
					tip = tip + "Eco: $" + round2(parseFloat(csize) * 0.4)
							+ "\n";
				}
				fee.innerText = "$" + round2(feed);
				tip = tip + "Total: $" + round2(feed);
				fee.title = tip;
			}
			var eMSRP = document.getElementById("iPrice");
			var spc1 = parent.document.getElementById("iSPC1lvl").value;
			eMSRP.value = round2(parseFloat(ele_cost.value) * parseFloat(spc1));
			prodEditUpdate('iPrice');
		}
		// setTimeout("prodEditUpdate('iCost')",100);
		//setTimeout("prodEditUpdate('iPrice')", 100);
		//setTimeout("addSPCVals()", 100);
		addSPCVals();
		var i = editArray.indexOf('iCost');
		if(i == -1) {
			editArray.splice(editArray.length,0,'iCost');
		}
		//prodEditUpdate('iCost');
	} else {
		var i = editArray.indexOf(eleName);
		if(i == -1) {
			editArray.splice(editArray.length,0,eleName);
		}
	}
}

Array.prototype.indexOf = function(p_val) {
	for(var i = 0, l = this.length; i < l; i++) {
		if(this[i] == p_val) {
			return i;
		}
	}
	return -1;
};

function editProductHelp() {
	if(editArray.length == 0) {
		alert("No changes made.");
		return;
	}
	var answer = confirm("Confirm edit product");
	if (answer)
		setTimeout("editProduct()", 100);
	else
		return;
}

function refreshProduct() {
	var selSID = document.getElementById('selSID').value;
	var selPID = document.getElementById('selPID').value;
	if (curProduct == null) {
		// alert("NP");
		return;
	} else {
		choose_product(curProduct);
		editArray = new Array();
	}
}

function editProduct() {
	/*var selSID = document.getElementById('selSID').value;
	var selPID = document.getElementById('selPID').value;
	if (selSID == "-1" || selPID == "-1") {
		alert("Please select product to edit");
		return;
	}
	if (window.editMasterArray.length <= 0) {
		alert("No changes has been made to the record");
		return;
	}
	var str = window.editMasterArray.substring(0,
			window.editMasterArray.length - 1);
	// alert(str);
	var getstr = str + "&ecStoreID=" + selSID + "&ecID=" + selPID;
	// alert(getstr);*/
	var editString = "";
	//alert(editArray);
	for(var i=0;i<editArray.length;i++) {
		var ele = document.getElementById(editArray[i]);
		if(ele.type == "text" || ele.type == "file") {
			if(editArray[i] == "iSPC1prc" || editArray[i] == "iSPC2prc" || 
				editArray[i] == "iSPC3prc" || editArray[i] == "iSPC4prc" || 
				editArray[i] == "iSPC5prc" || editArray[i] == "iSPC6prc" || 
				editArray[i] == "iSPC7prc" || editArray[i] == "iSPC8prc" || 
				editArray[i] == "iSPC9prc" || editArray[i] == "iSPC10prc") {
				editString += editArray[i].replace("prc","") + "=" + ele.value + "&";
			} else {
				editString += editArray[i] + "=" + ele.value + "&";
			}
		} else if(ele.type == "select-one") {
			editString += editArray[i] + "=" + ele.selectedIndex + "&";
		} else {
			editString += editArray[i] + "=" + ele.innerHTML + "&";
		}
	}
	var sID = document.getElementById("selSID").value;
	var pID = document.getElementById("selPID").value;
	
	editString += "sID=" + sID + "&pID=" + pID;
	if (navigator.appName == "Microsoft Internet Explorer") {
		http = new ActiveXObject("Microsoft.XMLHTTP");
	} else {
		http = new XMLHttpRequest();
	}
	//alert(editString);//robert@icon1.net
	http.open("GET", "../script/product_edit.php?" + editString, true);
	http.onreadystatechange = function() {
		if (http.readyState == 4) {
			var str = http.responseText;
			var msg = document.getElementById("lb_returnMessage");
			//alert(str);
			if(str.substr(7,3) == "ERR") {
			   //alert("ERROR");
				$("#returnMessage").css({
					"backgroundColor": "#FF3300"
				});
				msg.style.color = "white";
				// msg.style.fontWeight = "bold";
				msg.innerHTML = "Product update failed.";
				$("#returnMessage").slideDown(300).delay(1500).fadeOut('fast');
				document.getElementById("debugger").value += str + "\n";
				//editArray = new Array();
			} else if(str.substr(7,3) == "MSG") {
				//alert("MESSAGE");
				$("#returnMessage").css({
					"backgroundColor": "#00FF00"
				});
				msg.style.color = "black";
				// msg.style.fontWeight = "bold";
				msg.innerHTML = "Product update successful.";
				$("#returnMessage").slideDown(300).delay(1500).fadeOut('fast');
				editArray = new Array();
			} else {
				document.getElementById("debugger").value += str + "\n";
			}
			//return;
			
		}
	}
	http.send(null);
	//window.editMasterArray = "";
	editArray = new Array();
}

function loadEditInfo(sid, pid) {
	// alert(sid + "+" + pid);
	var titles = new Array('edit_iStoreID','edit_pSupplier','edit_pName',
			'edit_pCatType','edit_pDescription', 'edit_pUPC','edit_pSku',
			'edit_pProdCode','edit_pBSize','edit_pCSize','edit_iCost',
			'edit_iPrice','edit_iTax1','edit_iTax2','edit_iMaxQty',
			'edit_iMinQty','edit_iQty','edit_pBPrice');

	var poststr = "sid=" + sid + "&pid=" + pid;

	if (navigator.appName == "Microsoft Internet Explorer") {
		http = new ActiveXObject("Microsoft.XMLHTTP");
	} else {
		http = new XMLHttpRequest();
	}
	http.open("GET", "../php/product_retrieveedit.php?" + poststr, true);
	http.onreadystatechange = function() {
		if (http.readyState == 4) {
			var str = http.responseText;
			var list = str.split('|');
			// alert(list);
			for ( var i = 0; i < (titles.length); i++) {
				var ele = document.getElementById(titles[i]);
				if (titles[i] == "edit_pBPrice") {
					ele.value = list[i];
				} else if (titles[i] == "edit_pCSize") {
					// alert(list[i]);
					if (list[i] == "0.00") {
						ele.selectedIndex = 0;
					} else if (list[i] == "5.00") {
						ele.selectedIndex = 1;
					} else if (list[i] == "0.25") {
						ele.selectedIndex = 2;
					} else if (list[i] == "1.00") {
						ele.selectedIndex = 3;
					} else if (list[i] == "2.00") {
						ele.selectedIndex = 4;
					} else if (list[i] == "3.00") {
						ele.selectedIndex = 5;
					} else if (list[i] == "3.50") {
						ele.selectedIndex = 6;
					} else {
						ele.selectedIndex = 0;
					}
				} else {
					if (ele.type == "select-one") {
						ele.selectedIndex = list[i];
					} else {
						ele.innerText = list[i];
					}
				}
			}
			// setTimeout("fillPricesEdit()", 100);
		}
	}
	http.send(null);
}

function disableAddProductPopup() {
	// disables popup only if it is enabled
	if (addProductWindowOpen = 1) {
		$("#backgroundPopup").fadeOut("fast");
		$("#addProductPopupContact").fadeOut("fast");
		addProductWindowOpen = 0;
	}
}

function cancelAddProductPopup() {
	// disables popup only if it is enabled
	setTimeout("clearAddProduct()", 50);
	if (addProductWindowOpen = 1) {
		$("#backgroundPopup").fadeOut("fast");
		$("#addProductPopupContact").fadeOut("fast");
		addProductWindowOpen = 0;
	}
}

function disableEditProductPopup() {
	// disables popup only if it is enabled
	if (editProductWindowOpen = 1) {
		$("#backgroundPopup").fadeOut("fast");
		$("#editProductPopupContact").fadeOut("fast");
		editProductWindowOpen = 0;
	}
}

function disableEditSPCPopup() {
	// disables popup only if it is enabled
	if (editSPCWindowOpen = 1) {
		$("#backgroundPopup").fadeOut("fast");
		$("#editSPCPopupContact").fadeOut("fast");
		editSPCWindowOpen = 0;
	}
}

function showAddProductPopup() {
	// loads popup only if it is disabled
	setTimeout("showAddProductPopupHelp()", 100);
}

function showAddProductPopupHelp() {
	if (addProductWindowOpen == 0) {
		$("#backgroundPopup").css( {
			"opacity" : "0.7"
		});
		$("#backgroundPopup").fadeIn("fast");
		$("#addProductPopupContact").fadeIn("fast");
		addProductWindowOpen = 1;
	}
}

function showEditSPCPopup() {
	var box_list = new Array("editSPC1", "editSPC2", "editSPC3", "editSPC4",
			"editSPC5", "editSPC6", "editSPC7", "editSPC8", "editSPC9",
			"editSPC10");
	if (navigator.appName == "Microsoft Internet Explorer") {
		http = new ActiveXObject("Microsoft.XMLHTTP");
	} else {
		http = new XMLHttpRequest();
	}
	http.open("GET", "../php/product_getspc.php", true);
	http.onreadystatechange = function() {
		if (http.readyState == 4) {
			var str = http.responseText;
			var list = str.split('|');
			// alert(str);
			for ( var i = 0; i < box_list.length; i++) {
				var ele = document.getElementById(box_list[i]);
				ele.value = list[i];
			}
		}
	}
	http.send(null);
	setTimeout("showEditSPCPopupHelp()", 100);
}

function showEditSPCPopupHelp() {
	if (editSPCWindowOpen == 0) {
		$("#backgroundPopup").css( {
			"opacity" : "0.7"
		});
		$("#backgroundPopup").fadeIn("fast");
		$("#editSPCPopupContact").fadeIn("fast");
		editSPCWindowOpen = 1;
	}
}

function showEditProductPopup() {
	// loads popup only if it is disabled
	var pid = document.getElementById("selPID").value;
	var sid = document.getElementById("selSID").value;
	if (pid == "-1" || sid == "-1") {
		alert("Please select a product first");
		return;
	} else {
		var url = "../php/product_editspec.php?pid=" + pid + "&sid=" + sid;
		window.open(url, "editSpecFrame");
		setTimeout("showEditProductPopupHelp()", 100);
	}
}

function showEditProductPopupHelp() {
	if (editProductWindowOpen == 0) {
		$("#backgroundPopup").css( {
			"opacity" : "0.7"
		});
		$("#backgroundPopup").fadeIn("fast");
		$("#editProductPopupContact").fadeIn("fast");
		editProductWindowOpen = 1;
	}
}

function roundNumber(num, dec) {
	var result = Math.round(num * Math.pow(10, dec)) / Math.pow(10, dec);
	return result;
}

function submitSPC() {
	var array = editSPCArray.substr(0, editSPCArray.length - 1);
	// alert(array);
	if (navigator.appName == "Microsoft Internet Explorer") {
		http = new ActiveXObject("Microsoft.XMLHTTP");
	} else {
		http = new XMLHttpRequest();
	}
	http.open("GET", "../php/product_editspc.php?str=" + array, true);
	http.onreadystatechange = function() {
		if (http.readyState == 4) {
			var str = http.responseText;
			alert(str);
		}
	}
	http.send(null);
}

function updateSPCArray(eleName) {
	// alert(eleName);
	var ele = document.getElementById(eleName);
	// var editSPCArray = window.editSPCArray;
	if (ele.type == "text" || ele.type == "file") {
		if (editSPCArray.indexOf(eleName) != -1) {
			// alert("STRING EXISTS");
			// var subs = editSPCArray.substring(editSPCArray.indexOf(eleName) +
			// eleName.length + 1,
			// editSPCArray.indexOf("|",editSPCArray.indexOf(eleName) +
			// eleName.length + 1));
			var before = editSPCArray.substring(0, editSPCArray
					.indexOf(eleName)
					+ eleName.length + 1);
			var after = editSPCArray.substring(editSPCArray.indexOf("|",
					editSPCArray.indexOf(eleName) + eleName.length + 1),
					editSPCArray.length);
			editSPCArray = before + ele.value + after;
		} else {
			editSPCArray += eleName + "@" + ele.value;
			editSPCArray += "|";
		}
	} else if (ele.type == "select-one") {
		if (editSPCArray.indexOf(eleName) != -1) {
			var before = editSPCArray.substring(0, editSPCArray
					.indexOf(eleName)
					+ eleName.length + 1);
			var after = editSPCArray.substring(editSPCArray.indexOf("|",
					editSPCArray.indexOf(eleName) + eleName.length + 1),
					editSPCArray.length);
			editSPCArray = before + ele.selectedIndex + after;
		} else {
			editSPCArray += eleName + "@" + ele.selectedIndex;
			editSPCArray += "|";
		}
	} else if (ele.type == "checkbox") {
		var checkd = "";
		if (editSPCArray.indexOf(eleName) != -1) {
			if (ele.checked == true) {
				checkd = "1";
			} else {
				checkd = "0";
			}
			var before = editSPCArray.substring(0, editSPCArray
					.indexOf(eleName)
					+ eleName.length + 1);
			var after = editSPCArray.substring(editSPCArray.indexOf("|",
					editSPCArray.indexOf(eleName) + eleName.length + 1),
					editSPCArray.length);
			editSPCArray = before + checkd + after;
		} else {
			if (ele.checked == true) {
				checkd = "1";
			} else {
				checkd = "0";
			}
			editSPCArray += eleName + "@" + checkd;
			editSPCArray += "|";
		}
	}
}

function setBPrice() {
	var ele_base = document.getElementById("new_pBSize");
	var str = "getBPrice(" + ele_base.selectedIndex + ")";
	setTimeout(str, 100);
}

function setCPrice() {
	var ele_base = document.getElementById("new_pCSize");
	var str = "getCPrice('" + ele_base[ele_base.selectedIndex].innerHTML + "')";
	setTimeout(str, 100);
}

function fillPrices() {
	var ele_cost = document.getElementById("new_iCost");
	var ele_base = document.getElementById("new_pBSize");
	var bprice = document.getElementById("new_pBPrice").value;
	var csize = document.getElementById("new_pCPrice").value;
	if (isNaN(ele_cost.value)) {
		alert("Please enter a valid NUMBER value");
		ele_cost.value = "";
		return;
	} else {
		var box_list = new Array("new_iSPC1", "new_iSPC2", "new_iSPC3",
				"new_iSPC4", "new_iSPC5", "new_iSPC6", "new_iSPC7",
				"new_iSPC8", "new_iSPC9", "new_iSPC10");
		for ( var i = 0; i < box_list.length; i++) {
			var multi = box_list[i] + "lvl";
			var multiplier = document.getElementById(multi).innerHTML;
			var price = document.getElementById(box_list[i]);
			var result = round2(parseFloat(ele_cost.value)
					* parseFloat(multiplier));
			price.innerHTML = "$" + result;
			var tip = "";
			var index = box_list[i] + "fee";
			var fee = document.getElementById(index);
			var feed = 0.00;
			var catg = document.getElementById("new_pCatType").selectedIndex;
			var csize = document.getElementById("new_pCSize").selectedIndex;
			if (csize == "2" || csize == "0" || catg != "2") {
				feed = parseFloat(result) + 0.00;
				tip = tip + "Tint: $" + "0.00" + "\n";
			} else {
				feed = parseFloat(result) + parseFloat(bprice)
						* parseFloat(csize);
				tip = tip + "Tint: $"
						+ round2(parseFloat(bprice) * parseFloat(csize)) + "\n";
			}
			if (csize == "NA") {
				feed = feed + 0.00;
				tip = tip + "Eco: $" + "0.00" + "\n";
			} else {
				feed = feed + parseFloat(csize) * 0.4;
				tip = tip + "Eco: $" + round2(parseFloat(csize) * 0.4) + "\n";
			}
			fee.innerText = "$" + round2(feed);
			tip = tip + "Total: $" + round2(feed);
			fee.title = tip;
			if (box_list[i] == "new_iSPC1") {
				var msrp = document.getElementById("new_iPrice");
				msrp.value = result;
			}
		}
	}
}

/*
 * function fillPricesEdit() { var ele_cost =
 * document.getElementById("edit_iCost"); var bprice =
 * document.getElementById("edit_pBPrice").value; //alert(bprice);
 * if(isNaN(ele_cost.value)){ alert("Please enter a valid NUMBER value for
 * Product Cost"); ele_cost.value = ""; return; } else { var box_list = new
 * Array("edit_iSPC1prc","edit_iSPC2prc","edit_iSPC3prc",
 * "edit_iSPC4prc","edit_iSPC5prc","edit_iSPC6prc","edit_iSPC7prc",
 * "edit_iSPC8prc","edit_iSPC9prc","edit_iSPC10prc"); for(var i=0;i<box_list.length;i++) {
 * var multi = box_list[i].replace("prc","lvl"); var multiplier =
 * document.getElementById(multi).innerHTML; var price =
 * document.getElementById(box_list[i]); //alert(ele_cost.value+"*"+multiplier);
 * var result = round2(parseFloat(ele_cost.value) * parseFloat(multiplier));
 * price.innerHTML = result; var tip = ""; var index =
 * box_list[i].replace("prc","fee"); var fee = document.getElementById(index);
 * var feed = 0.00; var catg =
 * document.getElementById("edit_pCatType").selectedIndex; var csize =
 * document.getElementById("edit_pCSize").selectedIndex; if(csize=="1Q" ||
 * csize=="NA" || catg != "PAINT") { feed = parseFloat(result) + 0.00; tip = tip +
 * "Tint: $" + "0.00" + "\n"; } else { feed = parseFloat(result) +
 * parseFloat(bprice)*parseFloat(csize); tip = tip + "Tint: $" +
 * round2(parseFloat(bprice)*parseFloat(csize)) + "\n"; } if(csize == "NA") {
 * feed = feed + 0.00; tip = tip + "Eco: $" + "0.00" + "\n"; } else { feed =
 * feed + parseFloat(csize)*0.4; tip = tip + "Eco: $" +
 * round2(parseFloat(csize)*0.4) + "\n"; } fee.innerText = "$" + round2(feed);
 * tip = tip + "Total: $" + round2(feed); fee.title = tip; } var eMSRP =
 * document.getElementById("edit_iPrice"); var spc1 =
 * parent.document.getElementById("iSPC1lvl").innerHTML; eMSRP.value =
 * round2(parseFloat(ele_cost.value)*parseFloat(spc1)); }
 * setTimeout("prodEditUpdate('edit_iCost')",100);
 * setTimeout("prodEditUpdate('edit_iPrice')",100); }
 */

function reset_row() {
	var selSID = document.getElementById('selSID');
	var selPID = document.getElementById('selPID');
	var titles = new Array('iStoreID', 'pSupplier', 'pName', 'pCatType',
			'pDescription', 'pUPC', 'pSku', 'pProdCode', 'pCSize', 'pBSize',
			'iCost', 'iPrice', 'iTax1', 'iTax2', 'iMinQty', 'iQty', 'iMaxQty',
			'pPromo', 'iSPC1prc', 'iSPC2prc', 'iSPC3prc', 'iSPC4prc',
			'iSPC5prc', 'iSPC6prc', 'iSPC7prc', 'iSPC8prc', 'iSPC9prc',
			'iSPC10prc', 'pBPrice', 'iSPC1lvl', 'iSPC2lvl', 'iSPC3lvl',
			'iSPC4lvl', 'iSPC5lvl', 'iSPC6lvl', 'iSPC7lvl', 'iSPC8lvl',
			'iSPC9lvl', 'iSPC10lvl', 'iSPC1fee', 'iSPC2fee', 'iSPC3fee',
			'iSPC4fee', 'iSPC5fee', 'iSPC6fee', 'iSPC7fee', 'iSPC8fee',
			'iSPC9fee', 'iSPC10fee');

	selSID.value = "-1";
	selPID.value = "-1";
	for ( var i = 0; i < (titles.length); i++) {
		var ele = document.getElementById(titles[i]);
		if (titles[i] == "pBPrice") {
			ele.value = "0";
		} else if (titles[i] == "pDe") {
			// alert(ele.type);
		} else {
			if (ele.type == "select-one") {
				ele.selectedIndex = 0;
			} else if (ele.type == "text" || ele.type == "textarea") {
				ele.value = "";
			} else {
				ele.innerHTML = "";
			}
		}
	}
}

function choose_product(obj) {
	if (parent.curProduct == null) {
		// obj.style.background = "#000000";
		// obj.style.color = "#FFFFFF";
		// obj.addClass("clicked");
		// obj.addClass("clicked");
	} else {
		// alert(obj.selected);
		/*
		 * parent.curProduct.style.background = "#FFFFE0";
		 * parent.curProduct.style.color = "#000000"; obj.style.background =
		 * "#000000"; obj.style.color = "#FFFFFF";
		 */
	}
	// window.prevObj = obj;
	parent.curProduct = obj;

	editMasterArray = "";
	var selSID = parent.document.getElementById('selSID');
	var selPID = parent.document.getElementById('selPID');
	var titles = new Array('iStoreID','pSupplier','pName','pCatType',
			'pDescription','pUPC','pSku','pProdCode','pCSize','pBSize',
			'iCost','iPrice','iTax1','iTax2','iMinQty','iQty','iMaxQty',
			'pPromo','iSPC1prc','iSPC2prc','iSPC3prc','iSPC4prc',
			'iSPC5prc','iSPC6prc','iSPC7prc','iSPC8prc','iSPC9prc',
			'iSPC10prc','pPicture','bprice');

	selSID.value = obj.p1;
	selPID.value = obj.p2;
	// alert("store" + sid + ":product" + pid);
	// url = "../php/product_spec.php?sid=" + sid + "&pid=" + pid;
	// window.open(url,"specFrame");
	var poststr = "sid=" + obj.p1 + "&pid=" + obj.p2;

	if (navigator.appName == "Microsoft Internet Explorer") {
		http = new ActiveXObject("Microsoft.XMLHTTP");
	} else {
		http = new XMLHttpRequest();
	}
	http.open("GET", "../php/product_retrievemain.php?" + poststr, true);
	http.onreadystatechange = function() {
		if (http.readyState == 4) {
			var str = http.responseText;
			// alert(str);
			parent.document.getElementById("debugger").value += str;
			var list = str.split('|');
			//alert(list);
			var catg;
			var bsize;
			var csize;
			var cost;
			var bprice = list[list.length - 1];
			// alert(bprice);
			for ( var i = 0; i < (titles.length - 1); i++) {
				// EcoFee: IIf(Not IsNull([pCSize]),[pCSize]*0.4,0)
				// Tint: IIf([dSize]="1Q" Or [pCatType]<>2,0,[pCSize]*[bPrice])
				var ele = parent.document.getElementById(titles[i]);
				if (list[i] == "") {
					if (ele.type == "pPromo") {
						ele.value = "";
					} if (titles[i] == "pPicture") {
						parent.document.getElementById("thumbPicture").src = "../prod_pictures/na.jpg";
						parent.document.getElementById("fullPPicture").src = "../prod_pictures/na.jpg";
						
					} else {
						ele.innerText = "";
						ele.style.backgroundColor = "#ff0000";
					}
				} else {
					if (titles[i] == "pPromo") {
						ele.value = list[i];
					} else if (titles[i] == "pDesc") {
						ele.value = list[i];
					} else if (titles[i] == "iCost") {
						ele.value = list[i];
						cost = parseFloat(list[i]);
						/*
						 * var msrp = parent.document.getElementById("pMSRP");
						 * var spc1 =
						 * parent.document.getElementById("iSPC1lvl").innerHTML;
						 * msrp.innerText = round2(cost * parseFloat(spc1));
						 */
					} else if (titles[i] == "iPrice") {
						// do nothing
					} else if (titles[i] == "pCatType") {
						catg = list[i];
						ele.selectedIndex = list[i];
					} else if (titles[i] == "pBSize") {
						bsize = list[i];
						ele.selectedIndex = list[i];
					} else if (titles[i] == "pCSize") {
						csize = list[i];
						ele.selectedIndex = list[i];
					} else if (titles[i] == "pPicture") {
						//alert("Q:"+list[i]);
						if(list[i] != "") {
							var loc = list[i].replace("C:fakepath","../prod_pictures/");
							//alert(loc);
							parent.document.getElementById("thumbPicture").src =  loc;
							parent.document.getElementById("fullPPicture").src = loc;
						} else {
							var loc = "../prod_pictures/na.jpg";
							parent.document.getElementById("thumbPicture").src =  loc;
							parent.document.getElementById("fullPPicture").src = loc;
						}
					} else if (titles[i] == "iSPC1prc"
							|| titles[i] == "iSPC2prc"
							|| titles[i] == "iSPC3prc"
							|| titles[i] == "iSPC4prc"
							|| titles[i] == "iSPC5prc"
							|| titles[i] == "iSPC6prc"
							|| titles[i] == "iSPC7prc"
							|| titles[i] == "iSPC8prc"
							|| titles[i] == "iSPC9prc"
							|| titles[i] == "iSPC10prc") {
						if (titles[i] == "iSPC1prc") {
							var msrp = parent.document.getElementById("iPrice");
							msrp.value = list[i];
						}
						var price = parseFloat(list[i]);
						ele.value = price;
						ele.disabled = false;
						var lvlindex = titles[i].replace("prc", "lvl");
						var lvl = parent.document.getElementById(lvlindex);
						lvl.disabled = false;
						lvl.value = round2(price / cost);
						var tip = "";
						var index = titles[i].replace("prc", "fee");
						var fee = parent.document.getElementById(index);
						var feed = 0.00;
						if (csize == "0.25" || csize == "0.00" || catg != "2") {
							feed = price + 0.00;
							tip = tip + "Tint: $" + "0.00" + "\n";
						} else {
							feed = price + parseFloat(bprice)
									* parseFloat(csize);
							tip = tip
									+ "Tint: $"
									+ round2(parseFloat(bprice)
											* parseFloat(csize)) + "\n";
						}
						if (csize == "0.00") {

							feed = feed + 0.00;
							tip = tip + "Eco: $" + "0.00" + "\n";
						} else {

							feed = feed + parseFloat(csize) * 0.4;
							tip = tip + "Eco: $"
									+ round2(parseFloat(csize) * 0.4) + "\n";
						}
						fee.innerText = "$" + round2(feed);
						tip = tip + "Total: $" + round2(feed);
						fee.title = tip;
					} else {
						if (ele.type == "select-one") {
							ele.selectedIndex = list[i];
						} else {
							ele.value = list[i];
						}
					}
				}
			}
			var k = 0;
			for(var j=titles.length + 1;j<list.length;j++) {
				var id = "invc" + k;
				parent.document.getElementById(id).innerHTML = list[j];
				k++;
			}
		}
	}
	http.send(null);
}

function zoomImage() {
	//alert("test");
	var img = document.getElementById("thumbPicture");
	//alert(img.src);
	if(curProduct != null && (img.src).indexOf("na.jpg") < 0) {
		//var pic = document.getElementById("pPicture");
		$("#picture_bg").fadeIn("fast");
		$("#picture_control").fadeIn("fast");
		$("#full_picture").fadeIn("slow");
		fullPictureOpen = 1;
	}
}

function restoreImage() {
	var pic = document.getElementById("pPicture");
	$("#picture_bg").fadeOut("fast");
	$("#picture_control").fadeOut("fast");
	$("#full_picture").fadeOut("fast");
	fullPictureOpen = 0
}

function browseImage() {
	var pic = document.getElementById("new_pPicture");
	window.location= 'file:///' + pic.value;
}

function showSPC() {
	var box_list = new Array("iSPC1lvl", "iSPC2lvl", "iSPC3lvl", "iSPC4lvl",
			"iSPC5lvl", "iSPC6lvl", "iSPC7lvl", "iSPC8lvl", "iSPC9lvl",
			"iSPC10lvl");
	if (navigator.appName == "Microsoft Internet Explorer") {
		http = new ActiveXObject("Microsoft.XMLHTTP");
	} else {
		http = new XMLHttpRequest();
	}
	http.open("GET", "../php/product_getspc.php", true);
	http.onreadystatechange = function() {
		if (http.readyState == 4) {
			var str = http.responseText;
			var list = str.split('|');
			// alert(str);
			for ( var i = 0; i < box_list.length; i++) {
				var ele = document.getElementById(box_list[i]);
				ele.innerHTML = list[i];
			}
		}
	}
	http.send(null);
}

function setPromo(type) {
	var promoNote = document.getElementById('pPromo').value;
	if (promoNote == "") {
		alert("Please fill in promo note");
		return;
	} else {
		var selPID = parent.document.getElementById('selPID').value;
		if (selPID == "-1") {
			alert("Please select a product to put on promo");
		} else {
			var poststr = "";
			if(type == "add") {
				poststr += "selPID=" + selPID + "&note=" + promoNote;
			} else {
				poststr += "selPID=" + selPID + "&note=removexoxo";
			}

			if (navigator.appName == "Microsoft Internet Explorer") {
				http = new ActiveXObject("Microsoft.XMLHTTP");
			} else {
				http = new XMLHttpRequest();
			}
			http.open("GET", "../script/product_addpromo.php?" + poststr, true);
			http.onreadystatechange = function() {
				if (http.readyState == 4) {
					var str = http.responseText;
					// alert(str);
					var msg = document.getElementById("lb_returnMessage");
					//alert(str);
					if(str.substr(0,1) == "0") {
					   //alert("ERROR");
						$("#returnMessage").css({
							"backgroundColor": "#FF3300"
						});
						msg.style.color = "white";
						// msg.style.fontWeight = "bold";
						msg.innerHTML = "Product promo add failed.";
						$("#returnMessage").slideDown(300).delay(1500).fadeOut('fast');
						document.getElementById("debugger").value += str.substr(1) + "\n";
						//editArray = new Array();
					} else if(str.substr(0,1) == "1") {
						//alert("MESSAGE");
						$("#returnMessage").css({
							"backgroundColor": "#00FF00"
						});
						msg.style.color = "black";
						// msg.style.fontWeight = "bold";
						msg.innerHTML = str.substr(1);
						$("#returnMessage").slideDown(300).delay(1500).fadeOut('fast');
						document.getElementById("pPromo").value = "";
						document.getElementById("pPromo").focus();
					} else {
						document.getElementById("debugger").value += str + "\n";
					}
				}
			}
			http.send(null);
		}
	}
}

function addInventory() {
	var psAddQty = document.getElementById('psAddQty').value;
	if (psAddQty == "" || isNaN(psAddQty)) {
		alert("Please input a valid quantity to add");
		return;
	} else {
		var selSID = parent.document.getElementById('selSID').value;
		var selPID = parent.document.getElementById('selPID').value;
		if (selSID == "-1" || selPID == "-1") {
			alert("Please select a product to be added");
		} else {
			var poststr = "selSID=" + selSID + "&selPID=" + selPID + "&qty="
					+ psAddQty;

			if (navigator.appName == "Microsoft Internet Explorer") {
				http = new ActiveXObject("Microsoft.XMLHTTP");
			} else {
				http = new XMLHttpRequest();
			}
			http.open("GET", "../script/product_addinventory.php?" + poststr,
					true);
			http.onreadystatechange = function() {
				if (http.readyState == 4) {
					var str = http.responseText;
					// alert(str);
					var msg = document.getElementById("lb_returnMessage");
					//alert(str);
					if(str.substr(0,1) == "0") {
					   //alert("ERROR");
						$("#returnMessage").css({
							"backgroundColor": "#FF3300"
						});
						msg.style.color = "white";
						// msg.style.fontWeight = "bold";
						msg.innerHTML = "Inventory add failed.";
						$("#returnMessage").slideDown(300).delay(1500).fadeOut('fast');
						document.getElementById("debugger").value += str.substr(1) + "\n";
						//editArray = new Array();
					} else if(str.substr(0,1) == "1") {
						//alert("MESSAGE");
						$("#returnMessage").css({
							"backgroundColor": "#00FF00"
						});
						msg.style.color = "black";
						// msg.style.fontWeight = "bold";
						msg.innerHTML = str.substr(1);
						$("#returnMessage").slideDown(300).delay(1500).fadeOut('fast');
						document.getElementById("psAddQty").value = "";
						document.getElementById("psAddQty").focus();
					} else {
						document.getElementById("debugger").value += str + "\n";
					}
				}
			}
			http.send(null);
		}
	}
}

function resetSPC() {
	var c = document.getElementById("iCost").value;
	var cost = parseFloat(c);
	var box_list = new Array("iSPC1lvl", "iSPC2lvl", "iSPC3lvl", "iSPC4lvl",
			"iSPC5lvl", "iSPC6lvl", "iSPC7lvl", "iSPC8lvl", "iSPC9lvl",
			"iSPC10lvl");
	if (navigator.appName == "Microsoft Internet Explorer") {
		http = new ActiveXObject("Microsoft.XMLHTTP");
	} else {
		http = new XMLHttpRequest();
	}
	http.open("GET", "../php/product_getspc.php", true);
	http.onreadystatechange = function() {
		if (http.readyState == 4) {
			var str = http.responseText;
			var list = str.split('|');
			// alert(str);
			for ( var i = 0; i < box_list.length; i++) {
				var ele = document.getElementById(box_list[i]);
				ele.value = list[i];
				var spc = parseFloat(list[i]);
				var index = box_list[i].replace("lvl", "prc");
				var lvl = document.getElementById(index);
				lvl.value = round2(cost * spc);
				if (box_list[i] == "iSPC1lvl") {
					document.getElementById("iPrice").value = lvl.value;
				}
			}
		}
	}
	http.send(null);
}

function calcSPCVals(eleName) {
	var pid = document.getElementById("selPID").value;
	var sid = document.getElementById("selSID").value;
	var c = document.getElementById("iCost").value;
	// alert(c);
	var cost = c;
	if (pid == "-1" || sid == "-1") {
		alert("Please select a product first");
		return;
	} else {
		var tag = eleName.substring(5);
		if (tag == "lvl") {
			var index = eleName.replace("lvl", "prc");
			var change = document.getElementById(index);
			var newL = parseFloat(document.getElementById(eleName).value);
			change.value = round2(cost * newL);
		} else if (tag == "prc") {
			var index = eleName.replace("prc", "lvl");
			var change = document.getElementById(index);
			var newP = parseFloat(document.getElementById(eleName).value);
			change.value = round2(newP / cost);
		} else {
			return;
		}
		if (eleName == "iSPC1prc" || eleName == "iSPC1lvl") {
			document.getElementById("iPrice").value = document
					.getElementById("iSPC1prc").value;
		}
	}
}

function calcSPC() {
	alert("RECALCULATE SPC FUNCTION NOT IMPLEMENTED LINE product.js line 728");
}

function addSPCVals() {
	var box_list = new Array("iSPC1prc", "iSPC2prc", "iSPC3prc", "iSPC4prc",
			"iSPC5prc", "iSPC6prc", "iSPC7prc", "iSPC8prc", "iSPC9prc",
			"iSPC10prc");
	for ( var i = 0; i < box_list.length; i++) {
		//var index = box_list[i].replace("prc", "");
		//index = index.replace("p", "");
		//alert("[" + index + "]");
		prodEditUpdate(box_list[i]);
	}
		/*var ele = document.getElementById(box_list[i]);
		if (box_list[i] == "iSPC10prc") {
			var tag = "i" + box_list[i].substring(1, 6);
			if (editMasterArray.indexOf(tag) != -1) {
				var before = editMasterArray.substring(0, editMasterArray
						.indexOf(tag)
						+ tag.length + 1);
				var after = editMasterArray.substring(editMasterArray.indexOf(
						"|", editMasterArray.indexOf(tag) + tag.length + 1),
						editMasterArray.length);
				editMasterArray = before + ele.value + after;
			} else {
				editMasterArray += tag + "@" + ele.value;
				editMasterArray += "|";
			}
			// editMasterArray +=
			// "i"+box_list[i].substring(1,6)+"@"+document.getElementById(box_list[i]).value+"|";
		} else {
			var tag = "i" + box_list[i].substring(1, 5);
			if (editMasterArray.indexOf(tag) != -1) {
				var before = editMasterArray.substring(0, editMasterArray
						.indexOf(tag)
						+ tag.length + 1);
				var after = editMasterArray.substring(editMasterArray.indexOf(
						"|", editMasterArray.indexOf(tag) + tag.length + 1),
						editMasterArray.length);
				editMasterArray = before + ele.value + after;
			} else {
				editMasterArray += tag + "@" + ele.value;
				editMasterArray += "|";
			}
			// editMasterArray +=
			// "i"+box_list[i].substring(1,5)+"@"+document.getElementById(box_list[i]).value+"|";
		}
	}*/
}

function addNewSPCVals() {
	var box_list = new Array("new_iSPC1", "new_iSPC2", "new_iSPC3",
			"new_iSPC4", "new_iSPC5", "new_iSPC6", "new_iSPC7", "new_iSPC8",
			"new_iSPC9", "new_iSPC10");
	for ( var i = 0; i < box_list.length; i++) {
		var ele = document.getElementById(box_list[i]);
		if (box_list[i] == "new_iSPC10") {
			var tag = "i" + box_list[i].substring(5, 10);
			if (newMasterArray.indexOf(tag) != -1) {
				var before = newMasterArray.substring(0, newMasterArray
						.indexOf(tag)
						+ tag.length + 1);
				var after = newMasterArray.substring(newMasterArray.indexOf(
						"|", newMasterArray.indexOf(tag) + tag.length + 1),
						newMasterArray.length);
				newMasterArray = before + ele.value + after;
			} else {
				newMasterArray += tag + "@" + ele.value;
				newMasterArray += "|";
			}
			// newMasterArray +=
			// "i"+box_list[i].substring(1,6)+"@"+document.getElementById(box_list[i]).value+"|";
		} else {
			var tag = "i" + box_list[i].substring(5, 9);
			if (newMasterArray.indexOf(tag) != -1) {
				var before = newMasterArray.substring(0, newMasterArray
						.indexOf(tag)
						+ tag.length + 1);
				var after = newMasterArray.substring(newMasterArray.indexOf(
						"|", newMasterArray.indexOf(tag) + tag.length + 1),
						newMasterArray.length);
				newMasterArray = before + ele.value + after;
			} else {
				newMasterArray += tag + "@" + ele.value;
				newMasterArray += "|";
			}
			// newMasterArray +=
			// "i"+box_list[i].substring(1,5)+"@"+document.getElementById(box_list[i]).value+"|";
		}
	}
}

function saveSPC() {
	var selSID = parent.document.getElementById('selSID').value;
	var selPID = parent.document.getElementById('selPID').value;
	var str = "";
	var box_list = new Array("iSPC1prc", "iSPC2prc", "iSPC3prc", "iSPC4prc",
			"iSPC5prc", "iSPC6prc", "iSPC7prc", "iSPC8prc", "iSPC9prc",
			"iSPC10prc");
	for ( var i = 0; i < box_list.length; i++) {
		if (box_list[i] == "iSPC10prc") {
			str += "i" + box_list[i].substring(1, 6) + "@"
					+ document.getElementById(box_list[i]).value;
		} else {
			str += "i" + box_list[i].substring(1, 5) + "@"
					+ document.getElementById(box_list[i]).value + "|";
		}
	}
	// alert(str);
	var getstr = str + "&ecStoreID=" + selSID + "&ecID=" + selPID;
	// alert(getstr);
	if (navigator.appName == "Microsoft Internet Explorer") {
		http = new ActiveXObject("Microsoft.XMLHTTP");
	} else {
		http = new XMLHttpRequest();
	}
	http.open("GET", "../script/product_edit.php?str=" + getstr, true);
	http.onreadystatechange = function() {
		if (http.readyState == 4) {
			var str = http.responseText;
			alert(str);
		}
	}
	http.send(null);
}

function openNewRecord() {
	// url = "../php/product_newspec.php";
	// window.open(url,"specFrame");
}

function selectedRecord() {
	var selSID = parent.document.getElementById('selSID').value;
	var selPID = parent.document.getElementById('selPID').value;
	if (selSID == "-1" || selPID == "-1") {
		url = "../php/product_spec.php";
		window.open(url, "specFrame");
	} else {
		url = "../php/product_spec.php?sid=" + selSID + "&pid=" + selPID;
		window.open(url, "specFrame");
	}
}

function clearAddProduct() {
	var eleName = new Array("new_iStoreID", "new_pSupplier", "new_pName",
			"new_pCatType", "new_pDescription", "new_pUPC", "new_pSku",
			"new_pProdCode", "new_pBSize", "new_pCSize", "new_iCost",
			"new_iPrice", "new_iTax1", "new_iTax2", "new_iMinQty", "new_iQty",
			"new_iMaxQty", "new_iSPC1", "new_iSPC2", "new_iSPC3", "new_iSPC4",
			"new_iSPC5", "new_iSPC6", "new_iSPC7", "new_iSPC8", "new_iSPC9",
			"new_iSPC10", "new_iSPC1fee", "new_iSPC2fee", "new_iSPC3fee",
			"new_iSPC4fee", "new_iSPC5fee", "new_iSPC6fee", "new_iSPC7fee",
			"new_iSPC8fee", "new_iSPC9fee", "new_iSPC10fee");
	for (i = 0; i < eleName.length; i++) {
		if (document.getElementById(eleName[i]) == null) {
			alert(eleName[i]);
		} else if (document.getElementById(eleName[i]).type == "select-one") {
			document.getElementById(eleName[i]).selectedIndex = 0;
		} else if (document.getElementById(eleName[i]).type == "text") {
			document.getElementById(eleName[i]).value = "";
		} else {
			document.getElementById(eleName[i]).innerHTML = "";
		}
	}
}

function checkInput(eleName) {
	var str = document.getElementById(eleName).value;
	if(eleName == "new_pUPC") {
		var regEx = /\d{12}/;
		var reg = str.match(regEx);
		if(reg == null || str.length > 12) {
			document.getElementById(eleName).style.backgroundColor = "orange";
		} else if(reg != null) {
			document.getElementById(eleName).style.backgroundColor = "white";
		}
	} else if(eleName == "new_iCost") {
		if(isNaN(str)) {
			document.getElementById(eleName).style.backgroundColor = "orange";
		} else {
			document.getElementById(eleName).value = round2(str);
			document.getElementById(eleName).style.backgroundColor = "white";
		}
	} else if(eleName == "new_iMinQty" || eleName == "new_iQty" || eleName == "new_iMaxQty") {
		if(isNaN(str)) {
			document.getElementById(eleName).style.backgroundColor = "orange";
		} else {
			document.getElementById(eleName).style.backgroundColor = "white";
		}
	} else if(eleName == "new_pPicture") {
		var str = document.getElementById(eleName);
		//alert(str.type);
	}
}

function saveNewProductHelp() {
	var complete = true;
	var mia = "";
	var panel = 1;
	var eleNames = new Array("pSupplier", "pName", "pCatType",
			"pUPC","iCost", "iTax1", "iTax2", "iMinQty",
			"iQty", "iMaxQty", "iSPC1", "iSPC2", "iSPC3", "iSPC4", "iSPC5",
			"iSPC6", "iSPC7", "iSPC8", "iSPC9", "iSPC10");
	for ( var i = 0; i < eleNames.length; i++) {
		var ele = document.getElementById("new_" + eleNames[i]);
		if(eleNames[i] == "pCatType" && ele.selectedIndex == 2) {
			//23"WS Samsung TN Film (LTM230TT01/02)
			if(document.getElementById("new_pBSize").selectedIndex == 0 && document.getElementById("new_pCSize").selectedIndex == 0) {
				ele.style.backgroundColor = "red";
				document.getElementById("new_pBSize").focus();
				document.getElementById("np_warning").innerHTML = "Must select sizes for paint products.";
				return;
				/*complete = false;
				if(mia == "") {
					mia = "new_" + eleNames[i];
				}*/
			}
			
		} else if (ele.type == "text" && ele.value == "") {
			ele.style.backgroundColor = "red";
			if(mia == "") {
				mia = "new_" + eleNames[i];
			}
			//ele.focus();
			complete = false;
		} else if (ele.type == "select-one" && ele.selectedIndex == 0) {
			if(mia == "") {
				mia = "new_" + eleNames[i];
			}
			ele.style.backgroundColor = "red";
			//ele.focus();
			complete = false;
		} 
	}
	//alert(complete);
	
	if (!complete) {
		//document.getElementById(mia).focus();
		document.getElementById("np_warning").innerHTML = "One or more required field are not filled in.";
		return;
	}
	var answer = confirm("Confirm add new product");
	if (answer) {
		setTimeout("fillPrices()", 500);
		setTimeout("saveNewProduct()", 100);
	} else {
		return;
	}
}

function saveNewProduct() {
	var eleName = new Array("iStoreID", "pSupplier", "pName", "pCatType",
			"pDescription", "pUPC", "pSku", "pProdCode", "pBSize", "pCSize",
			"iCost", "iPrice", "iTax1", "iTax2", "iMinQty", "iQty", "iMaxQty",
			"pPicture","iSPC1", "iSPC2", "iSPC3", "iSPC4", "iSPC5", "iSPC6", "iSPC7",
			"iSPC8", "iSPC9", "iSPC10");
	var i;
	var poststr = "";
	var x = 0;
	// alert(document.getElementById(eleName).value);
	for (i = 0; i < eleName.length; i++) {

		var ele = document.getElementById("new_" + eleName[i]);
		if (ele == null) {
			alert(eleName[i]);
		}
		// alert(document.getElementById(eleName[i]).type);
		if (ele.type == "text" || ele.type == "file") {
			if (newMasterArray.indexOf(eleName[i]) != -1) {
				// alert("STRING EXISTS");
				// var subs =
				// newMasterArray.substring(newMasterArray.indexOf(eleName[i]) +
				// eleName[i].length + 1,
				// newMasterArray.indexOf("|",newMasterArray.indexOf(eleName[i])
				// + eleName[i].length + 1));
				var before = newMasterArray.substring(0, newMasterArray
						.indexOf(eleName[i])
						+ eleName[i].length + 1);
				var after = newMasterArray
						.substring(newMasterArray.indexOf("&", newMasterArray
								.indexOf(eleName[i])
								+ eleName[i].length + 1), newMasterArray.length);
				newMasterArray = before + ele.value + after;
			} else {
				newMasterArray += eleName[i] + "=" + ele.value;
				newMasterArray += "&";
			}
		} else if (ele.type == "select-one") {
			if (newMasterArray.indexOf(eleName[i]) != -1) {
				var before = newMasterArray.substring(0, newMasterArray
						.indexOf(eleName[i])
						+ eleName[i].length + 1);
				var after = newMasterArray
						.substring(newMasterArray.indexOf("&", newMasterArray
								.indexOf(eleName[i])
								+ eleName[i].length + 1), newMasterArray.length);
				newMasterArray = before + ele.selectedIndex + after;
			} else {
				newMasterArray += eleName[i] + "=" + ele.selectedIndex;
				newMasterArray += "&";
			}
		} else {
			if (newMasterArray.indexOf(eleName[i]) != -1) {
				var before = newMasterArray.substring(0, newMasterArray
						.indexOf(eleName[i])
						+ eleName[i].length + 1);
				var after = newMasterArray
						.substring(newMasterArray.indexOf("&", newMasterArray
								.indexOf(eleName[i])
								+ eleName[i].length + 1), newMasterArray.length);
				newMasterArray = before + ele.innerHTML + after;
			} else {
				newMasterArray += eleName[i] + "=" + ele.innerHTML.substring(1);
				newMasterArray += "&";
			}
			// newMasterArray +=
			// "i"+box_list[i].substring(1,6)+"@"+document.getElementById(box_list[i]).value+"|";
		}
	}
	//makePOSTRequest("../script/product_addnew.php",newMasterArray);
	// alert(newMasterArray);
	// alert(getstr);
	if (navigator.appName == "Microsoft Internet Explorer") {
		http = new ActiveXObject("Microsoft.XMLHTTP");
	} else {
		http = new XMLHttpRequest();
	}
	http.open("GET", "../script/product_addnew.php?" + newMasterArray, true);
	http.onreadystatechange = function() {
		if (http.readyState == 4) {
			var str = http.responseText;
			// alert(str);
			var debug = document.getElementById("debugger");
			debug.value += str;
			/*if(str.substr(0,1) == "0") {
				   //alert("ERROR");
					$("#returnMessage").css({
						"backgroundColor": "#FF3300"
					});
					msg.style.color = "white";
					// msg.style.fontWeight = "bold";
					msg.innerHTML = "Inventory add failed.";
					$("#returnMessage").slideDown(300).delay(1500).fadeOut('fast');
					document.getElementById("debugger").value += str.substr(1) + "\n";
					//editArray = new Array();
				} else if(str.substr(0,1) == "1") {
					//alert("MESSAGE");
					$("#returnMessage").css({
						"backgroundColor": "#00FF00"
					});
					msg.style.color = "black";
					// msg.style.fontWeight = "bold";
					msg.innerHTML = str.substr(1);
					$("#returnMessage").slideDown(300).delay(1500).fadeOut('fast');
					document.getElementById("psAddQty").value = "";
					document.getElementById("psAddQty").focus();
				} else {
					document.getElementById("debugger").value += str + "\n";
				}*/
		}
	}
	http.send(null);
	newMasterArray = "";
	disableAddProductPopup();
}

function ssaveNewProduct() {
	var eleName = new Array("new_iStoreID", "new_pSupplier", "new_pName",
			"new_pCatType", "new_pDescription", "new_pUPC", "new_pSku",
			"new_pProdCode", "new_pSize", "new_pBSize", "new_pSize",
			"new_pCSize", "new_iCost", "new_iPrice", "new_iTax1", "new_iTax2",
			"new_iMinQty", "new_iQty", "new_iMaxQty", "new_iSPC1prc",
			"new_iSPC2prc", "new_iSPC3prc", "new_iSPC4prc", "new_iSPC5prc",
			"new_iSPC6prc", "new_iSPC7prc", "new_iSPC8prc", "new_iSPC9prc",
			"new_iSPC10prc");
	var postValue = new Array();
	var i;
	var poststr = "";
	var x = 0;
	// alert(document.getElementById(eleName).value);
	for (i = 0; i < eleName.length; i++) {
		// alert(document.getElementById(eleName[i]).type);
		if (eleName[i] == "new_iCost") {
			setTimeout("addNewSPCVals()", 100);
		}
		if (document.getElementById(eleName[i]).type == "select-one") {
			// alert(document.getElementById(eleName[i]).options[document.getElementById(eleName[i]).selectedIndex].innerHTML);
			// alert(document.getElementById(eleName[i]).selectedIndex);
			if (document.getElementById(eleName[i])[document
					.getElementById(eleName[i]).selectedIndex].innerHTML != "") {
				/*
				 * if(x == 0) { poststr += eleName[i] + "=" +
				 * encodeURI(document.getElementById(eleName[i])[document.getElementById(eleName[i]).selectedIndex].innerHTML); }
				 * else { poststr += "&" + eleName[i] + "=" +
				 * encodeURI(document.getElementById(eleName[i])[document.getElementById(eleName[i]).selectedIndex].innerHTML); }
				 */
				if (x == 0) {
					poststr += eleName[i]
							+ "="
							+ encodeURI(document.getElementById(eleName[i]).selectedIndex);
				} else {
					poststr += "&"
							+ eleName[i]
							+ "="
							+ encodeURI(document.getElementById(eleName[i]).selectedIndex);
				}
				x++;
			}
		} else {
			if (document.getElementById(eleName[i]).value != "") {
				if (x == 0) {
					poststr += eleName[i]
							+ "="
							+ encodeURI(document.getElementById(eleName[i]).value);
				} else {
					poststr += "&"
							+ eleName[i]
							+ "="
							+ encodeURI(document.getElementById(eleName[i]).value);
				}
				x++;
			}
		}
	}
	// makePOSTRequest('../php/customer_addnew.php',poststr);
	alert('../script/product_addnew.php' + poststr);
}