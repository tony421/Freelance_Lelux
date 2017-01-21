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
		
		public function addTherapist($therapistInfo)
		{
			// Check existed names in every cases
			if ($this->_dataMapper->isExistedTherapistName($therapistInfo)) {
				return Utilities::getResponseResult(false, 'Therapist Name ['.$therapistInfo['therapist_name'].'] already existed, please check the name.');
				//return Utilities::getResponseResult(false, 'Therapist Name ['.$therapistInfo['therapist_name'].'] or Username ['.$therapistInfo['therapist_username'].'] already existed, please check the infotmation.');
			}
			
			$affectedRow = $this->_dataMapper->addTherapist($therapistInfo);
			
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
			
			$affectedRow = $this->_dataMapper->updateTherapist($therapistInfo);
				
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
	}
?>







