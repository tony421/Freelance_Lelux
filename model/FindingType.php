<?php
	class FindingType
	{
		private $_findingTypeID;
		private $_findingTypeName;
		
		public function setFindingTypeID($findingTypeID)
		{
			$this->_findingTypeID = $findingTypeID;
		}
		
		public function getFindingTypeID()
		{
			return $this->_findingTypeID;
		}
		
		public function setFindingTypeName($findingTypeName)
		{
			$this->_findingTypeName = $findingTypeName;
		}
		
		public function getFindingTypeName()
		{
			return $this->_findingTypeName;
		}
	}
?>