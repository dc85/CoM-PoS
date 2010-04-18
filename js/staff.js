var addStaffWindowOpen = 0;
var editMasterArray = "";
var showDebug = 0;
var editArray = new Array();

/*
 * Add page changes
 */
var cur_page = 0;

function nextClick() {
	if(cur_page == 1) {
		$("#add1").fadeOut("fast");
		$("#add2").fadeIn("fast");
		cur_page = 2;
	} else if(cur_page == 2) {
		$("#add2").fadeOut("fast");
		$("#add3").fadeIn("fast");
		cur_page = 3;
	} else if(cur_page == 3) {
		$("#add3").fadeOut("fast");
		$("#add4").fadeIn("fast");
		cur_page = 4;
	} else {
		return;
	}
}

function getHours() {
	var total = 0.0;
	var shiftAry = new Array("sSchedule0","sSchedule1","sSchedule2","sSchedule3","sSchedule4","sSchedule5","sSchedule6");
	for(var i=0; i<shiftAry.length; i++) {
		var ele = parent.document.getElementById(shiftAry[i]);
		if(ele.selectedIndex == 2 || ele.selectedIndex == 3) {
			total += 5;
		} else if(ele.selectedIndex == 4 || ele.selectedIndex == 6) {
			total += 8;
		} else if(ele.selectedIndex == 5 || ele.selectedIndex == 7) {
			total += 4;
		} else {
			total += 0;
		}
	}
	//alert(total);
	if(parseFloat(total) <= 40) {
		parent.document.getElementById("staff_totalhour").innerHTML = roundNumber(total,2);
	} else {
		parent.document.getElementById("staff_totalhour").innerHTML = roundNumber(total,2);
		parent.document.getElementById("staff_totalhour").style.color = "red";
	}
}


function prevClick() {
	if(cur_page == 4) {
		$("#add4").fadeOut("fast");
		$("#add3").fadeIn("fast");
		cur_page = 3;
	} else if(cur_page == 3) {
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

function checkInput(eleName) {
	var str = document.getElementById(eleName).value;
	if(eleName == "new_sCNumber") {
		var regEx = /\d{10}/;
		var reg = str.match(regEx);
		if(reg == null || str.length > 10) {
			document.getElementById(eleName).style.backgroundColor = "orange";
		} else if(reg != null) {
			document.getElementById(eleName).style.backgroundColor = "white";
		}
	} else if(eleName == "new_sStaff") {
		document.getElementById(eleName).value = str.toUpperCase();
	} else if(eleName == "new_sStoreCredit" || eleName == "new_sHrRate" || eleName == "new_cCredit") {
		if(isNaN(str)) {
			document.getElementById(eleName).style.backgroundColor = "orange";
		} else {
			document.getElementById(eleName).value = round2(str);
			document.getElementById(eleName).style.backgroundColor = "white";
		}
	} else if(eleName == "new_cRIN") {
		var regEx = /[a-zA-Z]\d{14}/;
		var reg = str.match(regEx);
		if(reg == null || str.length > 15) {
			document.getElementById(eleName).style.backgroundColor = "orange";
		} else if(reg != null) {
			document.getElementById(eleName).value = str.toUpperCase();
			document.getElementById(eleName).style.backgroundColor = "white";
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
	}
}

function refreshStaff(store, id) {
	/*var pageSID = parent.document.getElementById('pageSID');
	var pageSSID = parent.document.getElementById('pageSSID');
	pageSID.value = id;
	pageSSID.value = store;*/
	
	var poststr = "sStoreID=" + store + "&sID=" + id;
	
	var wrkShift = new Array("","DAY OFF","FRONT SHIFT 1",
			"FRONT SHIFT 2","MIDDLE SHIFT 1","MIDDLE SHIFT 2",
			"BACK SHIFT 1","BACK SHIFT 2","IT");
	var titles = new Array("","sStoreID","sFullName","sID",
			"sCNumber","sCustomer","sStaff","sLevel",
			"sPassword",/*"sPswdExpMM","sPswdExp",*/"sStoreCredit",
			"sHrRate","sDfHr","sSchedule0","sSchedule1",
			"sSchedule2","sSchedule3","sSchedule4",
			"sSchedule5","sSchedule6","sIsActive")

	if (navigator.appName == "Microsoft Internet Explorer") {
		http = new ActiveXObject("Microsoft.XMLHTTP");
	} else {
		http = new XMLHttpRequest();
	}
	//parent.document.getElementById("debugger").value +="../script/staff_retrieve.php?" + poststr + "\n"; 
	http.open("GET", "../script/staff_retrieve.php?" + poststr, true);
	//alert(poststr);
	http.onreadystatechange = function() {
		if (http.readyState == 4) {
			if (http.status == 200) {
				var str = http.responseText;
				//alert(str);
				var msg = document.getElementById("lb_returnMessage");
				if(str.substr(0,1) == "0") {
					$("#returnMessage").css({
						"backgroundColor": "#FF3300"
					});
					msg.style.color = "white";
					msg.style.fontWeight = "bold";
					msg.innerHTML = "Staff information retrieval failed.";
					$("#returnMessage").slideDown(300).delay(1500).fadeOut('fast');
					document.getElementById("debugger").value += str.substr(2) + "\n";
				} else if(str.substr(0,1) == "1") {
					//alert("WTF");
					$("#returnMessage").css({
						"backgroundColor": "#00FF00"
					});
					msg.style.color = "black";
					//msg.style.fontWeight = "bold";
					msg.innerHTML = "Staff information retrieval succsessful.";
					$("#returnMessage").slideDown(300).delay(1500).fadeOut('fast');
					document.getElementById("debugger").value += str.substr(2) + "\n";
					var list = str.split('|');
					for ( var i = 1; i < titles.length; i++) {
						var ele = document.getElementById(titles[i]);
						if(ele == null) {
							alert(titles[i]);
						}
						if(titles[i] == "sStoreID" || titles[i] == "sLevel" ) {
							ele.selectedIndex = list[i];
						} else if(titles[i] == "sIsActive") {
							if(list[i] == "1") {
								ele.value = "Active";
							} else {
								ele.value = "Inactive";
							}
						} else if(titles[i] == "sPassword") {
							ele.value = "";
						} else if(titles[i] == "sSchedule0" || titles[i] == "sSchedule1" ||
								titles[i] == "sSchedule2" || titles[i] == "sSchedule3" ||
								titles[i] == "sSchedule4" || titles[i] == "sSchedule5" ||
								titles[i] == "sSchedule6") {
							ele.selectedIndex = list[i];
						} else {
							ele.value = list[i];
						}
					}
				}
			} else {
				alert("Problem");
			}
		}
	}
	http.send(null);
	return;
}

function choose_staff(store, id) {
	var pageSID = parent.document.getElementById('pageSID');
	var pageSSID = parent.document.getElementById('pageSSID');
	pageSID.value = store;
	pageSSID.value = id;
	var poststr = "sStoreID=" + store + "&sID=" + id;
	//alert(poststr);
	var wrkShift = new Array("","DAY OFF","FRONT SHIFT 1",
			"FRONT SHIFT 2","MIDDLE SHIFT 1","MIDDLE SHIFT 2",
			"BACK SHIFT 1","BACK SHIFT 2","IT");
	var titles = new Array("","sStoreID","sFullName","cEmail1","sID",
			"sCNumber","sCustomer","sStaff","sLevel",
			"sPassword",/*"sPswdExpMM","sPswdExp",*/"sStoreCredit",
			"sHrRate","sDfHr","sSchedule0","sSchedule1",
			"sSchedule2","sSchedule3","sSchedule4",
			"sSchedule5","sSchedule6","sIsActive")

	if (navigator.appName == "Microsoft Internet Explorer") {
		http = new ActiveXObject("Microsoft.XMLHTTP");
	} else {
		http = new XMLHttpRequest();
	}
	//parent.document.getElementById("debugger").value +="../script/staff_retrieve.php?" + poststr + "\n"; 
	http.open("GET", "../script/staff_retrieve.php?" + poststr, true);
	http.onreadystatechange = function() {
		if (http.readyState == 4) {
			if (http.status == 200) {
				var str = http.responseText;
				//alert(str);
				var msg = parent.document.getElementById("lb_returnMessage");
				if(str.substr(0,1) == "0") {
					$("#returnMessage",window.parent.document).css({
						"backgroundColor": "#FF3300"
					});
					msg.style.color = "white";
					msg.style.fontWeight = "bold";
					msg.innerHTML = "Staff information retrieval failed.";
					$("#returnMessage",window.parent.document).slideDown(300).delay(1500).fadeOut('fast');
					parent.document.getElementById("debugger").value += str.substr(2) + "\n";
				} else if(str.substr(0,1) == "1") {
					//alert("WTF");
					$("#returnMessage",window.parent.document).css({
						"backgroundColor": "#00FF00"
					});
					msg.style.color = "black";
					//msg.style.fontWeight = "bold";
					msg.innerHTML = "Staff information retrieval succsessful.";
					$("#returnMessage",window.parent.document).slideDown(300).delay(1500).fadeOut('fast');
					parent.document.getElementById("debugger").value += str.substr(2) + "\n";
					var list = str.split('|');
					for ( var i = 1; i < titles.length; i++) {
						var ele = parent.document.getElementById(titles[i]);
						if(ele == null) {
							alert(titles[i]);
						}
						if(titles[i] == "sStoreID" || titles[i] == "sLevel" ) {
							ele.selectedIndex = list[i];
						} else if(titles[i] == "sIsActive") {
							if(list[i] == "1") {
								ele.value = "Active";
							} else {
								ele.value = "Inactive";
							}
						} else if(titles[i] == "sPassword") {
							ele.value = "";
						} else if(titles[i] == "sSchedule0" || titles[i] == "sSchedule1" ||
								titles[i] == "sSchedule2" || titles[i] == "sSchedule3" ||
								titles[i] == "sSchedule4" || titles[i] == "sSchedule5" ||
								titles[i] == "sSchedule6") {
							ele.selectedIndex = list[i];
						} else {
							ele.value = list[i];
						}
					}
				}
				//parent.document.getElementById("debugger").value += http.responseText+"\n";
				//var list = str.split('|');
				//parent.document.getElementById("debugger").value += list.length+"/"+titles.length+" elements returned.\n";
				// alert(list.length);
				// alert(titles.length);
				
			} else {
				alert("Problem");
			}
			getHours();
		}
	}
	http.send(null);
	return;
}

function staffEditUpdate(eleName) {
	var i = editArray.indexOf(eleName);
	if(i == -1) {
		editArray.splice(editArray.length,0,eleName);
	}
	//alert(editArray);
}

function staffEidtHelp() {
	if(editArray.length == 0) {
		alert("No changes made.");
		return;
	}
	var answer = confirm("Confirm edit staff");
	if (answer) {
		//setTimeout("fillPrices()", 500);
		setTimeout("staffEdit()", 100);
	} else {
		return;
	}
}

function staffEdit() {
	//alert(editArray.length);
	var editString = "";
	for(var i=0;i<editArray.length;i++) {
		var ele = document.getElementById(editArray[i]);
		if(ele.type == "text") {
			editString += editArray[i] + "=" + ele.value + "&";
		} else if(ele.type == "select-one") {
			editString += editArray[i] + "=" + ele.selectedIndex + "&";
		}
	}
	var sID = document.getElementById("sID").value;
	
	editString += "sID=" + sID;
	//alert("staff_edit.php?"+editString);
	//staffPOSTRequest("../script/staff_edit.php", editString);
	if (navigator.appName == "Microsoft Internet Explorer") {
		http = new ActiveXObject("Microsoft.XMLHTTP");
	} else {
		http = new XMLHttpRequest();
	}
	http.open("GET", "../script/staff_edit.php?" + editString, true);
	http.onreadystatechange = function() {
		if (http.readyState == 4) {
			if (http.status == 200) {
				var str = http.responseText;
				//alert(str);
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
					//editArray = new Array();
				} else if(str.substr(7,3) == "MSG") {
					//alert("MESSAGE");
					$("#returnMessage").css({
						"backgroundColor": "#00FF00"
					});
					msg.style.color = "black";
					// msg.style.fontWeight = "bold";
					msg.innerHTML = "Staff update successful.";
					$("#returnMessage").slideDown(300).delay(1500).fadeOut('fast');
					editArray = new Array();
				}
				//return;
				
			} else {
				alert("Problem");
			}
		}
	}
	http.send(null);
}

function staffEditCancel() {
	editArray = new Array();
	var store = document.getElementById('pageSID').value;
	var staff = document.getElementById('pageSSID').value;
	//testB();
	//alert("TEST");
	refreshStaff(store,staff);
	//alert("TEST2");
	//return;
	//var fun = "choose_staff("+store+","+staff+")";
	//setTimeout(fun,500);
}

function testB() {
	alert("GGYY");
}

Array.prototype.indexOf = function(p_val) {
	for(var i = 0, l = this.length; i < l; i++) {
		if(this[i] == p_val) {
			return i;
		}
	}
	return -1;
};

function addNewStaffHelp() {
	var complete = true;
	var panel = 4;
	var mia = "";
	var eleNames = new Array("sStoreID","cFirstN","cLastN","sCNumber",
			"sStaff","sSchedule0","sSchedule1","sSchedule2","sSchedule3",
			"sSchedule4","sSchedule5","sSchedule6");
	for(var i=0;i<eleNames.length;i++) {
		var ele = document.getElementById("new_"+eleNames[i]);
		if(ele.type == "text") {
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
	
	var eleNames = new Array("cDoB","cRIN","cCardNum","cCustType",
			"cCoName","cUnitB","cAdrBus","cCityB","cProvB","cZipB",
			"cpType1","cPhone1");
	for(var i=0;i<eleNames.length;i++) {
		var ele = document.getElementById("new_"+eleNames[i]);
		if(ele.type == "text") {
			if(ele.value == "") {
				complete = false;
				if(mia == "") {
					mia = "new_" + eleNames[i];
				}
				ele.style.backgroundColor = "red";
				if(panel > 2) {
					panel = 2;
				}
			}
		} else if(ele.type == "select-one") {
			if(ele.selectedIndex == 0) {
				complete = false;
				if(mia == "") {
					mia = "new_" + eleNames[i];
				}
				ele.style.backgroundColor = "red";
				if(panel > 2) {
					panel = 2;
				}
			}
		} else {
			if(ele.value == "") {
				complete = false;
				if(mia == "") {
					mia = "new_" + eleNames[i];
				}
				ele.style.backgroundColor = "red";
				if(panel > 2) {
					panel = 2;
				}
			}
		}
	}

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
	var answer = confirm("Confirm add new staff");
	if (answer) {
		//setTimeout("fillPrices()", 500);
		setTimeout("addNewStaff()", 100);
	} else {
		return;
	}
}

function addNewStaff() {
	var eleNames = new Array("sStoreID","sCNumber","sCustomer",
		"sStaff","sLevel","sPassword","sPswdExpMM","sPswdExp",
		"sStoreCredit","sHrRate","sDfHr","sSchedule0","sSchedule1",
		"sSchedule2","sSchedule3","sSchedule4","sSchedule5",
		"sSchedule6","cTitle","cFirstN","cLastN","cAKA","cDoB","cRIN",
		"cCardNum","cCustType","cCoName","cShirtSize","cPantSize",
		"cExpert","cpType4","cPhone4","cpType5","cPhone5","cE1","cE2",
		"cE3","cE4","cE5","cE6","cE7","cE8","cE9","cE10","cUnitB",
		"cAdrBus","cCityB","cProvB","cZipB","cpType1","cPhone1",
		"cEmail1","cUnitH","cAdrHome","cCityH","cProvH","cZipH",
		"cpType2","cPhone2","cEmail2","cUnitS","cAdrShip","cCityS",
		"cProvS","cZipS","cpType3","cPhone3","cEmail3","cTax1",
		"cTax2","cSPC","cEcoFee","cCustRep","cExpNum","cExpDate",
		"cCredit");
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
	makePOSTRequest('../script/staff_addnew.php',poststr);
}

$(document).ready(function() {
	$("#addStaffPopupContactClose").click(function() {
		disableAddStaffPopup();
	});

	// Click out event!
	$("#backgroundPopup").click(function() {
		if (addStaffWindowOpen == 1) {
			disableAddStaffPopup();
		}
	});
	// Press Escape event!
	$(document).keypress(function(e) {
		if (e.keyCode == 27 && (addStaffWindowOpen == 1)) {
			disableAddStaffPopup();
		}
	});
});

function addStaff() {
	var eleName = new Array('nsTitle', 'nsFirstN', 'nsLastN', 'nsStoreID',
			'nsCNumber', 'nsStaff', 'nsPassword',
			'nsLevel', 'nsPswdExpMM', 'nsStoreCredit', 'nsHrRate',
			'nsDfHr', 'nsSchedule0', 'nsSchedule1', 'nsSchedule2',
			'nsSchedule3', 'nsSchedule4', 'nsSchedule5', 'nsSchedule6');
	var postValue = new Array();
	var i;
	var poststr = "";
	var x = 0;
	// alert(document.getElementById(eleName).value);
	for (i = 0; i < eleName.length; i++) {
		// alert(document.getElementById(eleName[i]).type);
		if (document.getElementById(eleName[i]).type == "select-one") {
			// alert(document.getElementById(eleName[i]).options[document.getElementById(eleName[i]).selectedIndex].innerHTML);
			// alert(document.getElementById(eleName[i]).selectedIndex);
			if (eleName[i] == "nsStoreID") {
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
			} else if (document.getElementById(eleName[i])[document
					.getElementById(eleName[i]).selectedIndex].innerHTML != "") {
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
				/*
				 * if(x == 0) { poststr += eleName[i] + "=" +
				 * encodeURI(document.getElementById(eleName[i])[document.getElementById(eleName[i]).selectedIndex].innerHTML); }
				 * else { poststr += "&" + eleName[i] + "=" +
				 * encodeURI(document.getElementById(eleName[i])[document.getElementById(eleName[i]).selectedIndex].innerHTML); }
				 * x++;
				 */
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
	if (navigator.appName == "Microsoft Internet Explorer") {
		http = new ActiveXObject("Microsoft.XMLHTTP");
	} else {
		http = new XMLHttpRequest();
	}
	http.open("GET", "../php/staff_addnew.php?" + poststr, true);
	http.onreadystatechange = function() {
		if (http.readyState == 4) {
			if (http.status == 200) {
				var str = http.responseText;
				alert(str);
			} else {
				alert("Problem");
			}
		}
	}
	http.send(null);
	/*alert(poststr);
	try {
		makePOSTRequest('../php/staff_addnew.php', poststr);
	} catch(err) {
		alert(err.description);
	}*/
}

function updatePassword() {
	var pw = document.getElementById('sPassword').value;
	if (pw == "") {
		alert("Please provide a password");
		return;
	} else {
		var store = parent.document.getElementById('pageSID').value;
		var staff = parent.document.getElementById('pageSSID').value;
		if (store == "-1" || staff == "-1") {
			alert("Please select staff first");
		} else {
			var poststr = "store=" + store + "&staff=" + staff + "&pw="
					+ pw;

			if (navigator.appName == "Microsoft Internet Explorer") {
				http = new ActiveXObject("Microsoft.XMLHTTP");
			} else {
				http = new XMLHttpRequest();
			}
			http.open("GET", "../script/staff_changepw.php?" + poststr,
					true);
			http.onreadystatechange = function() {
				if (http.readyState == 4) {
					var str = http.responseText;
					//alert(str);
					var msg = document.getElementById("lb_returnMessage");
					if(str.substr(0,1) == "0") {
						$("#returnMessage").css({
							"backgroundColor": "#FF3300"
						});
						msg.style.color = "white";
						//msg.style.fontWeight = "bold";
						msg.innerHTML = "Password change failed.";
						$("#returnMessage").slideDown(300).delay(1500).fadeOut('fast');
						document.getElementById("debugger").value += str.substr(1) + "\n";
					} else if(str.substr(0,1) == "1") {
						$("#returnMessage").css({
							"backgroundColor": "#00FF00"
						});
						msg.style.color = "black";
						//msg.style.fontWeight = "bold";
						msg.innerHTML = "Password change successful.";
						$("#returnMessage").slideDown(300).delay(1500).fadeOut('fast');
					}
					// alert(str);
					//var debug = document.getElementById("debugger");
					//debug.value += str;
				}
			}
			http.send(null);
		}
	}
}

function staffStatSet() {
	/*var pageSID = parent.document.getElementById('pageSID');
	var pageSSID = parent.document.getElementById('pageSSID');
	
	if(pageSID.value == "-1" && pageSSID.value == "-1") {
		alert("Must select a staff first");
		return;
	}*/
	var sID = document.getElementById("sID").value; 
	if(sID == "") {
		alert("Must select a staff first");
		return;
	}
	
	//id = pageSID.value;
	//store = pageSSID.value;
	
	var ele = parent.document.getElementById("sIsActive");
	var activity;
	
	if(ele.value == "Active") {
		activity = 0;
	} else if(ele.value == "Inactive") {
		activity = 1;
	}
	
	var poststr = "sID=" + sID + "&set=" + activity;
	//alert(poststr);
	if (navigator.appName == "Microsoft Internet Explorer") {
		http = new ActiveXObject("Microsoft.XMLHTTP");
	} else {
		http = new XMLHttpRequest();
	}
	http.open("GET", "../script/staff_setstatus.php?" + poststr, true);
	http.onreadystatechange = function() {
		if (http.readyState == 4) {
			if (http.status == 200) {
				var str = http.responseText;
				var msg = document.getElementById("lb_returnMessage");
				if(str.substr(0,1) == "0") {
					$("#returnMessage").css({
						"backgroundColor": "#FF3300"
					});
					msg.style.color = "white";
					//msg.style.fontWeight = "bold";
					msg.innerHTML = "Staff status change failed.";
					$("#returnMessage").slideDown(300).delay(1500).fadeOut('fast');
					document.getElementById("debugger").value += str.substr(1) + "\n";
				} else if(str.substr(0,1) == "1") {
					$("#returnMessage").css({
						"backgroundColor": "#00FF00"
					});
					msg.style.color = "black";
					//msg.style.fontWeight = "bold";
					msg.innerHTML = "Staff status change successful.";
					$("#returnMessage").slideDown(300).delay(1500).fadeOut('fast');
				}
				//document.getElementById("debugger").value += str;
				if (str.substring(1) == "1") {
					ele.value = "Active";
				} else if(str.substring(1) != "1") {
					ele.value = "Inactive";
				}
			} else {
				alert("Problem");
			}
		}
	}
	http.send(null);
}

function showAddStaffPopup() {
	// loads popup only if it is disabled
	setTimeout("showAddStaffPopupHelp()", 100);
}

function showAddStaffPopupHelp() {
	if (addStaffWindowOpen == 0) {
		$("#backgroundPopup").css( {
			"opacity" : "0.7"
		});
		$("#backgroundPopup").fadeIn("fast");
		$("#addStaffPopupContact").fadeIn("fast");
		$("#add2").hide();
		$("#add3").hide();
		$("#add4").hide();
		$("#add1").show();
		document.getElementById('new_sStoreID').focus();
		addStaffWindowOpen = 1;
		cur_page = 1;
	}
}

function editStaff() {
	if (window.editSpecFrame.editMasterArray.length <= 0) {
		alert("No changes has been made to the record");
		return;
	}
	var pageSID = document.getElementById('pageSID');
	var pageSSID = document.getElementById('pageSSID');
	// var getstr =
	// window.editSpecFrame.editMasterArray.substring(0,window.editSpecFrame.editMasterArray.length-1);
	var getstr = window.editSpecFrame.editMasterArray.substring(0,
			window.editSpecFrame.editMasterArray.length - 1)
			+ "&esStoreID=" + pageSSID.value + "&esID=" + pageSID.value;
	// alert(getstr);
	if (navigator.appName == "Microsoft Internet Explorer") {
		http = new ActiveXObject("Microsoft.XMLHTTP");
	} else {
		http = new XMLHttpRequest();
	}
	http.open("GET", "../php/staff_edit.php?str=" + getstr, true);
	http.onreadystatechange = function() {
		if (http.readyState == 4) {
			var str = http.responseText;
			alert(str);
		}
	}
	http.send(null);
}

function addtoEditStaffArray(eleName) {
	var ele = document.getElementById(eleName);
	if (ele.type == "text") {
		if (editMasterArray.indexOf(eleName) != -1) {
			// alert("STRING EXISTS");
			// var subs =
			// editMasterArray.substring(editMasterArray.indexOf(eleName) +
			// eleName.length + 1,
			// editMasterArray.indexOf("|",editMasterArray.indexOf(eleName) +
			// eleName.length + 1));
			var before = editMasterArray.substring(0, editMasterArray
					.indexOf(eleName)
					+ eleName.length + 1);
			var after = editMasterArray.substring(editMasterArray.indexOf("|",
					editMasterArray.indexOf(eleName) + eleName.length + 1),
					editMasterArray.length);
			editMasterArray = before + ele.value + after;
		} else {
			editMasterArray += eleName + "@" + ele.value;
			editMasterArray += "|";
		}
	} else if (ele.type == "select-one") {
		if (editMasterArray.indexOf(eleName) != -1) {
			var before = editMasterArray.substring(0, editMasterArray
					.indexOf(eleName)
					+ eleName.length + 1);
			var after = editMasterArray.substring(editMasterArray.indexOf("|",
					editMasterArray.indexOf(eleName) + eleName.length + 1),
					editMasterArray.length);
			editMasterArray = before + ele.selectedIndex + after;
		} else {
			editMasterArray += eleName + "@" + ele.selectedIndex;
			editMasterArray += "|";
		}
	} else if (ele.type == "checkbox") {
		var checkd = "";
		if (editMasterArray.indexOf(eleName) != -1) {
			if (ele.checked == true) {
				checkd = "1";
			} else {
				checkd = "0";
			}
			var before = editMasterArray.substring(0, editMasterArray
					.indexOf(eleName)
					+ eleName.length + 1);
			var after = editMasterArray.substring(editMasterArray.indexOf("|",
					editMasterArray.indexOf(eleName) + eleName.length + 1),
					editMasterArray.length);
			editMasterArray = before + checkd + after;
		} else {
			if (ele.checked == true) {
				checkd = "1";
			} else {
				checkd = "0";
			}
			editMasterArray += eleName + "@" + checkd;
			editMasterArray += "|";
		}
	}
}

function disableAddStaffPopup() {
	// disables popup only if it is enabled
	if (addStaffWindowOpen = 1) {
		$("#backgroundPopup").fadeOut("fast");
		$("#addStaffPopupContact").fadeOut("fast");
		addStaffWindowOpen = 0;
		cur_page = 1;
	}
	document.getElementById("staffSearch").focus();
}


function cancelAddStaffPopup() {
	// disables popup only if it is enabled
	setTimeout("clearAddStaff()",50);
	if(addCustomerWindowOpen = 1){
		$("#backgroundPopup").fadeOut("fast");
		$("#addStaffPopupContact").fadeOut("fast");
		addStaffWindowOpen = 0;
		cur_page = 0;
	}
	document.getElementById("staffSearch").focus();
}

function clearAddStaff() {
	var eleNames = new Array("sStoreID","sCNumber","sStaff","sLevel",
			"sPassword","sPswdExpMM","sPswdExp","sStoreCredit",
			"sHrRate","sDfHr","sSchedule0","sSchedule1","sSchedule2",
			"sSchedule3","sSchedule4","sSchedule5","sSchedule6",
			"cTitle","cFirstN","cLastN","cAKA","cDoB","cRIN",
			"cCardNum","cCustType","cCoName","cShirtSize","cPantSize",
			"cExpert","cpType4","cPhone4","cpType5","cPhone5","cE1",
			"cE2","cE3","cE4","cE5","cE6","cE7","cE8","cE9","cE10",
			"cUnitB","cAdrBus","cCityB","cProvB","cZipB","cpType1",
			"cPhone1","cEmail1","cUnitH","cAdrHome","cCityH","cProvH",
			"cZipH","cpType2","cPhone2","cEmail2","cUnitS","cAdrShip",
			"cCityS","cProvS","cZipS","cpType3","cPhone3","cEmail3",
			"cTax1","cTax2","cSPC","cEcoFee","cCustRep","cExpNum",
			"cExpDate","cCredit");
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