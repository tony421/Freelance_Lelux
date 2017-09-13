var SHIFT_TYPE_COLOR_DEFAULT = 'white';

var CONTROL_DDL_SHIFT_TYPE = '<select id="{0}" class="form-control">{1}</select>';
var CONTROL_SPAN_SHIFT_TYPE = '<span id="{0}" class="form-control" style="cursor: default;">{1}</span>';

var PREFIX_DDL_SHIFT_TYPE = 'ddlShiftType_'; // <prefix>_<therapist_id>-<date>
var PREFIX_HIDDEN_DATE = 'hiddenDate_';

var DDL_SEPARATOR = '#';
var ID_SEPARATOR = '_';

var _shiftTypes = [];
var _shiftTypeOptions = [];
var _roster, _days, _permission, _next, _previous;

var _dtRoster, $tableRoster;

var $btnNext, $btnPrevious;

function initPage()
{
	main_ajax_success_hide_loading();
	
	initShiftType();
	
	$btnNext = $('#btnNext');
	$btnPrevious = $('#btnPrevious');
	
	$btnNext.click(function(){
		getWeekRoster(_next);
	});
	
	$btnPrevious.click(function(){
		getWeekRoster(_previous);
	});
}

function initShiftType() {
	main_request_ajax('../therapist/therapist-boundary.php', 'GET_SHIFT_TYPE', {}, onInitShiftTypeDone);
}
function onInitShiftTypeDone(response) {
	if (response.success) {
		var shiftTypes = response.result;
		
		_shiftTypes = shiftTypes;
		_shiftTypeOptions = [];
		
		option = "<option style='background-color: " + SHIFT_TYPE_COLOR_DEFAULT + ";' value='0'></option>";
		_shiftTypeOptions.push(option);
		
		$.each(shiftTypes, function (i, shiftType){
			shiftTypeID = shiftType['shift_type_id'];
			
			if (shiftTypeID != 5) {
				option = "<option style='background-color: " + shiftType['shift_type_color'] + ";' value='" + shiftType['shift_type_id'] + "'>" + shiftType['shift_type_name'] + "</option>";
				_shiftTypeOptions.push(option);
			}
		});
		
		// During initialization, get the roster of the current week
		//
		getWeekRoster(currentDate());
	} else {
		main_alert_message(msg);
	}
}

function getWeekRoster(date) {
	var days = daysOfWeek(date);
	main_request_ajax('roster-boundary.php', 'GET_ROSTER', days, onGetWeekRosterDone);
}
function onGetWeekRosterDone(response) {
	if (response.success) {
		_roster = response.result['roster'];
		_days = response.result['days'];
		_permission = response.result['permission'];
		
		_next = nextWeekDate(_days[0]);
		_previous = previousWeekDate(_days[0]);
		
		initTable(_days, _permission);
		addRows(_roster, _days);
	} else {
		main_alert_message(response.msg);
	}
}

function initTable(days, permission) {
	$tableRoster = $('#tableRoster');
	
	if ($.fn.dataTable.isDataTable($tableRoster)) {
		_dtRoster.rows().remove().draw();
		_dtRoster.destroy();
	}
	
	_dtRoster = $tableRoster.DataTable({
		scrollY: 570,
		scrollX: true,
		scrollCollapse: true,
		paging: false,
		info: false,
		searching: false,
		ordering: false,
		fixedColumns: { leftColumns: 1 },
		rowId: 'massage_record_id',
		columns : initTableColumns(days, permission)
	});
}
function initTableColumns(days, permission) {
	var columns = [];
	
	columns.push({ data: 'therapist_id', visible: false });
	columns.push({ title: '', data: 'therapist_name', className: 'text-nowrap' });
	
	for (var i = 0; i < days.length; i++) {
		dateTitle = '<span style="color: ' + DAY_COLORS[i] + '">' + formatDate(days[i], MOMENT_FULL_DAY_FORMAT) + '<br>' + formatDate(days[i], MOMENT_FULL_DATE_FORMAT) + '</span>';
		
		columns.push({ title: dateTitle, data: days[i], className: 'text-center text-nowrap roster-header roster-selection'
			, render: function ( data, type, row, meta ) { 
				// Rendering Issue: using days[i] only point to the lasted value of the for loop
				// So, to find the precise date of the column is to use meta.col (column index) and minus it by 2.
				// This is because there are the first two columns before the date columns
				//
				colIndex = meta.col;
				date = days[colIndex - 2];
				
				id = PREFIX_DDL_SHIFT_TYPE + row['therapist_id'] + ID_SEPARATOR + date;
								
				if (_permission < PERMISSION_RECEPTION) {
					$spanVal = '';
					typeIndex = data - 1;
					if (typeIndex >= 0 && typeIndex <= 1 )
						$spanVal = _shiftTypes[typeIndex]['shift_type_name'];
					
					return CONTROL_SPAN_SHIFT_TYPE.format(id, $spanVal);
				}
				else {
					return CONTROL_DDL_SHIFT_TYPE.format(id, _shiftTypeOptions);
				}
	    	}
		});
	}
	
	return columns;
}

function addRows(roster, days) {
	for (var i = 0; i < roster.length; i++) {
		var row = [];
		
		row['therapist_id'] = roster[i]['therapist_id'];
		row['therapist_name'] = roster[i]['therapist_name'];
		
		for (var d = 0; d < days.length; d++) {
			shiftTypeID = roster[i][days[d]];
			row[days[d]] = shiftTypeID;
			row[days[d] + ID_SEPARATOR + 'date'] = shiftTypeID;
		}
		
		_dtRoster.row.add(row).draw();
		
		for (var d = 0; d < days.length; d++) {
			shiftTypeID = roster[i][days[d]];
			setShiftTypeOptionForTable(row['therapist_id'], days[d], shiftTypeID);
		}
	}
}
function setShiftTypeOptionForTable(therapistID, date, shiftTypeID) {
	var id = PREFIX_DDL_SHIFT_TYPE + therapistID + ID_SEPARATOR + date;
	//console.log('=======');
	//console.log($('#' + id).length);
	
	if ($('#' + id).length) {
		$('#' + id).val(shiftTypeID);
		setTimeout(setShiftTypeColor(id), 100);
		
		if (!(_permission < PERMISSION_RECEPTION)) {
			$('#' + id).change(function(){
				setShiftTypeColor(this);
				
				ctrlID = $(this).attr('id');
				manageShift(ctrlID);
			});
		}
	}
}
function setShiftTypeColor(id) {
	// 0 = white, 1 = green, 2 = orange 
	
	if (!($(id).length))
		id = '#' + id;
	
	shiftTypeID = $(id).val();
	//console.log(shiftTypeID + ' | ' + SHIFT_SELETION_COLORS[shiftTypeID]);
	
	shiftType = getSelectedShiftType(shiftTypeID);
	if (typeof(shiftType) === 'undefined')
		$(id).css('background-color', SHIFT_TYPE_COLOR_DEFAULT);
	else
		$(id).css('background-color', shiftType['shift_type_color']);
}
function getSelectedShiftType(shiftTypeID) {
	for (var i = 0; i < _shiftTypes.length; i++) {
		if (shiftTypeID == _shiftTypes[i]['shift_type_id'])
			return _shiftTypes[i];
	}
	
	return undefined;
}

function manageShift(id) {
	if (!($(id).length))
		id = '#' + id;
	
	shiftTypeID = $(id).val();
	shiftType = getSelectedShiftType(shiftTypeID);
	//console.log(shiftTypeID);
	
	idParts = id.split(ID_SEPARATOR);
	therapistID = idParts[1];
	date = idParts[2];
	
	var shiftInfo = {
		therapist_id: therapistID
		, shift_date: date
		, shift_type_id: shiftTypeID
		, shift_type_time_start: typeof(shiftType) == 'undefined' ? '00:00:00' : shiftType['shift_type_time_start']
	};
	
	main_request_ajax('roster-boundary.php', 'MANAGE_ROSTER', shiftInfo, onManageShiftDone);
}
function onManageShiftDone(response) {
	if (response.success) {
		// if managing shift is succeeded, then do nothing
	} else {
		main_alert_message(response.msg);
	}
}








