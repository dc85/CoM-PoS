/* Reports page methods */
var salesReportWindowOpen = 0;
var employeeReportWindowOpen = 0;
var showDebug = 0;

function showLoadingZone() {
	$("#loadingZone").fadeIn("fast");
}

function hideLoadingZone() {
	$("#loadingZone").fadeOut("fast");
}

function showSalesReport() {
	if(salesReportWindowOpen==0){
		$("#default").fadeOut("fast");
		$("#sales_report").fadeIn("fast");
		hideEmployeeReport();
		salesReportWindowOpen = 1;
	}
}

function hideSalesReport() {
	if(salesReportWindowOpen==1){
		$("#sales_report").fadeOut("fast");
		$("#default").fadeIn("fast");

		salesReportWindowOpen = 0;
	}
}

function showEmployeeReport() {
	if(employeeReportWindowOpen==0){
		
		$("#default").fadeOut("fast");
		$("#employee_report").fadeIn("fast");
		hideSalesReport();
		employeeReportWindowOpen = 1;
	}
}

function hideEmployeeReport() {
	if(employeeReportWindowOpen==1){
		$("#employee_report").fadeOut("fast");
		$("#default").fadeIn("fast");

		employeeReportWindowOpen = 0;
	}
}

function loadReport() {
	//alert("Show report test");
	
	var list = document.getElementById("salesReportType")[document.getElementById("salesReportType").selectedIndex].innerHTML;
	var start = document.getElementById("start-date").value;
	var end = document.getElementById("end-date").value;
	
	var url = "../php/reports_print.php?list=" + list + "&start=" + start + "&end=" + end;
	//alert(url);
	window.open(url,"tableFrame");
}

function loadEmployeeReport() {
	var list = document.getElementById("employeeReportType")[document.getElementById("employeeReportType").selectedIndex].innerHTML;
	var date = document.getElementById("employee_date").value;
	
	var url = "../php/reports_employee_print.php?list=" + list + "&date=" + date;
	//alert(url);
	window.open(url,"employeeTableFrame");
}