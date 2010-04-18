var showDebug = 0;

function focus_ci() {
	document.getElementById("tb_ci").value = "";
	document.getElementById("tb_ci").focus();
}

function submit_ci() {
	var id = document.getElementById("tb_ci").value;
	if(id == "") {
		focus_ci();
		return;
	}
	var msg = document.getElementById("clock_msg");
	var myRe=/^0\d{9}$/;
	var len = myRe.exec(id);
	if(len) {
		if(navigator.appName == "Microsoft Internet Explorer") {
			http = new ActiveXObject("Microsoft.XMLHTTP");
		} else {
			http = new XMLHttpRequest();
		}
		http.open("GET", "../script/ts_clockin.php?type=si&id=" + len, true);
		http.onreadystatechange=function() {
			if(http.readyState == 4) {
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
						msg.innerHTML = "Out of office status set failed.";
						$("#returnMessage").slideDown(300).delay(1500).fadeOut('fast');
						document.getElementById("debugger").value += str.substr(2) + "\n";
					} else if(str.substr(0,1) == "1") {
						//alert("MESSAGE");
						$("#returnMessage").css({
							"backgroundColor": "#00FF00"
						});
						msg.style.color = "black";
						// msg.style.fontWeight = "bold";
						msg.innerHTML = "Out of office status set successful.";
						$("#returnMessage").slideDown(300).delay(1500).fadeOut('fast');
						//editArray = new Array();
						var f = parent.document.getElementById('tableFrame');
						f.contentWindow.location.reload(true);
					} else {
						$("#returnMessage").css({
							"backgroundColor": "#FFCC33"
						});
						msg.style.color = "black";
						// msg.style.fontWeight = "bold";
						msg.innerHTML = str;
						$("#returnMessage").slideDown(300).delay(1500).fadeOut('fast');
					}
				}
			}
		}
		http.send(null);
	} else {
		document.getElementById("debugger").value += str;
	}
}

function focus_co() {
	document.getElementById("tb_co").value = "";
	document.getElementById("tb_co").focus();
}

function submit_co() {
	var id = document.getElementById("tb_co").value;
	var msg = document.getElementById("clock_msg");
	var myRe=/^0\d{9}$/;
	var len = myRe.exec(id);
	if(len) {
		if(navigator.appName == "Microsoft Internet Explorer") {
			http = new ActiveXObject("Microsoft.XMLHTTP");
		} else {
			http = new XMLHttpRequest();
		}
		http.open("GET", "../script/ts_clockin.php?type=so&id=" + len, true);
		http.onreadystatechange=function() {
			if(http.readyState == 4) {
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
						msg.innerHTML = "Out of office status set failed.";
						$("#returnMessage").slideDown(300).delay(1500).fadeOut('fast');
						document.getElementById("debugger").value += str.substr(2) + "\n";
					} else if(str.substr(0,1) == "1") {
						//alert("MESSAGE");
						$("#returnMessage").css({
							"backgroundColor": "#00FF00"
						});
						msg.style.color = "black";
						// msg.style.fontWeight = "bold";
						msg.innerHTML = "Out of office status set successful.";
						$("#returnMessage").slideDown(300).delay(1500).fadeOut('fast');
						//editArray = new Array();
						var f = parent.document.getElementById('tableFrame');
						f.contentWindow.location.reload(true);
					} else {
						$("#returnMessage").css({
							"backgroundColor": "#FFCC33"
						});
						msg.style.color = "black";
						// msg.style.fontWeight = "bold";
						msg.innerHTML = str;
						$("#returnMessage").slideDown(300).delay(1500).fadeOut('fast');
					}
				}
			}
		}
		http.send(null);
	} else {
		document.getElementById("debugger").value += str;
	}
}

function focus_oo() {
	document.getElementById("tb_oo").value = "";
	document.getElementById("tb_oo").focus();	
}

function submit_oo() {
	var id = document.getElementById("tb_oo").value;
	var msg = document.getElementById("clock_msg");
	var myRe=/^0\d{9}$/;
	var len = myRe.exec(id);
	if(len) {
		if(navigator.appName == "Microsoft Internet Explorer") {
			http = new ActiveXObject("Microsoft.XMLHTTP");
		} else {
			http = new XMLHttpRequest();
		}
		http.open("GET", "../script/ts_clockin.php?type=oo&id=" + len, true);
		http.onreadystatechange=function() {
			if(http.readyState == 4) {
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
						msg.innerHTML = "Out of office status set failed.";
						$("#returnMessage").slideDown(300).delay(1500).fadeOut('fast');
						document.getElementById("debugger").value += str.substr(2) + "\n";
					} else if(str.substr(0,1) == "1") {
						//alert("MESSAGE");
						$("#returnMessage").css({
							"backgroundColor": "#00FF00"
						});
						msg.style.color = "black";
						// msg.style.fontWeight = "bold";
						msg.innerHTML = "Out of office status set successful.";
						$("#returnMessage").slideDown(300).delay(1500).fadeOut('fast');
						//editArray = new Array();
						var f = parent.document.getElementById('tableFrame');
						f.contentWindow.location.reload(true);
					} else {
						$("#returnMessage").css({
							"backgroundColor": "#FFCC33"
						});
						msg.style.color = "black";
						// msg.style.fontWeight = "bold";
						msg.innerHTML = str;
						$("#returnMessage").slideDown(300).delay(1500).fadeOut('fast');
					}
				}
			}
		}
		http.send(null);
	} else {
		document.getElementById("debugger").value += str;
	}
}