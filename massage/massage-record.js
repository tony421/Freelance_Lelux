var _is_add_mode;
var _commissionRate, _requestConditions, _minimumRequest;
var _records, _editingRecord;
var _previousSelectedTherapist;
var _therapistOptions, _massageTypeOptions, _editModeMassageTypeOptions;

var $dateInput;
var $txtDate, $ddlMassageType, $ddlTherapist, $cbRequested, $txtMinutes, $txtStamp;
var $txtTimeIn, $txtTimeOut;
var $txtCash, $cbPromotionPrice, $txtCredit, $txtHICAPS, $txtVoucher;
var $txtStdCommission, $txtReqReward, $txtCommissionTotal;
var $btnAdd, $btnUpdate, $btnDelete, $btnCancelEdit;
var $btnCommissionReport, $btnIncomeReport;
var $tableRecord, $tableRecordBody;
var dtTableRecord;

var DATE_PICKER_FORMAT = 'DD, d MM yyyy';
var MOMENT_DATE_PICKER_FORMAT = 'dddd, D MMMM YYYY';
var MOMENT_DATE_FORMAT = 'YYYY-M-D';
var MOMENT_TIME_FORMAT = 'HH:mm';
var MOMENT_DATE_TIME_FORMAT = 'YYYY-M-D HH:mm';

function initPage()
{	
	main_ajax_success_hide_loading();
	
	_is_add_mode = true;
	
	$dateInput = $('#dateInput');
	$txtDate = $('#txtDate');
	$ddlTherapist = $('#ddlTherapist');
	$ddlMassageType = $('#ddlMassageType');
	$cbRequested = $('#cbRequested');
	$txtMinutes = $('#txtMinutes');
	$txtTimeIn = $('#txtTimeIn');
	$txtTimeOut = $('#txtTimeOut');
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
		calTimeOut();
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
	});
	
	$txtReqReward.change(function(){
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
	$txtReqReward.focus(function(){ $(this).select(); });
	
	//$txtTimeIn.inputmask({alias:"Regex", regex:"^([0-9]|0[0-9]|1[0-9]|2[0-3]):[0-5][0-9]$", placeholder:"_"});
	$txtTimeIn.inputmask("hh:mm"); // "hh:mm t" => 11:30 pm
	$txtTimeIn.focus(function(){ $(this).select(); });
	$txtTimeIn.change(function(){
		calTimeOut();
	});
	setTimeIn();
	
	$cbRequested.change(function(){
		calReqReward();
	});
	
	$cbPromotionPrice.change(function(){
		calReqReward();
	});

	$ddlTherapist.change(function(){
		// ***This can be deleted, if retail function is implemented!!!
		selectedTherapist = $ddlTherapist.find('option:selected').text();
		if ($ddlTherapist.find('option:selected').text() == '[Voucher]') {
			$txtMinutes.val(0);
			setTimeIn('00:00');
		}
		else {
			if (_previousSelectedTherapist == '[Voucher]') {
				$txtMinutes.val(60);
				setTimeIn();
			}
		}
		
		_previousSelectedTherapist = selectedTherapist;
		
		calReqReward();
	});
	
	$ddlMassageType.change(function(){
		if ($(this).val() === 'ADD_NEW_MASSAGE_TYPE') // "ADD NEW MASSAGE TYPE" selected 
		{
			main_open_child_window('../massagetype/massagetype.php', initMassageTypes);
			main_set_dropdown_index(this);
		}
		
		calReqReward();
	});
	
	initDatepicker(new Date());
	initDataTable();
	
	initTherapists();
	initMassageTypes();
	
	turnOffEditMode();
}

function initTherapists()
{
	main_request_ajax('../therapist/therapist-boundary.php', 'GET_THERAPIST', {}, onInitTherapistsRequestDone);
}

function onInitTherapistsRequestDone(response)
{
	if (response.success) {
		therapists = response.result;
		_therapistOptions = therapists;
		
		bindTherapistOption(therapists);
		
		initConfig();
	}
	else {
		main_alert_message(response.msg);
	}
}

function bindTherapistOption(therapists)
{
	$ddlTherapist.empty();
	$.each(therapists, function (i, therapist){
		option = "<option value='" + therapist['therapist_id'] + "'>" + therapist['therapist_name'] + "</option>";
		
		$ddlTherapist.append(option);
	});
}

function initMassageTypes()
{
	main_request_ajax('../massagetype/massagetype-boundary.php', 'GET_MASSAGE_TYPE', {}, onInitMassageTypesDone);
}

function onInitMassageTypesDone(response)
{
	if (response.success) {
		massageTypes = response.result;
		_massageTypeOptions = massageTypes;
		
		if (_is_add_mode) {
			bindMassageTypeOption(massageTypes);
		}
		else {
			setEditModeMassageType();
		}
	}
}

function bindMassageTypeOption(massageTypes)
{
	$ddlMassageType.empty();
	$.each(massageTypes, function (i, massageType){
		option = "<option value='" + massageType['massage_type_id'] + "'>" + massageType['massage_type_name'] + "</option>";
		
		$ddlMassageType.append(option);
	});
	
	$ddlMassageType.append("<optgroup label='--------------------------------------------'></optgroup>");
	$ddlMassageType.append("<option value='ADD_NEW_MASSAGE_TYPE'>&gt;&gt; ADD/EDIT MASSAGE TYPE &lt;&lt;</option>");
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
	    showOnFocus: false,
	    orientation: "bottom auto"
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
		scrollY: _main_datatable_scroll_y,
		paging: false,
		info: false,
		searching: false,
		ordering: true,
		order: [[0, 'desc']], // default ordering - row_no:desc
		rowId: 'massage_record_id',
		columns: [
		    { data: "row_no"},
		    { data: "therapist_name"
		    	, render: function ( data, type, row ) { return (row['massage_record_requested'] == 1) ? data + ' <img src="../image/req.png" title="Requested">' : data; } },
		    //{ data: "massage_record_requested", orderable: false, className: 'text-center'
		    	//, render: function ( data, type, row ) { return (data == 1) ? '<span class="glyphicon glyphicon-ok"></span>' : '<span class="glyphicon glyphicon-remove"></span>' } },
		    //{ data: "massage_record_minutes", orderable: false },
		    { data: "massage_type_name", orderable: false, className: 'text-nowrap'
		    	, render: function ( data, type, row ) { return data + " (" + row['massage_record_minutes'] + ")"; } },
		    { data: "massage_record_time_in_out", orderable: false, className: 'text-center text-nowrap' },
		    { data: "massage_record_stamp", orderable: false, className: 'text-center' },
		    { data: "massage_record_cash", orderable: false, className: 'text-right text-nowrap'
		    	, render: function ( data, type, row ) { return (row['massage_record_promotion'] == 1) ? '<img src="../image/pro.png" title="Promotion Price"> ' + '$' + data : '$' + data; } },
		    //{ data: "massage_record_promotion", orderable: false, className: 'text-center'
		    	//, render: function ( data, type, row ) { return (data == 1) ? '<span class="glyphicon glyphicon-ok"></span>' : '<span class="glyphicon glyphicon-remove"></span>' } },
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
			massage_record_requested: result[i]['massage_record_requested'],
			massage_type_name: result[i]['massage_type_name'],
			massage_record_minutes: result[i]['massage_record_minutes'],
			massage_record_time_in_out: result[i]['massage_record_time_in_out'],
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

function getEditModeTherapist()
{
	
}

function getDeletedMassageType()
{
	deletedItem = { 
		massage_type_id: _editingRecord['massage_type_id']
		, massage_type_name: _editingRecord['massage_type_name'] + " (Deleted)"
		, massage_type_active: _editingRecord['massage_type_active']
		, massage_type_commission: _editingRecord['massage_type_commission']};
		
	return deletedItem;
}

function setEditModeMassageType()
{
	_editModeMassageTypeOptions = _massageTypeOptions.slice(0);
	if (_editingRecord['massage_type_active'] == 0) {
		_editModeMassageTypeOptions.push(getDeletedMassageType());
				
		bindMassageTypeOption(_editModeMassageTypeOptions);
	}
}

function setEditingRecord(recordIndex)
{
	// get editing record 
	_editingRecord = _records[recordIndex];
	
	// set editing record in inputs
	//$dateInput.datepicker('setDate', _editingRecord['massage_record_date']);
	$dateInput.datepicker('destroy'); // users cannot change the date during editing the item
	
	if (_editingRecord['therapist_active'] == 0) {
		listWithDeletedItem = _therapistOptions.slice(0);
		deletedItem = {
			therapist_id: _editingRecord['therapist_id']
			, therapist_name: _editingRecord['therapist_name'] + " (Deleted)"
			, therapist_active: _editingRecord['therapist_active']
		};
		listWithDeletedItem.push(deletedItem);
		
		bindTherapistOption(listWithDeletedItem);
	}
	else {
		
	}
	$ddlTherapist.val(_editingRecord['therapist_id']);
	
	setEditModeMassageType();
	$ddlMassageType.val(_editingRecord['massage_type_id']);
	
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
	$txtTimeIn.val(_editingRecord['massage_record_time_in']);
	$txtTimeOut.val(_editingRecord['massage_record_time_out']);
	
	//$txtName.val(_editingTherapist['therapist_name']);
	//$txtUsername.val(_editingTherapist['therapist_username']);
	//$txtPassword.val(_editingTherapist['therapist_password']);
	
	unbindRecordRowsSelection(); // users cannot select a row in datatable during editing the item
	main_move_to_title_text();
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
	
	bindTherapistOption(_therapistOptions);
	bindMassageTypeOption(_massageTypeOptions);
	
	calReqReward();
	setTimeIn();
}

function calCommission()
{
	if ($ddlTherapist.find('option:selected').text() != '[Voucher]') {
		minutes = $txtMinutes.val();
		reward = parseFloat($txtReqReward.autoNumeric('get'));
		commission = minutes * _commissionRate;
	
		$txtStdCommission.autoNumeric('set', commission);
		$txtCommissionTotal.autoNumeric('set', commission + reward);
	}
	else {
		$txtStdCommission.autoNumeric('set', 0);
		$txtCommissionTotal.autoNumeric('set', 0);	
	}
}

function calReqReward()
{
	if ($ddlTherapist.find('option:selected').text() != '[Voucher]') {
		minutes = parseInt($txtMinutes.val());
		freeStamp = parseInt($txtStamp.val());
	
		minutes = minutes - freeStamp;
	
		if (minutes >= _minimumRequest) {
			reward = 0.0;
			
			req = $cbRequested.is(':checked');
			//stamp = parseInt($txtStamp.val()) > 0 ? true : false; // stamp condition is changed to be minute condition
			promo = $cbPromotionPrice.is(':checked');
			
			//alert(req + '|' + stamp + '|' + promo);
			
			$.each(_requestConditions, function (i, condition){
				if (condition['request_condition_request'] == req 
						//&& condition['request_condition_stamp'] == stamp 
						&& condition['request_condition_promotion'] == promo) {
					
					reward = parseFloat(condition['request_condition_amt']);
					//$txtReqReward.autoNumeric('set', reward);
					
					return false; // use as break statement
				}
			});
			
			// calculate Extra Commission from MassageType
			selectedIndexMassageType = $ddlMassageType.prop('selectedIndex');
			if (_is_add_mode)
				reward += parseFloat(_massageTypeOptions[selectedIndexMassageType]['massage_type_commission']);
			else
				reward += parseFloat(_editModeMassageTypeOptions[selectedIndexMassageType]['massage_type_commission']);
			
			$txtReqReward.autoNumeric('set', reward);
		}
		else {
			$txtReqReward.autoNumeric('set', 0);
		}
	}
	else {
		$txtReqReward.autoNumeric('set', 0);
	}
	
	calCommission();
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
								if ($txtTimeIn.inputmask('isComplete')) {
									return true;
								}
								else {
									main_alert_message('Please enter "Time In"!', function(){ $txtTimeIn.focus();});
								}
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
		'massage_type_id': $ddlMassageType.val(),
		'massage_record_requested': $cbRequested.is(':checked'),
		'massage_record_minutes': $txtMinutes.val(),
		'massage_record_stamp': $txtStamp.val(),
		'massage_record_cash': $txtCash.autoNumeric('get'),
		'massage_record_promotion': $cbPromotionPrice.is(':checked'),
		'massage_record_credit': $txtCredit.autoNumeric('get'),
		'massage_record_hicaps': $txtHICAPS.autoNumeric('get'),
		'massage_record_voucher': $txtVoucher.autoNumeric('get'),
		'massage_record_commission': $txtStdCommission.autoNumeric('get'),
		'massage_record_request_reward': $txtReqReward.autoNumeric('get'),
		'massage_record_time_in': getTimeIn(),
		'massage_record_time_out': getTimeOut()
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
	main_confirm_message('Do you want to DELETE the massage record?', function() {
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

function setTimeIn(time)
{
	time = typeof(time) === "undefined" ? moment().format(MOMENT_TIME_FORMAT) : time;
	
	$txtTimeIn.val(time);
	calTimeOut();
}

function calTimeOut()
{
	if ($txtTimeIn.inputmask("isComplete")) {
		timeIn = $txtTimeIn.val().split(":");
		minutes = $txtMinutes.val().trim().length ? $txtMinutes.val() : 0;
		
		timeOut = moment([1900, 1, 1, timeIn[0], timeIn[1], 0]).add(minutes, 'minutes').format(MOMENT_TIME_FORMAT);
		$txtTimeOut.val(timeOut);
	}
	else {
		$txtTimeOut.val('');
	}
}

function getTimeIn()
{
	timeIn = $txtTimeIn.val().split(":");
	date = moment($dateInput.datepicker('getDate')).format(MOMENT_DATE_FORMAT);
	//alert(moment(date));
	//alert(moment($dateInput.datepicker('getDate')).add(timeIn[0], 'hours'));
	//alert(moment(date).add(timeIn[0], 'hours').add(timeIn[1], 'minutes').format(MOMENT_DATE_TIME_FORMAT));
	
	return moment(date).add(timeIn[0], 'hours').add(timeIn[1], 'minutes').format(MOMENT_DATE_TIME_FORMAT);
}

function getTimeOut()
{
	minutes = $txtMinutes.val().trim().length ? $txtMinutes.val() : 0;
	return moment(getTimeIn()).add(minutes, 'minutes').format(MOMENT_DATE_TIME_FORMAT);
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








