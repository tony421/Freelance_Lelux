<?php
	require_once '../controller/DataAccess.php';
	
	class ProviderDataMapper
	{
		private $_dataAccess;
		
		public function ProviderDataMapper()
		{
			$this->_dataAccess = new DataAccess();
		}
		
		public function getProviders()
		{
			$sql = "select * 
					from provider
					where provider_active = 1
					order by provider_id";
				
			return $this->_dataAccess->select($sql);
		}
		
		public function getProvidersDisplay()
		{
			$sql = "select provider_id, provider_no, provider_name as provider_name_short
						, concat(provider_name, ' (', provider_no, ')') as provider_name, provider_active 
					from provider
					where provider_active = 1
					order by provider_id";
		
			return $this->_dataAccess->select($sql);
		}
		
		public function addProvider($providerInfo)
		{
			$sql_format = "
					insert into provider
						(provider_no, provider_name, provider_active)
					values ('%s', '%s', 1)";
			
			$sql = sprintf($sql_format
					, $providerInfo['provider_no']
					, ucwords(strtolower($providerInfo['provider_name'])));
			
			return $this->_dataAccess->insert($sql);
		} // addProvider
		
		public function updateProvider($providerInfo)
		{
			$sql_format = "
					update provider
					set provider_no = '%s'
						, provider_name = '%s'
						, provider_update_datetime = NOW()
					where provider_id = %d";
			
			$sql = sprintf($sql_format
					, $providerInfo['provider_no']
					, ucwords(strtolower($providerInfo['provider_name']))
					, $providerInfo['provider_id']);
			
			return $this->_dataAccess->update($sql);
		} // updateProvider
		
		public function deleteProvider($providerInfo)
		{
			$sql_format = "update provider set provider_active = 0 where provider_id = %d";
			//$sql_format = "delete from provider where provider_id = %d";
				
			$sql = sprintf($sql_format
					, $providerInfo['provider_id']);
				
			return $this->_dataAccess->delete($sql);
		} // deleteProvider
	}
?>