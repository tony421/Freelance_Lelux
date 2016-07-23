<?php
	require_once '../controller/Authentication.php';
	require_once '../controller/MassageDataMapper.php';
	require_once '../controller/Config.php';
	require_once '../controller/Utilities.php';

	class MassageFunction
	{
		const MODE_ADD = 1;
		const MODE_UPDATE = 2;
		const MODE_VOID = 3;
		
		const RETURN_ITEM_CONFIG = 'CONFIG';
		const RETURN_ITEM_RECORDS = 'RECORDS';
		
		private $_dataMapper;
		
		public function MassageFunction() 
		{
			$this->_dataMapper = new MassageDataMapper(); 	
		}
		
		public function getConfig($date)
		{
			$config = new Config();
			$comRate = $config->getCommissionRate($date);
			$reqConditions = $config->getRequestConditions($date);
			$minReq = $config->getMinRequest($date);
			
			$result['commission_rate'] = $comRate;
			$result['request_conditions'] = $reqConditions;
			$result['minimum_request'] = $minReq;
			
			return Utilities::getResponseResult(true, '', $result);
		}
		
		public function getRecords($date)
		{
			$result = $this->_dataMapper->getRecords($date);
			
			$countResult = count($result);
			for ($i = 0; $i < $countResult; $i++) {
				$result[$i]['row_no'] = $i + 1;
				$result[$i]['massage_record_date'] = Utilities::convertDateForDisplay($result[$i]['massage_record_date']);
			}
			
			return Utilities::getResponseResult(true, '', $result);
		}
		
		public function addRecord($recordInfo)
		{
			$affectedRow = $this->executeCommand($recordInfo, self::MODE_ADD);
			
			if ($affectedRow > 0) {
				return Utilities::getResponseResult(true, 'The massage record has been added successfully.');
			}
			else {
				return Utilities::getResponseResult(false, 'Adding a new massage record has failed!');
			}
		}
		
		public function updateRecord($recordInfo)
		{
			$affectedRow = $this->executeCommand($recordInfo, self::MODE_UPDATE);
			
			if ($affectedRow > 0) {
				return Utilities::getResponseResult(true, 'Updating the record has been successful.');
			}
			else {
				return Utilities::getResponseResult(false, 'Updating the record has failed!');
			}
		}
		
		public function voidRecord($recordID)
		{
			$recordInfo['massage_record_id'] = $recordID;
			$affectedRow = $this->executeCommand($recordInfo, self::MODE_VOID);
			
			if ($affectedRow > 0) {
				return Utilities::getResponseResult(true, 'Deleting the record has been successful.');
			}
			else {
				return Utilities::getResponseResult(false, 'Deleting the record has failed!');
			}
		}
		
		private function executeCommand($recordInfo, $mode = self::MODE_ADD)
		{
			switch($mode) {
				case self::MODE_ADD :
					$recordInfo['massage_record_create_user'] = Authentication::getUser()->getID();
					$recordInfo['massage_record_create_datetime'] = Utilities::getDateTimeNowForDB();
					return $this->_dataMapper->addRecord($recordInfo);
					
				case self::MODE_UPDATE :
					$recordInfo['massage_record_update_user'] = Authentication::getUser()->getID();
					$recordInfo['massage_record_update_datetime'] = Utilities::getDateTimeNowForDB();
					return $this->_dataMapper->updateRecord($recordInfo);
					
				case self::MODE_VOID :
					$recordInfo['massage_record_void_user'] = Authentication::getUser()->getID();
					$recordInfo['massage_record_void_datetime'] = Utilities::getDateTimeNowForDB();
					return $this->_dataMapper->voidRecord($recordInfo);
			}
		}
		
		public function getCommissionDailyReport($date)
		{
			return $this->_dataMapper->getCommissionDailyReport($date);
		}
		
		public function getIncomeDailyReport($date)
		{
			return $this->_dataMapper->getIncomeDailyReport($date);
		}
	}

?>







