function roundNumber(num, dec) {
	var result = Math.round(num*Math.pow(10,dec))/Math.pow(10,dec);
	return result;
}

function showGraph(a,b,c,d,e,f) {
	//alert(specFrame.name);
	var api = new jGCharts.Api(); 
	jQuery('<img>') 
	.attr('src', api.make({
		data : [[a,b,c,d,e,f], [300.00,1.00,6575.00,300.00,985.00,50.00]],
		size : '617x275',
		colors : ['ff0000','ff9900','ffff00','33ff00','0000ff','cc00cc'], 
		grid        : true, //default false 
		grid_x      : 10,    //default 10 
		grid_y      : 0,    //default 10 
		grid_line   : 5,   //default 10 
		grid_blank  : 0,
		type : 'bhs',
		bar_width : 100, 
		axis_labels : ['Sales','Target'],
		bar_spacing: 20})) 
	.appendTo("#graph");
}

function getTotals() {
	var date = parent.document.getElementById('datePick').value;
	//alert(date);
	if(navigator.appName == "Microsoft Internet Explorer") {
		http = new ActiveXObject("Microsoft.XMLHTTP");
	} else {
		http = new XMLHttpRequest();
	}

	http.open("GET", "../php/timesheetgraph_summary.php?date=" + date, true);
	http.onreadystatechange=function() {
		if(http.readyState == 4) {
			var str = http.responseText;
			var innerstr = "";
			//alert(str);
			if(str.length > 0) {
				var list = str.split('|');
				for(var i=0;i<list.length;i++) {
					var twin = list[i].split(':');
					if(twin[0] == "1") {
						var tot = document.getElementById('sundrietot');
						var pec = document.getElementById('sundriepec');
						tot.innerText = "$"+twin[1];
						innerstr += twin[1] + ",";
						pec.innerText = roundNumber(twin[1]/985*100,2) + "%";
					} else if(twin[0] == "2") {
						var tot = document.getElementById('painttot');
						var pec = document.getElementById('paintpec');
						tot.innerText = "$"+twin[1];
						innerstr += twin[1] + ",";
						pec.innerText = roundNumber(twin[1]/6575*100,2) + "%";
					} else if(twin[0] == "3") {
						var tot = document.getElementById('draperytot');
						var pec = document.getElementById('draperypec');
						tot.innerText = "$"+twin[1];
						innerstr += twin[1] + ",";
						pec.innerText = roundNumber(twin[1]/300*100,2) + "%";
					} else if(twin[0] == "4") {
						var tot = document.getElementById('wallpapertot');
						var pec = document.getElementById('wallpaperpec');
						tot.innerText = "$"+twin[1];
						innerstr += twin[1] + ",";
						pec.innerText = roundNumber(twin[1]/50*100,2) + "%";
					} else if(twin[0] == "5") {
						var tot = document.getElementById('servicetot');
						var pec = document.getElementById('servicepec');
						tot.innerText = "$"+twin[1];
						innerstr += twin[1] + ",";
						pec.innerText = roundNumber(twin[1]/300*100,2) + "%";
					} else if(twin[0] == "6") {
						var tot = document.getElementById('nocatgtot');
						var pec = document.getElementById('nocatgpec');
						tot.innerText = "$"+twin[1];
						innerstr += twin[1];
						pec.innerText = roundNumber(twin[1]/1*100,2) + "%";
					} else {
						return;
					}
					
				}
				var funcstr = "showGraph("+innerstr+")"
				//alert(funcstr);
				setTimeout(funcstr, 200);
				
			}
		}
	}
	http.send(null);
}