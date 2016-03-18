<?php
	require_once 'Therapist.php';
	
	class Report
	{
		private $_id;
		private $_date;
		private $_detail;
		private $_recommendation;
		private $_hour;
		//private $_therapistID;
		private $_therapist; // Therapist Class
		private $_clientID;
		private $_createDateTime;
		private $_createUser;
		private $_updateDateTime;
		private $_updateUser;
		private $_voidDateTime;
		private $_voidUser;
		
		public function setID($id)
		{
			$this->_id = $id;
		}
		
		public function getID()
		{
			return $this->_id;
		}
		
		public function setDate($date)
		{
			$this->_date = $date;
		}
		
		public function getDate()
		{
			return $this->_date;
		}
		
		public function setDetail($detail)
		{
			$this->_detail = $detail;
		}
		
		public function getDetail()
		{
			return $this->_detail;
		}
		
		public function setRecommendation($recommendation)
		{
			$this->_recommendation = $recommendation;
		}
		
		public function getRecommendation()
		{
			return $this->_recommendation;
		}
		
		public function setHour($hour)
		{
			$this->_hour = $hour;
		}
		
		public function getHour()
		{
			return $this->_hour;
		}
		/*
		public function setTherapistID($therapistID)
		{
			$this->_therapistID = $therapistID;
		}
		
		public function getTherapistID()
		{
			return $this->_therapistID;
		}
		*/
		public function setTherapist($therapist)
		{
			$this->_therapist = $therapist;
		}
		
		public function getTherapist()
		{
			return $this->_therapist;
		}
		
		public function setClientID($clientID)
		{
			$this->_clientID = $clientID;
		}
		
		public function getClientID()
		{
			return $this->_clientID;
		}
		
		public function setCreateUser($createUser)
		{
			$this->_createUser = $createUser;
		}
		
		public function getCreateUser()
		{
			return $this->_createUser;
		}
		
		public function setCreateDateTime($createDateTime)
		{
			$this->_createDateTime = $createDateTime;
		}
		
		public function getCreateDateTime()
		{
			return $this->_createDateTime;
		}
		
		public function setUpdateUser($updateUser)
		{
			$this->_updateUser = $updateUser;
		}
		
		public function getUpdateUser()
		{
			return $this->_updateUser;
		}
		
		public function setUpdateDateTime($updateDateTime)
		{
			$this->_updateDateTime = $updateDateTime;
		}
		
		public function getUpdateDateTime()
		{
			return $this->_updateDateTime;
		}
		
		public function setVoidUser($voidUser)
		{
			$this->_voidUser = $voidUser;
		}
		
		public function getVoidUser()
		{
			return $this->_voidUser;
		}
		
		public function setVoidDateTime($voidDateTime)
		{
			$this->_voidDateTime = $voidDateTime;
		}
		
		public function getVoidDateTime()
		{
			return $this->_voidDateTime;
		}
	}
?>