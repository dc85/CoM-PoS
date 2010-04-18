//SETTING UP OUR POPUP
//0 means disabled; 1 means enabled;
var menuStatus = 0;
var loginPopupStatus = 0;
var signinPopupStatus = 0;
var signoutPopupStatus = 0;

var fileListWin = null;

//loading popup with jQuery magic!
function loadLoginPopup(){
	//loads popup only if it is disabled
	if(loginPopupStatus==0){
		$("#backgroundPopup").css({
			"opacity": "0.5"
		});
		$("#backgroundPopup").fadeIn("slow");
		$("#loginPopupContact").fadeIn("slow");
		loginPopupStatus = 1;
	}
}

function showLoginPopup(){
	//loads popup only if it is disabled
	if(loginPopupStatus==0){
		$("#loginPopup").fadeIn("slow");
		loginPopupStatus = 1;
	}
}

function showMenu() {
	$("#footermenu").fadeIn("fast");
	menuStatus = 1;
}

function hideMenu() {
	$("#footermenu").fadeOut("fast");
	menuStatus = 0;
}

function hideLoginPopup(){
	//disables popup only if it is enabled
	if(loginPopupStatus==1){
		$("#loginPopup").fadeOut("slow");
		loginPopupStatus = 0;
	}
}

function loadSigninPopup(){
	//loads popup only if it is disabled
	if(signinPopupStatus==0){
		$("#backgroundPopup").css({
			"opacity": "0.7"
		});
		$("#backgroundPopup").fadeIn("slow");
		$("#signinPopupContact").fadeIn("slow");
		signinPopupStatus = 1;
	}
}

function loadSignoutPopup(){
	//loads popup only if it is disabled
	if(signoutPopupStatus==0){
		$("#backgroundPopup").css({
			"opacity": "0.7"
		});
		$("#backgroundPopup").fadeIn("slow");
		$("#signoutPopupContact").fadeIn("slow");
		signoutPopupStatus = 1;
	}
}

//disabling popup with jQuery magic!
function disablePopup(){
	//disables popup only if it is enabled
	if(loginPopupStatus==1 || signinPopupStatus==1 || signoutPopupStatus==1){
		$("#backgroundPopup").fadeOut("slow");
		$("#loginPopupContact").fadeOut("slow");
		$("#signinPopupContact").fadeOut("slow");
		$("#signoutPopupContact").fadeOut("slow");
		loginPopupStatus = 0;
		signinPopupStatus = 0;
		signoutPopupStatus = 0;
	}
}

//centering popup
function centerLoginPopup(){
	//request data for centering
	var windowWidth = 0, windowHeight = 0;
	if(typeof(window.innerWidth) == 'number') {
		windowWidth = window.innerWidth;
		windowHeight = window.innerHeight;
	} else {
		windowWidth = document.body.clientWidth;
		windowHeight = document.body.clientHeight;
	}
	var popupHeight = $("#loginPopupContact").height();
	var popupWidth = $("#loginPopupContact").width();
	//centering
	$("#loginPopupContact").css({
		"position": "absolute",
		"top": windowHeight/2-popupHeight/2,
		"left": windowWidth/2-popupWidth/2
	});
	//only need force for IE6
	
	$("#backgroundPopup").css({
		"height": windowHeight
	});
	
}

function centerSigninPopup(){
	//request data for centering
	var windowWidth = 0, windowHeight = 0;
	if(typeof(window.innerWidth) == 'number') {
		windowWidth = window.innerWidth;
		windowHeight = window.innerHeight;
	} else {
		windowWidth = document.body.clientWidth;
		windowHeight = document.body.clientHeight;
	}
	var popupHeight = $("#signinPopupContact").height();
	var popupWidth = $("#signinPopupContact").width();
	//centering
	$("#signinPopupContact").css({
		"position": "absolute",
		"top": windowHeight/2-popupHeight/2,
		"left": windowWidth/2-popupWidth/2
	});
	//only need force for IE6
	
	$("#backgroundPopup").css({
		"height": windowHeight
	});
	
}

function openFileList(){
	var wshell = new ActiveXObject("WScript.Shell");
	var sID = wshell.ExpandEnvironmentStrings("%pos%");
	//alert(sID);
    if(fileListWin==null || fileListWin.closed){
    	fileListWin = window.open("src/login.html", "_blank", "resizable=0, location=0, scrollbars=1");
        fileListWin.moveTo(0,0);
    } else {
        fileListWin.focus();
    }
}

function centerSignoutPopup(){
	//request data for centering
	var windowWidth = 0, windowHeight = 0;
	if(typeof(window.innerWidth) == 'number') {
		windowWidth = window.innerWidth;
		windowHeight = window.innerHeight;
	} else {
		windowWidth = document.body.clientWidth;
		windowHeight = document.body.clientHeight;
	}
	var popupHeight = $("#signoutPopupContact").height();
	var popupWidth = $("#signoutPopupContact").width();
	//centering
	$("#signoutPopupContact").css({
		"position": "absolute",
		"top": windowHeight/2-popupHeight/2,
		"left": windowWidth/2-popupWidth/2
	});
	//only need force for IE6
	
	$("#backgroundPopup").css({
		"height": windowHeight
	});
	
}

function clickClockin() {
	if(signinPopupStatus==0){
		centerSigninPopup();
		loadSigninPopup();
	} else if(signinPopupStatus==1) {
		disablePopup();
	}
}

function clockinSubmit() {
	var clockInBox = document.getElementById('clockinvalue');
	
	if(navigator.appName == "Microsoft Internet Explorer") {
		http = new ActiveXObject("Microsoft.XMLHTTP");
	} else {
		http = new XMLHttpRequest();
	}
	http.open("GET", "php/clockin.php?id=" + clockInBox.value, true);
	http.onreadystatechange=function() {
		if(http.readyState == 4) {
			if (http.status == 200) {
				var str = http.responseText;
				//alert(str);
				alert(str);
				
			} else {
				alert(http.responseText);
			}
		}
	}
	http.send(null);
}

function clockoutSubmit() {
	var clockOutBox = document.getElementById('clockoutvalue');
	
	if(navigator.appName == "Microsoft Internet Explorer") {
		http = new ActiveXObject("Microsoft.XMLHTTP");
	} else {
		http = new XMLHttpRequest();
	}
	http.open("GET", "php/clockout.php?id=" + clockOutBox.value, true);
	http.onreadystatechange=function() {
		if(http.readyState == 4) {
			if (http.status == 200) {
				var str = http.responseText;
				//alert(str);
				alert(str);
				
			} else {
				alert(http.responseText);
			}
		}
	}
	http.send(null);
}

function clickClockout() {
	if(signoutPopupStatus==0){
		centerSignoutPopup();
		loadSignoutPopup();
	} else if(signoutPopupStatus==1) {
		disablePopup();
	}
}

function showFooterMenu() {
	if(menuStatus==0) {
		showMenu();
	} else if(menuStatus==1) {
		hideMenu();
	}
}

//CONTROLLING EVENTS IN jQuery
$(document).ready(function(){
	//LOADING POPUP
	//Click the button event!
	/*$("#button").click(function(){
		//centering with css
		var mesg = document.getElementById(this.id);
		alert(mesg.innerHTML);
		centerPopup();
		//load popup
		loadPopup();
	});*/
	
	//CLOSING POPUP
	//Click the x event!
	$("#signinPopupContactClose").click(function(){
		disablePopup();
	});
	
	$("#signoutPopupContactClose").click(function(){
		disablePopup();
	});
	
	$("#loginPopupClose").click(function(){
		hideLoginPopup();
	});
	//Click out event!
	$("#backgroundPopup").click(function(){
		disablePopup();
	});
	//Press Escape event!
	$(document).keypress(function(e){
		if(e.keyCode==27 && (loginPopupStatus==1 || signinPopupStatus==1 || signoutPopupStatus==1)){
			disablePopup();
		}
	});
	
	$(document).bind('keydown', 'f7', function(){
		if(signinPopupStatus==0){
			centerSigninPopup();
			loadSigninPopup();
		} else if(signinPopupStatus==1) {
			disablePopup();
		}
	});
	
	$(document).bind('keydown', 'f8', function(){
		if(signoutPopupStatus==0){
			centerSignoutPopup();
			loadSignoutPopup();
		} else if(signoutPopupStatus==1) {
			disablePopup();
		}
	});

	$(document).bind('keydown', 'f9', function(){
		openFileList();
	});
	
	/*$(document).bind('keydown', 'f9', function(){
		if(loginPopupStatus==0){
			showLoginPopup();
		} else if(loginPopupStatus==1) {
			hideLoginPopup();
		}
	});*/
});

function focus_si() {
	document.getElementById("tb_si").value = "";
	document.getElementById("tb_si").focus();
}

function submit_si() {
	var id = document.getElementById("tb_si").value;
	var msg = document.getElementById("clock_msg");
	var myRe=/^0\d{9}$/;
	var len = myRe.exec(id);
	if(len) {
		if(navigator.appName == "Microsoft Internet Explorer") {
			http = new ActiveXObject("Microsoft.XMLHTTP");
		} else {
			http = new XMLHttpRequest();
		}
		http.open("GET", "script/clockin.php?type=si&id=" + len, true);
		http.onreadystatechange=function() {
			if(http.readyState == 4) {
				if (http.status == 200) {
					var str = http.responseText;
					//alert(str);
					msg.innerHTML = str;
				}
			}
		}
		http.send(null);
	} else {
		msg.innerHTML = "Invalid Staff ID";
	}
}

function focus_so() {
	document.getElementById("tb_so").value = "";
	document.getElementById("tb_so").focus();
}

function submit_so() {
	var id = document.getElementById("tb_so").value;
	var msg = document.getElementById("clock_msg");
	var myRe=/^0\d{9}$/;
	var len = myRe.exec(id);
	if(len) {
		if(navigator.appName == "Microsoft Internet Explorer") {
			http = new ActiveXObject("Microsoft.XMLHTTP");
		} else {
			http = new XMLHttpRequest();
		}
		http.open("GET", "script/clockin.php?type=so&id=" + len, true);
		http.onreadystatechange=function() {
			if(http.readyState == 4) {
				if (http.status == 200) {
					var str = http.responseText;
					//alert(str);
					var msg = document.getElementById("clock_msg");
					msg.innerHTML = str;
				}
			}
		}
		http.send(null);
	} else {
		msg.innerHTML = "Invalid Staff ID";
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
		http.open("GET", "script/clockin.php?type=oo&id=" + len, true);
		http.onreadystatechange=function() {
			if(http.readyState == 4) {
				if (http.status == 200) {
					var str = http.responseText;
					//alert(str);
					var msg = document.getElementById("clock_msg");
					msg.innerHTML = str;
				}
			}
		}
		http.send(null);
	} else {
		msg.innerHTML = "Invalid Staff ID";
	}
}