<?php
	require_once '../controller/Authentication.php';
	require_once '../controller/HealthFundFunction.php';
	//require_once '../controller/Utilities.php';
	
	//Utilities::handleError(); // when an error happens you use this function to catch the error
	
	$result = '';
	
	try {
		if (Authentication::userExists()) {
			if (!empty($_POST['mode'])) {
				$mode = $_POST['mode'];
				Utilities::logInfo('HealthFund-Boundary | mode: '.$mode);
			
				$healthFundFunction = new HealthFundFunction();
			
				if ($mode == "GET_HEALTH_FUND") {
					$result = $healthFundFunction->getHealthFunds();
				}
				else {
					throw new Exception('Mode is not found!');
				}
			}
			else {
				throw new Exception('Mode is empty!');
			}
		} else {
			$result = Utilities::getTimeoutResponseResult();
		}
	}
	catch(Exception $e) {
		Utilities::logError(sprintf("Error Code: %s\nMessage: %s\nFile: %s\nLine: %s\nStack Trace:%s"
				, $e->getCode(), $e->getMessage(), $e->getFile(), $e->getLine(), var_export($e->getTrace(), true)));
			
		$result = Utilities::getResponseResult(false, 'System error occurred!, please contact admin.');
	}
	
	Utilities::logInfo('HealthFund-Boundary | result: '.var_export($result, true));
	echo json_encode($result);
			
	exit;
?>