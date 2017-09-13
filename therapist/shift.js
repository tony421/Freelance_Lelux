var _is_add_mode;

var PREFIX_BTN_DELETE = '#btnDelete';
var PREFIX_BTN_ABSENT = '#btnAbsent';
var PREFIX_BTN_WORKING = '#btnWorking';

var $lblTherapist, $ddlTherapist, $ddlShift, $txtTimeStart;
var $btnAdd, $btnDeleteAll, $btnUpdate, $btnCancel;
var $tableShift, _dtTableShift;
var _shifts, _editingShift;

function initPage() {
	main_ajax_success_hide_loading();
	
	_is_add_mode = true;
	
	$lblTherapist = $('#lblTherapist');
	$ddlTherapist = $("#ddlTherapist");
	$ddlShift = $("#ddlShift");
	$txtTimeStart = $("#txtTimeStart");
	
	$btnAdd = $("#btnAdd");
	$btnDeleteAll = $('#btnDeleteAll');
	$btnUpdate = $("#btnUpdate");
	$btnCancel = $("#btnCancel");
	
	$btnAdd.click(function(){
		addTherapistToShift();
	});
	$btnDeleteAll.click(function(){
		main_confirm_message('Do you want to DELETE ALL therapists?'
			, function(){
				deleteAllTherapists();
			}, function(){}, 1);
	});
	
	$btnUpdate.click(function(){
		updateTherapistOnShift();
	});
	$btnCancel.click(function(){
		turnOffEditMode();
		parent.initDatepicker();
		setTableShiftRowsSelection();
		setTimeStart();
	});
	
	$txtTimeStart.keypress(function(e){
		if (e.which == 13) {
			if (_is_add_mode)
				$btnAdd.click();
			else
				$btnUpdate.click();
			
			return false;
		}
	});
	
	initTimeInput($txtTimeStart);
	setTimeStart();
	
	initShiftType();
	initTherapist();
	initTableShift();
	
	getTherapistsOnShift();
	
	turnOffEditMode();
}

function initShiftType() {
	main_request_ajax('therapist-boundary.php', 'GET_SHIFT_TYPE', {}, initShiftTypeDone);
}
function initShiftTypeDone(response) {
	if (response.success) {		
		bindShiftType(response.result);
	}
}
function bindShiftType(shiftTypes) {
	$ddlShift.empty();
	
	if(shiftTypes.length) {
		$.each(shiftTypes, function (i, shiftType){
			option = "<option value='" + shiftType['shift_type_id'] + "'>" + shiftType['shift_type_name'] + "</option>";
			
			$ddlShift.append(option);
		});
	}
}

function initTherapist() {
	selectedDate = parent.getSelectedDailyRecordDate();
	main_request_ajax('therapist-boundary.php', 'GET_THERAPIST_OFF_SHIFT', selectedDate, onInitTherapistDone);
}
function onInitTherapistDone(response) {
	if (response.success) {		
		bindTherapist(response.result);
		$ddlTherapist.focus();
	}
}
function bindTherapist(therapists) {
	$ddlTherapist.empty();
	
	if(therapists.length) {
		$.each(therapists, function (i, therapist){
			option = "<option value='" + therapist['therapist_id'] + "'>" + therapist['therapist_name'] + "</option>";
			
			$ddlTherapist.append(option);
		});
	}
}

function initTableShift() {
	$tableShift = $('#tableShift');
	
	_dtTableShift = $tableShift.DataTable({
		scrollY: _main_datatable_scroll_y,
		scrollX: true,
		language: {
		      emptyTable: "No therapists on this shift"
		},
		paging: false,
		info: false,
		searching: false,
		ordering: false,
		rowId: "shift_id",
		columns: [
		          { data: "row_no", width: "10%", className: "text-center" }
		          , { data: "therapist_name", width: "15%" }
		          , { data: "shift_type_name", width: "20%", className: "text-center"
		        	  , render: function(data, type, row) {
		        		  return '<b><span style="color: ' + row['shift_type_color'] + '">' + data + '</span></b>';
		        	  }
		          }
		          , { data: "shift_time_start", width: "15%", className: "text-center"
		        	  , render: function(data, type, row) {
		        		  return formatTime(data); 
		        	  }
		          }
		          , { data: "shift_working", width: "10%", className: "text-center"
		        	  , render: function(data, type, row) {
		        		  if (data == 1)
		        			  return '<b><span style="color: green">Working</span></b>';
		        		  else 
		        			  return '<b><span style="color: red">Absent</span></b>'; 
		        	  }
		          }
		          , { data: "shift_id", className: "text-center text-nowrap"
		        	  , render: function(data, type, row) {
		        		  if (row['shift_working'] == 1) {
		        			  return '<button type="button" id="btnAbsent' + data + '" class="btn btn-warning btn-xs">Absent</button> <button type="button" id="btnDelete' + data + '" class="btn btn-danger btn-xs">Delete</button>';
		        		  } else {
		        			  return '<button type="button" id="btnWorking' + data + '" class="btn btn-success btn-xs">Working</button> <button type="button" id="btnDelete' + data + '" class="btn btn-danger btn-xs">Delete</button>';
		        		  }
		        	  }
		          }
		]
	});
}
function getTherapistsOnShift() {
	selectedDate = parent.getSelectedDailyRecordDate();
	main_request_ajax('therapist-boundary.php', 'GET_THERAPIST_ON_SHIFT', selectedDate, onGetTherapistsOnShiftDone);
}
function onGetTherapistsOnShiftDone(response) {
	if (response.success) {
		_shifts = response.result;
		
		bindTableShift(response.result);
	}
	else {
		clearTableShift();
		main_alert_message(response.msg);
	}
}
function clearTableShift() {
	_dtTableShift.rows().remove().draw();
}
function bindTableShift(shifts) {
	clearTableShift();
	addShiftRows(shifts);
	setTableShiftRowsSelection();
}
function addShiftRows(shifts) {
	for (var i = 0; i < shifts.length; i++) {
		_dtTableShift.row.add({
			row_no: shifts[i]['row_no']
			, therapist_name: shifts[i]['therapist_name']
			, shift_type_name: shifts[i]['shift_type_name']
			, shift_time_start: shifts[i]['shift_time_start']
			, shift_working: shifts[i]['shift_working']
			, shift_id: shifts[i]['shift_id']
			, shift_type_color: shifts[i]['shift_type_color']
		}).draw();
		
		setTableShiftOption(PREFIX_BTN_WORKING, 'WORK_THERAPIST_ON_SHIFT', shifts[i]['shift_id'], shifts[i]['therapist_name']);
		setTableShiftOption(PREFIX_BTN_ABSENT, 'ABSENT_THERAPIST_ON_SHIFT', shifts[i]['shift_id'], shifts[i]['therapist_name']);
		setTableShiftOption(PREFIX_BTN_DELETE, 'DELETE_THERAPIST_ON_SHIFT', shifts[i]['shift_id'], shifts[i]['therapist_name']);
	}
}
function setTableShiftOption(btnName, command, shiftID, therapistName) {
	if ($(btnName + shiftID).length) {
		$(btnName + shiftID).click(function(){
			if (btnName == PREFIX_BTN_DELETE) {
				main_confirm_message('Do you want to DELETE the therapist?'
					, function(){
						main_request_ajax('therapist-boundary.php', command, {shift_id: shiftID, therapist_name: therapistName}, onTableShiftOptionDone);
					}, function(){}, 1);
			} else {
				main_request_ajax('therapist-boundary.php', command, {shift_id: shiftID, therapist_name: therapistName}, onTableShiftOptionDone);
			}
		});
	}		
}
function onTableShiftOptionDone(response) {
	if (response.success) {
		if (!_is_add_mode) {
			turnOffEditMode();
			parent.initDatepicker();
			setTimeStart();
		}
		
		initTherapist();	
		getTherapistsOnShift();
	}
	else
		main_alert_message(response.msg);
}
function setTableShiftRowsSelection()
{
	$tableShift.on('click', 'tr', function () {
        if ($(this).hasClass('selected')) {
            //$(this).removeClass('selected');
        }
        else {
            _dtTableShift.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
        }
    });
	
	$tableShift.on('dblclick', 'tr', function () {
		//main_alert_message(dtTableRecord.row('.selected').id());
		turnOnEditMode();
		setEditingShift(getSelectedRowIndex());
	});
}
function unbindTableShiftRowsSelection() 
{
	$tableShift.unbind(); // unbind events to prevent duplicate events
}
function getSelectedRowIndex()
{
	return _dtTableShift.row('.selected').index(); // can also use .id()
}

function validateShiftInfo() {
	if (isTimeInputComplete($txtTimeStart)) {
		return true;
	}
	else {
		main_alert_message('Please enter "Start Time"!', function(){ $txtTimeStart.focus();});
	}
	
	return false;
}
function getShiftInfo() {
	var shiftInfo = {
		shift_date: parent.getSelectedDailyRecordDate()
		, therapist_id: $ddlTherapist.val()
		, therapist_name: getDDLSelectedText($ddlTherapist)
		, shift_type_id: $ddlShift.val()
		, shift_time_start: getTimeStart()
	};
	
	return shiftInfo;
}
function getEditedShiftInfo() {
	var shiftInfo = {
		shift_id: _editingShift['shift_id']
		, shift_date: _editingShift['shift_date']
		, therapist_id: _editingShift['therapist_id']
		, shift_type_id: $ddlShift.val()
		, shift_time_start: getTimeStart()
	};
	
	return shiftInfo;
}

function addTherapistToShift() {
	if (validateShiftInfo()) {
		main_request_ajax('therapist-boundary.php', 'ADD_THERAPIST_TO_SHIFT', getShiftInfo(), onAddTherapistToShiftDone);
	}
}
function onAddTherapistToShiftDone(response) {
	if (response.success) {
		initTherapist();	
		getTherapistsOnShift();
	}
	else
		main_alert_message(response.msg);
}

function deleteAllTherapists() {
	selectedDate = parent.getSelectedDailyRecordDate();
	main_request_ajax('therapist-boundary.php', 'DELETE_ALL_THERAPIST_ON_SHIFT', selectedDate, onDeleteAllTherapistsDone);
}
function onDeleteAllTherapistsDone(response) {
	if (response.success) {
		initTherapist();	
		getTherapistsOnShift();
	}
	else
		main_alert_message(response.msg);
}

function updateTherapistOnShift() {
	if (validateShiftInfo()) {
		// before updating, init datepicker, turn off the edit mode and set time_start to current
		parent.initDatepicker();
		shiftInfo = getEditedShiftInfo();
		
		turnOffEditMode();
		setTimeStart();
		
		main_request_ajax('therapist-boundary.php', 'UPDATE_THERAPIST_ON_SHIFT', shiftInfo, onUpdateTherapistOnShift);
	}
}
function onUpdateTherapistOnShift(response) {
	if (response.success) {		
		getTherapistsOnShift();
	}
	else {
		setTableShiftRowsSelection();
		main_alert_message(response.msg);
	}	
}

function setTimeStart(time) {
	time = typeof(time) === "undefined" ? currentTime() : time;
	
	setTimeInput($txtTimeStart, time);
}
function getTimeStart() {
	return parent.getSelectedDailyRecordDate() + ' ' + getTimeInput($txtTimeStart);
}

function turnOnEditMode()
{
	_is_add_mode = false;
	
	$btnAdd.addClass('hidden');
	$btnDeleteAll.addClass('hidden');
	$ddlTherapist.addClass('hidden');
	
	$btnUpdate.removeClass('hidden');
	$btnCancel.removeClass('hidden');
	$lblTherapist.removeClass('hidden');
}
function turnOffEditMode()
{
	_is_add_mode = true;
	
	$btnAdd.removeClass('hidden');
	$btnDeleteAll.removeClass('hidden');
	$ddlTherapist.removeClass('hidden');
	
	$btnUpdate.addClass('hidden');
	$btnCancel.addClass('hidden');
	$lblTherapist.addClass('hidden');
}

function setEditingShift(recordIndex)
{
	// get editing record 
	_editingShift = _shifts[recordIndex];
	
	// users cannot change the date during editing the item
	parent.destroyDatepicker();
	
	$lblTherapist.text(_editingShift['therapist_name']);
	$ddlShift.val(_editingShift['shift_type_id']);
	setTimeInput($txtTimeStart, _editingShift['shift_time_start'].split(' ')[1]);
	
	unbindTableShiftRowsSelection(); // users cannot select a row in datatable during editing the item
}

//will be called by PARENT
function clearFrameEditMode()
{
	if (!_is_add_mode) {
		parent.initDatepicker();
		
		turnOffEditMode();
		setTableShiftRowsSelection();
	}
}

//will be called by PARENT
function updateFrameContent()
{
	initTherapist();
	getTherapistsOnShift();
}
	
	
	
	
	
	
	
	
	
	