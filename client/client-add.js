var $btnAddClient;
var $ddlHealfund;
var $txtMemNo;
var $txtPatientID;
var $txtFirstName;
var $txtLastName;
var $radMale;
var $radFemale;
var $txtAddress;
var $txtPostcode;
var $txtEmail;
var $txtContactNo;
var $txtBirthday;
var $txtOccupation;
var $cbTrueLocal;
var $cbGoogle;
var $cbPassing;
var $cbWord;
var $cbFlyer;
var $cbFacebook;
var $cbGiftVoucher;
var $cbReferred;
var $txtReferred;
var $txtSports;
var $cbStroke;
var $txtStroke;
var $cbCancer;
var $txtCancer;
var $cbIsomnia;
var $txtIsomnia;
var $cbHeadache;
var $txtHeadache;
var $cbHeartCon;
var $txtHeartCon;
var $cbPain;
var $txtPain;
var $cbBloodPressure;
var $txtBloodPressure;
var $cbAllergy;
var $txtAllergy;
var $cbBrokenBone;
var $txtBrokenBone;
var $cbDisease;
var $txtDisease;
var $cbPregnancy;
var $txtPregnancy;
var $cbSoreBack;
var $txtSoreBack;
var $txtOtherCon;
var $txtEmerConName;
var $txtEmerConNo;

var findingElements = [
                       { 'id': '1', 'name': 'True Local', 'suffix': 'TrueLocal' },
                       { id: '2', name: 'Google', suffix: 'Google' },
                       { id: '3', name: 'Passing By', suffix: 'Passing' },
                       { id: '4', name: 'Word of Mouth', suffix: 'Word' },
                       { id: '5', name: 'Flyer', suffix: 'Flyer' },
                       { id: '6', name: 'Facebook', suffix: 'Facebook' },
                       { id: '7', name: 'GiftVoucher', suffix: 'GiftVoucher' },
                       { id: '8', name: 'Referred By', suffix: 'Referred', remark: true },
                       ];

var conditionElements = [
                         { id: '1', name: 'Stroke', suffix: 'Stroke' },
                         { id: '2', name: 'Cancer', suffix: 'Cancer' },
                         { id: '3', name: 'Isomnia', suffix: 'Isomnia' },
                         { id: '4', name: 'Headache', suffix: 'Headache' },
                         { id: '5', name: 'Heart Conditions', suffix: 'HeartCon' },
                         { id: '6', name: 'Pain/Stiffness', suffix: 'Pain' },
                         { id: '7', name: 'High/Low Blood Pressure', suffix: 'BloodPressure' },
                         { id: '8', name: 'Allergies/Asthma', suffix: 'Allergy' },
                         { id: '9', name: 'Borken/Dislocated Bones', suffix: 'BrokenBone' },
                         { id: '10', name: 'Contigious/Infectious Diseases', suffix: 'Disease' },
                         { id: '11', name: 'Pregnancy/Breastfeeding', suffix: 'Pregnancy' },
                         { id: '12', name: 'Sore Back', suffix: 'SoreBack' },
                         ];

function initPage()
{
	$btnAddClient = $('#btnAddClient');
	$ddlHealfund = $('#ddlHealfund');
	$txtMemNo = $('#txtMemNo');
	$txtPatientID = $('#txtPatientID');
	$txtFirstName = $('#txtFirstName');
	$txtLastName = $('#txtLastName');
	$radMale = $('#radMale');
	$radFemale = $('#radFemale');
	$txtAddress = $('#txtAddress');
	$txtPostcode = $('#txtPostcode');
	$txtEmail = $('#txtEmail');
	$txtContactNo = $('#txtContactNo');
	$txtBirthday = $('#txtBirthday');
	$txtOccupation = $('#txtOccupation');
	$cbTrueLocal = $('#cbTrueLocal');
	$cbGoogle = $('#cbGoogle');
	$cbPassing = $('#cbPassing');
	$cbWord = $('#cbWord');
	$cbFlyer = $('#cbFlyer');
	$cbFacebook = $('#cbFacebook');
	$cbGiftVoucher = $('#cbGiftVoucher');
	$cbReferred = $('#cbReferred');
	$txtReferred = $('#txtReferred');
	$txtSports = $('#txtSports');
	$cbStroke = $('#cbStroke');
	$txtStroke = $('#txtStroke');
	$cbCancer = $('#cbCancer');
	$txtCancer = $('#txtCancer');
	$cbIsomnia = $('#cbIsomnia');
	$txtIsomnia = $('#txtIsomnia');
	$cbHeadache = $('#cbHeadache');
	$txtHeadache = $('#txtHeadache');
	$cbHeartCon = $('#cbHeartCon');
	$txtHeartCon = $('#txtHeartCon');
	$cbPain = $('#cbPain');
	$txtPain = $('#txtPain');
	$cbBloodPressure = $('#cbBloodPressure');
	$txtBloodPressure = $('#txtBloodPressure');
	$cbAllergy = $('#cbAllergy');
	$txtAllergy = $('#txtAllergy');
	$cbBrokenBone = $('#cbBrokenBone');
	$txtBrokenBone = $('#txtBrokenBone');
	$cbDisease = $('#cbDisease');
	$txtDisease = $('#txtDisease');
	$cbPregnancy = $('#cbPregnancy');
	$txtPregnancy = $('#txtPregnancy');
	$cbSoreBack = $('#cbSoreBack');
	$txtSoreBack = $('#txtSoreBack');
	$txtOtherCon = $('#txtOtherCon');
	$txtEmerConName = $('#txtEmerConName');
	$txtEmerConNo = $('#txtEmerConNo');
	
	$btnAddClient.click(function(){
		var x = getClientInfo();
		//alert(x.client_gender + ' | ' + x.client_membership_no);
		
		var y = '';
		var find = getClientConditions();
		for (var i = 0; i < find.length; i++) {
			y += '[id: ' + find[i].id + ', checked: ' + find[i].checked + ', remark: ' + find[i].remark + '] \n ';	
		}
		alert(y);
	}); // btnAddClient.click
}

function getClientInfo()
{
	var clientInfo = {
			client_id: '',
			client_membership_no: $txtMemNo.val(),
			client_patient_id: $txtPatientID.val(),
			health_fund_id: $ddlHealfund.val(),
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
			client_condition_remark: ($('#txt' + conditionElements[i].suffix).length) ? $('#txt' + conditionElements[i].suffix).val() : ''
		});
	}
	
	return conditions;
}









