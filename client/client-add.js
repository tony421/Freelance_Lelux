var $btnAddClient;

function initPage()
{
	main_ajax_success_hide_loading();
	
	initElementVariables();
	
	$btnAddClient = $('#btnAddClient');
	$txtEmail.inputmask('email');
	$txtBirthday.inputmask('date');
	$txtContactNo.inputmask('9999-999-999');
	$txtEmerConNo.inputmask('9999-999-999');
	
	$txtPatientID.TouchSpin({
		verticalbuttons: true,
		min: 1,
		max: 9
	});
	
	$btnAddClient.click(function(){
		if (validateInputs()) {
			main_confirm_message('Do you want to add a new client?', addClient, function(){ $btnAddClient.focus(); });
		}
	}); // btnAddClient.click
}

function addClient()
{
	var clientInfo = getClientInfo();
	main_request_ajax('client-boundary.php', 'ADD_CLIENT', clientInfo, onRequestDone);
}

function onRequestDone(response)
{
	//alert(response);
	if (response.success) {
		main_info_message(response.msg, function(){
			main_redirect('../client/client-report.php?id=' + response.result);
		});
	}
	else
		main_alert_message(response.msg);
}

function getClientInfo()
{
	var clientInfo = {
			client_id: '',
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
			client_condition_remark: $('#txt' + conditionElements[i].suffix).val(),
			//client_condition_remark: ($('#txt' + conditionElements[i].suffix).length) ? $('#txt' + conditionElements[i].suffix).val() : ''
		});
	}
	
	return conditions;
}









