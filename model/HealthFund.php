<?php
	class HealthFund
	{
		private $_id;
		private $_name;
		
		public function setID($id)
		{
			$this->_id = $id;
		}
		
		public function getID()
		{
			return $this->_id;
		}
		
		public function setName($name)
		{
			$this->_name = $name;
		}
		
		public function getName()
		{
			return $this->_name;
		}
	}
?>