_main_datatable_scroll_y = 350;

var PERMISSION_ADMIN = 9;
var PERMISSION_MANAGER = 8;
var PERMISSION_RECEPTION = 7;

var DAY_COLORS = {
	0: '#FFCF00', 1: '#F42494', 2: 'green', 3: 'orange', 4: 'blue', 5: 'purple', 6: 'red'
};

var DATE_PICKER_FORMAT = 'DD, d MM yyyy';

var MOMENT_FULL_DAY_FORMAT = 'dddd';
var MOMENT_FULL_DATE_FORMAT = 'D MMM YYYY';
var MOMENT_DATE_DB_FORMAT = 'YYYY-MM-DD';
var MOMENT_DATE_FORMAT = 'YYYY-M-D';

var MOMENT_TIME_FORMAT = 'HH:mm';
var MOMENT_TIME_12_FORMAT = 'hh:mm a';
var MOMENT_DATE_TIME_FORMAT = 'YYYY-M-D HH:mm';

var ICON_OK = '<span class="glyphicon glyphicon-ok" style="color: green; font-size: 1.2em;" aria-hidden="true"></span>';
var ICON_REMOVE = '<span class="glyphicon glyphicon-remove" style="color: red; font-size: 1.2em;" aria-hidden="true"></span>';

// First, checks if it isn't implemented yet.
if (!String.prototype.format) {
  String.prototype.format = function() {
    var args = arguments;
    return this.replace(/{(\d+)}/g, function(match, number) { 
      return typeof args[number] != 'undefined'
        ? args[number]
        : match
      ;
    });
  };
}

function main_request_ajax(url, mode, data, onSuccess)
{
	main_loading_show(); // show loading

	// ***Note
	//		- a single quate copied from any document can cause an error [Unexpected end of input]
	//			, so check carefully or type by yourself.
	
	$.ajax({
		url: url,
		type: 'post',
		data: { 'mode' : mode, 'data' : data},
		// [dataType] can be removed so that receiving any types of return data from PHP
		// eg. check the actual error on PHP if we received ParserError
		// eg. result from print_r()
		dataType: 'json', 
		success: function(response){
			if (response.timeout === undefined) {
				onSuccess(response);
			} else {
				main_redirect('../login/');
			}
		},
		error: function(xhr, errType, err){
			//console.log(xhr.responseText.trim());
			
			main_loading_hide(); // hide loading when error occurred
			//main_alert_message('Error Type: ' + errType + ' | Error:' + err);
			main_alert_message('System Error! Please contact admin.');
			
			console.log(xhr.responseText.trim());
			// write log on the server
			main_request_ajax('../log/log-boundary.php', '', xhr.responseText.trim(), function(response){});
			
			// N.B.
			// undefined object (non-exist jquery object) from Client-Side cause ParserError
			// unmatch keys of the object between client and server (php) also cause ParserError
		}
	});
}

function main_redirect(url)
{
	parent.window.location.replace(url);
}

function main_open_new_tab(url)
{
	window.open(url, '_blank');
}

function main_open_child_window(url, callback)
{
	window.open(url + '?child=1', 'child_window', 'width=1200 height=650');
	window.parentCallback = typeof(callback) === 'undefined' ? function(){} : callback;
}

function main_set_dropdown_index(dropdown, index)
{
	$(dropdown).prop('selectedIndex', typeof(index) === 'undefined' ? 0 : index);
}

function main_log_out()
{
	main_request_ajax('../authentication/authentication-boundary.php', 'LOG_OUT', {}, main_on_log_out_success);
}

function main_on_log_out_success(response)
{
	if (response.success) {
		main_redirect('../login/');
	}
	else {
		main_alert_message(response.msg);
	}
}

function main_get_parameter(name, url) {
    if (!url) url = window.location.href;
    url = url.toLowerCase(); // This is just to avoid case sensitiveness  
    name = name.replace(/[\[\]]/g, "\\$&").toLowerCase();// This is just to avoid case sensitiveness for query parameter name
    var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
        results = regex.exec(url);
    if (!results) return null;
    if (!results[2]) return '';
    return decodeURIComponent(results[2].replace(/\+/g, " "));
}

function main_convert_date_format(date)
{
	// Bug with Datebox's formatter
	// set it like this : data-options="formatter: main_date_box_format"
	var day = date.getDate();
	var month = date.getMonth()+1;
	var year = date.getFullYear();
	
	day = day < 10 ? ('0' + day) : day;
	month = month < 10 ? ('0' + month) : month;
	
	return day + '/' + month + '/' + year;
}

function main_alert_message(msg, fnOnOK)
{
	parent.$.messagebox.alert({ message : msg, icon : 'error', onOK : fnOnOK });
}

function main_info_message(msg, fnOnOK)
{
	parent.$.messagebox.alert({ message : msg, icon : 'info', onOK : fnOnOK });
}

function main_confirm_message(msg, fnOnYes, fnOnNo, defaultBtn)
{
	if (typeof(defaultBtn) === 'undefined') defaultBtn = 0;
	
	parent.$.messagebox.confirm({ message : msg, icon : 'question', onYes : fnOnYes, onNo : fnOnNo, defaultButton: defaultBtn });
}

// show loading
function main_loading_show()
{
	parent.$.loadingpanel.show();
}

// hide loading
function main_loading_hide()
{
	parent.$.loadingpanel.hide();
}

// used for hide loading when any ajax request finished
function main_ajax_success_hide_loading()
{
	$(document).ajaxSuccess(function(){
		main_loading_hide();
	});
}

function main_move_to_title_text(scrollTop, completeFunc) {
	if (typeof(scrollTop) === 'undefined') scrollTop = 230;
	if (typeof(completeFunc) === 'undefined') completeFunc = function(){};
	
	$(parent.document.body).animate({ scrollTop: scrollTop }, {
		duration: 500,
		complete: completeFunc
	});
}

function main_enable_control(ctrl) {
	if ($(ctrl).prop('disabled') == true)
		$(ctrl).prop('disabled', '');
}

function main_disable_control(ctrl) {
	if ($(ctrl).prop('disabled') == false)
		$(ctrl).prop('disabled', 'true');
}

function main_is_int(n){
    return n % 1 === 0;
}

function main_is_float(n){
    return n % 1 !== 0;
}

function main_resize_frame(obj) {
	obj.style.height = obj.contentWindow.document.body.scrollHeight + 'px';
}

function main_get_frame_content(frameName) {
	return window.frames[frameName].frameElement.contentWindow;
}

function initMoneyInput(control, min, max) {
	$(control).autoNumeric('init', { vMin: min, vMax: max, aSign: '$' });
	$(control).css('text-align', 'right');
	$(control).focus(function(){ $(this).select(); });
	$(control).change(function() {
		if (!($(this).val().length))
			setMoneyInputValue($(this), 0);
	});
}

function setMoneyInputValue(control, val) {
	$(control).autoNumeric('set', val);
}

function getMoneyInputValue(control) {
	return parseFloat($(control).autoNumeric('get'));
	//return $(control).val().replace(/\$/i, '');
}

function setSubmitTabIndex(currentControl, nextControl) {
	$(currentControl).keypress(function(e){
		if (e.which == 13) {
			if (typeof(nextControl) == 'function')
				nextControl();
			else
				$(nextControl).focus();
			
			return false;
		}
	});
}

function setTextAllSelection(control) {
	$(control).focus(function(){ $(this).select(); });
}

function initDatepickerInput(control) {
	$(control).datepicker({
	    format: DATE_PICKER_FORMAT,
	    weekStart: 1,
	    todayBtn: "linked",
	    daysOfWeekHighlighted: "0,6",
	    autoclose: true,
	    showOnFocus: false,
	    orientation: "bottom auto"
	});
}

function destroyDatepickerInput(control) {
	$(control).datepicker('destroy');
}

function getDatepickerValue(control) {
	return moment($(control).datepicker('getDate')).format(MOMENT_DATE_FORMAT);
	//return $(control).datepicker('getDate');
}

function setDatepickerInputValue(control, date) {
	$(control).datepicker('setDate', date);
}

function initTouchSpinInput(control, min, max, initVal, step) {
	$(control).TouchSpin({
		verticalbuttons: true,
		initval: initVal,
		min: min,
		max: max,
		step: step
	});
	$(control).change(function() {
		if (!($(this).val().length))
			$(this).val(min);
	});
}

function setTouchSpinInputValue(control, val) {
	$(control).val(val);
}

function getTouchSpinInputValue(control) {
	return $(control).val();
}

function initTimeInput(control) {
	$(control).inputmask("hh:mm t"); // "hh:mm t" => "07:10 pm", "hh:mm" => "19:10"
	//$(control).focus(function(){ $(this).select(); });
}
function setTimeInput(control, val) {
	$(control).val(moment(currentDate() + ' ' + val).format(MOMENT_TIME_12_FORMAT));
}
function getTimeInput(control) {
	return moment(currentDate() + ' ' + $(control).val()).format(MOMENT_TIME_FORMAT);
}
function isTimeInputComplete(control) {
	return $(control).inputmask("isComplete");
}

function convertDBFormatDate(date) {
	return moment(date).format('YYYY-M-D');
}

function getDDLSelectedText(control) {
	return $(control).find('option:selected').text();
}

function currentDate() {
	return moment().format(MOMENT_DATE_FORMAT);
}
function currentTime() {
	return moment().round(5, 'minutes').format(MOMENT_TIME_12_FORMAT);
}
function currentDateTime() {
	return moment().format(MOMENT_DATE_TIME_FORMAT);
}

function formatDate(date, formatString) {
	formatString = typeof(formatString) === 'undefined' ? MOMENT_DATE_FORMAT : formatString;
	return moment(date).format(formatString);
}
function formatTime(val) {
	return moment(val).format(MOMENT_TIME_12_FORMAT);
}

function weekNumberOfYear(date) {
	return moment(date, MOMENT_DATE_FORMAT).isoWeek();
}
function nextWeekDate(date) {
	return moment(date, MOMENT_DATE_FORMAT).add(7, 'days').format(MOMENT_DATE_FORMAT)
}
function previousWeekDate(date) {
	return moment(date, MOMENT_DATE_FORMAT).add(-7, 'days').format(MOMENT_DATE_FORMAT)
}
function dayOfWeekNumber(date) {	
	return moment(date, MOMENT_DATE_FORMAT).isoWeekday();
}
function daysOfWeek(date) {
	var days = [];
	
	weekdayNo = dayOfWeekNumber(date);
	
	// 1 = Mon, 2 = Tue, 3 = Wed, ..., 7 = Sun
	for(var i = 1; i <= 7; i++) {
		weekdayDiff = i - weekdayNo;
		
		day = moment(date, MOMENT_DATE_FORMAT).add(weekdayDiff, 'days').format(MOMENT_DATE_DB_FORMAT);
		days.push(day);
	}
	
	return days;
}





