<?php
	class ClientCondition
	{
		private $_clientID;
		private $_conditionTypeID;
		private $_remark;
		private $_checked;
		
		public function ClientCondition($clientID, $conditionTypeID)
		{
			$this->setClientID($clientID);
			$this->setConditionTypeID($conditionTypeID);
		}
		
		public function setClientID($clientID)
		{
			$this->_clientID = $clientID;
		}
		
		public function getClientID()
		{
			return $this->_clientID;
		}
		
		public function setConditionTypeID($conditionTypeID)
		{
			$this->_conditionTypeID = $conditionTypeID;
		}
		
		public function getConditionTypeID()
		{
			return $this->_conditionTypeID;
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
			$this->_checked = $checked;
		}
		
		public function getChecked()
		{
			return $this->_checked;
		}
	}
?>