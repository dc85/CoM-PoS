//SETTING UP OUR POPUP
//0 means disabled; 1 means enabled;
var popupStatus = 0;
var addPopupStatus = 0;

//loading popup with jQuery magic!
function loadPopup(){
	//loads popup only if it is disabled
	if(popupStatus==0){
		$("#backgroundPopup").css({
			"opacity": "0.7"
		});
		$("#backgroundPopup").fadeIn("slow");
		$("#cartPopup").fadeIn("slow");
		popupStatus = 1;
	}
}

function loadAddPopup(){
	//loads popup only if it is disabled
	if(addPopupStatus==0){
		$("#backgroundPopup").css({
			"opacity": "0.7"
		});
		$("#backgroundPopup").fadeIn("slow");
		$("#addPopup").fadeIn("slow");
		addPopupStatus = 1;
	}
}

//disabling popup with jQuery magic!
function disablePopup(){
	//disables popup only if it is enabled
	if(popupStatus==1){
		$("#backgroundPopup").fadeOut("slow");
		$("#cartPopup").fadeOut("slow");
		popupStatus = 0;
	}
	if (addPopupStatus==1) {
		$("#backgroundPopup").fadeOut("slow");
		$("#addPopup").fadeOut("slow");
		addPopupStatus = 0;
	}
}

//centering popup
function centerPopup(){
	//request data for centering
	var windowWidth = document.documentElement.clientWidth;
	var windowHeight = document.documentElement.clientHeight;
	var popupHeight = $("#cartPopup").height();
	var popupWidth = $("#cartPopup").width();
	//centering
	$("#cartPopup").css({
		"position": "absolute",
		"top": windowHeight/2-popupHeight/2,
		"left": windowWidth/2-popupWidth/2
	});
	//only need force for IE6
	
	$("#backgroundPopup").css({
		"height": windowHeight
	});
}

function centerAddPopup() {
	//request data for centering
	var windowWidth = document.documentElement.clientWidth;
	var windowHeight = document.documentElement.clientHeight;
	var popupHeight = $("#addPopup").height();
	var popupWidth = $("#addPopup").width();
	//centering
	$("#addPopup").css({
		"position": "absolute",
		"top": windowHeight/2-popupHeight/2,
		"left": windowWidth/2-popupWidth/2
	});
	//only need force for IE6
	
	$("#backgroundPopup").css({
		"height": windowHeight
	});
}

function popadd(upc) {
	centerAddPopup();
	loadAddPopup();
	
	var br = document.createElement('br');
	var loc = document.getElementById("addPopupContent");
	
	popTitle = document.createElement("h1");
	popTitle.innerText = "Add item to cart: " + upc;

	loc.appendChild(popTitle);
	//loc.appendChild(br);
	
	loc.innerHTML += "<center><b>Quantity: </b></center>";
	loc.innerHTML += "<center><input type=\"text\" name=\"Quantity\" value=\"1\" size=\"5\" title=\"How many do you wish to purchase?\"></center>";
	loc.innerHTML += "<center><input type=\"submit\" name=\"addToCart\" value=\"Add to cart\" onClick=\"manageCart('add',1)\"></center>";
}

function popcore(tag) {
	centerPopup();
	loadPopup();
	
	var br = document.createElement('br');
	var loc = document.getElementById("cartPopupContent");
	
	popTitle = document.createElement("h1");
	popTitle.innerText = "This is a: " + tag;
	
	//loc.innerHTML += "<center>HI ARE YOU THERE?</center>";
	//loc.innerText = "TEST TEST";
	loc.appendChild(popTitle);
	//alert("popCore1");
	loc.appendChild(br);
	//alert("popCore2");
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
	$("#cartPopupContactClose").click(function(){
		$("#cartPopupContent").empty();
		disablePopup();
	});
	$("#addPopupContactClose").click(function(){
		$("#addPopupContent").empty();
		disablePopup();
	});
	//Click out event!
	$("#backgroundPopup").click(function(){
		if(popupStatus==1) {
			$("#cartPopupContent").empty();
			//$("#addPopupContent").empty();
			disablePopup();	
		}
		if(addPopupStatus==1) {
			$("#addPopupContent").empty();
			//$("#addPopupContent").empty();
			disablePopup();
		}
	});
	//Press Escape event!
	$(document).keypress(function(e){
		if(e.keyCode==27 && (popupStatus==1 || addPopupStatus==1)){
			if(popupStatus==1) {
				$("#cartPopupContent").empty();
				//$("#addPopupContent").empty();
				disablePopup();	
			}
			if(addPopupStatus==1) {
				$("#addPopupContent").empty();
				//$("#addPopupContent").empty();
				disablePopup();
			}
		}
	});

});