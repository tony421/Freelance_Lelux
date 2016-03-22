var _clientID;
var _clientInfo;

function initPage()
{
	_clientID = main_get_parameter('id'); 
	//alert(_clientID + ' | ' + _clientID.length);
	
	// *** Can use just _clientID to check empty value
	if (_clientID != null && _clientID.length > 0) {
		initElementVariables();
		getClientInfo(_clientID);
	}
	else {
		//alert('id is null or empty');
		main_redirect('../client/client-search.php');
	}
}

function getClientInfo(clientID)
{
	main_request_ajax('client-boundary.php', 'GET_CLIENT_INFO', clientID, onRequestDone);
}

function onRequestDone(response)
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










