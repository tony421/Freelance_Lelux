<?php
	require_once '../controller/Authentication.php';
	require_once '../controller/MassageTypeFunction.php';
	//require_once '../controller/Utilities.php';
	
	if (Authentication::userExists()) {
		if (!empty($_POST['mode'])) {
			$result;
		
			try {
				$mode = $_POST['mode'];
				Utilities::logInfo('MassageType-Boundary | mode: '.$mode);
					
				$assageTypeFunction = new MassageTypeFunction();
					
				if ($mode == "GET_MASSAGE_TYPE") {
					$result = $assageTypeFunction->getMassageTypes();
				}
				else if ($mode == "GET_MASSAGE_TYPE_DISPLAY") {
					$result = $assageTypeFunction->getMassageTypesDisplay();
				}
				else if ($mode == "ADD_MASSAGE_TYPE") {
					$assageTypeInfo = $_POST['data'];
					$result = $assageTypeFunction->addMassageType($assageTypeInfo);
				}
				else if ($mode == "UPDATE_MASSAGE_TYPE") {
					$assageTypeInfo = $_POST['data'];
					$result = $assageTypeFunction->updateMassageType($assageTypeInfo);
				}
				else if ($mode == "DELETE_MASSAGE_TYPE") {
					$assageTypeInfo = $_POST['data'];
					$result = $assageTypeFunction->deleteMassageType($assageTypeInfo);
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
			
			Utilities::logInfo('MassageType-Boundary | result: '.var_export($result, true));
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