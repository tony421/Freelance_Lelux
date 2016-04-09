var ID = 'client_id';
var HEALTH_FUND_ID = 'health_fund_id';
var MEMBERSHIP_NO = 'client_membership_no';
var PATIENT_ID = 'client_patient_id';
var FIRST_NAME = 'client_first_name';
var LAST_NAME = 'client_last_name';
var GENDER = 'client_gender';
var ADDRESS = 'client_address';
var POSTCODE = 'client_postcode';
var EMAIL = 'client_email';
var CONTACT_NO = 'client_contact_no';
var BIRTHDAY = 'client_birthday';
var OCCUPATION = 'client_occupation';
var SPORTS = 'client_sports';
var OTHER_CON = 'client_other_conditions';
var EMER_CON_NAME = 'client_emergency_contact_name';
var EMER_CON_NO = 'client_emergency_contact_no';
var CREATE_DATETIME = 'client_create_datetime';
var CREATE_USER = 'client_create_user';
var UPDATE_DATETIME = 'client_update_datetime';
var UPDATE_USER = 'client_update_user';
var CREATE_DATETIME = 'client_void_datetime';
var CREATE_USER = 'client_void_user';

var $ddlHealthFund;
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
var $rowHealthFundInput;

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

function initElementVariables()
{
	$ddlHealthFund = $('#ddlHealthFund');
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
	$rowHealthFundInput = $('#rowHealthFundInput');
	
	initHealthFunds();
	
	$ddlHealthFund.change(function(){
		toggleHealthFundClinetInputs();
	});
}

function initHealthFunds()
{
	main_request_ajax('../healthfund/healthfund-boundary.php', 'GET_HEALTH_FUND', {}, onInitHealthFunds);
}

function onInitHealthFunds(response)
{
	if (response.success) {
		healthfunds = response.result;

		$.each(healthfunds, function (i, healthfund){
			option = "<option value='" + healthfund['health_fund_id'] + "'>" + healthfund['health_fund_name'] + "</option>";
			
			$ddlHealthFund.append(option);
		});
	}
} // onInitHealthFunds

function validateInputs()
{
	isHealthFund = isHealthFundClient();
	
	if ((isHealthFund && $txtMemNo.val().trim().length) || !isHealthFund) {
		if (isHealthFund && $txtPatientID.val().trim().length || !isHealthFund) {
			if ($txtFirstName.val().trim().length) {
				if ($txtLastName.val().trim().length) {
					if ($txtEmail.inputmask("isComplete") || $txtEmail.val() == "") {
						if ($txtContactNo.inputmask("isComplete") || $txtContactNo.val() == "") {
							if ($txtBirthday.inputmask("isComplete") || $txtBirthday.val() == "") {
								if ($txtEmerConNo.inputmask("isComplete") || $txtEmerConNo.val() == "") {
									return true;
								}
								else {
									main_alert_message('Please enter a valid "Emergency Contact : Phone No."', function(){ $txtEmerConNo.focus();});
								}
							}
							else {
								main_alert_message('Please enter a valid date "Date of Birth"', function(){ $txtBirthday.focus();});
							}
						}
						else {
							main_alert_message('Please enter a valid "Contact No."', function(){ $txtContactNo.focus();});
						}
					}
					else {
						main_alert_message('Please enter a valid "Email"', function(){ $txtEmail.focus();});
					}
				}
				else {
					main_alert_message('Please enter "Last Name"', function(){ $txtLastName.focus();});
				}
			}
			else {
				main_alert_message('Please enter "First Name"', function(){ $txtFirstName.focus();});
			}
		}
		else {
			main_alert_message('Please enter "PatientID"', function(){ $txtPatientID.focus();});
		}
	}
	else {
		main_alert_message('Please enter "Membership No."', function(){ $txtMemNo.focus();});
	}
	
	return false;
}

function isHealthFundClient()
{
	if ($ddlHealthFund.val() == 0)
		return false;
	else
		return true;
}

function showHealthFundClinetInputs()
{
	if ($rowHealthFundInput.hasClass('hidden'))
		$rowHealthFundInput.removeClass('hidden');
}

function hideHealthFundClinetInputs()
{
	if (!($rowHealthFundInput.hasClass('hidden')))
		$rowHealthFundInput.addClass('hidden');
}

function toggleHealthFundClinetInputs()
{
	if (isHealthFundClient()) {
		// If client use Health Fund, show "Membership No." and "PatientID"
		showHealthFundClinetInputs();
	}
	else {
		// If not, hide "Membership No." and "PatientID"
		hideHealthFundClinetInputs();
	}
}






