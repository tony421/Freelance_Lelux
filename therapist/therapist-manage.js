var _is_add_mode;

var $btnAdd;
var $btnUpdate;
var $btnDelete;
var $btnCancel;
var $txtName;
var $txtUsername;
var $txtPassword;
var $tableTherapist;
var $tableTherapistBody;
//var $rowActiveInput;
var _dtTableTherapist;
var _therapists;
var _editingTherapist;

function initPage()
{
	main_ajax_success_hide_loading();
	
	_is_add_mode = true;
	
	$btnAdd = $('#btnAdd');
	$btnUpdate = $('#btnUpdate');
	$btnDelete = $('#btnDelete');
	$btnCancel = $('#btnCancel');
	$txtName = $('#txtName');
	//$txtUsername = $('#txtUsername');
	$txtPassword = $('#txtPassword');
	//$rowActiveInput = $('#rowActiveInput');
	//hideActiveInputs();
	
	$tableTherapist = $('#tableTherapist');
	// keep instance of DataTable so that it will be used for row.add(), rows().remove() and others
	_dtTableTherapist = $tableTherapist.DataTable({
		scrollY: _main_datatable_scroll_y,
		paging: false,
		info: false,
		searching: false,
		ordering: false,
		rowId: 'therapist_id',
		columns: [
		    { data: "therapist_id", title: "ID", visible: false },
		    { data: "therapist_name", title: "Therapist Name" },
            //{ data: "therapist_username", title: "Therapist Username", visible: false },
            //{ data: "therapist_active", title: "Currently Working", orderable: false, className: 'text-center'
		    	//, render: function ( data, type, row ) { return (data == 1) ? '<span class="glyphicon glyphicon-ok text-success"></span>' : '<span class="glyphicon glyphicon-remove text-danger"></span>' } }
        ]
	});
	
	$tableTherapistBody = $('#tableTherapist tbody');
	
	$btnAdd.click(function(){
		if (validateInputs()) {
			main_confirm_message('Do you want to add a new therapist?', addTherapist, function(){ $btnAdd.focus(); });
		}
	});
	
	$btnUpdate.click(function(){
		if (validateInputs()) {
			updateTherapist();
		}
	});
	
	$btnDelete.click(function(){
		main_confirm_message('Do you want to DELETE the selected therapist?', deleteTherapist, function(){ $btnCancel.focus(); }, 1);
	});

	$btnCancel.click(function(){
		turnOffEditMode();
	});
	
	$txtName.keypress(function(e){
		if (e.which == 13) {
			$txtPassword.focus();
			//$txtUsername.focus();
			return false;
		}
	});
	
	/*$txtUsername.keypress(function(e){
		if (e.which == 13) {
			$txtPassword.focus();
			return false;
		}
	});*/
	
	$txtPassword.keypress(function(e){
		if (e.which == 13) {
			if (_is_add_mode)
				$btnAdd.click();
			else
				$btnUpdate.click();
			return false;
		}
	});
	
	getTherapists();
}

function getTherapists()
{
	main_request_ajax('therapist-boundary.php', 'GET_THERAPIST_FOR_MANAGE', {}, onGetTherapistsDone);
}

function onGetTherapistsDone(response)
{
	if (response.success) {
		_therapists = response.result;
		
		clearTableTherapist();
		addTherapistRows(_therapists);
		setTherapistRowSelection();
		
		clearInputs();
	}
}

function clearTableTherapist()
{
	_dtTableTherapist.rows().remove().draw();
	$tableTherapistBody.unbind(); // unbind events to prevent duplicate events
}

function addTherapistRows(result)
{
	for (var i = 0; i < result.length; i++) {
		_dtTableTherapist.row.add({
			therapist_id: result[i]['therapist_id'],
			therapist_name: result[i]['therapist_name'],
			//therapist_username: result[i]['therapist_username'],
			therapist_password: result[i]['therapist_password']
			//therapist_active: result[i]['therapist_active']
		}).draw();
	}
}

function setTherapistRowSelection()
{
	$tableTherapistBody.on('click', 'tr', function () {
        if ($(this).hasClass('selected')) {
            //$(this).removeClass('selected');
        }
        else {
        	_dtTableTherapist.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
        }
    });
	
	$tableTherapistBody.on('dblclick', 'tr', function () {
		//alert(_dtTableTherapist.row('.selected').index());
		turnOnEditMode(_dtTableTherapist.row('.selected').index());
	});
}

function validateInputs()
{
	if ($txtName.val().trim().length) {
		//if ($txtUsername.val().trim().length) {
			if ($txtPassword.val().trim().length) {
				return true;
			}
			else {
				main_alert_message('Please enter "Password"', function(){ $txtPassword.focus();});
			}
		//}
		//else {
			//main_alert_message('Please enter "Username"', function(){ $txtUsername.focus();});
		//}
	}
	else {
		main_alert_message('Please enter "Name"', function(){ $txtName.focus();});
	}
	
	return false;
} // validateInputs

function addTherapist()
{
	var therapistInfo = getTherapistInfo();
	main_request_ajax('therapist-boundary.php', 'ADD_THERAPIST', therapistInfo, onAddTherapistDone);
}

function onAddTherapistDone(response)
{
	if (response.success) {
		main_info_message(response.msg, getTherapists);
	}
	else
		main_alert_message(response.msg);
}

function updateTherapist()
{
	var therapistInfo = getEditedTherapistInfo();
	main_request_ajax('therapist-boundary.php', 'UPDATE_THERAPIST', therapistInfo, onUpdateTherapistDone);
}

function onUpdateTherapistDone(response)
{
	if (response.success) {
		turnOffEditMode();
		main_info_message(response.msg, getTherapists);
	}
	else
		main_alert_message(response.msg, function() {$txtName.focus();});
}

function deleteTherapist()
{
	var therapistInfo = getEditedTherapistInfo();
	main_request_ajax('therapist-boundary.php', 'DELETE_THERAPIST', therapistInfo, onDeleteTherapistDone);
}

function onDeleteTherapistDone(response)
{
	if (response.success) {
		turnOffEditMode();
		main_info_message(response.msg, getTherapists);
	}
	else
		main_alert_message(response.msg);
}

function getTherapistInfo()
{
	var therapistInfo = {
			therapist_id: '',
			therapist_name: $txtName.val(),
			//therapist_username: $txtUsername.val(),
			therapist_password: $txtPassword.val()
	};
	
	return therapistInfo;
}

function getEditedTherapistInfo()
{
	var therapistInfo = {
			therapist_id: _editingTherapist['therapist_id'],
			therapist_name: $txtName.val(),
			//therapist_username: $txtUsername.val(),
			therapist_password: $txtPassword.val()
	};
	
	return therapistInfo;
}

function clearInputs()
{
	$txtName.val('');
	//$txtUsername.val('');
	$txtPassword.val('');
	
	$txtName.focus();
}

function turnOnEditMode(therapistIndex)
{
	_is_add_mode = false;
	
	_editingTherapist = _therapists[therapistIndex];
	
	$btnAdd.addClass('hidden');
	$btnUpdate.removeClass('hidden');
	$btnDelete.removeClass('hidden');
	$btnCancel.removeClass('hidden');
	
	$txtName.val(_editingTherapist['therapist_name']);
	//$txtUsername.val(_editingTherapist['therapist_username']);
	$txtPassword.val(_editingTherapist['therapist_password']);
	
	//showActiveInputs();
	main_move_to_title_text(230, function(){ $txtName.focus(); });
}

function turnOffEditMode()
{
	_is_add_mode = true;
	
	$btnAdd.removeClass('hidden');
	$btnUpdate.addClass('hidden');
	$btnDelete.addClass('hidden');
	$btnCancel.addClass('hidden');
	
	//hideActiveInputs();
	clearInputs();
}

function showActiveInputs()
{
	if ($rowActiveInput.hasClass('hidden'))
		$rowActiveInput.removeClass('hidden');
}

function hideActiveInputs()
{
	if (!($rowActiveInput.hasClass('hidden')))
		$rowActiveInput.addClass('hidden');
}

