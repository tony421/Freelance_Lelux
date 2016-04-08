<?php
	require_once '../controller/ClientFunction.php';
	require_once '../controller/TherapistFunction.php';
	require_once '../controller/Utilities.php';
	
	if (!empty($_POST['mode']))
	{
		$result;
		
		try {
			$mode = $_POST['mode'];
			Utilities::logInfo('Client-Boundary | mode: '.$mode);
			
			$clientFunction = new ClientFunction();
			
			if ($mode == "GET_THERAPIST") {
				$therapistFunction = new TherapistFunction();
				$result = $therapistFunction->getTherapists();
			}
			else if ($mode == 'ADD_CLIENT') {
				$clientInfo = $_POST['data'];
				Utilities::logInfo('Client-Boundary | data[clientInfo]: '.var_export($clientInfo, true));
				
				$result = $clientFunction->addClient($clientInfo);
			}
			else if ($mode == 'SEARCH_CLIENT') {
				$search = $_POST['data'];
				Utilities::logInfo('Client-Boundary | data[search]: '.var_export($search, true));
				
				$result = $clientFunction->searchClients($search);
			}
			else if ($mode == 'GET_CLIENT_INFO') {
				$clientID = $_POST['data'];
				Utilities::logInfo('Client-Boundary | data[clientID]: '.var_export($clientID, true));
				
				$result = $clientFunction->getClientInfo($clientID);
			}
			else if ($mode == 'UPDATE_CLIENT') {
				$editedClientInfo = $_POST['data'];
				Utilities::logInfo('Client-Boundary | data[editedClientInfo]: '.var_export($editedClientInfo, true));
				
				$result = $clientFunction->updateClient($editedClientInfo);
			}
			else if ($mode == 'ADD_CLIENT_REPORT') {
				$reportInfo = $_POST['data'];
				Utilities::logInfo('Client-Boundary | data[reportInfo]: '.var_export($reportInfo, true));
				
				$result = $clientFunction->addReport($reportInfo);
			}
			else if ($mode == 'GET_REPORTS') {
				$clientID = $_POST['data'];
				Utilities::logInfo('Client-Boundary | data[clientID]: '.var_export($clientID, true));
				
				$result = $clientFunction->getReports($clientID);
			}
			else if ($mode == 'UPDATE_REPORT') {
				$reportItemInfo = $_POST['data'];
				Utilities::logInfo('Client-Boundary | data[reportItemInfo]: '.var_export($reportItemInfo, true));
				
				$result = $clientFunction->updateReportItem($reportItemInfo);
			}
			else {
				throw new Exception('Mode not found');
			}
		}
		catch(Exception $e) {
			//$isSuccess = false;
			$result = array('msg' => $e->getMessage(),'code' => $e->getCode());
		}
		
		Utilities::logInfo('Client-Boundary | result: '.var_export($result, true));
		echo json_encode($result);
	}
	else {
		//throw new Exception('Mode is empty');
		echo json_encode('Mode is empty');
	}
	
	exit;
?>









