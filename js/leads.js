var showDebug = 0;
var cur_page = 0;
var addLeadsWindowOpen = 0;
var editArray = new Array();

$(document).ready(function(){

	$("#addLeadsPopupContactClose").click(function(){
		disableAddLeadsPopup();
	});

	//Click out event!
	$("#backgroundPopup").click(function(){
		if(addLeadsWindowOpen == 1) {
			disableAddLeadsPopup();
		}

	});
	//Press Escape event!
	$(document).keypress(function(e){
		if(e.keyCode==27 && (addLeadsWindowOpen==1)){
			disableAddLeadsPopup();
		}
	});
});

function openPhoneScript() {
	var selSID = document.getElementById('selSID').value;
	var selCID = document.getElementById('selCID').value;
	if (selSID == "-1" || selCID == "-1") {
		var msg = document.getElementById("lb_returnMessage");
		$("#returnMessage").css({
			"backgroundColor": "#FF3300"
		});
		msg.style.color = "white";
		// msg.style.fontWeight = "bold";
		msg.innerHTML = "No leads was selected.";
		$("#returnMessage").slideDown(300).delay(1500).fadeOut('fast');
		return;
	}
	//alert(img.src);
	if(true) {
		//var pic = document.getElementById("pPicture");
		$("#phonescript_bg").fadeIn("fast");
		$("#phonescript_control").fadeIn("fast");
		$("#phonescript_picture").fadeIn("slow");
	}
}

function closePhoneScript() {
	if(true) {
		//var pic = document.getElementById("pPicture");
		$("#phonescript_bg").fadeOut("fast");
		$("#phonescript_control").fadeOut("fast");
		$("#phonescript_picture").fadeOut("fast");
	}
}


function scriptFax() {
	var selSID = document.getElementById('selSID').value;
	var selCID = document.getElementById('selCID').value;
	if (selSID == "-1" || selCID == "-1") {
		var msg = document.getElementById("lb_returnMessage");
		$("#returnMessage").css({
			"backgroundColor": "#FF3300"
		});
		msg.style.color = "white";
		// msg.style.fontWeight = "bold";
		msg.innerHTML = "No leads was selected.";
		$("#returnMessage").slideDown(300).delay(1500).fadeOut('fast');
		return;
	}
	html = '<h3>Fax Scripts</h3>'
		+ '<p>Select script</p>'
		+ '<div style="margin: 10px;"><center><select>'
		+ '<option>intro</option>'
		+ '<option>promo</option>'
		+ '<option>special event</option>'
		+ '</select></center></div>';
	$.prompt(html, {
		callback : popFax,
		buttons : {
			Ok : 1,
			Close : 2
		},
		prefix : 'brownJqi'
	});
}

function popFax() {
	if(true) {
		//var pic = document.getElementById("pPicture");
		$("#faxscript_bg").fadeIn("fast");
		$("#faxscript_control").fadeIn("fast");
		$("#faxscript_picture").fadeIn("slow");
	}
}

function closeFaxScript() {
	if(true) {
		//var pic = document.getElementById("pPicture");
		$("#faxscript_bg").fadeOut("fast");
		$("#faxscript_control").fadeOut("fast");
		$("#faxscript_picture").fadeOut("fast");
	}
}

function scriptMail() {
	var selSID = document.getElementById('selSID').value;
	var selCID = document.getElementById('selCID').value;
	if (selSID == "-1" || selCID == "-1") {
		var msg = document.getElementById("lb_returnMessage");
		$("#returnMessage").css({
			"backgroundColor": "#FF3300"
		});
		msg.style.color = "white";
		// msg.style.fontWeight = "bold";
		msg.innerHTML = "No leads was selected.";
		$("#returnMessage").slideDown(300).delay(1500).fadeOut('fast');
		return;
	}
	html = '<h3>E-Mail Scripts</h3>'
		+ '<p>Select script</p>'
		+ '<div class="field"><center><select>'
		+ '<option>intro</option>'
		+ '<option>who letter</option>'
		+ '<option>what letter</option>'
		+ '<option>will call later</option>'
		+ '<option>special event</option>'
		+ '<option>newsletter</option>'
		+ '<option>promo</option>'
		+ '</select></center></div>';
	$.prompt(html, {
		callback : popEmail,
		buttons : {
			Ok : 1,
			Close : 2
		},
		prefix : 'brownJqi'
	});
}

function popEmail() {
	if(true) {
		//var pic = document.getElementById("pPicture");
		$("#mailscript_bg").fadeIn("fast");
		$("#mailscript_control").fadeIn("fast");
		$("#mailscript_picture").fadeIn("slow");
	}
}

function closeMailScript() {
	if(true) {
		//var pic = document.getElementById("pPicture");
		$("#mailscript_bg").fadeOut("fast");
		$("#mailscript_control").fadeOut("fast");
		$("#mailscript_picture").fadeOut("fast");
	}
}

function showAddLeadsPopup(){
	//loads popup only if it is disabled
	setTimeout("showAddLeadsPopupHelp()", 500);
}

function add2Cust() {
	var selSID = document.getElementById('selSID').value;
	var selCID = document.getElementById('selCID').value;
	if (selSID == "-1" || selCID == "-1") {
		var msg = document.getElementById("lb_returnMessage");
		$("#returnMessage").css({
			"backgroundColor": "#FF3300"
		});
		msg.style.color = "white";
		// msg.style.fontWeight = "bold";
		msg.innerHTML = "No leads was selected.";
		$("#returnMessage").slideDown(300).delay(1500).fadeOut('fast');
		return;
	}
	//alert(window.paymentOptions);
	txt = '<h3>Add Leads to Customer</h3>'
		+ '<p>Com Number: </p>'
		+ '<center><input type="text" id="add2ID" name="add2ID" onchange="checkComNum();" /></center><br />';
	$.prompt(txt, {
		callback : add2CustSubmit,
		buttons : {
			OK : true,
			CANCEL : false
		},
		prefix : 'brownJqi'
	});
	document.getElementById("add2ID").focus();
}

function add2CustSubmit(v,m,f) {
	if(!v) {
		return;
	}
	var selSID = document.getElementById('selSID').value;
	var selCID = document.getElementById('selCID').value;
	var poststr = "selSID=" + selSID + "&selCID=" + selCID;
	poststr += "&num=" + f.add2ID;
	//alert(poststr);
	if (navigator.appName == "Microsoft Internet Explorer") {
		http = new ActiveXObject("Microsoft.XMLHTTP");
	} else {
		http = new XMLHttpRequest();
	}
	http.open("GET", "../script/leads_add2Cust.php?" + poststr,
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
				msg.innerHTML = "Add lead to customer failed.";
				$("#returnMessage").slideDown(300).delay(1500).fadeOut('fast');
				document.getElementById("debugger").value += str.substr(1) + "\n";
				//editArray = new Array();
				return;
			} else if(str.substr(0,1) == "1") {
				//alert("MESSAGE");
				$("#returnMessage").css({
					"backgroundColor": "#00FF00"
				});
				msg.style.color = "black";
				// msg.style.fontWeight = "bold";
				msg.innerHTML = "Lead added to customer.";
				$("#returnMessage").slideDown(300).delay(1500).fadeOut('fast');
			}
		}
	}
	http.send(null);
}

function checkComNum() {
	var str = document.getElementById("add2ID");
	var myRx = /^\d{5}$/;
	var rin = myRx.test(str.value);
	if(rin != "") {
		str.style.backgroundColor = "#A0DC4F";
	} else {
		str.style.backgroundColor = "#FE3D02";
	}
}

function setDND() {
	var selSID = parent.document.getElementById('selSID').value;
	var selCID = parent.document.getElementById('selCID').value;
	var cb = document.getElementById("cDND");
	if (selSID == "-1" || selCID == "-1") {
		var msg = document.getElementById("lb_returnMessage");
		$("#returnMessage").css({
			"backgroundColor": "#FF3300"
		});
		msg.style.color = "white";
		// msg.style.fontWeight = "bold";
		msg.innerHTML = "No leads was selected.";
		$("#returnMessage").slideDown(300).delay(1500).fadeOut('fast');
		if(cb.checked == true) {
			cb.checked = false;
		} else {
			cb.checked = true;
		}
		return;
	} else {
		var poststr = "selSID=" + selSID + "&selCID=" + selCID;
		if(cb.checked == true) {
			poststr += "&op=set";
		} else {
			poststr += "&op=remove";
		}

		if (navigator.appName == "Microsoft Internet Explorer") {
			http = new ActiveXObject("Microsoft.XMLHTTP");
		} else {
			http = new XMLHttpRequest();
		}
		http.open("GET", "../script/leads_setDND.php?" + poststr,
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
					msg.innerHTML = "DND status updated failed.";
					$("#returnMessage").slideDown(300).delay(1500).fadeOut('fast');
					document.getElementById("debugger").value += str.substr(1) + "\n";
					//editArray = new Array();
					return;
				} else if(str.substr(0,1) == "1") {
					//alert("MESSAGE");
					$("#returnMessage").css({
						"backgroundColor": "#00FF00"
					});
					msg.style.color = "black";
					// msg.style.fontWeight = "bold";
					msg.innerHTML = "DND status updated.";
					$("#returnMessage").slideDown(300).delay(1500).fadeOut('fast');
				}
			}
		}
		http.send(null);
	}
}

function updateContact() {
	var selSID = document.getElementById('selSID').value;
	var selCID = document.getElementById('selCID').value;
	if (selSID == "-1" || selCID == "-1") {
		var msg = document.getElementById("lb_returnMessage");
		$("#returnMessage").css({
			"backgroundColor": "#FF3300"
		});
		msg.style.color = "white";
		// msg.style.fontWeight = "bold";
		msg.innerHTML = "No leads was selected.";
		$("#returnMessage").slideDown(300).delay(1500).fadeOut('fast');
		return;
	}
	//alert(window.paymentOptions);
	txt = '<h3>Update Contact</h3>'
		+ '<p>Select Contact Type</p>';
	$.prompt(txt, {
		callback : repop,
		buttons : {
			Business : 1,
			Home : 2,
			Cell : 3,
			Email : 4,
			Fax : 5,
			Other: 0
		},
		prefix : 'brownJqi'
	});
}

function repop(v,m,f) {
	txt = '<h3>Update Contact</h3>'
		+'<p>Was the contact by ';
	if(v == 1) {
		txt += '"Business Number"';
	} else if(v == 2) {
		txt += '"Home Number"';
	} else if(v == 3) {
		txt += '"Cell Number"';
	} else if(v == 4) {
		txt += '"Email"';
	} else if(v == 5) {
		txt += '"Fax Number"';
	} else {
		txt += '"Other"';
	}
	txt += 'successful?</p><div class="field">'
		+ '<input type="hidden" id="type" name="type" value="'+ v +'" /></div>';
	$.prompt(txt, {
		callback : popfinal,
		buttons : {Yes : 1, No : 0},
		prefix : 'brownJqi'
	});
}

function popfinal(v,m,f) {
	//alert(v + " | " + f.type);
	var selSID = document.getElementById('selSID').value;
	var selCID = document.getElementById('selCID').value;
	var poststr = "selSID=" + selSID + "&selCID=" + selCID;
	poststr += "&type=" + f.type + "&code=" + v;
	poststr += "&staff=" + document.getElementById("defStaff").value;
	if (navigator.appName == "Microsoft Internet Explorer") {
		http = new ActiveXObject("Microsoft.XMLHTTP");
	} else {
		http = new XMLHttpRequest();
	}
	http.open("GET", "../script/leads_setCallcard.php?" + poststr,
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
				msg.innerHTML = "Callcard updated failed.";
				$("#returnMessage").slideDown(300).delay(1500).fadeOut('fast');
				document.getElementById("debugger").value += str.substr(1) + "\n";
				//editArray = new Array();
				return;
			} else if(str.substr(0,1) == "1") {
				//alert("MESSAGE");
				$("#returnMessage").css({
					"backgroundColor": "#00FF00"
				});
				msg.style.color = "black";
				// msg.style.fontWeight = "bold";
				msg.innerHTML = "Callcard updated.";
				$("#returnMessage").slideDown(300).delay(1500).fadeOut('fast');
			}
		}
	}
	http.send(null);
}

function showAddLeadsPopupHelp() {
	if(addLeadsWindowOpen==0){
		$("#backgroundPopup").css({
			"opacity": "0.7"
		});
		$("#backgroundPopup").fadeIn("fast");
		$("#addLeadsPopupContact").fadeIn("fast");
		addLeadsWindowOpen = 1;
		cur_page = 1;
	}
	//document.getElementById('leadSearch').focus();
}

function disableAddLeadsPopup(){
	//disables popup only if it is enabled
	if(addLeadsWindowOpen = 1){
		$("#backgroundPopup").fadeOut("fast");
		$("#addLeadsPopupContact").fadeOut("fast");
		addLeadsWindowOpen = 0;
		cur_page = 0;
	}
	document.getElementById("leadSearch").focus();
}

function cancelAddLeadsPopup() {
	// disables popup only if it is enabled
	setTimeout("clearAddLeads()",50);
	if(addLeadsWindowOpen = 1){
		$("#backgroundPopup").fadeOut("fast");
		$("#addLeadsPopupContact").fadeOut("fast");
		addLeadsWindowOpen = 0;
		cur_page = 0;
	}
	document.getElementById("leadSearch").focus();
}

function clearAddLeads() {
	var eleNames = new Array("scanRIN","cTitle","cFirstN","cLastN",
			"cAKA","cDoB","cRIN","cCardNum","cCustType","cCoName",
			"cShirtSize","cPantSize","cExpert","cpType4","cPhone4",
			"cpType5","cPhone5","cE1","cE2","cE3","cE4","cE5","cE6",
			"cE7","cE8","cE9","cE10","cUnitB","cAdrBus","cCityB","cProvB",
			"cZipB","cpType1","cPhone1","cEmail1","cUnitH","cAdrHome",
			"cCityH","cProvH","cZipH","cpType2","cPhone2","cEmail2",
			"cUnitS","cAdrShip","cCityS","cProvS","cZipS","cpType3",
			"cPhone3","cEmail3","cTax1","cTax2","cSPC","cEcoFee",
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

function reloadLIframe(){
	var url = "../php/leads_print.php?";
	if(document.getElementById("leadSearch").value != "") {
		url += "query=" + document.getElementById("leadSearch").value;
	}
	
	if(document.getElementById("leadZip").value != "") {
		url += "&zip=" + document.getElementById("leadZip").value;
	}
		
	var filterItem = document.getElementById("leadFilter")[document.getElementById("leadFilter").selectedIndex];
	var filter = filterItem.innerHTML;
	
	//var url = "../php/leads_print.php?filter=" + fil + "&zip=" + zip + "&query=" + query;
	if(filter == "ARCHITECT") {
		fil = "1";
	} else if(filter == "COLOUR CONSULTANT") {
		fil = "5";
	} else if(filter == "CONSTRUCTION") {
		fil = "6";
	} else if(filter == "DESIGNER") {
		fil = "3";
	} else if(filter == "DRY WALLER") {
		fil = "4";
	} else if(filter == "GENERAL CONTRACTOR") {
		fil = "2";
	} else if(filter == "PAINTER") {
		fil = "8";
	} else if(filter == "RESTORATION") {
		fil = "7";
	} else {
		fil = "10";
	}
	
	if(fil != "10") {
		url += "&filter=" + fil;
	}
	
	$("#loadingZone").css({
		"opacity": "0.5"
	});
	$("#loadingZone").fadeIn("fast");
	
	window.open(url,"tableFrame");
}

function choose_lead(obj) {
	var titles = new Array("cTitle","cLastN",
			"cFirstN","cAKA","cDoB","cRIN","cCardNum","cCustType",
			"cCoName","cShirtSize","cPantSize","cExpert","cpType4",
			"cPhone4","cpType5","cPhone5","cUnitB","cAdrBus","cCityB",
			"cProvB","cZipB","cpType1","cPhone1","cEmail1","cUnitH",
			"cAdrHome","cCityH","cProvH","cZipH","cpType2","cPhone2",
			"cEmail2","cUnitS","cAdrShip","cCityS","cProvS","cZipS","cpType3",
			"cPhone3","cEmail3","cDND","cLastCont","cContCode","cContType");
	if(parent.document.getElementById("usrLvl").value != "5") {
		titles.splice(titles.length,0,"cContStaff");
	}
	var selSID = parent.document.getElementById('selSID');
	var selCID = parent.document.getElementById('selCID');
	
	selSID.value = obj.sID;
	selCID.value = obj.cID;
	
	var poststr = "custStoreID=" + obj.sID + "&custID=" + obj.cID;
	//alert(poststr);

	if(navigator.appName == "Microsoft Internet Explorer") {
		http = new ActiveXObject("Microsoft.XMLHTTP");
	} else {
		http = new XMLHttpRequest();
	}
	http.open("GET", "../script/leads_retrieve.php?" + poststr, true);
	http.onreadystatechange=function() {
		if(http.readyState == 4) {
			var str = http.responseText;
			parent.document.getElementById("debugger").value += str;
			var list = str.split('|');
			for(var i = 0; i < (titles.length); i++) {
				var ele = parent.document.getElementById(titles[i]);
				/*try {
					 ele = parent.document.getElementById(titles[i]);
				} catch(err) {
					parent.document.getElementById("debugger").value += "ERROR: " + err;
				}*/
				if(ele == null) {
					parent.document.getElementById("debugger").value += "MIA: " + titles[i];
				}
				if(list[i] == "") {
					/*if (titles[i] == "pPromo") {
						ele.value = "";;
					} else {
						ele.innerText = "";
						ele.style.backgroundColor = "#ff0000";
					}*/
					if(titles[i] != "cpType1" ||
							titles[i] != "cpType2" || titles[i] != "cpType1" ||
							titles[i] != "cpType4" || titles[i] != "cpType5") {
						ele.style.backgroundColor = "#ff0000";
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
					ele.style.backgroundColor = "#FFFFFF";
					if(titles[i] == "cIsActive") {
						if(list[i] == "1") {
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
						//selLastN.value = list[i];
						ele.value = list[i];
					} else if(titles[i] == "cFirstN") {
						//selFirstN.value = list[i];
						ele.value = list[i];
					} else if(titles[i] == "cCustRep") {
						//alert(list[i]);
						ele.selectedIndex = list[i];
					} else if(titles[i] == "cEcoFee") {
						if(list[i] == "0") {
							ele.selectedIndex = 2;
							ele.style.backgroundColor = "#ff0000";
						} else {
							ele.selectedIndex = 1;
							ele.style.backgroundColor = "#33ff33";
						}
					} else if(titles[i] == "cE1" ||
							titles[i] == "cE2" || titles[i] == "cE3" ||
							titles[i] == "cE4" || titles[i] == "cE5" ||
							titles[i] == "cE6" || titles[i] == "cE7" ||
							titles[i] == "cE8" || titles[i] == "cE9" ||
							titles[i] == "cE10") {
						//alert(ele.type);
						if(list[i] == "0") {
							ele.checked = false;
						} else {
							ele.checked = true;
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
					} else if(titles[i] == "cDND") {
						var cb = parent.document.getElementById("cDND");
						cb.style.backgroundColor = "#FE3D02";
						if(list[i] == "NA") {
							parent.document.getElementById("lb_cDND").innerHTML = "<br /><br /><br />"+"Never";
							parent.document.getElementById("cDND").checked = false;
						} else {
							parent.document.getElementById("lb_cDND").innerHTML = "<br /><br /><br />"+list[i];
							parent.document.getElementById("cDND").checked = true;
						}
					} else if(titles[i] == "cLastCont") {
						ele.style.backgroundColor = "#A0DC4F";
						ele.innerHTML = list[i];
					} else if(titles[i] == "cContType") {
						ele.style.backgroundColor = "#A0DC4F";
						if(list[i] == "1") {
							ele.innerHTML = "Business Number";
						} else if(list[i] == "2") {
							ele.innerHTML = "Home Number";
						} else if(list[i] == "3") {
							ele.innerHTML = "Cell Number";
						} else if(list[i] == "4") {
							ele.innerHTML = "Email";
						} else if(list[i] == "5") {
							ele.innerHTML = "Fax Number";
						} else {
							ele.innerHTML = "Never";
						}
					} else if(titles[i] == "cContCode") {
						ele.style.backgroundColor = "#A0DC4F";
						if(list[i] == "1") {
							ele.innerHTML = "Yes";
						} else if(list[i] == "0") {
							ele.innerHTML = "No";
						} else {
							ele.innerHTML = "Never";
						}
					} else if(titles[i] == "cContStaff") {
						ele.style.backgroundColor = "#A0DC4F";
						if(list[i] == "0") {
							ele.innerHTML = "Never";
						} else {
							ele.innerHTML = list[i];
						}
					} else {
						if(ele.type == "text") {
							ele.value = list[i];
						} else if(ele.type == "select-one") {
							ele.selectedIndex = list[i];
						} else {
							//ele.innerHTML = list[i];
						}
					}
				}
			}
		}
	}
	http.send(null);
}

function resetLead() {
	var filterItem = document.getElementById("leadFilter").selectedIndex = 0;
	var zip = document.getElementById("leadZip").value = "";
	var query = document.getElementById("leadSearch").value = "";
}

/*
 * Edit leads functions
 */
//var editMasterArray = "";
/*function leadsEditf(eleName) {
	var ele = document.getElementById(eleName);
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
		if(editMasterArray.indexOf(eleName) != -1) {
			if(ele.checked == true) {
				checkd = "1";
			} else {
				checkd = "0";
			}
			var before = editMasterArray.substring(0,editMasterArray.indexOf(eleName) + eleName.length + 1);
			var after = editMasterArray.substring(editMasterArray.indexOf("&",editMasterArray.indexOf(eleName) + eleName.length + 1),editMasterArray.length);
			editMasterArray = before + checkd + after;
		} else {
			if(ele.checked == true) {
				checkd = "1";
			} else {
				checkd = "0";
			}
			editMasterArray += eleName + "=" + checkd;
			editMasterArray += "&";
		}
	}
}*/

function leadsEditUpdate(eleName) {
	//alert(editArray.length);
	var i = editArray.indexOf(eleName);
	if(i == -1) {
		editArray.splice(editArray.length,0,eleName);
	}
	//alert(editArray);
}

Array.prototype.indexOf = function(p_val) {
	for(var i = 0, l = this.length; i < l; i++) {
		if(this[i] == p_val) {
			return i;
		}
	}
	return -1;
};

function editLeadsHelp() {
	if(editArray.length == 0) {
		alert("No changes made.");
		return;
	}
	var answer = confirm("Confirm edit lead");
	if(answer)
		setTimeout("editLeads()",100);
	else
		return;
}

function refreshLeads() {
	var selSID = document.getElementById('selSID').value;
	var selCID = document.getElementById('selCID').value;
	if(selSID == "-1" || selCID == "-1") {
		return;
	} else {
		var fun = "choose_leads(" + selSID + "," + selCID + ")";
		setTimeout(fun,100);
		editArray = new Array();
	}
}

function editLeads() {
	var editString = "";
	for(var i=0;i<editArray.length;i++) {
		var ele = document.getElementById(editArray[i]);
		if(ele.type == "text") {
			editString += editArray[i] + "=" + ele.value + "&";
		} else if(ele.type == "select-one") {
			editString += editArray[i] + "=" + ele.selectedIndex + "&";
		}
	}
	var cID = document.getElementById("selCID").value;
	
	editString += "cID=" + cID;
	//alert("staff_edit.php?"+editString);
	//staffPOSTRequest("../script/staff_edit.php", editString);
	if (navigator.appName == "Microsoft Internet Explorer") {
		http = new ActiveXObject("Microsoft.XMLHTTP");
	} else {
		http = new XMLHttpRequest();
	}
	http.open("GET", "../script/leads_edit.php?" + editString, true);
	http.onreadystatechange = function() {
		if (http.readyState == 4) {
			if (http.status == 200) {
				var str = http.responseText;
				//alert(str);
				var msg = document.getElementById("lb_returnMessage");
				   //alert();
				if(str.substr(0,1) == "0") {
				   //alert("ERROR");
					$("#returnMessage").css({
						"backgroundColor": "#FF3300"
					});
					msg.style.color = "white";
					// msg.style.fontWeight = "bold";
					msg.innerHTML = "Leads update failed.";
					$("#returnMessage").slideDown(300).delay(1500).fadeOut('fast');
					document.getElementById("debugger").value += str(1) + "\n";
					//editArray = new Array();
				} else if(str.substr(0,1) == "1") {
					//alert("MESSAGE");
					$("#returnMessage").css({
						"backgroundColor": "#00FF00"
					});
					msg.style.color = "black";
					// msg.style.fontWeight = "bold";
					msg.innerHTML = "Leads update successful.";
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