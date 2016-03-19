<?php
	class ClientFinding
	{
		private $_clientID;
		private $_findingTypeID;
		private $_remark;
		private $_checked;
		
		public function ClientFinding($clientID, $findingTypeID)
		{
			$this->setClientID($clientID);
			$this->setFindingTypeID($findingTypeID);
		}
		
		public function setClientID($clientID)
		{
			$this->_clientID = $clientID;
		}
		
		public function getClientID()
		{
			return $this->_clientID;
		}
		
		public function setFindingTypeID($findingTypeID)
		{
			$this->_findingTypeID = $findingTypeID;
		}
		
		public function getFindingTypeID()
		{
			return $this->_findingTypeID;
		}
		
		public function setRemark($remark)
		{
			$this->_remark = $remark;
		}
		
		public function getRemark()
		{
			return $this->_remark;
		}
		
		public function setChecked($checked)
		{
			$this->_ = $checked;
		}
		
		public function getChecked()
		{
			return $this->_checked;
		}
	}
?>