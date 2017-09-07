var _is_add_mode;
var _commissionRate, _requestConditions, _minimumRequest;
var _records, _editingRecord;
var _previousSelectedTherapist;
var _therapistOptions, _massageTypeOptions, _editModeMassageTypeOptions, _roomOptions;
var _timelineSelectedRecordID;

var $dateInput;
var $txtDate, $ddlMassageType, $ddlTherapist, $ddlRoom, $cbRequested, $txtMinutes, $txtStamp;
var $txtTimeIn, $txtTimeOut;
var $txtCash, $cbPromotionPrice, $txtCredit, $txtHICAPS, $txtVoucher, $txtPaidTotal;
var $txtStdCommission, $txtReqReward, $txtCommissionTotal;
var $btnAdd, $btnUpdate, $btnDelete, $btnCancelEdit;
//var $btnCommissionReport, $btnIncomeReport;
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
	_timelineSelectedRecordID = 0;
	
	$dateInput = $('#dateInput');
	//$txtDate = $('#txtDate');
	$ddlTherapist = $('#ddlTherapist');
	$ddlRoom = $('#ddlRoom');
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
	$txtPaidTotal = $('#txtPaidTotal');
	$txtStdCommission = $('#txtStdCommission');
	$txtReqReward = $('#txtReqReward');
	$txtCommissionTotal = $('#txtCommissionTotal');
	$btnAdd = $('#btnAdd');
	$btnUpdate = $('#btnUpdate');
	$btnDelete = $('#btnDelete');
	$btnCancelEdit = $('#btnCancelEdit');
	//$btnCommissionReport = $('#btnCommissionReport');
	//$btnIncomeReport = $('#btnIncomeReport');
	
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
		//initDatepicker();
		parent.initDatepicker();
		setRecordRowsSelection();
		clearInputs();
	});
	
	/*
	$btnCommissionReport.click(function(){
		date = moment($dateInput.datepicker('getDate')).format(MOMENT_DATE_FORMAT);
		main_open_new_tab('../report/report.php?report_type=COMMISSION_DAILY_REPORT&date=' + date);
	});
	
	$btnIncomeReport.click(function(){
		date = moment($dateInput.datepicker('getDate')).format(MOMENT_DATE_FORMAT);
		main_open_new_tab('../report/report.php?report_type=INCOME_DAILY_REPORT&date=' + date);
	});
	*/
	
	/*$txtDate.change(function(){
		//alert(moment($dateInput.datepicker('getDate')).format(MOMENT_DATE_FORMAT) + ' | ' + getDate());
		if ($txtDate.val().trim().length) {
			initConfig();
		}
		else {
			main_alert_message('Please enter "Date"!', function(){ $txtDate.focus();});
		}
	});*/
	
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
	
	initMoneyInput($txtCash, 0, 1000.99);
	initMoneyInput($txtCredit, 0, 1000.99);
	initMoneyInput($txtHICAPS, 0, 1000.99);
	initMoneyInput($txtVoucher, 0, 1000.99);
	initMoneyInput($txtPaidTotal, 0, 1000.99);
	initMoneyInput($txtStdCommission, 0, 1000.99);
	initMoneyInput($txtReqReward, 0, 1000.99);
	initMoneyInput($txtCommissionTotal, 0, 1000.99);
	$txtCash.change(calPaidTotal);
	$txtCredit.change(calPaidTotal);
	$txtHICAPS.change(calPaidTotal);
	$txtVoucher.change(calPaidTotal);
	
	initTimeInput($txtTimeIn);
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

	/*$ddlTherapist.change(function(){
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
	});*/
	
	$ddlMassageType.change(function(){
		if ($(this).val() === 'ADD_NEW_MASSAGE_TYPE') // "ADD NEW MASSAGE TYPE" selected 
		{
			main_open_child_window('../massagetype/massagetype.php', initMassageTypes);
			
			if(_is_add_mode)
				main_set_dropdown_index(this);
			else
				$ddlMassageType.val(_editingRecord['massage_type_id']);
		}
		
		calReqReward();
	});
	
	//initDatepicker(new Date());
	//parent.initDatepicker(new Date()); // first initialize only in the paret page 
	initDataTable();
	
	initTherapists();
	initMassageTypes();
	initRooms();
	
	turnOffEditMode();
}

function initTherapists()
{
	selectedDate = parent.getSelectedDailyRecordDate();
	main_request_ajax('../therapist/therapist-boundary.php', 'GET_THERAPIST_WORKING_ON_SHIFT', selectedDate, onInitTherapistsRequestDone);
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
			$ddlMassageType.val(_editingRecord['massage_type_id']);
		}
		
		calReqReward();
	}
}

function bindMassageTypeOption(massageTypes)
{
	$ddlMassageType.empty();
	$ddlMassageType.unbind('click');
	
	if(massageTypes.length) {
		$.each(massageTypes, function (i, massageType){
			option = "<option value='" + massageType['massage_type_id'] + "'>" + massageType['massage_type_name'] + "</option>";
			
			$ddlMassageType.append(option);
		});
		
		$ddlMassageType.append("<optgroup label='--------------------------------------------'></optgroup>");
		$ddlMassageType.append("<option value='ADD_NEW_MASSAGE_TYPE'>&gt;&gt; ADD/EDIT MASSAGE TYPE &lt;&lt;</option>");
	} else {
		// If there is no "Massage Type" in the list, then do so
		$ddlMassageType.click(function(){
			main_open_child_window('../massagetype/massagetype.php', initMassageTypes);
		});
		$ddlMassageType.append("<option value='ADD_NEW_MASSAGE_TYPE'>ADD MASSAGE TYPE</option>");
	}
}

function initConfig()
{
	//date = moment($dateInput.datepicker('getDate')).format(MOMENT_DATE_FORMAT);
	date = parent.getSelectedDailyRecordDate(); // use getDate function of the parent
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

function initRooms() {
	main_request_ajax('../queueing/queueing-boundary.php', 'GET_ROOM', {}, onInitRoomsDone);
}
function onInitRoomsDone(response) {
	if (response.success) {
		_roomOptions = response.result;
		
		bindRoomDDL(_roomOptions);
	} else {
		main_alert_message(response.msg);
	}
}
function bindRoomDDL(rooms)
{
	$ddlRoom.empty();
	
	$.each(rooms, function (i, room){
		option = "<option value='" + room['room_no'] + "'>" + room['room_desc'] + "</option>";
		
		$ddlRoom.append(option);
	});	
}

/*function initDatepicker(date)
{
	initDatepickerInput($dateInput);
	
	// set current date
	if (typeof(date) !== 'undefined') $dateInput.datepicker('setDate', date);
	//$txtDate.val(moment().format(MOMENT_DATE_PICKER_FORMAT));
}*/

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
			{ data: "therapist_name", visible: false },
		    { data: "therapist_name", className: 'text-nowrap', orderData: [1]
		    	, render: function ( data, type, row ) { return (row['massage_record_requested'] == 1) ? data + ' <img src="../image/req.png" title="Requested">' : data; } },
		    //{ data: "massage_record_requested", orderable: false, className: 'text-center'
		    	//, render: function ( data, type, row ) { return (data == 1) ? '<span class="glyphicon glyphicon-ok"></span>' : '<span class="glyphicon glyphicon-remove"></span>' } },
		    //{ data: "massage_record_minutes", orderable: false },
		    { data: "massage_type_name", orderable: false, className: 'text-nowrap' },
		    { data: "massage_record_time_in_out", orderable: false, className: 'text-nowrap'
		    	, render: function ( data, type, row ) { 
		    		return data + " (" + row['massage_record_minutes'] + ")"; } },
		    { data: "room_no", orderable: false, className: 'text-center'},
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
		    { data: "massage_record_paid_total", orderable: false, className: 'text-right'
			    , render: function ( data, type, row ) { return '$'+ data; } },
		    { data: "massage_record_commission_total", orderable: false, className: 'text-right'
		    	, render: function ( data, type, row ) {
		    		amt = '$' + data;
		    		if (row['massage_record_request_reward'] > 0)
		    			amt += ' ($' + row['massage_record_request_reward'] + ')';
		    		
		    		return amt; 
		    } }
		    /*
		    { data: "massage_record_commission", orderable: false, className: 'text-right'
		    	, render: function ( data, type, row ) { return '$'+ data; } },
		    { data: "massage_record_request_reward", orderable: false, className: 'text-right'
		    	, render: function ( data, type, row ) { return '$'+ data; } },
		    { data: "massage_record_commission_total", orderable: false, className: 'text-right'
		    	, render: function ( data, type, row ) { return '$'+ data; } }
		    */
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
			massage_record_paid_total: result[i]['massage_record_paid_total'],
			massage_record_commission: result[i]['massage_record_commission'],
			massage_record_request_reward: result[i]['massage_record_request_reward'],
			massage_record_commission_total: result[i]['massage_record_commission_total'],
			room_no: result[i]['room_no']
		}).draw();
		
		if (_timelineSelectedRecordID != 0) {
			if(result[i]['massage_record_id'] == _timelineSelectedRecordID) {
				// select the row selected from "Booking" timeline
				dtTableRecord.$('tr:first').addClass('selected');
			}
		}
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
		setEditingRecord(getSelectedRowIndex());
	});
	
	if (_timelineSelectedRecordID != 0) {
		//turnOnEditMode();
		//setEditingRecord(getSelectedRowIndex());
		
		scrollToSelectedRow();
		_timelineSelectedRecordID = 0;
	}
}

function unbindRecordRowsSelection() 
{
	$tableRecordBody.unbind(); // unbind events to prevent duplicate events
}

function getSelectedRowIndex()
{
	return dtTableRecord.row('.selected').index(); // can also use .id()
}
function scrollToSelectedRow() {
	tableTop = $tableRecordBody.offset().top;
	selectedRowTop = $tableRecordBody.find('tr.selected').offset().top;
	containerTop = selectedRowTop - tableTop;
	
	$('.dataTables_scrollBody').scrollTop(containerTop);
	
	//console.log($tableRecordBody.find('tr.selected').offset());
	//console.log(containerTop);
}

function getRecords()
{
	//date = moment($dateInput.datepicker('getDate')).format(MOMENT_DATE_FORMAT);
	date = parent.getSelectedDailyRecordDate(); // use getDate function of the parent
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
	//
	//$dateInput.datepicker('destroy'); // users cannot change the date during editing the item
	parent.destroyDatepicker();
	
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
	
	$ddlTherapist.val(_editingRecord['therapist_id']);
	$ddlRoom.val(_editingRecord['room_no']);
	
	setEditModeMassageType();
	$ddlMassageType.val(_editingRecord['massage_type_id']);
	
	if (_editingRecord['massage_record_requested'] == true) $cbRequested.prop('checked', true);
	if (_editingRecord['massage_record_promotion'] == true) $cbPromotionPrice.prop('checked', true);
	
	$txtMinutes.val(_editingRecord['massage_record_minutes']);
	$txtStamp.val(_editingRecord['massage_record_stamp']);
	setTimeInput($txtTimeIn, _editingRecord['massage_record_time_in']);
	setTimeInput($txtTimeOut, _editingRecord['massage_record_time_out']);
	
	setMoneyInputValue($txtCash, _editingRecord['massage_record_cash']);
	setMoneyInputValue($txtCredit, _editingRecord['massage_record_credit']);
	setMoneyInputValue($txtHICAPS, _editingRecord['massage_record_hicaps']);
	setMoneyInputValue($txtVoucher, _editingRecord['massage_record_voucher']);
	setMoneyInputValue($txtPaidTotal, _editingRecord['massage_record_paid_total']);
	setMoneyInputValue($txtStdCommission, _editingRecord['massage_record_commission']);
	setMoneyInputValue($txtReqReward, _editingRecord['massage_record_request_reward']);
	setMoneyInputValue($txtCommissionTotal, _editingRecord['massage_record_commission_total']);
		
	unbindRecordRowsSelection(); // users cannot select a row in datatable during editing the item
	main_move_to_title_text(450);
}

function clearInputs()
{
	$txtMinutes.val(60);
	$txtStamp.val(0);
	$cbRequested.prop('checked', false)
	$cbPromotionPrice.prop('checked', false)
	setMoneyInputValue($txtCash, 0);
	setMoneyInputValue($txtCredit, 0);
	setMoneyInputValue($txtHICAPS, 0);
	setMoneyInputValue($txtVoucher, 0);
	setMoneyInputValue($txtPaidTotal, 0);
	setMoneyInputValue($txtStdCommission, 0);
	setMoneyInputValue($txtReqReward, 0);
	setMoneyInputValue($txtCommissionTotal, 0);
	
	$ddlTherapist.prop('selectedIndex', 0);
	$ddlMassageType.prop('selectedIndex', 0);
	$ddlRoom.prop('selectedIndex', 0);
	//bindTherapistOption(_therapistOptions);
	//bindMassageTypeOption(_massageTypeOptions);
	
	calReqReward();
	setTimeIn();
}

function calCommission()
{
	minutes = $txtMinutes.val();
	reward = getMoneyInputValue($txtReqReward);
	commission = minutes * _commissionRate;

	setMoneyInputValue($txtStdCommission, commission);
	setMoneyInputValue($txtCommissionTotal, commission + reward);
}

function calReqReward()
{
	minutes = parseInt($txtMinutes.val());
	
	//freeStamp = parseInt($txtStamp.val()); // changed back when 15/4/17
	//minutes = minutes - freeStamp; // changed back when 15/4/17

	if (minutes >= _minimumRequest) {
		reward = 0.0;
		
		req = $cbRequested.is(':checked');
		stamp = parseInt($txtStamp.val()) > 0 ? true : false; // stamp condition is changed to be minute condition; changed back when 15/4/17
		promo = $cbPromotionPrice.is(':checked');
		
		//alert(req + '|' + stamp + '|' + promo);
		
		$.each(_requestConditions, function (i, condition){
			if (condition['request_condition_request'] == req 
					&& condition['request_condition_stamp'] == stamp // changed back when 15/4/17
					&& condition['request_condition_promotion'] == promo) {
				
				reward = parseFloat(condition['request_condition_amt']);
				
				return false; // use as break statement
			}
		});
		
		// calculate Extra Commission from MassageType
		selectedMassageType = getSelectedMassageType();
		reward += parseFloat(selectedMassageType['massage_type_commission']);
		
		setMoneyInputValue($txtReqReward, reward);
	}
	else {
		setMoneyInputValue($txtReqReward, 0);
	}
	
	calCommission();
}

function getSelectedMassageType()
{
	selectedIndex = $ddlMassageType.prop('selectedIndex');
	
	if (_is_add_mode)
		selectedItem = _massageTypeOptions[selectedIndex];
	else
		selectedItem = _editModeMassageTypeOptions[selectedIndex];
	
	if (typeof(selectedItem) == 'undefined')
		selectedItem = {massage_type_id: 0, massage_type_name: '', massage_type_commission: 0};
	
	return selectedItem;
}

function validateRecordInfo()
{
	if ($txtMinutes.val().trim().length) {
		if ($txtStamp.val().trim().length) {
			if ($txtCash.val().length) {
				if ($txtCredit.val().length) {
					if ($txtHICAPS.val().length) {
						if ($txtVoucher.val().length) {
							if (isTimeInputComplete($txtTimeIn)) {
								if ($ddlMassageType.val() != 'ADD_NEW_MASSAGE_TYPE') {
									return true;
								} else {
									main_alert_message('Please add a massage type!', function() { main_open_child_window('../massagetype/massagetype.php', initMassageTypes); });
								}
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
	
	return false;
}

function getRecordInfo(recordID)
{
	var recordInfo = {
		'massage_record_id': typeof(recordID) === 'undefined' ? 0 : recordID,
		'massage_record_date': parent.getSelectedDailyRecordDate(), // use getDate function of the parent
		'therapist_id': $ddlTherapist.val(),
		'massage_type_id': $ddlMassageType.val(),
		'massage_record_requested': $cbRequested.is(':checked'),
		'massage_record_minutes': $txtMinutes.val(),
		'massage_record_stamp': $txtStamp.val(),
		'massage_record_cash': getMoneyInputValue($txtCash),
		'massage_record_promotion': $cbPromotionPrice.is(':checked'),
		'massage_record_credit': getMoneyInputValue($txtCredit),
		'massage_record_hicaps': getMoneyInputValue($txtHICAPS),
		'massage_record_voucher': getMoneyInputValue($txtVoucher),
		'massage_record_commission': getMoneyInputValue($txtStdCommission),
		'massage_record_request_reward': getMoneyInputValue($txtReqReward),
		'massage_record_time_in': getTimeIn(),
		'massage_record_time_out': getTimeOut(),
		'room_no': $ddlRoom.val()
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
		//initDatepicker();
		parent.initDatepicker();
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
		setRecordRowsSelection();
		main_alert_message(response.msg);
	}
}

function deleteRecord()
{
	main_confirm_message('Do you want to DELETE the massage record?', function() {
		//initDatepicker();
		parent.initDatepicker();
		//recordID = _editingRecord['massage_record_id'];
		recordInfo = _editingRecord;
		
		turnOffEditMode();
		clearInputs();
		
		main_request_ajax('massage-boundary.php', 'DELETE_RECORD', recordInfo, onDeleteRecordRequestDone);
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
		setRecordRowsSelection();
		main_alert_message(response.msg);
	}
}

function setTimeIn(time)
{
	time = typeof(time) === "undefined" ? currentTime() : time;
	
	setTimeInput($txtTimeIn, time);
	calTimeOut();
}

function calTimeOut()
{
	if (isTimeInputComplete($txtTimeIn)) {
		timeIn = getTimeInput($txtTimeIn).split(":");
		minutes = $txtMinutes.val().trim().length ? $txtMinutes.val() : 0;
		
		timeOut = moment([1900, 1, 1, timeIn[0], timeIn[1], 0]).add(minutes, 'minutes').format(MOMENT_TIME_12_FORMAT);
		$txtTimeOut.val(timeOut);
	}
	else {
		$txtTimeOut.val('');
	}
}

function getTimeIn()
{
	timeIn = getTimeInput($txtTimeIn).split(":");
	date = parent.getSelectedDailyRecordDate(); // use getDate function of the parent
	
	return moment(date, MOMENT_DATE_FORMAT).add(timeIn[0], 'hours').add(timeIn[1], 'minutes').format(MOMENT_DATE_TIME_FORMAT);
}

function getTimeOut()
{
	minutes = $txtMinutes.val().trim().length ? $txtMinutes.val() : 0;
	return moment(getTimeIn(), MOMENT_DATE_TIME_FORMAT).add(minutes, 'minutes').format(MOMENT_DATE_TIME_FORMAT);
}

function getSelectedDailyRecordDate() {
	return convertDBFormatDate(new Date());
}

function calPaidTotal() {
	var cash = getMoneyInputValue($txtCash);
	var credit = getMoneyInputValue($txtCredit);
	var hicaps = getMoneyInputValue($txtHICAPS);
	var voucher = getMoneyInputValue($txtVoucher);
	
	setMoneyInputValue($txtPaidTotal, cash + credit + hicaps + voucher);
}

// will be called by PARENT
function clearFrameEditMode()
{
	//alert('CLEAR - MASSAGE');
	if (!_is_add_mode) {
		parent.initDatepicker();
		
		turnOffEditMode();
		setRecordRowsSelection();
		clearInputs();
	}
}

//will be called by PARENT
function updateFrameContent()
{
	//alert("UPDATE - MASSAGE");
	initTherapists();
	setTimeIn();
}

//will be called by PARENT
function showMassageRecordDetails(recordID)
{
	initTherapists();
	_timelineSelectedRecordID = recordID;
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








