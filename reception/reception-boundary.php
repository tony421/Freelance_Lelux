<?php
	require_once '../controller/ReceptionFunction.php';
	require_once '../controller/ReportFunction.php';
	require_once '../controller/Utilities.php';
	
	Utilities::handleError(); // when an error happens you use this function to catch the error
	
	if (!empty($_POST['mode']))
	{
		$result;
	
		try {
			$mode = $_POST['mode'];
			Utilities::logInfo('Reception-Boundary | mode: '.$mode);
	
			$receptionFunction = new ReceptionFunction();
	
			if ($mode == 'GET_CONFIG') {	
				$result = $receptionFunction->getConfig();
			}
			else if ($mode == 'GET_SHOP_INCOME') {
				$date = $_POST['data'];
				$reportFunction = new ReportFunction();
				$result = $reportFunction->getDailyIncomeSummary($date);
			}
			else if ($mode == 'GET_RECORDS') {
				$date = $_POST['data'];
				Utilities::logInfo('Reception-Boundary | data[date]: '.var_export($date, true));
				
				$result = $receptionFunction->getRecords($date);
			}
			else if ($mode == 'ADD_RECORD') {
				$recordInfo = $_POST['data'];
				Utilities::logInfo('Reception-Boundary | data[recordInfo]: '.var_export($recordInfo, true));
					
				$result = $receptionFunction->addRecord($recordInfo);
			}
			else if ($mode == 'UPDATE_RECORD') {
				$recordInfo = $_POST['data'];
				Utilities::logInfo('Reception-Boundary | data[recordInfo]: '.var_export($recordInfo, true));
					
				$result = $receptionFunction->updateRecord($recordInfo);
			}
			else if ($mode == 'DELETE_RECORD') {
				$recordID = $_POST['data'];
				Utilities::logInfo('Reception-Boundary | data[recordID]: '.var_export($recordID, true));
					
				$result = $receptionFunction->voidRecord($recordID);
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
		
		Utilities::logInfo('Reception-Boundary | result: '.var_export($result, true));
		echo json_encode($result);
		//echo $result;
	}
	else {
		//throw new Exception('Mode is empty');
		echo json_encode('Mode is empty');
	}
			
	exit;
?>
