<?php
	require_once '../controller/Authentication.php';
	require_once '../controller/MassageDataMapper.php';
	require_once '../controller/BookingDataMapper.php';
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
		
		public function voidRecord($recordInfo)
		{
			$affectedRow = $this->executeCommand($recordInfo, self::MODE_VOID);
			
			if ($affectedRow > 0) {
				if ($recordInfo['booking_item_id'] != 0) {
					$bookingDataMapper = new BookingDataMapper();
					$bookingDataMapper->reverseBookingItemStatus($recordInfo['booking_item_id']);
				}					
					
				return Utilities::getResponseResult(true, 'Deleting the record has been successful.');
			}
			else {
				return Utilities::getResponseResult(false, 'Deleting the record has failed!');
			}
		}
		
		public function addRecordByQueueing($recordInfo)
		{
			$recordInfo['massage_record_commission'] = $this->getCommission($recordInfo['massage_record_date'], $recordInfo['massage_record_minutes']);
			$recordInfo['massage_record_request_reward'] = $this->getExtraCommission(
					$recordInfo['massage_record_date'], $recordInfo['massage_record_minutes']
					, filter_var($recordInfo['massage_record_requested'], FILTER_VALIDATE_BOOLEAN), $recordInfo['massage_record_stamp']
					, filter_var($recordInfo['massage_record_promotion'], FILTER_VALIDATE_BOOLEAN)
					, $recordInfo['massage_type_commission']);
			
			$affectedRow = $this->executeCommand($recordInfo, self::MODE_ADD);
				
			if ($affectedRow > 0) {
				return Utilities::getResponseResult(true, 'The massage record has been added successfully.');
			}
			else {
				return Utilities::getResponseResult(false, 'Adding a new massage record has failed!');
			}
		}
		
		public function addRecordByBooking($recordInfo)
		{
			$recordInfo['massage_record_commission'] = $this->getCommission($recordInfo['massage_record_date'], $recordInfo['massage_record_minutes']);
			$recordInfo['massage_record_request_reward'] = $this->getExtraCommission(
					$recordInfo['massage_record_date'], $recordInfo['massage_record_minutes']
					, filter_var($recordInfo['massage_record_requested'], FILTER_VALIDATE_BOOLEAN), $recordInfo['massage_record_stamp']
					, filter_var($recordInfo['massage_record_promotion'], FILTER_VALIDATE_BOOLEAN)
					, $recordInfo['massage_type_commission']);
			
			$affectedRow = $this->executeCommand($recordInfo, self::MODE_ADD, $recordInfo['booking_item_id']);
			
			if ($affectedRow > 0) {
				$bookingDataMapper = new BookingDataMapper();
				$affectedRow = $bookingDataMapper->confirmArrivalBookingItem($recordInfo['booking_id'], $recordInfo['booking_item_id']);
			}
			
			if ($affectedRow > 0) {
				$result['booking_move_to'] = $recordInfo['booking_time_in'];
				
				return Utilities::getResponseResult(true, 'The record is added successfully.', $result);
			} else {
				$this->_dataMapper->deleteRecordByBookingItem($recordInfo['booking_item_id']);
				return Utilities::getResponseResult(false, 'Adding record is failed!');
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
		
		private function executeCommand($recordInfo, $mode = self::MODE_ADD, $bookingItemID = 0)
		{
			switch($mode) {
				case self::MODE_ADD :
					$recordInfo['massage_record_create_user'] = Authentication::getUser()->getID();
					$recordInfo['massage_record_create_datetime'] = Utilities::getDateTimeNowForDB();					
					return $this->_dataMapper->addRecord($recordInfo, $bookingItemID);
					
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
		
		private function getCommission($date, $minutes)
		{
			$config = new Config();
			$comRate = $config->getCommissionRate($date);
			
			Utilities::logDebug('MassageFunction.getExtraCommission() | Standard Commission: '.($minutes * $comRate));
			
			return $minutes * $comRate;
		}
		
		private function getExtraCommission($date, $minutes, $isRequested, $stamp, $isPromo, $massageTypeCommission) {
			$config = new Config();
			$reqConditions = $config->getRequestConditions($date);
			$minReq = $config->getMinRequest($date);
			
			$commission = $massageTypeCommission;
			
			if ($minutes >= $minReq) {
				$isStampUsed = false;
				if ($stamp > 0)
					$isStampUsed = true; 
				
				for ($i = 0; $i < count($reqConditions); $i++) {
					Utilities::logDebug("Condition.Request[{$reqConditions[$i]['request_condition_request']}] | isRequested[{$isRequested}]");
					Utilities::logDebug("Condition.Stamp[{$reqConditions[$i]['request_condition_stamp']}] | isStampUsed[{$isStampUsed}]");
					Utilities::logDebug("Condition.Promotion[{$reqConditions[$i]['request_condition_promotion']}] | isPromo[{$isPromo}]");
					
					if (filter_var($reqConditions[$i]['request_condition_request'], FILTER_VALIDATE_BOOLEAN) == $isRequested
						&& filter_var($reqConditions[$i]['request_condition_stamp'], FILTER_VALIDATE_BOOLEAN) == $isStampUsed
						&& filter_var($reqConditions[$i]['request_condition_promotion'], FILTER_VALIDATE_BOOLEAN) == $isPromo) {
						$commission += $reqConditions[$i]['request_condition_amt'];
						break;
					}
				}
			}
			
			Utilities::logDebug('MassageFunction.getExtraCommission() | Extra Commission: '.$commission);
			return $commission;
		}
	}

?>







