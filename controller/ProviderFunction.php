<?php
	require_once '../controller/Session.php';
	require_once '../controller/ProviderDataMapper.php';
	require_once '../controller/Utilities.php';
	
	class ProviderFunction
	{
		private $_dataMapper;
	
		public function ProviderFunction()
		{
			$this->_dataMapper = new ProviderDataMapper();
		}
		
		public function getProviders()
		{
			$result = $this->_dataMapper->getProviders();
		
			if (count($result) > 0) {
				return Utilities::getResponseResult(true, '', $result);
			}
			else {
				Utilities::logInfo("There is no provider data in the system.");
				return Utilities::getResponseResult(true, 'There is no provider data in the system!', []);
			}
		} // getProviders
		
		public function getProvidersDisplay()
		{
			$result = $this->_dataMapper->getProvidersDisplay();
		
			if (count($result) > 0) {
				return Utilities::getResponseResult(true, '', $result);
			}
			else {
				Utilities::logInfo("There is no provider data in the system.");
				return Utilities::getResponseResult(true, 'There is no provider data in the system!', []);
			}
		} // getProvidersDisplay
		
		public function addProvider($providerInfo)
		{				
			$affectedRow = $this->_dataMapper->addProvider($providerInfo);
				
			if ($affectedRow > 0) {
				return Utilities::getResponseResult(true, 'New provider has been inserted successfully.');
			}
			else {
				return Utilities::getResponseResult(false, 'Adding new provider has failed!');
			}
		} // addProvider
		
		public function updateProvider($providerInfo)
		{				
			$affectedRow = $this->_dataMapper->updateProvider($providerInfo);
		
			if ($affectedRow > 0) {
				return Utilities::getResponseResult(true, 'Updating provider has been successful.');
			}
			else {
				return Utilities::getResponseResult(false, 'Updating provider has failed!');
			}
		} // updateProvider
		
		public function deleteProvider($providerInfo)
		{
			$affectedRow = $this->_dataMapper->deleteProvider($providerInfo);
		
			if ($affectedRow > 0) {
				return Utilities::getResponseResult(true, 'Deleting provider has been successful.');
			}
			else {
				return Utilities::getResponseResult(false, 'Deleting provider has failed!');
			}
		} // deleteProvider
	}
?>







