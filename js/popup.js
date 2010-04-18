//SETTING UP OUR POPUP
//0 means disabled; 1 means enabled;
var popupStatus = 0;

//loading popup with jQuery magic!
function loadPopup(){
	//loads popup only if it is disabled
	if(popupStatus==0){
		$("#backgroundPopup").css({
			"opacity": "0.7"
		});
		$("#backgroundPopup").fadeIn("slow");
		$("#popupContact").fadeIn("slow");
		popupStatus = 1;
	}
}

//disabling popup with jQuery magic!
function disablePopup(){
	//disables popup only if it is enabled
	if(popupStatus==1){
		$("#backgroundPopup").fadeOut("slow");
		$("#popupContact").fadeOut("slow");
		popupStatus = 0;
	}
}

//centering popup
function centerPopup(){
	//request data for centering
	var windowWidth = document.documentElement.clientWidth;
	var windowHeight = document.documentElement.clientHeight;
	var popupHeight = $("#popupContact").height();
	var popupWidth = $("#popupContact").width();
	//centering
	$("#popupContact").css({
		"position": "absolute",
		"top": windowHeight/2-popupHeight/2,
		"left": windowWidth/2-popupWidth/2
	});
	//only need force for IE6
	
	$("#backgroundPopup").css({
		"height": windowHeight
	});
	
}

function popcore(exp) {
	centerPopup();
	//load popup
	loadPopup();
	
	var br=document.createElement('br');
	var loc = document.getElementById("popupContent");
	
	popTitle = document.createElement("h1");
	popTitle.innerText = "Your account expires in " + exp + " day(s)";
	
	loc.appendChild(popTitle);
	loc.appendChild(br);
	loc.innerHTML += "<center><A href=\"#\" onClick=\"javascript:disablePopup();\"><img name=\"okay\" src=\"images/check.png\" width=\"64\" height=\"64\" border=\"0\"></A></center>";
}

//CONTROLLING EVENTS IN jQuery
$(document).ready(function(){
	
	//LOADING POPUP
	//Click the button event!
	$("#button").click(function(){
		//centering with css
		var mesg = document.getElementById(this.id);
		alert(mesg.innerHTML);
		centerPopup();
		//load popup
		loadPopup();
	});
				
	//CLOSING POPUP
	//Click the x event!
	$("#popupContactClose").click(function(){
		$("#popupContent").empty();
		disablePopup();
	});
	//Click out event!
	$("#backgroundPopup").click(function(){
		$("#popupContent").empty();
		disablePopup();
	});
	//Press Escape event!
	$(document).keypress(function(e){
		if(e.keyCode==27 && popupStatus==1){
			$("#popupContent").empty();
			disablePopup();
		}
	});

});