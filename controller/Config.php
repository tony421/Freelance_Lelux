<?php
	require_once '../controller/Session.php';
	require_once '../controller/ConfigDataMapper.php';
	require_once '../controller/Utilities.php';
	
	require_once '../config/Const_Config.php';
	
	class Config {
		public static function getCommissionRate($date)
		{
			$rates;
			
			if (Session::commissionRatesExist()) {
				$rates = Session::getCommissionRates();
				
				if (!array_key_exists($date, $rates)) {
					$configDataMapper = new ConfigDataMapper();
					$configInfo = $configDataMapper->getConfig(Const_Config::CONFIG_COMMISSION_RATE, $date);
					
					if (count($configInfo) > 0) {
						$newRate = $configInfo[0][Const_Config::CONFIG_VALUE];
					}
					else {
						$newRate = 0.0;
					}
					
					$rates[$date] = $newRate;
				}
			}
			else {
				$rates = array();
				$configDataMapper = new ConfigDataMapper();
				$configInfo = $configDataMapper->getConfig(Const_Config::CONFIG_COMMISSION_RATE, $date);
					
				if (count($configInfo) > 0) {
					$newRate = $configInfo[0][Const_Config::CONFIG_VALUE];
				}
				else {
					$newRate = 0.0;
				}
				
				$rates[$date] = $newRate;
			}
			
			Utilities::logInfo("Config | The commission rate on $date is $rates[$date]");
			Session::setCommissionRates($rates);
			return $rates[$date];
		}
		
		public static function getMinRequest($date)
		{
			$values;
				
			if (Session::minRequestsExist()) {
				$values = Session::getMinRequests();
		
				if (!array_key_exists($date, $values)) {
					$configDataMapper = new ConfigDataMapper();
					$configInfo = $configDataMapper->getConfig(Const_Config::CONFIG_MIN_REQUEST, $date);
						
					if (count($configInfo) > 0) {
						$newVal = $configInfo[0][Const_Config::CONFIG_VALUE];
					}
					else {
						$newVal = 0.0;
					}
						
					$values[$date] = $newVal;
				}
			}
			else {
				$values = array();
				$configDataMapper = new ConfigDataMapper();
				$configInfo = $configDataMapper->getConfig(Const_Config::CONFIG_MIN_REQUEST, $date);
					
				if (count($configInfo) > 0) {
					$newVal = $configInfo[0][Const_Config::CONFIG_VALUE];
				}
				else {
					$newVal = 0.0;
				}
		
				$values[$date] = $newVal;
			}
				
			Utilities::logInfo("Config | Minimum request value on $date is $values[$date]");
			Session::setMinRequests($values);
			return $values[$date];
		}
		
		public static function getRequestConditions($date)
		{
			$conditions;
			
			if (Session::requestConditionsExist()) {
				$conditions = Session::getRequestConditions();
				
				if (!array_key_exists($date, $conditions)) {
					$configDataMapper = new ConfigDataMapper();
					$reqCon = $configDataMapper->getRequestConditions($date);
						
					if (count($reqCon) > 0) {
						$conditions[$date] = $reqCon;
					}
					else {
						throw new Exception("Request Conditions on $date could not be found.");
					}
				}
			}
			else {
				$conditions = array();
				$configDataMapper = new ConfigDataMapper();
				$reqCon = $configDataMapper->getRequestConditions($date);
				
				if (count($reqCon) > 0) {
					$conditions[$date] = $reqCon;
				}
				else {
					throw new Exception("Request Conditions on $date could not be found.");
				}
			}
			
			Utilities::logInfo("Config | Request conditions on $date is ".var_export($conditions[$date], true));
			Session::setRequestConditions($conditions);
			return $conditions[$date];
		}
	}
?>







