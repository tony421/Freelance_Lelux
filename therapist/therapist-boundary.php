<?php
	require_once '../controller/TherapistFunction.php';
	require_once '../controller/Utilities.php';
	
	//Utilities::handleError(); // when an error happens you use this function to catch the error
		
	if (!empty($_POST['mode'])) {
		$result;
		
		try {
			$mode = $_POST['mode'];
			Utilities::logInfo('Therapist-Boundary | mode: '.$mode);
			
			$therapistFunction = new TherapistFunction();
			
			if ($mode == "GET_THERAPIST") {
				$result = $therapistFunction->getTherapists();
			}
			else if ($mode == "GET_THERAPIST_FOR_MANAGE") {
				$result = $therapistFunction->getTherapistsForManagement();
			}
			else if ($mode == "ADD_THERAPIST") {
				$therapistInfo = $_POST['data'];
				$result = $therapistFunction->addTherapist($therapistInfo);
			}
			else if ($mode == "UPDATE_THERAPIST") {
				$therapistInfo = $_POST['data'];
				$result = $therapistFunction->updateTherapist($therapistInfo);
			}
			else if ($mode == "DELETE_THERAPIST") {
				$therapistInfo = $_POST['data'];
				$result = $therapistFunction->deleteTherapist($therapistInfo);
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
		
		Utilities::logInfo('Therapist-Boundary | result: '.var_export($result, true));
		echo json_encode($result);
	}
	else {
		echo json_encode('Mode is empty');
	}
			
	exit;
?>