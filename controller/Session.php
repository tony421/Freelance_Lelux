<?php
	session_start();

	require_once('../config/Const_Config.php');
	
	class Session
	{
		public static function destroy()
		{
			session_destroy();
		}
		
		public static function userExists()
		{
			return isset($_SESSION['user']);
		}
		
		public static function setUser($therapist)
		{
			$_SESSION['user'] = serialize($therapist);
		}
		
		public static function getUser()
		{
			if(Session::userExists())
			{
				return unserialize($_SESSION['user']);
			}
			else 
			{
				throw new Exception("Session['user'] is not found.");
			}
		}
		
		public static function commissionRatesExist()
		{
			return isset($_SESSION[Const_Config::CONFIG_COMMISSION_RATE]);
		}
		
		public static function setCommissionRates($rates)
		{
			$_SESSION[Const_Config::CONFIG_COMMISSION_RATE] = $rates;
		}
		
		public static function getCommissionRates()
		{
			if(Session::commissionRatesExist())
			{
				return $_SESSION[Const_Config::CONFIG_COMMISSION_RATE];
			}
			else
			{
				throw new Exception("Session['CONFIG_COMMISSION_RATE'] is not found.");
			}
		}
		
		public static function minRequestsExist()
		{
			return isset($_SESSION[Const_Config::CONFIG_MIN_REQUEST]);
		}
		
		public static function setMinRequests($rates)
		{
			$_SESSION[Const_Config::CONFIG_MIN_REQUEST] = $rates;
		}
		
		public static function getMinRequests()
		{
			if(Session::minRequestsExist())
			{
				return $_SESSION[Const_Config::CONFIG_MIN_REQUEST];
			}
			else
			{
				throw new Exception("Session['CONFIG_MIN_REQUEST'] is not found.");
			}
		}
		
		public static function requestConditionsExist()
		{
			return isset($_SESSION[Const_Config::REQUEST_CONDITION]);
		}
		
		public static function setRequestConditions($reqComm)
		{
			$_SESSION[Const_Config::REQUEST_CONDITION] = $reqComm;
		}
		
		public static function getRequestConditions()
		{
			if(Session::requestConditionsExist())
			{
				return $_SESSION[Const_Config::REQUEST_CONDITION];
			}
			else
			{
				throw new Exception("Session['REQUEST_CONDITION'] is not found.");
			}
		}
	}
?>










