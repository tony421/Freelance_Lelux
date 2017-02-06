var $dateInput, $txtDate;
var $btnCommissionReport, $btnIncomeReport;

function initPage()
{	
	main_ajax_success_hide_loading();
	
	$dateInput = $("#dateInput");
	$txtDate = $("#txtDate");
	
	$btnCommissionReport = $('#btnCommissionReport');
	$btnIncomeReport = $('#btnIncomeReport');
	
	$btnCommissionReport.click(function(){
		date = getSelectedDailyRecordDate();
		main_open_new_tab('../report/report.php?report_type=COMMISSION_DAILY_REPORT&date=' + date);
	});
	
	$btnIncomeReport.click(function(){
		date = getSelectedDailyRecordDate();
		main_open_new_tab('../report/report.php?report_type=INCOME_DAILY_REPORT&date=' + date);
	});
	
	$txtDate.change(function(){		
		frameName = $('.panel-heading .nav li.active a').prop("name");
		main_get_frame_content(frameName).updateFrameContent();
	});
	
	initDatepicker(new Date());
	
	$('.panel-heading .nav a').click(function(){
		frameName = $(this).prop("name");
		main_get_frame_content(frameName).clearFrameEditMode();
		main_get_frame_content(frameName).updateFrameContent();
	});
}

function initDatepicker(date)
{
	initDatepickerInput($dateInput);
	
	// if the date var is set
	if (typeof(date) !== 'undefined')
		setDatepickerInputValue($dateInput, date);
}

function destroyDatepicker() {
	destroyDatepickerInput($dateInput);
}

function getSelectedDailyRecordDate()
{
	return getDatepickerValue($dateInput);
}

function clearFramesEditMode()
{
	main_get_frame_content('frameMassage').clearEditMode();
	main_get_frame_content('frameSale').clearEditMode();
	main_get_frame_content('frameReception').clearEditMode();
}

function testTabClick()
{
	alert(typeof($dateInput.datepicker()));
}











