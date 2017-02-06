<?php
	require_once '../controller/Authentication.php';
	require_once '../controller/ReceptionDataMapper.php';
	require_once '../controller/Utilities.php';
	require_once '../config/reception_config.php';

	class ReceptionFunction
	{
		const MODE_ADD = 1;
		const MODE_UPDATE = 2;
		const MODE_VOID = 3;
		
		private $_dataMapper;
		
		public function ReceptionFunction()
		{
			$this->_dataMapper = new ReceptionDataMapper();
		}
		
		public function getConfig()
		{
			$config['day_rate'] = RECEPTION_DAY_RATE;
			$config['hour_rate'] = RECEPTION_HOUR_RATE;
			$config['late_night_rate'] = RECEPTION_LATE_NIGHT_RATE;
			$config['com_sales'] = RECEPTION_COM_SALES;
			$config['com_rates'] = RECEPTION_COM_RATES;
				
			return Utilities::getResponseResult(true, '', $config);
		}
		
		public function getRecords($date)
		{
			$result = $this->_dataMapper->getRecords($date);
				
			$countResult = count($result);
			for ($i = 0; $i < $countResult; $i++) {
				$result[$i]['row_no'] = $i + 1;
				$result[$i]['reception_record_date'] = Utilities::convertDateForDisplay($result[$i]['reception_record_date']);
			}
				
			return Utilities::getResponseResult(true, '', $result);
		}
		
		public function addRecord($recordInfo)
		{
			$affectedRow = $this->executeCommand($recordInfo, self::MODE_ADD);
				
			if ($affectedRow > 0) {
				return Utilities::getResponseResult(true, 'The reception record has been added successfully.');
			}
			else {
				return Utilities::getResponseResult(false, 'Adding the new reception record has failed!');
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
			$recordInfo['reception_record_id'] = $recordID;
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
					$recordInfo['reception_record_create_user'] = Authentication::getUser()->getID();
					$recordInfo['reception_record_create_datetime'] = Utilities::getDateTimeNowForDB();
					return $this->_dataMapper->addRecord($recordInfo);
						
				case self::MODE_UPDATE :
					$recordInfo['reception_record_update_user'] = Authentication::getUser()->getID();
					$recordInfo['reception_record_update_datetime'] = Utilities::getDateTimeNowForDB();
					return $this->_dataMapper->updateRecord($recordInfo);
						
				case self::MODE_VOID :
					$recordInfo['reception_record_void_user'] = Authentication::getUser()->getID();
					$recordInfo['reception_record_void_datetime'] = Utilities::getDateTimeNowForDB();
					return $this->_dataMapper->voidRecord($recordInfo);
			}
		}
	}
?>












