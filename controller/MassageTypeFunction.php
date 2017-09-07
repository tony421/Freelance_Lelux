<?php
	require_once '../controller/Session.php';
	require_once '../controller/MassageTypeDataMapper.php';
	require_once '../controller/Utilities.php';
	
	class MassageTypeFunction
	{
		private $_dataMapper;
	
		public function MassageTypeFunction()
		{
			$this->_dataMapper = new MassageTypeDataMapper();
		}
		
		public function getMassageTypes()
		{
			$result = $this->_dataMapper->getMassageTypes();
		
			if (count($result) > 0) {
				return Utilities::getResponseResult(true, '', $result);
			}
			else {
				Utilities::logInfo("There is no massage type data in the system.");
				return Utilities::getResponseResult(true, 'There is no massage type data in the system!', []);
			}
		} // getMassageTypes
		
		public function getMassageTypesDisplay()
		{
			$result = $this->_dataMapper->getMassageTypesDisplay();
		
			if (count($result) > 0) {
				return Utilities::getResponseResult(true, '', $result);
			}
			else {
				Utilities::logInfo("There is no massage type data in the system.");
				return Utilities::getResponseResult(true, 'There is no massage type data in the system!', []);
			}
		} // getMassageTypesDisplay
		
		public function addMassageType($massageTypeInfo)
		{				
			$affectedRow = $this->_dataMapper->addMassageType($massageTypeInfo);
				
			if ($affectedRow > 0) {
				return Utilities::getResponseResult(true, 'New massage type has been inserted successfully.');
			}
			else {
				return Utilities::getResponseResult(false, 'Adding new massage type has failed!');
			}
		} // addMassageType
		
		public function updateMassageType($massageTypeInfo)
		{				
			$affectedRow = $this->_dataMapper->updateMassageType($massageTypeInfo);
		
			if ($affectedRow > 0) {
				return Utilities::getResponseResult(true, 'Updating massage type has been successful.');
			}
			else {
				return Utilities::getResponseResult(false, 'Updating massage type has failed!');
			}
		} // updateMassageType
		
		public function deleteMassageType($massageTypeInfo)
		{
			$affectedRow = $this->_dataMapper->deleteMassageType($massageTypeInfo);
		
			if ($affectedRow > 0) {
				return Utilities::getResponseResult(true, 'Deleting massage type has been successful.');
			}
			else {
				return Utilities::getResponseResult(false, 'Deleting massage type has failed!');
			}
		} // deleteMassageType
	}
?>







