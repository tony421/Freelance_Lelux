<?php
	class Therapist
	{
		private $_id;
		private $_name;
		private $_username;
		private $_password;
		private $_permission;
		
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
		
		public function setUsername($username)
		{
			$this->_username = $username;
		}
		
		public function getUsername()
		{
			return $this->_username;
		}
		
		public function setPassword($password)
		{
			$this->_password = $password;
		}
		
		public function getPassword()
		{
			return $this->_password;
		}
		
		public function setPermission($permission)
		{
			$this->_permission = $permission;
		}
		
		public function getPermission()
		{
			return $this->_permission;
		}
	}
?>