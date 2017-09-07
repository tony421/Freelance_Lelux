var PREFIX_BTN_DELETE = '#btnDelete';
var PREFIX_BTN_ABSENT = '#btnAbsent';
var PREFIX_BTN_WORKING = '#btnWorking';

var $ddlTherapist, $ddlShift, $btnAdd, $btnDeleteAll;
var $tableShift, _dtTableShift;

function initPage() {
	main_ajax_success_hide_loading();
	
	$ddlTherapist = $("#ddlTherapist");
	$ddlShift = $("#ddlShift");
	$btnAdd = $("#btnAdd");
	$btnDeleteAll = $('#btnDeleteAll');
	
	$btnAdd.click(function(){
		addTherapistToShift();
	});
	$btnDeleteAll.click(function(){
		main_confirm_message('Do you want to DELETE ALL therapists?'
			, function(){
				deleteAllTherapists();
			}, function(){}, 1);
	});
	
	initShiftType();
	initTherapist();
	initTableShift();
	
	getTherapistsOnShift();
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
		          , { data: "therapist_name", width: "30%" }
		          , { data: "shift_type_name", width: "15%", className: "text-center" }
		          , { data: "shift_working", width: "15%", className: "text-center"
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
}
function addShiftRows(shifts) {
	for (var i = 0; i < shifts.length; i++) {
		_dtTableShift.row.add({
			row_no: shifts[i]['row_no']
			, therapist_name: shifts[i]['therapist_name']
			, shift_type_name: shifts[i]['shift_type_name']
			, shift_working: shifts[i]['shift_working']
			, shift_id: shifts[i]['shift_id']
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
		initTherapist();	
		getTherapistsOnShift();
	}
	else
		main_alert_message(response.msg);
}

function getShiftInfo() {
	var shiftInfo = {
		shift_date: parent.getSelectedDailyRecordDate()
		, therapist_id: $ddlTherapist.val()
		, therapist_name: getDDLSelectedText($ddlTherapist)
		, shift_type_id: $ddlShift.val()
	};
	
	return shiftInfo;
}
function addTherapistToShift() {
	main_request_ajax('therapist-boundary.php', 'ADD_THERAPIST_TO_SHIFT', getShiftInfo(), onAddTherapistToShiftDone);
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

//will be called by PARENT
function clearFrameEditMode()
{
	//alert("CLEAR - Therapist");
}

//will be called by PARENT
function updateFrameContent()
{
	//alert("UPDATE - Therapist");
	initTherapist();
	getTherapistsOnShift();
}
	
	
	
	
	
	
	
	
	
	