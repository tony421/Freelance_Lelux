<?php
	require_once '../controller/Utilities.php';
	require_once '../controller/BookingSmsDataMapper.php';

	Utilities::handleError(); // when an error happens you use this function to catch the error
	
	//Utilities::logInfo('===== TASK - START ===== | task_send_booking_sms_today');
	
	$bookingSmsDataMapper = new BookingSmsDataMapper();
	
	$api_url = 'https://api.smsglobal.com/http-api.php?';
	$param_action = 'sendsms';
	$param_user = 'ie1aeqnx';
	$param_password = 'HrmpUQHW';
	$param_from = 'Lelux Thai';
	$time_format = 'g.i A';
	
	$todayDate = new DateTime();
	$todayDateStr = $todayDate->format('Y-m-d');
	$bookings = $bookingSmsDataMapper->getBookings($todayDateStr);
	$countBookings = count($bookings);
	
	//Utilities::logInfo('task_send_booking_sms_today | $bookings: '.var_export($bookings, true));
	//Utilities::logInfo("task_send_booking_sms_today | found {$countBookings} bookings on {$todayDateStr}");
	
	if ($countBookings > 0) {
		for ($i = 0; $i < $countBookings; $i++) {
			$bookingId = $bookings[$i]['booking_id'];
			$bookingName = $bookings[$i]['booking_name'];
			$bookingTel = str_replace('-', '', $bookings[$i]['booking_tel']);
			$bookingTimeIn = new DateTime($bookings[$i]['booking_time_in']);
			$bookingTimeOut = new DateTime($bookings[$i]['booking_time_out']);
			$bookingTime = $bookingTimeIn->format($time_format).' to '.$bookingTimeOut->format($time_format);
			
			$smsText = "Dear Customer, Lelux Thai Massage, 323A Keilor rd, Essendon. Confirming your appointment today at {$bookingTime}. If you can't make it, please call 03 9043 9742. Thanks";
			
			$http_curl = curl_init();
			$http_body = [
					'action' => $param_action,
					'user' => $param_user,
					'password' => $param_password,
					'from' => $param_from,
					'to' => $bookingTel,
					'text' => $smsText,
			];
			$http_payload = http_build_query($http_body);
			$http_url = $api_url.$http_payload;
			
			curl_setopt($http_curl, CURLOPT_URL, $http_url);
			curl_setopt($http_curl, CURLOPT_POST, false);
			curl_setopt($http_curl, CURLOPT_RETURNTRANSFER, true);
			
			//Utilities::logInfo("task_send_booking_sms_today | sending sms to: $bookingName ({$bookingTel})");
			$http_result = curl_exec($http_curl);
			curl_close($http_curl);
			//Utilities::logInfo("task_send_booking_sms_today | sending sms result: {$http_result}");
			
			$bookingSmsDataMapper->addBookingSms($bookingId, $http_result);
		}
	}
	
	//Utilities::logInfo('===== TASK - END ===== | task_send_booking_sms_today');
?>









