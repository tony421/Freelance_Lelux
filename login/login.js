var $btnLogin;
var $txtUsername;
var $txtPassword;

function initPage()
{
	main_ajax_success_hide_loading();
	
	$btnLogin = $('#btnLogin');
	$txtUsername = $('#txtUsername');
	$txtPassword = $('#txtPassword');
	
	$txtUsername.keypress(function(e){
		if (e.which == 13) {
			$txtPassword.focus();
			return false;
		} 
	});
	
	$txtPassword.keypress(function(e){
		if (e.which == 13) {
			$btnLogin.click();
			return false;
		} 
	});
	
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
		permission = response.result;
		
		if (permission == 9 || permission == 7)
			main_redirect('../client/client-add.php');
		else
			main_redirect('../roster/roster.php');
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
















