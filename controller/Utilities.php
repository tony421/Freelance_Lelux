<?php
	require_once '../controller/KLogger.php';
	
	date_default_timezone_set('Australia/Melbourne');

	class Utilities
	{
		const log_path = "/lelux/log/";
		//const log_path = "/support/log/";
		
		public static function getUniqueID($prefix = null)
		{
			return uniqid($prefix);
		}
		
		public static function getResponseMassage($success, $msg)
		{
			return array('success' => $success, 'msg' => $msg);
		}
		
		public static function getResponseResult($success, $msg, $result = [])
		{
			return array('success' => $success, 'msg' => $msg, 'result' => $result);
		}
		
		public static function getTimeoutResponseResult()
		{
			Utilities::logInfo('Session is expired!!');
			return array('timeout' => true);
		}
		
		public static function handleError()
		{
			// *** "set_error_handler" cannot catch a fatal error
			set_error_handler(function($code, $message, $file, $line){
				Utilities::logError("Error code: ".$code."\nMessage: ".$message."\nFile: ".$file."\nLine: ".$line);
			});
		}
		
		public static function getVal($array, $index)
		{
			if (isset($array[$index]))
				return $array[$index];
			else
				throw new Exception('Undefinded index: "'.$index.'"', 9001);
		}
		
		private static function setUpLogger()
		{
			//$this->logger = new KLogger(LOG_PATH, KLogger::DEBUG);
		}
		
		public static function logDebug($line)
		{
			try {
				$logger = new KLogger($_SERVER['DOCUMENT_ROOT'].self::log_path.'log-'.date('Ymd'), KLogger::DEBUG);
				$logger->LogDebug($line);
			} catch (Exception $e) {
			}
		}
		
		public static function logInfo($line)
		{
			try {
				$logger = new KLogger($_SERVER['DOCUMENT_ROOT'].self::log_path.'log-'.date('Ymd'), KLogger::DEBUG);
				$logger->LogInfo($line);
			} catch (Exception $e) {
			}
		}
		
		public static function logError($line)
		{
			try {
				$logger = new KLogger($_SERVER['DOCUMENT_ROOT'].self::log_path.'log-'.date('Ymd'), KLogger::DEBUG);
				$logger->LogError($line);
			} catch (Exception $e) {
			}
		}
		
		public static function getDateTimeNow()
		{
			return date('d-m-Y H:i:s');
		}
		
		public static function getDateTimeNowForDB()
		{
			return date('Y-m-d H:i:s');
		}
		
		public static function getDateNowForDB()
		{
			//return date('Y-m-d'); //1999-01-01
			return date('Y-n-j'); //1999-1-1
		}
		
		public static function getDateNowForDisplay()
		{
			return date('d/m/Y');
		}
		
		public static function getTimeNow()
		{
			return date('H:i:s');
		}
		
		public static function convertDate($date)
		{
			return date_format(date_create($date), 'd/m/Y');
		}
		
		public static function convertDateForDB($date, $format = 'd/m/Y')
		{
			//return date_format(date_create($date), 'Y-m-d'); // Can not convert from 'd/m/Y' format
			return date_create_from_format($format, $date)->format('Y-m-d');
		}
		
		public static function convertDateForDisplay($date)
		{
			return date_format(date_create($date), 'j/n/Y'); // e.g. 23/12/2016
		}
		
		public static function convertDateFullMonthDisplay($date)
		{
			return date_format(date_create($date), 'j F Y'); // e.g. 23/12/2016
		}
		
		public static function convertDateForFullDisplay($date)
		{
			return date_format(date_create($date), 'l, j F Y'); // e.g. Saturday, 23 July 2016
		}
		
		public static function convertDatetimeForDisplay($date)
		{
			return date_format(date_create($date), 'd/m/Y H:i:s');
		}
		
		public static function formatTime($date)
		{
			return date_format(date_create($date), 'h:i a'); // h:i A (12:50 AM)
		}
		
		public static function dateDiff($start, $end, $unit = 'minutes')
		{
			$interval = date_diff(date_create($start), date_create($end));
			$hours = 0;
			$minutes = 0;
			
			switch ($unit) {
				case 'minutes' :
				case 'm' :
				default :
					$hours = $interval->format('%h') * 60;
					$minutes = $interval->format('%i');
					return $hours + $minutes;
			}
		}
		
		public static function redirect($page)
		{
			header('Location: '.$page);
			die();
		}
		
		public static function getClientError($msg, $code = '')
		{
			$error = array('error' => array('msg' => $msg,'code' => $code));
		
			return json_encode($error);
		}
		
		public static function upperFirstLetter($str)
		{
			return ucwords(strtolower($str));
		}
		
		public static function isWeekend($date) {
		    return (date('N', strtotime($date)) >= 6);
		}
	}
?>