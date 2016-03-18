<?php
	class Membership
	{
		private $_no;
		private $_patientID;
		private $_clientID;
		private $_healthFundID;
		
		
		public function setNo($no)
		{
			$this->_no = $no;
		}
		
		public function getNo()
		{
			return $this->_no;
		}
		
		public function setPatientID($patientID)
		{
			$this->_patientID = $patientID;
		}
		
		public function getPatientID()
		{
			return $this->_patientID;
		}
		
		public function setClientID($clientID)
		{
			$this->_clientID = $clientID;
		}
		
		public function getClientID()
		{
			return $this->_clientID;
		}
		
		public function setHealthFundID($healthFundID)
		{
			$this->_healthFundID = $healthFundID;
		}
		
		public function getHealthFundID()
		{
			return $this->_healthFundID;
		}
	}
?>