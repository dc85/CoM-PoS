var showDebug = 0;
var allSelected = 0;
var cIDStr = "";

function popLPN(obj) {
	var selector;
	if(obj.id=="labSearchStr") {
		selector = document.getElementById('lPName');
	} else if(obj.id=="labSearchStr2") {
		selector = document.getElementById('lPName2');
	}
	selector.options.length=0;
	//selector.options = null;
	if(navigator.appName == "Microsoft Internet Explorer") {
		http = new ActiveXObject("Microsoft.XMLHTTP");
	} else {
		http = new XMLHttpRequest();
	}
	http.open("GET", "../script/getCustName.php?str=" + obj.value, true);
	http.onreadystatechange=function() {
		if(http.readyState == 4) {
			document.getElementById("debugger").value += (http.responseText);
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

function showInfo(obj) {
	//alert(obj[obj.selectedIndex].value);
	var infoAry = new Array(
			"Letter size, 3 by 10, 8pt font",
			"Letter size, 2 by 10, 8pt font",
			"Letter size, 2 by 7, 8pt font",
			"Letter size, 2 by 5, 8pt font",
			"Letter size, 1 by 3, 8pt font",
			"Letter size, 3 by 10, 8pt font",
			"A4 size, 2 by 7, 8pt font");
	document.getElementById("printInfoBox").value = infoAry[obj.selectedIndex];
}

function printLabelByMonth(obj) {
	if(obj.name == "birthday") {
		if(document.getElementById("lPMonth").selectedIndex != -1) {
			var url = "../script/print_label.php?"
				+ "month=" + document.getElementById("lPMonth").selectedIndex
				+ "&avery=" + document.getElementById("lPrintOps")[document.getElementById("lPrintOps").selectedIndex].value;
			// alert(url);
			window.open(url, "_blank", "height=595px,width=842px");
		} else {
			return;
		}
	} else if(obj.name == "purchase") {
		//alert(document.getElementById("lPMonth2")[document.getElementById("lPMonth2").selectedIndex].value);
		if(document.getElementById("lPMonth2").selectedIndex != -1) {
			var url = "../script/print_label.php?"
				+ "days=" + document.getElementById("lPMonth2")[document.getElementById("lPMonth2").selectedIndex].value
				+ "&avery=" + document.getElementById("lPrintOps")[document.getElementById("lPrintOps").selectedIndex].value;
			// alert(url);
			window.open(url, "_blank", "height=595px,width=842px");
		} else {
			return;
		}
	} else {
		return
	}
	//alert(document.getElementById("lPMonth").selectedIndex);

}

function printLabelByName() {
	var count = 0;
	var ip = document.getElementById("lPName");
	if(ip.selectedIndex != -1) {
		for(var i=0;i<ip.length;i++) {
			if(ip.options[i].selected == true) {
				//count++;
				if(parseInt(ip.options[i].value) > 0) {
					cIDStr += ip.options[i].value + ",";
				} else {
					//alert(ip.options[i].value);
				}
			}
		}
		
		var url = "../script/print_label.php?"
			+ "cids=" + (cIDStr.substring(0,cIDStr.length-1))
			+ "&avery=" + document.getElementById("lPrintOps")[document.getElementById("lPrintOps").selectedIndex].value;
		// alert(url);
		window.open(url, "_blank", "height=595px,width=842px");
	} else {
		return;
	}
}

function selectAllNames() {
	var ip = document.getElementById("lPName");
	//alert(ip.length);
	if(ip[0].innerHTML == "Type filter in search field (name,company)") {
		return;
	} else {
		if(allSelected == 0) {
			for(var i=0;i<ip.length;i++) {
				ip.options[i].selected = true;
			}
			allSelected = 1;
		} else {
			for(var i=0;i<ip.length;i++) {
				ip.options[i].selected = false;
			}
			allSelected = 0;
		}
	}
}