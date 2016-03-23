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
		
		public static function getDateNowForDisplay()
		{
			return date('d/m/Y');
		}
		
		public static function convertDate($date)
		{
			return date_format(date_create($date), 'd/m/Y');
		}
		
		public static function convertDateForDB($date)
		{
			//return date_format(date_create($date), 'Y-m-d'); // Can not convert from 'd/m/Y' format
			return date_create_from_format('d/m/Y', $date)->format('Y-m-d');
		}
		
		public static function convertDateForDisplay($date)
		{
			return date_format(date_create($date), 'm/d/Y');
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