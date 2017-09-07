<?php
	require_once '../controller/Authentication.php';
	require_once '../controller/Utilities.php';
	
	//Utilities::handleError(); // when an error happens you use this function to catch the error
	
	if (!empty($_POST['mode'])) {
		$result;
	
		try {
			$mode = $_POST['mode'];
			Utilities::logInfo('Authentication-Boundary | mode: '.$mode);
				
			$authentication = new Authentication();
				
			if ($mode == "LOG_IN") {
				$loginInfo = $_POST['data'];
				$result = $authentication->login($loginInfo);
			}
			else if ($mode == "LOG_OUT") {
				$result = $authentication->logout();
			}
			else if ($mode == "CHANGE_PASSWORD") {
				$passwordInfo = $_POST['data'];
				$result = $authentication->changePassword($passwordInfo);
			}
			else {
				throw new Exception('Mode not found');
			}
		}
		catch(Exception $e) {
			Utilities::logError(sprintf("Error Code: %s\nMessage: %s\nFile: %s\nLine: %s\nStack Trace:%s"
					, $e->getCode(), $e->getMessage(), $e->getFile(), $e->getLine(), var_export($e->getTrace(), true)));
				
			$result = Utilities::getResponseResult(false, 'System error occurred!, please contact admin.');
		}
	
		Utilities::logInfo('Authentication-Boundary | result: '.var_export($result, true));
		echo json_encode($result);
	}
	else {
		echo json_encode('Mode is empty');
	}
		
	exit;
?>