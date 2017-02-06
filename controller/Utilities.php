<?php
	require_once '../controller/KLogger.php';
	
	date_default_timezone_set('Australia/Melbourne');

	class Utilities
	{		
		public static function getUniqueID()
		{
			return uniqid();
		}
		
		public static function getResponseMassage($success, $msg)
		{
			return array('success' => $success, 'msg' => $msg);
		}
		
		public static function getResponseResult($success, $msg, $result = [])
		{
			return array('success' => $success, 'msg' => $msg, 'result' => $result);
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
			$logger = new KLogger($_SERVER['DOCUMENT_ROOT'].'/Freelance_Lelux/log/log-'.date('Ymd'), KLogger::DEBUG);
			$logger->LogDebug($line);
		}
		
		public static function logInfo($line)
		{
			$logger = new KLogger($_SERVER['DOCUMENT_ROOT'].'/Freelance_Lelux/log/log-'.date('Ymd'), KLogger::DEBUG);
			$logger->LogInfo($line);
		}
		
		public static function logError($line)
		{
			$logger = new KLogger($_SERVER['DOCUMENT_ROOT'].'/Freelance_Lelux/log/log-'.date('Ymd'), KLogger::DEBUG);
			$logger->LogError($line);
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
		
		public static function convertDateForFullDisplay($date)
		{
			return date_format(date_create($date), 'l, j F Y'); // e.g. Saturday, 23 July 2016
		}
		
		public static function convertDatetimeForDisplay($date)
		{
			return date_format(date_create($date), 'd/m/Y H:i:s');
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
	}
?>