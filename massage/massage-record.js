var _is_add_mode;
var _commissionRate, _requestConditions, _minimumRequest;
var _records, _editingRecord;

var $dateInput;
var $txtDate, $ddlTherapist, $cbRequested, $txtMinutes, $txtStamp;
var $txtCash, $cbPromotionPrice, $txtCredit, $txtHICAPS, $txtVoucher;
var $txtStdCommission, $txtReqReward, $txtCommissionTotal;
var $btnAdd, $btnUpdate, $btnDelete, $btnCancelEdit;
var $btnCommissionReport, $btnIncomeReport;
var $tableRecord, $tableRecordBody;
var dtTableRecord;

var DATE_PICKER_FORMAT = 'DD, d MM yyyy';
var MOMENT_DATE_PICKER_FORMAT = 'dddd, D MMMM YYYY';
var MOMENT_DATE_FORMAT = 'YYYY-M-D';

function initPage()
{	
	_is_add_mode = true;
	
	$dateInput = $('#dateInput');
	$txtDate = $('#txtDate');
	$ddlTherapist = $('#ddlTherapist');
	$cbRequested = $('#cbRequested');
	$txtMinutes = $('#txtMinutes');
	$txtStamp = $('#txtStamp');
	$txtCash = $('#txtCash');
	$cbPromotionPrice = $('#cbPromotionPrice');
	$txtCredit = $('#txtCredit');
	$txtHICAPS = $('#txtHICAPS');
	$txtVoucher = $('#txtVoucher');
	$txtStdCommission = $('#txtStdCommission');
	$txtReqReward = $('#txtReqReward');
	$txtCommissionTotal = $('#txtCommissionTotal');
	$btnAdd = $('#btnAdd');
	$btnUpdate = $('#btnUpdate');
	$btnDelete = $('#btnDelete');
	$btnCancelEdit = $('#btnCancelEdit');
	$btnCommissionReport = $('#btnCommissionReport');
	$btnIncomeReport = $('#btnIncomeReport');
	
	$btnAdd.click(function(){
		addRecord();
	});
	
	$btnUpdate.click(function(){
		updateRecord();
	});
	
	$btnDelete.click(function(){
		deleteRecord();
	});
	
	$btnCancelEdit.click(function(){
		turnOffEditMode();
		initDatepicker();
		setRecordRowsSelection();
		clearInputs();
	});
	
	$btnCommissionReport.click(function(){
		date = moment($dateInput.datepicker('getDate')).format(MOMENT_DATE_FORMAT);
		main_open_new_tab('../report/report.php?report_type=COMMISSION_DAILY_REPORT&date=' + date);
	});
	
	$btnIncomeReport.click(function(){
		date = moment($dateInput.datepicker('getDate')).format(MOMENT_DATE_FORMAT);
		main_open_new_tab('../report/report.php?report_type=INCOME_DAILY_REPORT&date=' + date);
	});
	
	$txtDate.change(function(){
		//alert(moment($dateInput.datepicker('getDate')).format(MOMENT_DATE_FORMAT) + ' | ' + getDate());
		if ($txtDate.val().trim().length) {
			initConfig();
		}
		else {
			main_alert_message('Please enter "Date"!', function(){ $txtDate.focus();});
		}
	});
	
	$txtMinutes.TouchSpin({
		verticalbuttons: true,
		initval: 60,
		min: 10,
		max: 600,
		step: 5
	});
	$txtMinutes.change(function(){
		calReqReward();
		calCommission();
	});
	
	$txtStamp.TouchSpin({
		verticalbuttons: true,
		initval: 0,
		min: 0,
		max: 600,
		step: 15
	});
	$txtStamp.change(function(){
		calReqReward();
		calCommission();
	});
	
	$txtCash.autoNumeric('init', { vMin: 0, vMax: 1000.99, aSign: '$' });
	$txtCredit.autoNumeric('init', { vMin: 0, vMax: 1000.99, aSign: '$' });
	$txtHICAPS.autoNumeric('init', { vMin: 0, vMax: 1000.99, aSign: '$' });
	$txtVoucher.autoNumeric('init', { vMin: 0, vMax: 1000.99, aSign: '$' });
	$txtStdCommission.autoNumeric('init', { vMin: 0, vMax: 1000.99, aSign: '$' });
	$txtReqReward.autoNumeric('init', { vMin: 0, vMax: 1000.99, aSign: '$' });
	$txtCommissionTotal.autoNumeric('init', { vMin: 0.0, vMax: 1000.99, aSign: '$' });
	
	$txtCash.focus(function(){ $(this).select(); });
	$txtCredit.focus(function(){ $(this).select(); });
	$txtHICAPS.focus(function(){ $(this).select(); });
	$txtVoucher.focus(function(){ $(this).select(); });
	
	$cbRequested.change(function(){
		calReqReward();
		calCommission();
	});
	
	$cbPromotionPrice.change(function(){
		calReqReward();
		calCommission();
	});
	
	initDatepicker(new Date());
	initDataTable();
	
	initTherapists();
	
	turnOffEditMode();
}

function initTherapists()
{
	main_request_ajax('../therapist/therapist-boundary.php', 'GET_THERAPIST', {}, onInitTherapistsRequestDone);
}

function onInitTherapistsRequestDone(response)
{
	if (response.success) {
		_therapistOptions = [];
		therapists = response.result;

		$.each(therapists, function (i, therapist){
			if (therapist['therapist_id'] != 0) {
				option = "<option value='" + therapist['therapist_id'] + "'>" + therapist['therapist_name'] + "</option>";
				
				_therapistOptions.push(option);
				$ddlTherapist.append(option);
			}
		});
		
		initConfig();
	}
	else {
		main_alert_message(response.msg);
	}
}

function initConfig()
{
	date = moment($dateInput.datepicker('getDate')).format(MOMENT_DATE_FORMAT);
	main_request_ajax('../massage/massage-boundary.php', 'GET_CONFIG', date, onInitConfigRequestDone);
}

function onInitConfigRequestDone(response)
{
	if (response.success) {
		_commissionRate = response.result['commission_rate'];
		_requestConditions = response.result['request_conditions'];
		_minimumRequest = response.result['minimum_request'];
		
		//alert(_minimumRequest);
		//alert(_commissionRate + ' | ' + _requestConditions);
		
		calReqReward();
		calCommission();
		getRecords();
	}
	else {
		main_alert_message(response.msg);
	}
}

function initDatepicker(date)
{
	$dateInput.datepicker({
	    format: DATE_PICKER_FORMAT,
	    weekStart: 1,
	    todayBtn: "linked",
	    daysOfWeekHighlighted: "0,6",
	    autoclose: true,
	    showOnFocus: false
	});
	
	// set current date
	if (typeof(date) !== 'undefined') $dateInput.datepicker('setDate', date);
	//$txtDate.val(moment().format(MOMENT_DATE_PICKER_FORMAT));
}

function initDataTable()
{
	$tableRecord = $('#tableRecord');
	// keep instance of DataTable so that it will be used for row.add(), rows().remove() and others
	dtTableRecord = $tableRecord.DataTable({
		paging: false,
		info: false,
		searching: false,
		ordering: true,
		order: [[0, 'desc']], // default ordering - row_no:desc
		rowId: 'massage_record_id',
		columns: [
		    { data: "row_no"},
		    { data: "therapist_name"},
		    { data: "massage_record_minutes", orderable: false },
		    { data: "massage_record_requested", orderable: false, className: 'text-center'
		    	, render: function ( data, type, row ) { return (data == 1) ? '<span class="glyphicon glyphicon-ok"></span>' : '<span class="glyphicon glyphicon-remove"></span>' } },
		    { data: "massage_record_stamp", orderable: false, className: 'text-center' },
		    { data: "massage_record_cash", orderable: false, className: 'text-right'
		    	, render: function ( data, type, row ) { return '$'+ data; } },
		    { data: "massage_record_promotion", orderable: false, className: 'text-center'
		    	, render: function ( data, type, row ) { return (data == 1) ? '<span class="glyphicon glyphicon-ok"></span>' : '<span class="glyphicon glyphicon-remove"></span>' } },
		    { data: "massage_record_credit", orderable: false, className: 'text-right'
		    	, render: function ( data, type, row ) { return '$'+ data; } },
		    { data: "massage_record_hicaps", orderable: false, className: 'text-right'
		    	, render: function ( data, type, row ) { return '$'+ data; } },
	    	{ data: "massage_record_voucher", orderable: false, className: 'text-right'
			    , render: function ( data, type, row ) { return '$'+ data; } },
		    { data: "massage_record_commission", orderable: false, className: 'text-right'
		    	, render: function ( data, type, row ) { return '$'+ data; } },
		    { data: "massage_record_request_reward", orderable: false, className: 'text-right'
		    	, render: function ( data, type, row ) { return '$'+ data; } },
		    { data: "massage_record_commission_total", orderable: false, className: 'text-right'
		    	, render: function ( data, type, row ) { return '$'+ data; } }
        ]
	});
	$tableRecordBody = $('#tableRecord tbody');
	
	//dummyDataSet();
}

function addRecordRows(result)
{
	for (var i = 0; i < result.length; i++) {
		dtTableRecord.row.add({
			massage_record_id: result[i]['massage_record_id'],
			row_no: result[i]['row_no'],
			therapist_name: result[i]['therapist_name'],
			massage_record_minutes: result[i]['massage_record_minutes'],
			massage_record_requested: result[i]['massage_record_requested'],
			massage_record_stamp: result[i]['massage_record_stamp'],
			massage_record_promotion: result[i]['massage_record_promotion'],
			massage_record_cash: result[i]['massage_record_cash'],
			massage_record_credit: result[i]['massage_record_credit'],
			massage_record_hicaps: result[i]['massage_record_hicaps'],
			massage_record_voucher: result[i]['massage_record_voucher'],
			massage_record_commission: result[i]['massage_record_commission'],
			massage_record_request_reward: result[i]['massage_record_request_reward'],
			massage_record_commission_total: result[i]['massage_record_commission_total']}).draw();
	}
	
	setRecordRowsSelection();
}

function setRecordRowsSelection()
{
	$tableRecordBody.on('click', 'tr', function () {
        if ($(this).hasClass('selected')) {
            //$(this).removeClass('selected');
        }
        else {
            dtTableRecord.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
        }
    });
	
	$tableRecordBody.on('dblclick', 'tr', function () {
		//main_alert_message(dtTableRecord.row('.selected').id());
		turnOnEditMode();
		setEditingRecord(dtTableRecord.row('.selected').index()); // can also use .id()
	});
}

function unbindRecordRowsSelection() 
{
	$tableRecordBody.unbind(); // unbind events to prevent duplicate events
}

function getDate()
{
	moment($dateInput.datepicker('getDate')).format('D/M/YYYY');
}

function getRecords()
{
	date = moment($dateInput.datepicker('getDate')).format(MOMENT_DATE_FORMAT);
	main_request_ajax('../massage/massage-boundary.php', 'GET_RECORDS', date, onGetRecordsRequestDone);
}

function onGetRecordsRequestDone(response)
{
	clearTableRecord();
	
	if (response.success) {
		_records = response.result;
		addRecordRows(_records);
	}
	else {
		main_alert_message(response.msg);
	}
}

function clearTableRecord()
{
	dtTableRecord.rows().remove().draw();
	unbindRecordRowsSelection();
}

function turnOnEditMode()
{
	_is_add_mode = false;
	
	$btnAdd.addClass('hidden');
	$btnUpdate.removeClass('hidden');
	$btnDelete.removeClass('hidden');
	$btnCancelEdit.removeClass('hidden');
}

function turnOffEditMode()
{
	_is_add_mode = true;
	
	$btnAdd.removeClass('hidden');
	$btnUpdate.addClass('hidden');
	$btnDelete.addClass('hidden');
	$btnCancelEdit.addClass('hidden');
}

function setEditingRecord(recordIndex)
{
	// get editing record 
	_editingRecord = _records[recordIndex];
	
	// set editing record in inputs
	//$dateInput.datepicker('setDate', _editingRecord['massage_record_date']);
	$dateInput.datepicker('destroy'); // users cannot change the date during editing the item
	
	$ddlTherapist.val(_editingRecord['therapist_id']);
	if (_editingRecord['massage_record_requested'] == true) $cbRequested.prop('checked', true); 
	$txtMinutes.val(_editingRecord['massage_record_minutes']);
	$txtStamp.val(_editingRecord['massage_record_stamp']);
	$txtCash.autoNumeric('set', _editingRecord['massage_record_cash']);
	if (_editingRecord['massage_record_promotion'] == true) $cbPromotionPrice.prop('checked', true);
	$txtCredit.autoNumeric('set', _editingRecord['massage_record_credit']);
	$txtHICAPS.autoNumeric('set', _editingRecord['massage_record_hicaps']);
	$txtVoucher.autoNumeric('set', _editingRecord['massage_record_voucher']);
	$txtStdCommission.autoNumeric('set', _editingRecord['massage_record_commission']);
	$txtReqReward.autoNumeric('set', _editingRecord['massage_record_request_reward']);
	$txtCommissionTotal.autoNumeric('set', _editingRecord['massage_record_commission_total']);
	
	//$txtName.val(_editingTherapist['therapist_name']);
	//$txtUsername.val(_editingTherapist['therapist_username']);
	//$txtPassword.val(_editingTherapist['therapist_password']);
	
	unbindRecordRowsSelection(); // users cannot select a row in datatable during editing the item
	$('body').animate({ scrollTop: 230 }, 400);
}

function clearInputs()
{
	$txtMinutes.val(60);
	$txtStamp.val(0);
	$cbRequested.prop('checked', false)
	$cbPromotionPrice.prop('checked', false)
	$txtCash.autoNumeric('set', 0);
	$txtCredit.autoNumeric('set', 0);
	$txtHICAPS.autoNumeric('set', 0);
	$txtVoucher.autoNumeric('set', 0);
	$txtStdCommission.autoNumeric('set', 0);
	$txtReqReward.autoNumeric('set', 0);
	$txtCommissionTotal.autoNumeric('set', 0);
	
	calReqReward();
	calCommission();
}

function calCommission()
{
	minutes = $txtMinutes.val();
	reward = parseFloat($txtReqReward.autoNumeric('get'));
	commission = minutes * _commissionRate;
	
	$txtStdCommission.autoNumeric('set', commission);
	$txtCommissionTotal.autoNumeric('set', commission + reward);
}

function calReqReward()
{
	minutes = parseInt($txtMinutes.val());
	freeStamp = parseInt($txtStamp.val());
	
	minutes = minutes - freeStamp;
	
	if (minutes >= _minimumRequest) {
		req = $cbRequested.is(':checked');
		//stamp = parseInt($txtStamp.val()) > 0 ? true : false; // stamp condition is changed to be minute condition
		promo = $cbPromotionPrice.is(':checked');
		
		//alert(req + '|' + stamp + '|' + promo);
		
		$.each(_requestConditions, function (i, condition){
			if (condition['request_condition_request'] == req 
					//&& condition['request_condition_stamp'] == stamp 
					&& condition['request_condition_promotion'] == promo) {
				
//				if (main_is_int(condition['request_condition_amt']) == true)
//					reward = parseInt(condition['request_condition_amt']);
//				else
//					reward = condition['request_condition_amt'];
				
				reward = condition['request_condition_amt'];
				//$txtReqReward.val(reward);
				$txtReqReward.autoNumeric('set', reward);
				
				return false; // use as break statement
			}
		});
	}
	else {
		$txtReqReward.autoNumeric('set', 0);
	}
}

function validateRecordInfo()
{
	if ($txtDate.val().trim().length) {
		if ($txtMinutes.val().trim().length) {
			if ($txtStamp.val().trim().length) {
				if ($txtCash.val().length) {
					if ($txtCredit.val().length) {
						if ($txtHICAPS.val().length) {
							if ($txtVoucher.val().length) {
								return true;
							}
							else {
								main_alert_message('Please enter "Voucher"!', function(){ $txtVoucher.focus();});
							}
						}
						else {
							main_alert_message('Please enter "HICAPS"!', function(){ $txtHICAPS.focus();});
						}
					}
					else {
						main_alert_message('Please enter "Credit"!', function(){ $txtCredit.focus();});
					}
				}
				else {
					main_alert_message('Please enter "Cash"!', function(){ $txtCash.focus();});
				}
			}
			else {
				main_alert_message('Please enter "Stamp"!', function(){ $txtStamp.focus();});
			}
		}
		else {
			main_alert_message('Please enter "Minutes"!', function(){ $txtMinutes.focus();});
		}
	}
	else {
		main_alert_message('Please enter "Date"!', function(){ $txtDate.focus();});
	}
	
	return false;
}

function getRecordInfo(recordID)
{
	var recordInfo = {
		'massage_record_id': typeof(recordID) === 'undefined' ? 0 : recordID,
		'massage_record_date': moment($dateInput.datepicker('getDate')).format(MOMENT_DATE_FORMAT),
		'therapist_id': $ddlTherapist.val(),
		'massage_record_requested': $cbRequested.is(':checked'),
		'massage_record_minutes': $txtMinutes.val(),
		'massage_record_stamp': $txtStamp.val(),
		'massage_record_cash': $txtCash.autoNumeric('get'),
		'massage_record_promotion': $cbPromotionPrice.is(':checked'),
		'massage_record_credit': $txtCredit.autoNumeric('get'),
		'massage_record_hicaps': $txtHICAPS.autoNumeric('get'),
		'massage_record_voucher': $txtVoucher.autoNumeric('get'),
		'massage_record_commission': $txtStdCommission.autoNumeric('get'),
		'massage_record_request_reward': $txtReqReward.autoNumeric('get')
	}
	
	return recordInfo;
}

function addRecord()
{
	if (validateRecordInfo()) {
		recordInfo = getRecordInfo();
		main_confirm_message('Do you want to add a massage record?'
				, function(){ main_request_ajax('massage-boundary.php', 'ADD_RECORD', recordInfo, onAddRecordRequestDone); }
				, function(){ $btnAdd.focus(); }
		);
	}
}

function onAddRecordRequestDone(response)
{
	if (response.success) {
		clearInputs();
		main_info_message(response.msg, getRecords);
	}
	else {
		main_alert_message(response.msg);
	}
}

function updateRecord()
{
	if (validateRecordInfo()) {
		// Need to initialize datepicker first so that can use its method 'getDate' in 'getRecordInfo'
		// otherwise datepicker will work incorrectly after 'getDate' called
		initDatepicker();  
		recordInfo = getRecordInfo(_editingRecord['massage_record_id']);
		turnOffEditMode();
		clearInputs();
		
		main_request_ajax('massage-boundary.php', 'UPDATE_RECORD', recordInfo, onUpdateRecordRequestDone);
	}
}

function onUpdateRecordRequestDone(response)
{
	if (response.success) {
		main_info_message(response.msg, getRecords);
	}
	else {
		main_alert_message(response.msg);
	}
}

function deleteRecord()
{
	main_confirm_message('Do you want to delete the massage record?', function() {
		initDatepicker();  
		recordID = _editingRecord['massage_record_id'];
		turnOffEditMode();
		clearInputs();
		
		main_request_ajax('massage-boundary.php', 'DELETE_RECORD', recordID, onDeleteRecordRequestDone);
	}, function(){
		$btnDelete.focus();
	}, 1);
}

function onDeleteRecordRequestDone(response)
{
	if (response.success) {
		main_info_message(response.msg, getRecords);
	}
	else {
		main_alert_message(response.msg);
	}
}

function dummyDataSet()
{
	for (var i = 0; i < 10; i++) {
		dtTableRecord.row.add({
			massage_record_id: i ,
			row_no: i,
			therapist_name: 'Sandy' + i,
			massage_record_minutes: i * 10,
			massage_record_requested: i % 2,
			massage_record_stamp: i,
			massage_record_promotion: i % 2,
			massage_record_cash: i * 5,
			massage_record_credit: i * 10,
			massage_record_hicaps: 0,
			massage_record_commission: i * 10 / 2,
			massage_record_request_reward: i % 2 * 5,
			massage_record_commission_total: 'xx'}).draw();
	}
}








