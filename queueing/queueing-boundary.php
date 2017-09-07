<?php
	require_once '../controller/Authentication.php';
	require_once '../controller/QueueFunction.php';
	require_once '../controller/RoomFunction.php';
	require_once '../controller/TherapistFunction.php';
	//require_once '../controller/Utilities.php';
	
	//Utilities::handleError(); // when an error happens you use this function to catch the error
	
	if (Authentication::userExists()) {
		if (!empty($_POST['mode']))
		{
			$result;
			
			try {
				$mode = $_POST['mode'];
				Utilities::logInfo('Queueing-Boundary | mode: '.$mode);
				
				$queueFunction = new QueueFunction();
				
				if ($mode == 'GET_ROOM') {
					$function = new RoomFunction();
					$result = $function->getAllRooms();
				}
				else if ($mode == 'GET_THERAPIST_ON_QUEUE') {
					$date = $_POST['data'];
					
					$result = $queueFunction->getTherapistsOnQueue($date);
				}
				else if ($mode == 'SEARCH_QUEUE_FOR_WALK_IN') {
					$searchInfo = $_POST['data'];
					Utilities::logInfo('Queueing-Boundary | data[$searchInfo]: '.var_export($searchInfo, true));
					
					$result = $queueFunction->getQueueForWalkIn($searchInfo);
				}
				else if ($mode == 'SEARCH_AVAILABILITY_FOR_BOOKING') {
					$searchInfo = $_POST['data'];
					Utilities::logInfo('Queueing-Boundary | data[$searchInfo]: '.var_export($searchInfo, true));
				
					$result = $queueFunction->searchAvailabilityForBooking($searchInfo);
				}
				else if ($mode == 'SEARCH_QUEUE_FOR_BOOKING') {
					$searchInfo = $_POST['data'];
					Utilities::logInfo('Queueing-Boundary | data[$searchInfo]: '.var_export($searchInfo, true));
					
					$result = $queueFunction->getQueueForBooking($searchInfo);
				}
				else {
					echo json_encode('Mode not found');
				}
			} catch(Exception $e) {
				Utilities::logError(sprintf("Error Code: %s\nMessage: %s\nFile: %s\nLine: %s\nStack Trace:%s"
						, $e->getCode(), $e->getMessage(), $e->getFile(), $e->getLine(), var_export($e->getTrace(), true)));
			
				$result = Utilities::getResponseResult(false, 'System error occurred!, please contact admin.');
			}
			
			Utilities::logInfo('Queueing-Boundary | result: '.var_export($result, true));
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





