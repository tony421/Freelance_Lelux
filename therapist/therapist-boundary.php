<?php
	require_once '../controller/Authentication.php';
	require_once '../controller/TherapistFunction.php';
	//require_once '../controller/Utilities.php';
	
	//Utilities::handleError(); // when an error happens you use this function to catch the error
		
	if (Authentication::userExists()) {
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
				else if ($mode == "GET_THERAPIST_WITH_UNKNOWN") {
					$result = $therapistFunction->getTherapistsWithUnknown();
				}
				else if ($mode == "GET_THERAPIST_OFF_SHIFT") {
					$selectedDate = $_POST['data'];
					$result = $therapistFunction->getTherapistsOffShift($selectedDate);
				}
				else if ($mode == "GET_THERAPIST_ON_SHIFT") {
					$selectedDate = $_POST['data'];
					$result = $therapistFunction->getTherapistsOnShift($selectedDate);
				}
				else if ($mode == "GET_THERAPIST_WORKING_ON_SHIFT") {
					$selectedDate = $_POST['data'];
					$result = $therapistFunction->getTherapistsWokringOnShift($selectedDate);
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
				else if ($mode == "GET_SHIFT_TYPE") {
					$result = $therapistFunction->getShiftType();
				}
				else if ($mode == "ADD_THERAPIST_TO_SHIFT") {
					$shiftInfo = $_POST['data'];
					Utilities::logInfo('Therapist-Boundary | data[$shiftInfo]: '.var_export($shiftInfo, true));
					
					$result = $therapistFunction->addTherapistToShift($shiftInfo);
				}
				else if ($mode == "UPDATE_THERAPIST_ON_SHIFT") {
					$shiftInfo = $_POST['data'];
					Utilities::logInfo('Therapist-Boundary | data[$editedShiftInfo]: '.var_export($shiftInfo, true));
						
					$result = $therapistFunction->updateTherapistOnShift($shiftInfo);
				}
				else if ($mode == "WORK_THERAPIST_ON_SHIFT") {
					$shiftInfo = $_POST['data'];
					$result = $therapistFunction->workTherapistOnShift($shiftInfo);
				}
				else if ($mode == "ABSENT_THERAPIST_ON_SHIFT") {
					$shiftInfo = $_POST['data'];
					$result = $therapistFunction->absentTherapistOnShift($shiftInfo);
				}
				else if ($mode == "DELETE_THERAPIST_ON_SHIFT") {
					$shiftInfo = $_POST['data'];
					$result = $therapistFunction->deleteTherapistOnShift($shiftInfo);
				}
				else if ($mode == "DELETE_ALL_THERAPIST_ON_SHIFT") {
					$date = $_POST['data'];
					$result = $therapistFunction->deleteAllTherapistOnShift($date);
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
	} else {
		$result = Utilities::getTimeoutResponseResult();
		
		echo json_encode($result);
	}
			
	exit;
?>