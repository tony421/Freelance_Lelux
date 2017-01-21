var _is_add_mode;
var _is_child;

var $btnAdd;
var $btnUpdate;
var $btnDelete;
var $btnCancel;
var $txtNo;
var $txtName;
var $tableProvider;
var $tableProviderBody;
var _dtTableProvider;
var _providers;
var _editingProvider;

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
	$txtNo = $('#txtNo');
	$txtName = $('#txtName');
	
	$tableProvider = $('#tableProvider');
	// keep instance of DataTable so that it will be used for row.add(), rows().remove() and others
	_dtTableProvider = $tableProvider.DataTable({
		paging: false,
		info: false,
		searching: false,
		ordering: false,
		rowId: 'provider_id',
		columns: [
		    { data: "provider_id", title: "ID", visible: false },
		    { data: "provider_no", title: "Provider No."},
		    { data: "provider_name", title: "Provider Name" }
        ]
	});
	
	$tableProviderBody = $('#tableProvider tbody');
	
	$btnAdd.click(function(){
		if (validateInputs()) {
			main_confirm_message('Do you want to add a new provider?', addProvider, function(){ $btnAdd.focus(); });
		}
	});
	
	$btnUpdate.click(function(){
		if (validateInputs()) {
			updateProvider();
		}
	});
	
	$btnDelete.click(function(){
		main_confirm_message('Do you want to DELETE the provider?', deleteProvider, function(){ $btnCancel.focus(); }, 1);
	});

	$btnCancel.click(function(){
		turnOffEditMode();
	});
	
	$txtNo.keypress(function(e){
		if (e.which == 13) {
			$txtName.focus();
			return false;
		}
	});
	
	$txtName.keypress(function(e){
		if (e.which == 13) {
			if (_is_add_mode)
				$btnAdd.click();
			else
				$btnUpdate.click();
			return false;
		}
	});
	
	getProviders();
}

function getProviders()
{
	main_request_ajax('provider-boundary.php', 'GET_PROVIDER', {}, onGetProviderDone);
}

function onGetProviderDone(response)
{
	if (response.success) {
		_providers = response.result;
		
		clearTableProvider();
		addProviderRows(_providers);
		setProviderRowSelection();
		
		clearInputs();
	}
}

function clearTableProvider()
{
	_dtTableProvider.rows().remove().draw();
	$tableProviderBody.unbind(); // unbind events to prevent duplicate events
}

function addProviderRows(result)
{
	for (var i = 0; i < result.length; i++) {
		_dtTableProvider.row.add({
			provider_id: result[i]['provider_id'],
			provider_no: result[i]['provider_no'],
			provider_name: result[i]['provider_name']}).draw();
	}
}

function setProviderRowSelection()
{
	$tableProviderBody.on('click', 'tr', function () {
        if ($(this).hasClass('selected')) {
            //$(this).removeClass('selected');
        }
        else {
        	_dtTableProvider.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
        }
    });
	
	$tableProviderBody.on('dblclick', 'tr', function () {
		turnOnEditMode(_dtTableProvider.row('.selected').index());
	});
}

function validateInputs()
{
	if ($txtNo.val().trim().length) {
		if ($txtName.val().trim().length) {
			return true;
		}
		else {
			main_alert_message('Please enter "Provider Name"', function(){ $txtName.focus();});
		}
	}
	else {
		main_alert_message('Please enter "Provider No."', function(){ $txtNo.focus();});
	}
	
	return false;
} // validateInputs

function addProvider()
{
	var providerInfo = getProviderInfo();
	main_request_ajax('provider-boundary.php', 'ADD_PROVIDER', providerInfo, onAddProviderDone);
}

function onAddProviderDone(response)
{
	if (response.success) {
		parentCallback(function() {
			main_info_message(response.msg, getProviders);
		});
	}
	else
		main_alert_message(response.msg);
}

function updateProvider()
{
	var providerInfo = getEditedProviderInfo();
	main_request_ajax('provider-boundary.php', 'UPDATE_PROVIDER', providerInfo, onUpdateProviderDone);
}

function onUpdateProviderDone(response)
{
	if (response.success) {
		parentCallback(function() {
			turnOffEditMode();
			main_info_message(response.msg, getProviders);
		});
	}
	else
		main_alert_message(response.msg);
}

function deleteProvider()
{
	var providerInfo = getEditedProviderInfo();
	main_request_ajax('provider-boundary.php', 'DELETE_PROVIDER', providerInfo, onDeleteProviderDone);
}

function onDeleteProviderDone(response)
{
	if (response.success) {
		parentCallback(function() {
			turnOffEditMode();
			main_info_message(response.msg, getProviders);
		});
	}
	else
		main_alert_message(response.msg);
}

function getProviderInfo()
{
	var providerInfo = {
			provider_id: '',
			provider_no: $txtNo.val(),
			provider_name: $txtName.val()
	};
	
	return providerInfo;
}

function getEditedProviderInfo()
{
	var providerInfo = {
			provider_id: _editingProvider['provider_id'],
			provider_no: $txtNo.val(),
			provider_name: $txtName.val()
	};
	
	return providerInfo;
}

function clearInputs()
{
	$txtNo.val('');
	$txtName.val('');
	
	$txtNo.focus();
}

function turnOnEditMode(providerIndex)
{
	_is_add_mode = false;
	
	_editingProvider = _providers[providerIndex];
	
	$btnAdd.addClass('hidden');
	$btnUpdate.removeClass('hidden');
	$btnDelete.removeClass('hidden');
	$btnCancel.removeClass('hidden');
	
	$txtNo.val(_editingProvider['provider_no']);
	$txtName.val(_editingProvider['provider_name']);
	
	main_move_to_title_text(function(){ $txtNo.focus(); });
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
	if (_is_child) {
		opener.parentCallback();
		self.close();
	}
	else {
		selfCallback();
	}
}





