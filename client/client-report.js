var _clientID;
var _clientInfo;

var $btnEditClient;
var $btnUpdateClient;
var $btnCancelEdit;
var $btnAddReport;

var $ddlReportTherapist, $txtReportDate, $ddlReportHour, $txtReportDetail, $txtReportRecom;

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
		$btnCancelEdit = $('#btnCancelEdit');
		$btnAddReport = $('#btnAddReport');
		
		$ddlReportTherapist = $('#ddlReportTherapist');
		$txtReportDate = $('#txtReportDate');
		$ddlReportHour = $('#ddlReportHour');
		$txtReportDetail = $('#txtReportDetail');
		$txtReportRecom = $('#txtReportRecom');
		
		$txtReportDate.val(main_convert_date_format(new Date())); // default ReportDate = today
//		$txtReportHour.TouchSpin({
//		      verticalbuttons: true
//	    });
		
		$btnEditClient.click(function(){
			setEditMode();
			
		});
		
		$btnUpdateClient.click(function(){
			updateClient();
		});
		
		$btnCancelEdit.click(function(){
			cancelEditClient();
		});
		
		$btnAddReport.click(function(){
			main_confirm_message('Do you want to add a report?', addReport);
		});
		
		//test
		//
		$('#btn421ton').click(function(){
			//alert($('#ddlReportHour').prop('name'));
			
			var str = "hello \"{0}\", you are <id=\"{0}\">, I am <div>{1}</div>";
			var code = "<div id=\"panelItem\" class=\"panel panel-warning\"> <div class=\"panel-heading\"> 	<div class=\"row\"> 		<div class=\"col-sm-6\"> 			<div class=\"panel-title\"> 				<b>Report on</b> 				<span id=\"lblItemDate\">23/3/2016</span> 			</div> 		</div> 		<div class=\"col-sm-6 text-right\"> 			<button type=\"button\" id=\"btnEditItem\" class=\"btn btn-info btn-xs\" name=\"\">Edit</button> 			<button type=\"button\" id=\"btnDeleteItem\" class=\"btn btn-danger btn-xs\" name=\"\">Delete</button> 			<button type=\"button\" id=\"btnUpdateItem\" class=\"btn btn-warning btn-xs\" name=\"\">Update</button> 			<button type=\"button\" id=\"btnCancelItem\" class=\"btn btn-default btn-xs\" name=\"index\">Cancel</button> 		</div> 	</div> </div> <div class=\"panel-body\"> 		<div class=\"form-group\"> 			<label class=\"col-sm-3 control-label\">Therapist</label> 			<div class=\"col-sm-3\"> 				<select id=\"ddlItemTherapist\" class=\"form-control\" disabled> 					<option value=\"1\">A</option> 					<option value=\"2\">B</option> 					<option value=\"3\">C</option> 					<option value=\"4\">D</option> 					<option value=\"5\">E</option> 					<option value=\"6\">F</option> 				</select> 			</div> 			<label class=\"col-sm-1 control-label\">Hours</label> 			<div class=\"col-sm-3\"> 				<select id=\"ddlItemHour\" class=\"form-control\" disabled> 					<option value=\"30\">30 Min</option> 					<option value=\"45\">45 Min</option> 					<option value=\"60\" selected>1 Hr</option> 					<option value=\"75\">1 Hr 15 Min</option> 					<option value=\"90\">1 Hr 30 Min</option> 					<option value=\"105\">1 Hr 45 Min</option> 					<option value=\"120\">2 Hr</option> 					<option value=\"135\">2 Hr 15 Min</option> 					<option value=\"150\">2 Hr 30 Min</option> 					<option value=\"165\">2 Hr 45 Min</option> 					<option value=\"180\">3 Hr</option> 					<option value=\"195\">3 Hr 15 Min</option> 					<option value=\"210\">3 Hr 30 Min</option> 					<option value=\"225\">3 Hr 45 Min</option> 					<option value=\"240\">4 Hr</option> 				</select> 			</div> 		</div> 		<div class=\"form-group\"> 			<label class=\"col-sm-3 control-label\">Massage Details</label> 			<div class=\"col-sm-9\"> 				<textarea id=\"txtItemDetail\" rows=\"2\" class=\"form-control\" readonly></textarea> 			</div> 		</div> 		<div class=\"form-group\"> 			<label class=\"col-sm-3 control-label\">Recommendation</label> 			<div class=\"col-sm-9\"> 				<textarea id=\"txtItemRecom\" rows=\"2\" class=\"form-control\" readonly></textarea> 			</div> 		</div> </div> <div class=\"panel-footer\"> 	<small> 		<b>Created by:</b> 		<span id=\"lblItemCreateUser\">Default</span> 		<b>Created on:</b> 		<span id=\"lblItemCreateDateTime\">23/3/2016 13:33</span> 		<b>Updated by:</b> 		<span id=\"lblItemUpdateUser\">Default</span> 		<b>Updated on:</b> 		<span id=\"lblItemUpdateDateTime\">23/3/2016 15:33</span> 	</small> </div> </div>";
			//alert(str.format("Mike", "ton"));
			//alert(code);
			$('#panelReportContainer').append(code);
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
		else
			$('#cb' + clientFindings[i]['finding_type_suffix']).prop('checked', false);
		
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
		else
			$('#cb' + clientConditions[i]['condition_type_suffix']).prop('checked', false);
		
		$('#txt' + clientConditions[i]['condition_type_suffix']).val(clientConditions[i]['client_condition_remark']);
	}
}

function setEditMode()
{
	$btnUpdateClient.removeClass('hidden');
	$btnCancelEdit.removeClass('hidden');
	$btnEditClient.addClass('hidden');
	
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
	$btnEditClient.removeClass('hidden');
	$btnUpdateClient.addClass('hidden');
	$btnCancelEdit.addClass('hidden');
	
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
	if (response.success) {
		main_info_message(response.msg);
		
		setViewMode();
	}
	else
		main_alert_message(response.msg);
}

function cancelEditClient()
{
	setViewMode();
	setClientInfo(_clientInfo);
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

function addReport()
{
	reportInfo = getReportInfo();
	main_request_ajax('client-boundary.php', 'ADD_CLIENT_REPORT', reportInfo, onAddReportDone);
}

function onAddReportDone(response)
{
	if (response.success) {
		main_info_message(response.msg);
	}
	else
		main_alert_message(response.msg);
}

function getReportInfo()
{
	var reportInfo = {
			report_id: '',
			client_id: _clientID,
			therapist_id: $ddlReportTherapist.val(),
			report_date: $txtReportDate.val(),
			report_hour: $ddlReportHour.val(),
			report_detail: $txtReportDetail.val(),
			report_recommendation: $txtReportRecom.val(),
	};
	
	return reportInfo;
}

function getReports()
{
	main_request_ajax('client-boundary.php', 'GET_REPORTS', _clientID, onGetReportsDone);
	
}

function onGetReportsDone()
{
	alert('get reports success');
}













