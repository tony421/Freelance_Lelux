<?php
	require_once '../controller/MassageFunction.php';
	require_once '../controller/Utilities.php';
	
	Utilities::handleError(); // when an error happens you use this function to catch the error
	// test data
	//$_POST['mode'] = 'DELETE_RECORD';
	//$_POST['data'] = 14;
	
	if (!empty($_POST['mode']))
	{
		$result;
		
		try {
			$mode = $_POST['mode'];
			Utilities::logInfo('Massage-Boundary | mode: '.$mode);
				
			$massageFunction = new MassageFunction();
				
			if ($mode == 'GET_CONFIG') {
				$date = $_POST['data'];
				Utilities::logInfo('Massage-Boundary | data[Date]: '.var_export($date, true));
				
				$result = $massageFunction->getConfig($date);
			}
			else if ($mode == 'GET_RECORDS') {
				$date = $_POST['data'];
				Utilities::logInfo('Massage-Boundary | data[Date]: '.var_export($date, true));
				
				$result = $massageFunction->getRecords($date);
			}
			else if ($mode == 'ADD_RECORD') {
				$recordInfo = $_POST['data'];
				Utilities::logInfo('Massage-Boundary | data[recordInfo]: '.var_export($recordInfo, true));
			
				$result = $massageFunction->addRecord($recordInfo);
			}
			else if ($mode == 'UPDATE_RECORD') {
				$recordInfo = $_POST['data'];
				Utilities::logInfo('Massage-Boundary | data[recordInfo]: '.var_export($recordInfo, true));
					
				$result = $massageFunction->updateRecord($recordInfo);
			}
			else if ($mode == 'DELETE_RECORD') {
				$recordID = $_POST['data'];
				Utilities::logInfo('Massage-Boundary | data[recordID]: '.var_export($recordID, true));
					
				$result = $massageFunction->voidRecord($recordID);
			}
			else {
				echo json_encode('Mode not found');
			}
		}
		catch(Exception $e) {
			Utilities::logError(sprintf("Error Code: %s\nMessage: %s\nFile: %s\nLine: %s\nStack Trace:%s"
					, $e->getCode(), $e->getMessage(), $e->getFile(), $e->getLine(), var_export($e->getTrace(), true)));
		
			$result = Utilities::getResponseResult(false, 'System error occurred!, please contact admin.');
		}
		
		Utilities::logInfo('Massage-Boundary | result: '.var_export($result, true));
		echo json_encode($result);
		//echo $result;
	}
	else {
		//throw new Exception('Mode is empty');
		echo json_encode('Mode is empty');
	}
	
	exit;
?>





