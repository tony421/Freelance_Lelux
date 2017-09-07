<?php
	require_once '../controller/DataAccess.php';

	class HealthFundDataMapper
	{
		private $_dataAccess;
		
		public function HealthFundDataMapper()
		{
			$this->_dataAccess = new DataAccess();
		}
		
		public function getHealthFunds()
		{
			$sql = "select * from health_fund order by health_fund_name";
				
			return $this->_dataAccess->select($sql);
		}
	}
?>