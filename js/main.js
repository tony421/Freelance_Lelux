_main_datatable_scroll_y = 350;

var DATE_PICKER_FORMAT = 'DD, d MM yyyy';
var MOMENT_DATE_DB_FORMAT = 'YYYY-M-D';

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
		success: onSuccess,
		error: function(xhr, desc, err){
			main_loading_hide(); // hide loading when error occurred
			main_alert_message('Details: ' + desc + ' | Error:' + err);
			
			// N.B.
			// undefined object (non-exist jquery object) from Client-Side cause ParserError
			// unmatch keys of the object between client and server (php) also cause ParserError
		}
	});
}

function main_redirect(url)
{
	window.location.replace(url);
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
}

function setMoneyInputValue(control, val) {
	$(control).autoNumeric('set', val);
}

function getMoneyInputValue(control) {
	return $(control).autoNumeric('get');
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
	return moment($(control).datepicker('getDate')).format(MOMENT_DATE_DB_FORMAT);
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
}

function setTouchSpinInputValue(control, val) {
	$(control).val(val);
}

function getTouchSpinInputValue(control) {
	return $(control).val();
}

function convertDBFormatDate(date) {
	return moment(date).format('YYYY-M-D');
}







