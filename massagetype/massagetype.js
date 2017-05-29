var _is_add_mode;
var _is_child;

var $btnAdd;
var $btnUpdate;
var $btnDelete;
var $btnCancel;
var $txtComm;
var $txtName;
var $tableMassageType;
var $tableMassageTypeBody;
var _dtTableMassageType;
var _massageTypes;
var _editingMassageType;

function initPage()
{
	main_ajax_success_hide_loading();
	
	_is_add_mode = true;
	
	// is the page child window?
	_is_child = main_get_parameter('child');
	if (_is_child != null && _is_child.length > 0)
		_is_child = true;
	else
		_is_child = false;
	
	$btnAdd = $('#btnAdd');
	$btnUpdate = $('#btnUpdate');
	$btnDelete = $('#btnDelete');
	$btnCancel = $('#btnCancel');
	$txtComm = $('#txtComm');
	$txtName = $('#txtName');
	
	$txtComm.autoNumeric('init', { vMin: 0, vMax: 999, aSign: '$' });
	$txtComm.focus(function(){ $(this).select(); });
	
	$tableMassageType = $('#tableMassageType');
	// keep instance of DataTable so that it will be used for row.add(), rows().remove() and others
	_dtTableMassageType = $tableMassageType.DataTable({
		scrollY: _main_datatable_scroll_y,
		paging: false,
		info: false,
		searching: false,
		ordering: false,
		rowId: 'massage_type_id',
		columns: [
		    { data: "massage_type_id", title: "ID", visible: false },
		    { data: "massage_type_name", title: "Massage Type Name" },
		    { data: "massage_type_commission", title: "Extra Commission"}
        ]
	});
	
	$tableMassageTypeBody = $('#tableMassageType tbody');
	
	$btnAdd.click(function(){
		if (validateInputs()) {
			main_confirm_message('Do you want to add a new massage type?', addMassageType, function(){ $btnAdd.focus(); });
		}
	});
	
	$btnUpdate.click(function(){
		if (validateInputs()) {
			updateMassageType();
		}
	});
	
	$btnDelete.click(function(){
		main_confirm_message('Do you want to DELETE the massage type?', deleteMassageType, function(){ $btnCancel.focus(); }, 1);
	});

	$btnCancel.click(function(){
		turnOffEditMode();
	});
	
	$txtName.keypress(function(e){
		if (e.which == 13) {
			$txtComm.focus();
			return false;
		}
	});
	
	$txtComm.keypress(function(e){
		if (e.which == 13) {
			if (_is_add_mode)
				$btnAdd.click();
			else
				$btnUpdate.click();
			return false;
		}
	});
	
	getMassageTypes();
}

function getMassageTypes()
{
	main_request_ajax('massagetype-boundary.php', 'GET_MASSAGE_TYPE_DISPLAY', {}, onGetMassageTypeDone);
}

function onGetMassageTypeDone(response)
{
	if (response.success) {
		_massageTypes = response.result;
		
		clearTableMassageType();
		addMassageTypeRows(_massageTypes);
		setMassageTypeRowSelection();
		
		clearInputs();
	}
}

function clearTableMassageType()
{
	_dtTableMassageType.rows().remove().draw();
	$tableMassageTypeBody.unbind(); // unbind events to prevent duplicate events
}

function addMassageTypeRows(result)
{
	for (var i = 0; i < result.length; i++) {
		_dtTableMassageType.row.add({
			massage_type_id: result[i]['massage_type_id'],
			massage_type_name: result[i]['massage_type_name'],
			massage_type_commission: result[i]['massage_type_commission']}).draw();
	}
}

function setMassageTypeRowSelection()
{
	$tableMassageTypeBody.on('click', 'tr', function () {
        if ($(this).hasClass('selected')) {
            //$(this).removeClass('selected');
        }
        else {
        	_dtTableMassageType.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
        }
    });
	
	$tableMassageTypeBody.on('dblclick', 'tr', function () {
		turnOnEditMode(_dtTableMassageType.row('.selected').index());
	});
}

function validateInputs()
{
	if ($txtName.val().trim().length) {
		if ($txtComm.val().trim().replace(/\$/i, '').length) {
			return true;
		}
		else {
			main_alert_message('Please enter "Extra Commission"', function(){ $txtComm.focus();});
		}
	}
	else {
		main_alert_message('Please enter "Massage Type Name"', function(){ $txtName.focus();});
	}
	
	return false;
} // validateInputs

function addMassageType()
{
	var massageTypeInfo = getMassageTypeInfo();
	main_request_ajax('massageType-boundary.php', 'ADD_MASSAGE_TYPE', massageTypeInfo, onAddMassageTypeDone);
}

function onAddMassageTypeDone(response)
{
	if (response.success) {
		parentCallback(function() {
			main_info_message(response.msg, getMassageTypes);
		});
	}
	else
		main_alert_message(response.msg);
}

function updateMassageType()
{
	var massageTypeInfo = getEditedMassageTypeInfo();
	main_request_ajax('massageType-boundary.php', 'UPDATE_MASSAGE_TYPE', massageTypeInfo, onUpdateMassageTypeDone);
}

function onUpdateMassageTypeDone(response)
{
	if (response.success) {
		parentCallback(function() {
			turnOffEditMode();
			main_info_message(response.msg, getMassageTypes);
		});
	}
	else
		main_alert_message(response.msg);
}

function deleteMassageType()
{
	var massageTypeInfo = getEditedMassageTypeInfo();
	main_request_ajax('massageType-boundary.php', 'DELETE_MASSAGE_TYPE', massageTypeInfo, onDeleteMassageTypeDone);
}

function onDeleteMassageTypeDone(response)
{
	if (response.success) {
		parentCallback(function() {
			turnOffEditMode();
			main_info_message(response.msg, getMassageTypes);
		});
	}
	else
		main_alert_message(response.msg);
}

function getMassageTypeInfo()
{
	var massageTypeInfo = {
			massage_type_id: '',
			massage_type_commission: $txtComm.val().replace(/\$/i, ''),
			massage_type_name: $txtName.val()
	};
	
	return massageTypeInfo;
}

function getEditedMassageTypeInfo()
{
	var massageTypeInfo = {
			massage_type_id: _editingMassageType['massage_type_id'],
			massage_type_commission: $txtComm.val().replace(/\$/i, ''),
			massage_type_name: $txtName.val()
	};
	
	return massageTypeInfo;
}

function clearInputs()
{
	$txtComm.val('');
	$txtName.val('');
	
	$txtName.focus();
}

function turnOnEditMode(massageTypeIndex)
{
	_is_add_mode = false;
	
	_editingMassageType = _massageTypes[massageTypeIndex];
	
	$btnAdd.addClass('hidden');
	$btnUpdate.removeClass('hidden');
	$btnDelete.removeClass('hidden');
	$btnCancel.removeClass('hidden');
	
	$txtComm.val(_editingMassageType['massage_type_commission']);
	$txtName.val(_editingMassageType['massage_type_name']);
	
	main_move_to_title_text(function(){ $txtName.focus(); });
}

function turnOffEditMode()
{
	_is_add_mode = true;
	
	$btnAdd.removeClass('hidden');
	$btnUpdate.addClass('hidden');
	$btnDelete.addClass('hidden');
	$btnCancel.addClass('hidden');
	
	clearInputs();
}

function parentCallback(selfCallback)
{
	// "opener" will be NULL if there is no parent window.
	if (_is_child) {
		opener.parentCallback();
		self.close();
	}
	else {
		selfCallback();
	}
}





