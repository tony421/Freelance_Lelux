var _is_add_mode;
var _config_day_rate, _config_hour_rate, _config_late_night_rate, _config_com_sales, _config_com_rates
var _shop_income;
var _records, _editingRecord;
var _therapistOptions, _editModeReceptionOptions;

var $ddlReception, $cbLateNightWork, $radDay, $radHour;
var $txtHour, $txtIncome;
var $txtStdCom, $txtExtraCom, $txtTotalCom;
var $btnAdd, $btnUpdate, $btnDelete, $btnCancelEdit;
var $tableRecord, $tableRecordBody;
var dtTableRecord;

function initPage()
{	
	main_ajax_success_hide_loading();
	
	_is_add_mode = true;
	
	$ddlReception = $('#ddlReception');
	$cbLateNightWork = $('#cbLateNightWork');
	$radDay = $('#radDay');
	$radHour = $('#radHour');
	$txtHour = $('#txtHour');
	$txtIncome = $('#txtIncome');
	$txtStdCom = $('#txtStdCom');
	$txtExtraCom = $('#txtExtraCom');
	$txtTotalCom = $('#txtTotalCom');
	$btnAdd = $('#btnAdd');
	$btnUpdate = $('#btnUpdate');
	$btnDelete = $('#btnDelete');
	$btnCancelEdit = $('#btnCancelEdit');
	
	initTouchSpinInput($txtHour, 0, 24, 0, 1);
	initMoneyInput($txtIncome, 0, 99999.99);
	initMoneyInput($txtStdCom, 0, 99999.99);
	initMoneyInput($txtExtraCom, 0, 99999.99);
	initMoneyInput($txtTotalCom, 0, 99999.99);
	
	$cbLateNightWork.change(function(){
		//calLateNightCom();
		calExtraCom();
	});
	
	$radDay.change(function(){ 
		if ($(this).is(':checked'))
			main_disable_control($txtHour);
		
		calWholeDayCom();
	});
	
	$radHour.change(function(){ 
		if ($(this).is(':checked'))
			main_enable_control($txtHour);
		
		calHalfDayCom();
	});
	
	$txtHour.change(function(){
		calHalfDayCom();
	});
	
	$txtExtraCom.change(function(){
		calTotalCom();
	});
	
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
		parent.initDatepicker();
		setRecordRowsSelection();
		clearInputs();
	});
	
	initDataTable();
	turnOffEditMode();
	
	initTherapists();
}

function initDataTable()
{
	$tableRecord = $('#tableRecord');
	//keep instance of DataTable so that it will be used for row.add(), rows().remove() and others
	dtTableRecord = $tableRecord.DataTable({
		scrollY: _main_datatable_scroll_y,
		paging: false,
		info: false,
		searching: false,
		ordering: true,
		order: [[0, 'desc']], // default ordering - row_no:desc
		rowId: 'reception_record_id',
		columns: [
		          { data: 'row_no'},
		          { data: 'therapist_name', orderable: false, className: 'text-nowrap'
		        	  , render: function(data, type, row) {
		        		  if (row['reception_record_late_night'] == 1)
		        			  return data + ' <img src="../image/late_night.png" title="Work After 9.30 PM">';
		        		  else
		        			  return data;
		        	  }
		          },
		          {data: 'reception_record_whole_day', orderable: false, className: 'text-nowrap'
		        	  , render: function(data, type, row) {
		        		  if (data == 1) 
		        			  return 'Whole-Day Work';
		        		  else 
		        			  return 'Half-Day Work (' + row['reception_record_hour'] + ' hr)';
		        	  }
		          },
		          { data: 'reception_record_shop_income', orderable: false, className: 'text-nowrap'
		        	  , render: function(data, type, row) {
		        		  return '$' + data + ' (Updated: ' + row['reception_record_update_datetime'] + ')';
		        	  }
		          },
		          { data: 'reception_record_std_com', orderable: false
		        	  , render: function(data, type, row) {
		        		  return '$' + data;
		        	  }
		          },
		          { data: 'reception_record_extra_com', orderable: false
		        	  , render: function(data, type, row) {
		        		  return '$' + data;
		        	  }
		          },
		          { data: 'reception_record_total_com', orderable: false
		        	  , render: function(data, type, row) {
		        		  return '$' + data;
		        	  }
		          }
		          ]
	});
	$tableRecordBody = $('#tableRecord tbody');
}

function initTherapists()
{
	selectedDate = parent.getSelectedDailyRecordDate();
	main_request_ajax('reception-boundary.php', 'GET_RECEPTIONIST_ON_SHIFT', selectedDate, onInitTherapistsDone);
}

function onInitTherapistsDone(response)
{
	if (response.success) {
		_therapistOptions = response.result;
		
		bindTherapistOption(_therapistOptions);
		
		initConfig();
	}
	else {
		main_alert_message(response.msg);
	}
}

function bindTherapistOption(therapists)
{
	$ddlReception.empty();
	$.each(therapists, function (i, therapist){
		option = "<option value='" + therapist['therapist_id'] + "'>" + therapist['therapist_name'] + "</option>";
		
		$ddlReception.append(option);
	});
}

function initConfig()
{
	main_request_ajax('reception-boundary.php', 'GET_CONFIG', {}, onInitConfigDone);
}

function onInitConfigDone(response)
{
	if (response.success) {
		_config_day_rate = response.result['day_rate'];
		_config_hour_rate = response.result['hour_rate'];
		_config_late_night_rate = response.result['late_night_rate'];
		_config_com_sales = response.result['com_sales'];
		_config_com_rates = response.result['com_rates'];
		
		calWholeDayCom(); // when the page just initialized, "Whole Day Working" is seleced.
		getShopIncome();
	}
	else {
		main_alert_message(response.msg);
	}
}

function getShopIncome()
{
	main_request_ajax('reception-boundary.php', 'GET_SHOP_INCOME', parent.getSelectedDailyRecordDate(), onGetShopIncome);
}

function onGetShopIncome(response)
{
	if (response.success) {
		_shop_income = response.result['shop_income'];
		if (_shop_income == null)
			_shop_income = 0;
		
		setMoneyInputValue($txtIncome, _shop_income);
		
		calExtraCom();
		getRecords();
	}
	else {
		main_alert_message(response.msg);
	}
}

function calWholeDayCom()
{
	setMoneyInputValue($txtStdCom, _config_day_rate);
	calTotalCom();
}

function calHalfDayCom()
{
	hourCom = _config_hour_rate * parseFloat(getTouchSpinInputValue($txtHour));
	setMoneyInputValue($txtStdCom, hourCom);
	calTotalCom();
}

function calLateNightCom(extraCom)
{
	if($cbLateNightWork.is(':checked')) {
		//extraCom = parseFloat(_config_late_night_rate) + parseFloat(getMoneyInputValue($txtExtraCom));
		extraCom += parseFloat(_config_late_night_rate);
		setMoneyInputValue($txtExtraCom, extraCom);
	}
	
	calTotalCom();
}

function calExtraCom()
{
	saleLevelIndex = 0;
	for(var i = 0; i < _config_com_sales.length; i++) {
		//alert(_shop_income + ' < ' + _config_com_sales[i]);
		if (_shop_income < _config_com_sales[i]) {
			saleLevelIndex = i;
			break;
		}
	}
	
	extraCom = _config_com_rates[saleLevelIndex];
	setMoneyInputValue($txtExtraCom, extraCom);
	
	calLateNightCom(extraCom);
}

function calTotalCom()
{
	totalCom = parseFloat(getMoneyInputValue($txtStdCom)) + parseFloat(getMoneyInputValue($txtExtraCom));
	setMoneyInputValue($txtTotalCom, totalCom);
}

function clearInputs()
{
	$cbLateNightWork.prop('checked', false)
	$radDay.prop('checked', true)
	main_disable_control($txtHour);
	setTouchSpinInputValue($txtHour, 0);
	setMoneyInputValue($txtIncome, _shop_income);
	
	//bindTherapistOption(_therapistOptions);
	//bindMassageTypeOption(_massageTypeOptions);
	
	calWholeDayCom();
	calExtraCom();
}

//will be called by PARENT
function clearFrameEditMode()
{
	//alert('CLEAR - RECEPTION');
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
	//alert("UPDATE - RECEPTION");
	initTherapists();
	getShopIncome();
	getRecords();
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

function validateRecordInfo()
{
	if ($radHour.is(':checked')) {
		if (parseInt(getTouchSpinInputValue($txtHour)) > 0) {
			return 1;
		} else {
			main_alert_message('Please enter amount of "Hour"!', function(){ $txtHour.focus();});
		}
	} else {
		return 1;
	}
}

function getRecordInfo(recordID)
{
	var recordInfo = {
		'reception_record_id': typeof(recordID) === 'undefined' ? 0 : recordID,
		'reception_record_date': parent.getSelectedDailyRecordDate(),
		'therapist_id': $ddlReception.val(),
		'reception_record_late_night': $cbLateNightWork.is(':checked'),
		'reception_record_whole_day': $radDay.is(':checked'),
		'reception_record_hour': ($radDay.is(':checked')) ? 0 : getTouchSpinInputValue($txtHour),
		'reception_record_shop_income': getMoneyInputValue($txtIncome),
		'reception_record_std_com': getMoneyInputValue($txtStdCom),
		'reception_record_extra_com': getMoneyInputValue($txtExtraCom),
		'reception_record_total_com': getMoneyInputValue($txtTotalCom),
	}
	
	return recordInfo;
}

function addRecord()
{
	if (validateRecordInfo()) {
		recordInfo = getRecordInfo();
		main_confirm_message('Do you want to add a reception record?'
				, function(){ main_request_ajax('reception-boundary.php', 'ADD_RECORD', recordInfo, onAddRecordDone); }
				, function(){ $btnAdd.focus(); }
		);
	}
}

function onAddRecordDone(response)
{
	if (response.success) {
		clearInputs();
		main_info_message(response.msg, getRecords);
	}
	else {
		main_alert_message(response.msg);
	}
}

function getRecords()
{
	date = parent.getSelectedDailyRecordDate(); // use getDate function of the parent
	main_request_ajax('reception-boundary.php', 'GET_RECORDS', date, onGetRecordsDone);
}

function onGetRecordsDone(response)
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

function addRecordRows(result)
{
	for (var i = 0; i < result.length; i++) {
		dtTableRecord.row.add({
			reception_record_id: result[i]['reception_record_id'],
			row_no: result[i]['row_no'],
			therapist_name: result[i]['therapist_name'],
			reception_record_date: result[i]['reception_record_date'],
			reception_record_late_night: result[i]['reception_record_late_night'],
			reception_record_whole_day: result[i]['reception_record_whole_day'],
			reception_record_hour: result[i]['reception_record_hour'],
			reception_record_shop_income: result[i]['reception_record_shop_income'],
			reception_record_std_com: result[i]['reception_record_std_com'],
			reception_record_extra_com: result[i]['reception_record_extra_com'],
			reception_record_total_com: result[i]['reception_record_total_com'],
			reception_record_update_datetime: result[i]['reception_record_update_datetime'],
		}).draw();
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

function clearTableRecord()
{
	dtTableRecord.rows().remove().draw();
	unbindRecordRowsSelection();
}

function unbindRecordRowsSelection() 
{
	$tableRecordBody.unbind(); // unbind events to prevent duplicate events
}

function setEditingRecord(recordIndex)
{
	// get editing record 
	_editingRecord = _records[recordIndex];
	
	parent.destroyDatepicker(); // users cannot change the date during editing the item
	
	setEditModeReceptionOption();
	$ddlReception.val(_editingRecord['therapist_id']);

	if (_editingRecord['reception_record_late_night'] == true) {
		$cbLateNightWork.prop('checked', true);
	} else {
		$cbLateNightWork.prop('checked', false);
	} 
	
	if (_editingRecord['reception_record_whole_day'] == true) {
		$radDay.prop('checked', true);
		main_disable_control($txtHour);
		setTouchSpinInputValue($txtHour, 0);
	}
	else { 
		$radHour.prop('checked', true);
		main_enable_control($txtHour);
		setTouchSpinInputValue($txtHour, _editingRecord['reception_record_hour']);
	}
	
	setMoneyInputValue($txtStdCom, _editingRecord['reception_record_std_com']);
	setMoneyInputValue($txtExtraCom, _editingRecord['reception_record_extra_com']);
	setMoneyInputValue($txtTotalCom, _editingRecord['reception_record_total_com']);
	
	//getShopIncome();
	calExtraCom();
	
	unbindRecordRowsSelection(); // users cannot select a row in datatable during editing the item
	main_move_to_title_text(450);
}

function setEditModeReceptionOption()
{
	_editModeReceptionOptions = _therapistOptions.slice(0);
	if (_editingRecord['therapist_active'] == 0) {
		_editModeReceptionOptions.push(getDeletedTherapist());
				
		bindTherapistOption(_editModeReceptionOptions);
	}
}

function getDeletedTherapist()
{
	deletedItem = { 
		therapist_id: _editingRecord['therapist_id']
		, therapist_name: _editingRecord['therapist_name'] + " (Deleted)"
		, therapist_active: _editingRecord['therapist_active']};
		
	return deletedItem;
}

function updateRecord()
{
	if (validateRecordInfo()) {
		//Need to initialize datepicker first so that can use its method 'getDate' in 'getRecordInfo'
		// otherwise datepicker will work incorrectly after 'getDate' called
		parent.initDatepicker();
		
		recordInfo = getRecordInfo(_editingRecord['reception_record_id']);
		turnOffEditMode();
		clearInputs();
		
		main_request_ajax('reception-boundary.php', 'UPDATE_RECORD', recordInfo, onUpdateRecordDone);
	}
}

function onUpdateRecordDone(response)
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
	main_confirm_message('Do you want to DELETE the record?', function() {
		parent.initDatepicker();
		recordID = _editingRecord['reception_record_id'];
		turnOffEditMode();
		clearInputs();
		
		main_request_ajax('reception-boundary.php', 'DELETE_RECORD', recordID, onDeleteRecordDone);
	}, function(){
		$btnDelete.focus();
	}, 1);
}

function onDeleteRecordDone(response)
{
	if (response.success) {
		main_info_message(response.msg, getRecords);
	}
	else {
		setRecordRowsSelection();
		main_alert_message(response.msg);
	}
}






