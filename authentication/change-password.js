var $btnChangePassword;
var $txtOldPassword;
var $txtNewPassword;
var $txtNewPasswordConfirm;

function initPage()
{
	main_ajax_success_hide_loading();
	
	$btnChangePassword = $('#btnChangePassword');
	$txtOldPassword = $('#txtOldPassword');
	$txtNewPassword = $('#txtNewPassword');
	$txtNewPasswordConfirm = $('#txtNewPasswordConfirm');
	
	$txtOldPassword.keypress(function(e){
		if (e.which == 13) {
			$txtNewPassword.focus();
			return false;
		} 
	});
	
	$txtNewPassword.keypress(function(e){
		if (e.which == 13) {
			$txtNewPasswordConfirm.focus();
			return false;
		} 
	});
	
	$txtNewPasswordConfirm.keypress(function(e){
		if (e.which == 13) {
			$btnChangePassword.click();
			return false;
		} 
	});
	
	$btnChangePassword.click(function(){
		if (validateInputs()) {
			changePassword();
		}
	});
	
	$txtOldPassword.focus();
} // initPage

function changePassword()
{
	main_request_ajax('../authentication/authentication-boundary.php', 'CHANGE_PASSWORD', getPasswordInfo(), onChangePasswordDone);
} // changePassword

function onChangePasswordDone(response)
{
	if (response.success) {
		main_info_message(response.msg, main_log_out);
	}
	else
		main_alert_message(response.msg, function(){ $txtOldPassword.focus();});
}

function getPasswordInfo()
{
	$passwordInfo = {
		therapist_old_password: $txtOldPassword.val().trim()
		, therapist_new_password: $txtNewPassword.val().trim()
	};
	
	return $passwordInfo;
} // getPasswordInfo

function validateInputs()
{
	if ($txtOldPassword.val().trim().length) {
		if ($txtNewPassword.val().trim().length) {
			if ($txtNewPasswordConfirm.val().trim().length) {
				if ($txtNewPassword.val().trim() == $txtNewPasswordConfirm.val().trim()) {
					return true;
				}
				else {
					main_alert_message('The confirm password does not match!', function(){ $txtNewPasswordConfirm.focus();});
				}
			}
			else {
				main_alert_message('Please enter "New Password (Confirm)"', function(){ $txtNewPasswordConfirm.focus();});
			}
		}
		else {
			main_alert_message('Please enter "New Password"', function(){ $txtNewPassword.focus();});
		}
	}
	else {
		main_alert_message('Please enter "Old Password"', function(){ $txtOldPassword.focus();});
	}
	
	return false;
} // validateInputs






