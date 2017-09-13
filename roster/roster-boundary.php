<?php
	require_once '../controller/Authentication.php';
	require_once '../controller/RosterFunction.php';
	//require_once '../controller/Utilities.php';
	
	//Utilities::handleError(); // when an error happens you use this function to catch the error
		
	if (Authentication::userExists()) {
		if (!empty($_POST['mode'])) {
			$result;
				
			try {
				$mode = $_POST['mode'];
				Utilities::logInfo('Roster-Boundary | mode: '.$mode);
				
				$rosterFunction = new RosterFunction();
				
				if ($mode == 'GET_ROSTER') {
					$days = $_POST['data'];
					Utilities::logInfo('Roster-Boundary | data[$days]: '.var_export($days, true));
					
					$result = $rosterFunction->getRoster($days);
				} else if ($mode == 'MANAGE_ROSTER') {
					$shiftInfo = $_POST['data'];
					Utilities::logInfo('Roster-Boundary | data[$shiftInfo]: '.var_export($shiftInfo, true));
					
					$result = $rosterFunction->manageRoster($shiftInfo);
				}
			} catch(Exception $e) {
				Utilities::logError(sprintf("Error Code: %s\nMessage: %s\nFile: %s\nLine: %s\nStack Trace:%s"
								, $e->getCode(), $e->getMessage(), $e->getFile(), $e->getLine(), var_export($e->getTrace(), true)));
				
				$result = Utilities::getResponseResult(false, 'System error occurred!, please contact admin.');
			}
			
			Utilities::logInfo('Roster-Boundary | result: '.var_export($result, true));
			echo json_encode($result);
		} else {
			echo json_encode('Mode is empty');
		}
	} else {
		$result = Utilities::getTimeoutResponseResult();
	
		echo json_encode($result);
	}
	
	exit;
?>





