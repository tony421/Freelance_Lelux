<?php
	class ConditionType
	{
		private $_conditionTypeID;
		private $_conditionTypeName;
		
		public function setConditionTypeID($conditionTypeID)
		{
			$this->_conditionTypeID = $conditionTypeID;
		}
		
		public function getConditionTypeID()
		{
			return $this->_conditionTypeID;
		}
		
		public function setConditionTypeName($conditionTypeName)
		{
			$this->_conditionTypeName = $conditionTypeName;
		}
		
		public function getConditionTypeName()
		{
			return $this->_conditionTypeName;
		}
	}
?>