<?php
	require_once '../controller/Session.php';
	require_once '../model/Therapist.php';
	require_once '../controller/TherapistDataMapper.php';
	require_once '../controller/Utilities.php';
	
	class TherapistFunction
	{
		private $_dataMapper;
		
		public function TherapistFunction()
		{
			$this->_dataMapper = new TherapistDataMapper();
		}
		
		public function getTherapists()
		{
			$result = $this->_dataMapper->getTherapists();
				
			if (count($result) > 0) {
				return Utilities::getResponseResult(true, '', $result);
			}
			else {
				Utilities::logInfo("There is no therapist data in the system.");
				return Utilities::getResponseResult(false, 'There is no therapist data in the system!');
			}
		} // getTherapists
		
		public function getTherapistsForManagement()
		{
			$result = $this->_dataMapper->getTherapistsForManagement();
		
			if (count($result) > 0) {
				return Utilities::getResponseResult(true, '', $result);
			}
			else {
				Utilities::logInfo("There is no therapist data in the system.");
				return Utilities::getResponseResult(false, 'There is no therapist data in the system!');
			}
		} // getTherapistsForManagement
		
		public function getTherapistsWithUnknown()
		{
			$result = $this->_dataMapper->getTherapistsWithUnknown();
		
			if (count($result) > 0) {
				return Utilities::getResponseResult(true, '', $result);
			}
			else {
				Utilities::logInfo("There is no therapist data in the system.");
				return Utilities::getResponseResult(false, 'There is no therapist data in the system!');
			}
		} // getTherapists
		
		public function getTherapistsOffShift($date)
		{
			$result = $this->_dataMapper->getTherapistsOffShift($date);
			
			if (count($result) > 0) {
				return Utilities::getResponseResult(true, '', $result);
			}
			else {
				Utilities::logInfo("There is no therapist data in the system.");
				return Utilities::getResponseResult(false, 'There is no therapist data in the system!');
			}
		}
		
		public function getTherapistsOnShift($date)
		{
			$result = $this->_dataMapper->getTherapistsOnShift($date);
			
			$countResult = count($result);
			for ($i = 0; $i < $countResult; $i++) {
				$result[$i]['row_no'] = $i + 1;
			}
			
			if ($countResult <= 0) {
				Utilities::logInfo("There is no therapist on shift {$date}");
			}
			
			return Utilities::getResponseResult(true, '', $result);
		}
		
		public function getTherapistsWokringOnShift($date)
		{
			$result = $this->_dataMapper->getTherapistsWorkingOnShift($date);
			
			if (count($result) <= 0) {
				Utilities::logInfo("There is no therapist on shift {$date}");
			}
				
			return Utilities::getResponseResult(true, '', $result);
		}
		
		public function addTherapist($therapistInfo)
		{
			// Check existed names in every cases
			if ($this->_dataMapper->isExistedTherapistName($therapistInfo)) {
				return Utilities::getResponseResult(false, 'Therapist Name ['.$therapistInfo['therapist_name'].'] already existed, please check the name.');
				//return Utilities::getResponseResult(false, 'Therapist Name ['.$therapistInfo['therapist_name'].'] or Username ['.$therapistInfo['therapist_username'].'] already existed, please check the infotmation.');
			}
			
			$affectedRow = $this->_dataMapper->addTherapist($this->manipulateNameInfo($therapistInfo));
			
			if ($affectedRow > 0) {
				return Utilities::getResponseResult(true, 'New therapist has been inserted successfully.');
			}
			else {
				return Utilities::getResponseResult(false, 'Adding new therapist has failed!');
			}
		} // addTherapist
		
		public function updateTherapist($therapistInfo)
		{
			// Check existed names in every cases
			if ($this->_dataMapper->isExistedTherapistName($therapistInfo)) {
				return Utilities::getResponseResult(false, 'Therapist Name ['.$therapistInfo['therapist_name'].'] already existed, please check the name.');
				//return Utilities::getResponseResult(false, 'Therapist Name ['.$therapistInfo['therapist_name'].'] or Username ['.$therapistInfo['therapist_username'].'] already existed, please check the infotmation.');
			}
			
			$affectedRow = $this->_dataMapper->updateTherapist($this->manipulateNameInfo($therapistInfo));
				
			if ($affectedRow > 0) {
				return Utilities::getResponseResult(true, 'Updating therapist has been successful.');
			}
			else {
				return Utilities::getResponseResult(false, 'Updating therapist has failed!');
			}
		} // updateTherapist
		
		public function deleteTherapist($therapistInfo)
		{				
			$affectedRow = $this->_dataMapper->deleteTherapist($therapistInfo);
		
			if ($affectedRow > 0) {
				return Utilities::getResponseResult(true, 'Deleting therapist has been successful.');
			}
			else {
				return Utilities::getResponseResult(false, 'Deleting therapist has failed!');
			}
		} // deleteTherapist
		
		public function getShiftType()
		{
			$result = $this->_dataMapper->getShiftType();
			
			if (count($result) > 0) {
				return Utilities::getResponseResult(true, '', $result);
			}
			else {
				Utilities::logInfo("There is no shift type data in the system.");
				return Utilities::getResponseResult(false, 'There is no shift type data in the system!');
			}
		}
		
		public function addTherapistToShift($shiftInfo)
		{	
			if ($shiftInfo['shift_type_id'] == 1 || $shiftInfo['shift_type_id'] == 6)
				$shiftWorking = 1;
			else
				$shiftWorking = 0;
			
			$affectedRow = $this->_dataMapper->addTherapistToShift($shiftInfo, $shiftWorking);
				
			if ($affectedRow > 0) {
				return Utilities::getResponseResult(true, "{$shiftInfo['therapist_name']} has been added to the shift.");
			}
			else {
				return Utilities::getResponseResult(false, 'Adding the therapist has failed!');
			}
		}
		
		public function updateTherapistOnShift($shiftInfo)
		{
			$affectedRow = $this->_dataMapper->updateTherapistOnShift($shiftInfo);
			
			if ($affectedRow > 0) {
				return Utilities::getResponseResult(true, "Updating shift has succeeded.");
			}
			else {
				return Utilities::getResponseResult(false, 'Updating shift has failed!');
			}
		}
		
		public function workTherapistOnShift($shiftInfo)
		{
			$affectedRow = $this->_dataMapper->workTherapistOnShift($shiftInfo['shift_id']);
				
			if ($affectedRow > 0) {
				return Utilities::getResponseResult(true, "{$shiftInfo['therapist_name']} is working on the shift.");
			}
			else {
				return Utilities::getResponseResult(false, 'Setting the therapist to be working has failed!');
			}
		}
		
		public function absentTherapistOnShift($shiftInfo)
		{
			$affectedRow = $this->_dataMapper->absentTherapistOnShift($shiftInfo['shift_id']);
			
			if ($affectedRow > 0) {
				return Utilities::getResponseResult(true, "{$shiftInfo['therapist_name']} is absent on the shift.");
			}
			else {
				return Utilities::getResponseResult(false, 'Absenting the therapist has failed!');
			}
		}
		
		public function deleteTherapistOnShift($shiftInfo)
		{
			$affectedRow = $this->_dataMapper->deleteTherapistOnShift($shiftInfo['shift_id']);
				
			if ($affectedRow > 0) {
				return Utilities::getResponseResult(true, "{$shiftInfo['therapist_name']} is deleted from the shift.");
			}
			else {
				return Utilities::getResponseResult(false, 'Deleting the therapist has failed!');
			}
		}
		
		public function deleteAllTherapistOnShift($date)
		{
			$affectedRow = $this->_dataMapper->deleteAllTherapistOnShift($date);
			
			if ($affectedRow > 0) {
				return Utilities::getResponseResult(true, "ALL therapists are deleted from the shift.");
			}
			else {
				return Utilities::getResponseResult(false, 'Deleting the therapist has failed!');
			}
		}
		
		private function manipulateNameInfo($info)
		{
			$info['therapist_name'] = ucwords(strtolower($info['therapist_name']));
			return  $info;
		}
	}
?>







