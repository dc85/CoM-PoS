function addToSeminar() {
	var pageCID = document.getElementById("pageCID").value;
	var semCID = document.getElementById("semCID").value;
	var semPaid = document.getElementById("semPaid").checked;
	var semTA = document.getElementById("semTA").value;
	
	if(pageCID == "-1") {
		alert("No customer selected");
		return;
	}
	
	if(semCID == true) {
		sem = "0";
	} else {
		sem = "-1";
	}
	
	if(navigator.appName == "Microsoft Internet Explorer") {
		http = new ActiveXObject("Microsoft.XMLHTTP");
	} else {
		http = new XMLHttpRequest();
	}
	//alert("cID" + pageCID + "comNum" + semCID + "paid" + semPaid + "ta" + semTA);
	http.open("GET", "../script/seminar_addCust.php?cID=" + pageCID +
			"&comNum=" + semCID +
			"&paid=" + semPaid +
			"&ta=" + semTA, true);
	http.onreadystatechange=function() {
		if(http.readyState == 4) {
			var str = http.responseText;
			alert(str);
		}
	}
	http.send(null);
}

function getCustomer() {
	var semCID = document.getElementById("semCID").value;
	
	var pageCID = document.getElementById("pageCID");
	var pageName = document.getElementById("pageName");
	var pageCo = document.getElementById("pageCo");
	var pageP1 = document.getElementById("pageP1");
	var pageP2 = document.getElementById("pageP2");
	
	if(navigator.appName == "Microsoft Internet Explorer") {
		http = new ActiveXObject("Microsoft.XMLHTTP");
	} else {
		http = new XMLHttpRequest();
	}
	http.open("GET", "../script/seminar_getCust.php?cID=" + semCID, true);
	http.onreadystatechange=function() {
		if(http.readyState == 4) {
			var str = http.responseText;
			//alert(str);
			var list = str.split("|");
			
			var fill = "";
			if(list[1].length > 0) {
				fill = list[0] + " - " + list[1];
				pageCo.value = list[1];
			} else {
				fill = list[0];
			}
			pageCID.value = list[2];
			pageName.value = list[0];
			pageCo.value = list[1];
			pageP1.value = list[3];
			pageP2.value = list[4];
			var semCName = document.getElementById("semCName");
			semCName.value = fill;
		}
	}
	http.send(null);
}