/* 
 * door =	6.5*2.83 
 * window =	3*2.3
 */
var dimdoor = 6.5*2.83;
var dimwind = 3*2.3;

function roundNumber(num, dec) {
	var result = Math.round(num*Math.pow(10,dec))/Math.pow(10,dec);
	return result;
}

function resetWallpaper() {
	//alert("wallpaper reset");
	var wproomorwall = document.getElementById('wproomorwall');
	var wpwallwidth = document.getElementById('wpwallwidth');
	var wpwalllength = document.getElementById('wpwalllength');
	var wpwallheight = document.getElementById('wpwallheight');
	var wpdoors = document.getElementById('wpdoors');
	var wpwindows = document.getElementById('wpwindows');
	var wppattern = document.getElementById('wppattern');
	var wpleeway = document.getElementById('wpleeway');
	var wproomsqft = document.getElementById('wproomsqft');
	var wprollsneeded = document.getElementById('wprollsneeded');
	
	wproomorwall.selectedIndex = 0;
	wpwallwidth.value = "0.00";
	wpwalllength.value = "0.00";
	wpwallheight.value = "0.00";
	wpdoors.selectedIndex = 0;
	wpwindows.selectedIndex = 0;
	wppattern.selectedIndex = 0;
	wpleeway.selectedIndex = 0;
	wproomsqft.value = "";
	wprollsneeded.value = "";
}

function calculateWallpaper() {
	var cbLW = 1.1;
	var cbRW = 2;
	var cbRpt = 25;
	var wproomorwall = document.getElementById('wproomorwall');
	var wpwallwidth = document.getElementById('wpwallwidth');
	var wpwalllength = document.getElementById('wpwalllength');
	var wpwallheight = document.getElementById('wpwallheight');
	var wpdoors = document.getElementById('wpdoors');
	var wpwindows = document.getElementById('wpwindows');
	var wppattern = document.getElementById('wppattern');
	var wpleeway = document.getElementById('wpleeway');
	var wproomsqft = document.getElementById('wproomsqft');
	var wprollsneeded = document.getElementById('wprollsneeded');
	doors = wpdoors[wpdoors.selectedIndex].value;
	windows = wpwindows[wpwindows.selectedIndex].value;
	
	ww = wpwallwidth.value;
	wl = wpwalllength.value;
	wh = wpwallheight.value;
	room = roundNumber(ww*wh*cbRW*cbLW, 2);
	room = room + roundNumber(wl*wh*cbRW*cbLW, 2);
	offset = doors*dimdoor;
	offset = offset + windows*dimwind;
	wall = roundNumber(room - offset, 2);
	var rolls = 0;
	
	if(wall/cbRpt != parseInt(wall/cbRpt)) {
		rolls = parseInt(wall/cbRpt) + 1;
	} else {
		rolls = wall/cbRpt;
	}
	wproomsqft.value = wall; 
	wprollsneeded.value = rolls;
	wprollsneeded.focus();
}

function resetPaint() {
	//alert("paint reset");
	var paintroomorwall = document.getElementById('paintroomorwall');
	var paintwallwidth = document.getElementById('paintwallwidth');
	var paintwalllength = document.getElementById('paintwalllength');
	var paintwallheight = document.getElementById('paintwallheight');
	var paintdoors = document.getElementById('paintdoors');
	var paintwindows = document.getElementById('paintwindows');
	var paintpattern = document.getElementById('paintpattern');
	var paintleeway = document.getElementById('paintleeway');
	var paintroomsqft = document.getElementById('paintroomsqft');
	var paintgallonsneeded = document.getElementById('paintgallonsneeded');
	var paintprimerneeded = document.getElementById('paintprimerneeded');
	
	paintroomorwall.selectedIndex = 0;
	paintwallwidth.value = "0.00";
	paintwalllength.value = "0.00";
	paintwallheight.value = "0.00";
	paintdoors.selectedIndex = 0;
	paintwindows.selectedIndex = 0;
	paintpattern.selectedIndex = 0;
	paintleeway.selectedIndex = 0;
	paintroomsqft.value = "";
	paintgallonsneeded.value = "";
	paintprimerneeded.value = "";
}

function calculatePaint() {
	var cbLW = 1.1;
	var cbRW = 2;
	var cbRptP = 2;
	var paintroomorwall = document.getElementById('paintroomorwall');
	var paintwallwidth = document.getElementById('paintwallwidth');
	var paintwalllength = document.getElementById('paintwalllength');
	var paintwallheight = document.getElementById('paintwallheight');
	var paintdoors = document.getElementById('paintdoors');
	var paintwindows = document.getElementById('paintwindows');
	var paintpattern = document.getElementById('paintpattern');
	var paintleeway = document.getElementById('paintleeway');
	var paintroomsqft = document.getElementById('paintroomsqft');
	var paintgallonsneeded = document.getElementById('paintgallonsneeded');
	var paintprimerneeded = document.getElementById('paintprimerneeded');
	doors = paintdoors[paintdoors.selectedIndex].value;
	windows = paintwindows[paintwindows.selectedIndex].value;
	
	ww = paintwallwidth.value;
	wl = paintwalllength.value;
	wh = paintwallheight.value;
	room = roundNumber(ww*wh*cbRW*cbLW, 2);
	room = room + roundNumber(wl*wh*cbRW*cbLW, 2);
	offset = doors*dimdoor;
	offset = offset + windows*dimwind;
	txPrim = roundNumber((room - offset)/400, 2);
	txRSp = roundNumber((room - offset)*cbRptP, 2);
	txGal = txRSp/400;
	paintgallonsneeded.value = txGal;
	paintprimerneeded.value = txPrim;
	paintroomsqft.value = txRSp;
	paintgallonsneeded.setFocus();
}