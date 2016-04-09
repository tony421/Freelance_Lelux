var $btnLogin;
var $txtUsername;
var $txtPassword;

function initPage()
{
	$btnLogin = $('#btnLogin');
	$txtUsername = $('#txtUsername');
	$txtPassword = $('#txtPassword');
	
	$btnLogin.click(function(){
		if (validateInputs()) {
			login();
		}
	});
	
	$txtUsername.focus();
} // initPage

function login(loginInfo)
{
	main_request_ajax('../authentication/authentication-boundary.php', 'LOG_IN', getLoginInfo(), onLoginDone);
}

function onLoginDone(response)
{
	if (response.success) {
		//main_info_message(response.msg);
		main_redirect('../client/client-add.php');
	}
	else
		main_alert_message(response.msg, function(){ $txtUsername.focus();});
}

function validateInputs()
{
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
	
	return false;
} // validateInputs

function getLoginInfo()
{
	var therapistInfo = {
			therapist_username: $txtUsername.val(),
			therapist_password: $txtPassword.val()
	};
	
	return therapistInfo;
} // getTherapistInfo
















