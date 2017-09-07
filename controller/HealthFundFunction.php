<?php
	require_once '../controller/HealthFundDataMapper.php';
	require_once '../controller/Utilities.php';
	
	class HealthFundFunction
	{
		private $_dataMapper;
	
		public function HealthFundFunction()
		{
			$this->_dataMapper = new HealthFundDataMapper();
		}
		
		public function getHealthFunds()
		{
			$result = $this->_dataMapper->getHealthFunds();
			
			if (count($result) > 0) {
				return Utilities::getResponseResult(true, '', $result);
			}
			else {
				Utilities::logInfo("There is no health fund data in the system.");
				return Utilities::getResponseResult(false, 'There is no health fund data in the system!');
			}
		} // getHealthFunds
	}
?>