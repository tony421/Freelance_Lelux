var _clientID;
var _clientInfo;
var _reports;
var _therapistOptions;

var $btnEditClient;
var $btnUpdateClient;
var $btnCancelEdit;
var $btnAddReport;

var $ddlReportTherapist, $txtReportDate, $ddlReportHour, $txtReportDetail, $txtReportRecom;
var $panelReportContainer;
var prefixPanelItem = '#panelItem';
var prefixBtnEditItem = '#btnEditItem';
var prefixBtnDeleteItem = '#btnDeleteItem';
var prefixBtnUpdateItem = '#btnUpdateItem';
var prefixBtnCancelItem = '#btnCancelItem';
var prefixItemTherapist = '#ddlItemTherapist';
var prefixItemHour = '#ddlItemHour';
var prefixItemDetail = '#txtItemDetail';
var prefixItemRecom = '#txtItemRecom';
var prefixItemUpdateUser = '#lblItemUpdateUser';
var prefixItemUpdateDatetime = '#lblItemUpdateDatetime';

var panelItemTemplate = "<div id=\"panelItem{0}\" class=\"panel panel-warning\"> <div class=\"panel-heading\"> <div class=\"row\"> <div class=\"col-sm-6\"> <div class=\"panel-title\"> <b>Report on</b> <span id=\"lblItemDate{0}\">{2}</span> </div> </div> <div class=\"col-sm-6 text-right\"> <button type=\"button\" id=\"btnEditItem{0}\" class=\"btn btn-info btn-xs\" name=\"{0}\">Edit</button> <button type=\"button\" id=\"btnDeleteItem{0}\" class=\"btn btn-danger btn-xs\" name=\"{0}\">Delete</button> <button type=\"button\" id=\"btnUpdateItem{0}\" class=\"btn btn-warning btn-xs\" name=\"{1}\">Update</button> <button type=\"button\" id=\"btnCancelItem{0}\" class=\"btn btn-default btn-xs\" name=\"{1}\">Cancel</button> </div> </div> </div> <div class=\"panel-body\"> <div class=\"form-group\"> <label class=\"col-sm-3 control-label\">Therapist</label> <div class=\"col-sm-3\"> <select id=\"ddlItemTherapist{0}\" class=\"form-control\" disabled> {9} </select> </div> <label class=\"col-sm-1 control-label\">Hours</label> <div class=\"col-sm-3\"> <select id=\"ddlItemHour{0}\" class=\"form-control\" disabled> <option value=\"30\">30 Min</option> <option value=\"45\">45 Min</option> <option value=\"60\" selected>1 Hr</option> <option value=\"75\">1 Hr 15 Min</option> <option value=\"90\">1 Hr 30 Min</option> <option value=\"105\">1 Hr 45 Min</option> <option value=\"120\">2 Hr</option> <option value=\"135\">2 Hr 15 Min</option> <option value=\"150\">2 Hr 30 Min</option> <option value=\"165\">2 Hr 45 Min</option> <option value=\"180\">3 Hr</option> <option value=\"195\">3 Hr 15 Min</option> <option value=\"210\">3 Hr 30 Min</option> <option value=\"225\">3 Hr 45 Min</option> <option value=\"240\">4 Hr</option> </select> </div> </div> <div class=\"form-group\"> <label class=\"col-sm-3 control-label\">Massage Details</label> <div class=\"col-sm-9\"> <textarea id=\"txtItemDetail{0}\" rows=\"2\" class=\"form-control\" readonly>{3}</textarea> </div> </div> <div class=\"form-group\"> <label class=\"col-sm-3 control-label\">Recommendation</label> <div class=\"col-sm-9\"> <textarea id=\"txtItemRecom{0}\" rows=\"2\" class=\"form-control\" readonly>{4}</textarea> </div> </div> </div> <div class=\"panel-footer\"> <small> <b>Created by:</b> <span id=\"lblItemCreateUser{0}\">{5}</span> <b>Created on:</b> <span id=\"lblItemCreateDatetime{0}\">{6}</span> <b>Updated by:</b> <span id=\"lblItemUpdateUser{0}\">{7}</span> <b>Updated on:</b> <span id=\"lblItemUpdateDatetime{0}\">{8}</span> </small> </div> </div>";

function initPage()
{
	_clientID = main_get_parameter('id');
	//alert(_clientID + ' | ' + _clientID.length);
	
	if (_clientID != null && _clientID.length > 0) {
		initElementVariables();
		$txtEmail.inputmask('email');
		$txtBirthday.inputmask('date');
		$txtContactNo.inputmask('9999-999-999');
		$txtEmerConNo.inputmask('9999-999-999');
		
		getClientInfo(_clientID);
		
		$btnEditClient = $('#btnEditClient');
		$btnUpdateClient = $('#btnUpdateClient');
		$btnCancelEdit = $('#btnCancelEdit');
		$btnAddReport = $('#btnAddReport');
		
		$panelReportContainer = $('#panelReportContainer');
		$ddlReportTherapist = $('#ddlReportTherapist');
		$txtReportDate = $('#txtReportDate');
		$ddlReportHour = $('#ddlReportHour');
		$txtReportDetail = $('#txtReportDetail');
		$txtReportRecom = $('#txtReportRecom');
		
		$txtReportDate.inputmask('date');
		$txtReportDate.val(main_convert_date_format(new Date())); // default ReportDate = today
		
		$btnEditClient.click(function(){
			setEditMode();
		});
		
		$btnUpdateClient.click(function(){
			if (validateInputs()) {
				main_confirm_message('Do you want to update client information?', updateClient);
			}
		});
		
		$btnCancelEdit.click(function(){
			cancelEditClient();
		});
		
		$btnAddReport.click(function(){
			if (validateReportInputs())
				main_confirm_message('Do you want to add a report?', addReport);
		});
	}
	else {
		// If clientID is null or empty, go back to search page
		main_redirect('../client/client-search.php');
	}
}

function initTherapists()
{
	main_request_ajax('client-boundary.php', 'GET_THERAPIST', {}, onGetTherapistsDone);
}

function onGetTherapistsDone(response)
{
	if (response.success) {
		_therapistOptions = [];
		therapists = response.result;

		$.each(therapists, function (i, therapist){
			option = "<option value='" + therapist['therapist_id'] + "'>" + therapist['therapist_name'] + "</option>";
			
			_therapistOptions.push(option);
			$ddlReportTherapist.append(option);
		});
		
		getReports();
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
		
		setClientInfo(_clientInfo);
		toggleHealthFundClinetInputs();
		
		initTherapists();
		//getReports();
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
	
	clientInfo[GENDER] == false ? $radMale.prop('checked', true) : $radFemale.prop('checked', true);
	
	setClientFindings(clientInfo['client_findings']);
	setClientConditions(clientInfo['client_conditions']);
}

function setClientFindings(clientFindings)
{
	for (var i = 0; i < clientFindings.length; i++) {
		//alert(clientFindings[i]['finding_type_suffix'] + ' | ' + clientFindings[i]['client_finding_checked']);
		if (clientFindings[i]['client_finding_checked'] == true || clientFindings[i]['client_finding_checked'] == 'true')
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
		if (clientConditions[i]['client_condition_checked'] == true || clientConditions[i]['client_condition_checked'] == 'true')
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
		// If updated successful, disable all inputs and update cache [_clientInfo]
		main_info_message(response.msg);
		setViewMode();
		
		_clientInfo = response.result;
	}
	else {
		// If updating client info failed, disable all inputs and reverse inputs' values to origin
		main_alert_message(response.msg);
		cancelEditClient();
	}
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
			finding_type_suffix: findingElements[i].suffix,
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
			condition_type_suffix: conditionElements[i].suffix,
			client_condition_checked: $('#cb' + conditionElements[i].suffix).is(':checked'),
			client_condition_remark: $('#txt' + conditionElements[i].suffix).val()
		});
	}
	
	return conditions;
}

function validateReportInputs()
{
	if ($txtReportDate.val().length) {
		if ($txtReportDate.inputmask("isComplete")) {
			if ($txtReportDetail.val().trim().length) {
				return true;
				/*
				if ($txtReportRecom.val().trim().length) {
					return true;
				}
				else {
					main_alert_message('Please enter "Report Recommendation"', function(){ $txtReportRecom.focus();});
				}
				*/
			}
			else {
				main_alert_message('Please enter "Report Massage Details"', function(){ $txtReportDetail.focus();});
			}
		}
		else {
			main_alert_message('Please enter a valid "Report Date"', function(){ $txtReportDate.focus();});
		}
	}
	else {
		main_alert_message('Please enter "Report Date"', function(){ $txtReportDate.focus();});
	}
	
	return false;
}

function clearReportInputs()
{
	$txtReportDate.val('');
	$ddlReportHour.val('60');
	$txtReportDetail.val('');
	$txtReportRecom.val('');
}

function addReport()
{
	reportInfo = getReportInfo();
	main_request_ajax('client-boundary.php', 'ADD_CLIENT_REPORT', reportInfo, onAddReportDone);
}

function onAddReportDone(response)
{
	if (response.success) {
		main_info_message(response.msg, getReports);
		clearReportInputs();
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

function onGetReportsDone(response)
{
	if (response.success) {
		_reports = response.result;
		$panelReportContainer.empty();
		
		for(var i = 0; i < _reports.length; i++) {
			reportID = _reports[i]['report_id'];
			
			$panelReportContainer.append(panelItemTemplate.format(
					reportID,
					i,
					_reports[i]['report_date'],
					_reports[i]['report_detail'],
					_reports[i]['report_recommendation'],
					_reports[i]['report_create_user'],
					_reports[i]['report_create_datetime'],
					_reports[i]['report_update_user'],
					_reports[i]['report_update_datetime'],
					_therapistOptions
				));
			
			setReportItemBtnEdit(reportID);
			setReportItemBtnUpdate(reportID);
			setReportItemBtnCancel(reportID);
			
			setReportItemViewMode(reportID);
			setReportItemTherapist(reportID, _reports[i]['therapist_id']);
			setReportItemHour(reportID, _reports[i]['report_hour']);
		}
	}
	else {
		//main_alert_message(response.msg);
	}
}

function setReportItemBtnEdit(reportID)
{
	$(prefixBtnEditItem + reportID).click(function(){
		// Don't need to find "report_id" agian because the value of [reportID] is already bound
		//reportItemID = $(this).prop('name');
		setReportItemEditMode(reportID);
	});
}

function setReportItemBtnUpdate(reportID)
{
	$(prefixBtnUpdateItem + reportID).click(function(){
		// Don't need to find "report_id" agian because the value of [reportID] is already bound
		//reportItemID = $(this).prop('name');
		setReportItemViewMode(reportID);
		
		reportItemIndex = $(this).prop('name');
		
		reportItemInfo = {
			report_item_index: reportItemIndex,
			report_id: reportID,
			report_hour: getReportItemHour(reportID),
			report_detail: getReportItemDetail(reportID),
			report_recommendation: getReportItemRecom(reportID),
			therapist_id: getReportItemTherapist(reportID)
		};
		
		updateReportItem(reportItemInfo);
	});
}

function updateReportItem(reportItemInfo)
{
	main_request_ajax('client-boundary.php', 'UPDATE_REPORT', reportItemInfo, onUpdateReportItem);
}

function onUpdateReportItem(response)
{
	reportItemInfo = response.result;
	
	if (response.success) {
		// If succeeded, update nwe values in cache [_report] at specific index
		main_info_message(response.msg);
		
		updatedReportID = reportItemInfo['report_id'];
		updatedReportItemIndex = reportItemInfo['report_item_index'];
		
		_reports[updatedReportItemIndex]['therapist_id'] = reportItemInfo['therapist_id'];
		_reports[updatedReportItemIndex]['report_hour'] = reportItemInfo['report_hour'];
		_reports[updatedReportItemIndex]['report_detail'] = reportItemInfo['report_detail'];
		_reports[updatedReportItemIndex]['report_recommendation'] = reportItemInfo['report_recommendation'];
		
		setReportItemUpdateUser(updatedReportID, reportItemInfo['report_update_user']);
		setReportItemUpdateDatetime(updatedReportID, reportItemInfo['report_update_datetime']);
	}
	else {
		// If cannot update, reverse inputs' values to original values
		main_alert_message(response.msg);
		reverseReportItem(reportItemInfo['report_id'], reportItemInfo['report_item_index']);
	}
}

function setReportItemBtnDelete(reportID)
{
}

function setReportItemBtnCancel(reportID)
{
	$(prefixBtnCancelItem + reportID).click(function(){
		setReportItemViewMode(reportID);
		
		reportItemIndex = $(this).prop('name');
		reverseReportItem(reportID, reportItemIndex);
	});
}

function setReportItemViewMode(reportID)
{
	$(prefixBtnEditItem + reportID).removeClass('hidden');
	$(prefixBtnDeleteItem + reportID).addClass('hidden');
	$(prefixBtnUpdateItem + reportID).addClass('hidden');
	$(prefixBtnCancelItem + reportID).addClass('hidden');
	
	$(prefixItemTherapist + reportID).prop('disabled', true);
	$(prefixItemHour + reportID).prop('disabled', true);
	$(prefixItemDetail + reportID).prop('readonly', true);
	$(prefixItemRecom + reportID).prop('readonly', true);
}

function setReportItemEditMode(reportID)
{
	$(prefixBtnEditItem + reportID).addClass('hidden');
	$(prefixBtnDeleteItem + reportID).addClass('hidden');
	$(prefixBtnUpdateItem + reportID).removeClass('hidden');
	$(prefixBtnCancelItem + reportID).removeClass('hidden');
	
	$(prefixItemTherapist + reportID).prop('disabled', '');
	$(prefixItemHour + reportID).prop('disabled', '');
	$(prefixItemDetail + reportID).prop('readonly', '');
	$(prefixItemRecom + reportID).prop('readonly', '');
}

function reverseReportItem(reportID, reportItemIndex)
{
	setReportItemTherapist(reportID, _reports[reportItemIndex]['therapist_id']);
	setReportItemHour(reportID, _reports[reportItemIndex]['report_hour']);
	setReportItemDetail(reportID, _reports[reportItemIndex]['report_detail']);
	setReportItemRecom(reportID, _reports[reportItemIndex]['report_recommendation']);
}

function setReportItemTherapist(reportID, therapistID)
{
	$(prefixItemTherapist + reportID).val(therapistID);
}

function getReportItemTherapist(reportID)
{
	return $(prefixItemTherapist + reportID).val();
}

function setReportItemHour(reportID, hour)
{
	$(prefixItemHour + reportID).val(hour);
}

function getReportItemHour(reportID)
{
	return $(prefixItemHour + reportID).val();
}

function setReportItemDetail(reportID, detail)
{
	$(prefixItemDetail + reportID).val(detail);
}

function getReportItemDetail(reportID)
{
	return $(prefixItemDetail + reportID).val();
}

function setReportItemRecom(reportID, recom)
{
	$(prefixItemRecom + reportID).val(recom);
}

function getReportItemRecom(reportID)
{
	return $(prefixItemRecom + reportID).val();
}

function setReportItemUpdateUser(reportID, user)
{
	$(prefixItemUpdateUser + reportID).text(user);
}

function setReportItemUpdateDatetime(reportID, datetime)
{
	$(prefixItemUpdateDatetime + reportID).text(datetime);
}













