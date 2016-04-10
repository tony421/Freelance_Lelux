var $btnAdd;
var $btnUpdate;
var $btnCancel;
var $txtName;
var $txtUsername;
var $txtPassword;
var $tableTherapist;
var $tableTherapistBody;
var _dtTableTherapist;
var _therapists;
var _editingTherapist;

function initPage()
{
	$btnAdd = $('#btnAdd');
	$btnUpdate = $('#btnUpdate');
	$btnCancel = $('#btnCancel');
	$txtName = $('#txtName');
	$txtUsername = $('#txtUsername');
	$txtPassword = $('#txtPassword');
	
	$tableTherapist = $('#tableTherapist');
	// keep instance of DataTable so that it will be used for row.add(), rows().remove() and others
	_dtTableTherapist = $tableTherapist.DataTable({
		paging: false,
		info: false,
		searching: false,
		ordering: false,
		rowId: 'therapist_id',
		columns: [
		    { data: "therapist_id", title: "ID", visible: false },
		    { data: "therapist_name", title: "Therapist Name" },
            { data: "therapist_username", title: "Therapist Username" }
        ]
	});
	
	$tableTherapistBody = $('#tableTherapist tbody');
	
	$btnAdd.click(function(){
		if (validateInputs()) {
			main_confirm_message('Do you want to add a new therapist?', addTherapist);
		}
	});
	
	$btnUpdate.click(function(){
		if (validateInputs()) {
			updateTherapist();
		}
	});

	$btnCancel.click(function(){
		turnOffEditMode();
	});
	
	getTherapists()
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
			therapist_username: result[i]['therapist_username'],
			therapist_password: result[i]['therapist_password']}).draw();
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
		if ($txtUsername.val().trim().length) {
			if ($txtPassword.val().trim().length) {
				return true;
			}
			else {
				main_alert_message('Please enter "Password"', function(){ $txtPassword.focus();});
			}
		}
		else {
			main_alert_message('Please enter "Username"', function(){ $txtUsername.focus();});
		}
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
		main_alert_message(response.msg);
}

function getTherapistInfo()
{
	var therapistInfo = {
			therapist_id: '',
			therapist_name: $txtName.val(),
			therapist_username: $txtUsername.val(),
			therapist_password: $txtPassword.val()
	};
	
	return therapistInfo;
}

function getEditedTherapistInfo()
{
	var therapistInfo = {
			therapist_id: _editingTherapist['therapist_id'],
			therapist_name: $txtName.val(),
			therapist_username: $txtUsername.val(),
			therapist_password: $txtPassword.val()
	};
	
	return therapistInfo;
}

function clearInputs()
{
	$txtName.val('');
	$txtUsername.val('');
	$txtPassword.val('');
	
	$txtName.focus();
}

function turnOnEditMode(therapistIndex)
{
	_editingTherapist = _therapists[therapistIndex];
	
	$btnAdd.addClass('hidden');
	$btnUpdate.removeClass('hidden');
	$btnCancel.removeClass('hidden');
	
	$txtName.val(_editingTherapist['therapist_name']);
	$txtUsername.val(_editingTherapist['therapist_username']);
	$txtPassword.val(_editingTherapist['therapist_password']);
	
	$txtName.focus();
	$('body').animate({ scrollTop: 0 }, 400);
}

function turnOffEditMode()
{
	$btnAdd.removeClass('hidden');
	$btnUpdate.addClass('hidden');
	$btnCancel.addClass('hidden');
	
	clearInputs();
}



