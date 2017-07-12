<?php
	require_once '../controller/BookingFunction.php';
	require_once '../controller/QueueFunction.php';
	require_once '../controller/RoomFunction.php';
	require_once '../controller/Utilities.php';
	
	//Utilities::handleError(); // when an error happens you use this function to catch the error
	
	if (!empty($_POST['mode']))
	{
		$result;
	
		try {
			$mode = $_POST['mode'];
			Utilities::logInfo('Booking-Boundary | mode: '.$mode);
			
			$bookingFunction = new BookingFunction();
			
			if ($mode == 'GET_CONFIG') {
				$date = $_POST['data'];
				$result = $bookingFunction->getConfig($date);
			}
			else if ($mode == 'GET_BOOKING_TIMELINE') {
				$date = $_POST['data'];
				Utilities::logInfo('Booking-Boundary | data[$date]: '.$date);
			
				$result = $bookingFunction->getBookingTimeline($date);
			}
			else if ($mode == 'ADD_BOOKING') {
				$bookingInfo = $_POST['data'];
				Utilities::logInfo('Booking-Boundary | data[$bookingInfo]: '.var_export($bookingInfo, true));
					
				$result = $bookingFunction->addBooking($bookingInfo);
			}
			else {
				echo json_encode('Mode not found');
			}
		} catch (Exception $e) {
			Utilities::logError(sprintf("Error Code: %s\nMessage: %s\nFile: %s\nLine: %s\nStack Trace:%s"
					, $e->getCode(), $e->getMessage(), $e->getFile(), $e->getLine(), var_export($e->getTrace(), true)));
			
			$result = Utilities::getResponseResult(false, 'System error occurred!, please contact admin.');
		}
		
		Utilities::logInfo('Booking-Boundary | result: '.var_export($result, true));
		echo json_encode($result);
	} else {
		echo json_encode('Mode is empty');
	}
	
	exit;
?>









