<?php
	require_once '../controller/ReportFunction.php';
	require_once '../controller/Utilities.php';
	
	Utilities::handleError(); // when an error happens you use this function to catch the error
	
	if (!empty($_POST['mode'])) {
		$result;
	
		try {
			$mode = $_POST['mode'];
			Utilities::logInfo('Report-Boundary | mode: '.$mode);
				
			$reportFunction = new ReportFunction();
			
			if ($mode == 'GET_CLIENT_YEAR_OPTION') {
				$result = $reportFunction->getClientYearOption();
			}
			else {
				echo 'Report type is not found.';
			}
		}
		catch(Exception $e) {
			Utilities::logError(sprintf("Error Code: %s\nMessage: %s\nFile: %s\nLine: %s\nStack Trace:%s"
					, $e->getCode(), $e->getMessage(), $e->getFile(), $e->getLine(), var_export($e->getTrace(), true)));
		
			$result = Utilities::getResponseResult(false, 'System error occurred!, please contact admin.');
		}
		
		Utilities::logInfo('Report-Boundary | result: '.var_export($result, true));
		echo json_encode($result);
	}
	else {
		echo json_encode('Mode is empty');
	}
	
	exit;
?>