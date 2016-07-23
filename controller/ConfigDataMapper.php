<?php
	require_once '../controller/DataAccess.php';

	class ConfigDataMapper
	{
		private $_dataAccess;
		
		public function ConfigDataMapper()
		{
			$this->_dataAccess = new DataAccess();
		}
		
		public function getConfig($configName, $date) {
			$sql_format = "select config_name, config_value from config where config_name = '%s' 
					and ('%s' between config_active_date_start and config_active_date_end)";
			
			$sql = sprintf($sql_format
					, $configName
					, $date);
			
			return $this->_dataAccess->select($sql);
		}
		
		public function getRequestConditions($date) {
			$sql_format = "select request_condition_request, request_condition_promotion, request_condition_stamp, request_condition_amt from request_condition 
					where '%s' between request_condition_active_date_start and request_condition_active_date_end";
				
			$sql = sprintf($sql_format
					, $date);
				
			return $this->_dataAccess->select($sql);
		}
	}
?>







