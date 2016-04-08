<?php
	require_once '../controller/TherapistFunction.php';
	require_once '../controller/Utilities.php';
	
	if (!empty($_POST['mode'])) {
		$result;
		
		try {
			$mode = $_POST['mode'];
			Utilities::logInfo('Therapist-Boundary | mode: '.$mode);
			
			$therapistFunction = new TherapistFunction();
			
			if ($mode == "GET_THERAPIST") {
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
			else {
				throw new Exception('Mode not found');
			}
		}
		catch(Exception $e) {
			$result = array('msg' => $e->getMessage(),'code' => $e->getCode());
		}
		
		Utilities::logInfo('Therapist-Boundary | result: '.var_export($result, true));
		echo json_encode($result);
	}
	else {
		echo json_encode('Mode is empty');
	}
			
	exit;
?>