var _clientID;
var _clientInfo;

var $btnEditClient;
var $btnUpdateClient;

function initPage()
{
	_clientID = main_get_parameter('id');
	//alert(_clientID + ' | ' + _clientID.length);
	
	// *** Can use just _clientID to check empty value
	//
	if (_clientID != null && _clientID.length > 0) {
		initElementVariables();
		getClientInfo(_clientID);
		
		$btnEditClient = $('#btnEditClient');
		$btnUpdateClient = $('#btnUpdateClient');
		
		$btnEditClient.click(function(){
			setEditMode();
			$btnUpdateClient.removeClass('hidden');
			$btnEditClient.addClass('hidden');
			
		});
		
		$btnUpdateClient.click(function(){
			updateClient();
		});
	}
	else {
		//alert('id is null or empty');
		main_redirect('../client/client-search.php');
	}
}

function getClientInfo(clientID)
{
	main_request_ajax('client-boundary.php', 'GET_CLIENT_INFO', clientID, onGetClientInfoDone);
}

function onGetClientInfoDone(response)
{
	//alert(response);
	if (response.success) {
		_clientInfo = response.result;
		
		//alert(_clientInfo['client_conditions']);
		//alert(_clientInfo[ID] + ' | ' + _clientInfo[FIRST_NAME]);
		setClientInfo(_clientInfo);
	}
	else {
		main_alert_message(response.msg);
	}
}

function setClientInfo(clientInfo)
{
	$ddlHealthFund.val(clientInfo[HEALTH_FUND_ID]);
	$txtMemNo.val(clientInfo[MEMBERSHIP_NO]);
	$txtPatientID.val(clientInfo[PATIENT_ID]);
	$txtFirstName.val(clientInfo[FIRST_NAME]);
	$txtLastName.val(clientInfo[LAST_NAME]);
	
	// alert(clientInfo[GENDER]);
	clientInfo[GENDER] == false ? $radMale.prop('checked', true) : $radFemale.prop('checked', true);
	
	$txtAddress.val(clientInfo[ADDRESS]);
	$txtPostcode.val(clientInfo[POSTCODE]);
	$txtEmail.val(clientInfo[EMAIL]);
	$txtContactNo.val(clientInfo[CONTACT_NO]);
	$txtBirthday.val(clientInfo[BIRTHDAY]);
	$txtOccupation.val(clientInfo[OCCUPATION]);
	$txtSports.val(clientInfo[SPORTS]);
	$txtOtherCon.val(clientInfo[OTHER_CON]);
	$txtEmerConName.val(clientInfo[EMER_CON_NAME]);
	$txtEmerConNo.val(clientInfo[EMER_CON_NO]);	
	
	setClientFindings(clientInfo['client_findings']);
	setClientConditions(clientInfo['client_conditions']);
}

function setClientFindings(clientFindings)
{
	for (var i = 0; i < clientFindings.length; i++) {
		//alert(clientFindings[i]['finding_type_suffix']);
		if (clientFindings[i]['client_finding_checked'] == true)
			$('#cb' + clientFindings[i]['finding_type_suffix']).prop('checked', true);
		
		if ($('#txt' + clientFindings[i]['finding_type_suffix']).length)
			$('#txt' + clientFindings[i]['finding_type_suffix']).val(clientFindings[i]['client_finding_remark']);
	}
}

function setClientConditions(clientConditions)
{
	for (var i = 0; i < clientConditions.length; i++) {
		//alert(clientConditions[i]['condition_type_suffix']);
		if (clientConditions[i]['client_condition_checked'] == true)
			$('#cb' + clientConditions[i]['condition_type_suffix']).prop('checked', true);
		
		$('#txt' + clientConditions[i]['condition_type_suffix']).val(clientConditions[i]['client_condition_remark']);
	}
}

function setEditMode()
{
	//alert($txtFirstName.prop('readonly'));
	$txtFirstName.prop('readonly', '');
	$txtLastName.prop('readonly', '');
	$radMale.prop('disabled', '');
	$radFemale.prop('disabled', '');
	$txtAddress.prop('readonly', '');
	$txtPostcode.prop('readonly', '');
	$txtEmail.prop('readonly', '');
	$txtContactNo.prop('readonly', '');
	$txtBirthday.prop('readonly', '');
	$txtOccupation.prop('readonly', '');
	$txtSports.prop('readonly', '');
	$txtOtherCon.prop('readonly', '');
	$txtEmerConName.prop('readonly', '');
	$txtEmerConNo.prop('readonly', '');
	
	for (var i = 0; i < findingElements.length; i++) {
		$('#cb' + findingElements[i].suffix).prop('disabled', '');
		if ($('#txt' + findingElements[i].suffix).length)
			$('#txt' + findingElements[i].suffix).prop('readonly', '');
	}
	
	for (var i = 0; i < conditionElements.length; i++) {
		$('#cb' + conditionElements[i].suffix).prop('disabled', '');
		if ($('#txt' + conditionElements[i].suffix).length)
			$('#txt' + conditionElements[i].suffix).prop('readonly', '');
	}
}

function setViewMode()
{
	$txtFirstName.prop('readonly', 'true');
	$txtLastName.prop('readonly', 'true');
	$radMale.prop('disabled', 'true');
	$radFemale.prop('disabled', 'true');
	$txtAddress.prop('readonly', 'true');
	$txtPostcode.prop('readonly', 'true');
	$txtEmail.prop('readonly', 'true');
	$txtContactNo.prop('readonly', 'true');
	$txtBirthday.prop('readonly', 'true');
	$txtOccupation.prop('readonly', 'true');
	$txtSports.prop('readonly', 'true');
	$txtOtherCon.prop('readonly', 'true');
	$txtEmerConName.prop('readonly', 'true');
	$txtEmerConNo.prop('readonly', 'true');
	
	for (var i = 0; i < findingElements.length; i++) {
		$('#cb' + findingElements[i].suffix).prop('disabled', 'true');
		if ($('#txt' + findingElements[i].suffix).length)
			$('#txt' + findingElements[i].suffix).prop('readonly', 'true');
	}
	
	for (var i = 0; i < conditionElements.length; i++) {
		$('#cb' + conditionElements[i].suffix).prop('disabled', 'true');
		if ($('#txt' + conditionElements[i].suffix).length)
			$('#txt' + conditionElements[i].suffix).prop('readonly', 'true');
	}
}

function updateClient()
{
	editedClientInfo = getEditedClientInfo();
	main_request_ajax('client-boundary.php', 'UPDATE_CLIENT', editedClientInfo, onUpdateClientDone);
}

function onUpdateClientDone(response)
{
	//alert(response);
	if (response.success) {
		main_info_message(response.msg);
		
		setViewMode();
		$btnEditClient.removeClass('hidden');
		$btnUpdateClient.addClass('hidden');
	}
	else
		main_alert_message(response.msg);
}

function getEditedClientInfo()
{
	var clientInfo = {
			client_id: _clientID,
			client_membership_no: $txtMemNo.val(),
			client_patient_id: $txtPatientID.val(),
			health_fund_id: $ddlHealthFund.val(),
			client_first_name: $txtFirstName.val(),
			client_last_name: $txtLastName.val(),
			client_gender: ($radMale.is(':checked')) ? $radMale.val() : $radFemale.val(),
			client_address: $txtAddress.val(),
			client_postcode: $txtPostcode.val(), 
			client_email: $txtEmail.val(),
			client_contact_no: $txtContactNo.val(),
			client_birthday: $txtBirthday.val(),
			client_occupation: $txtOccupation.val(),
			client_sports: $txtSports.val(),
			client_other_conditions: $txtOtherCon.val(),
			client_emergency_contact_name: $txtEmerConName.val(),
			client_emergency_contact_no: $txtEmerConNo.val(),
			client_findings: getClientFindings(),
			client_conditions: getClientConditions()
	};
	
	return clientInfo;
}

function getClientFindings()
{
	var findings = [];
	
	// ***json does not work with for..in
	//
	for (var i = 0; i < findingElements.length; i++) {
		findings.push({
			finding_type_id: findingElements[i].id,
			client_finding_checked: $('#cb' + findingElements[i].suffix).is(':checked'),
			client_finding_remark: ($('#txt' + findingElements[i].suffix).length) ? $('#txt' + findingElements[i].suffix).val() : ''
		});
	}
	
	return findings;
}

function getClientConditions()
{
	var conditions = [];
	
	for (var i = 0; i < conditionElements.length; i++) {
		conditions.push({
			condition_type_id: conditionElements[i].id,
			client_condition_checked: $('#cb' + conditionElements[i].suffix).is(':checked'),
			client_condition_remark: $('#txt' + conditionElements[i].suffix).val()
		});
	}
	
	return conditions;
}






