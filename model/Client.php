<?php
	require_once 'ClientFinding.php';
	require_once 'ClientCondition.php';
	
	class Client
	{
		private $_id;
		private $_membershipNo;
		private $_patientID;
		private $_healthFundID;
		private $_firstName;
		private $_lastName;
		private $_gender;
		private $_address;
		private $_postcode;
		private $_email;
		private $_contactNo;
		private $_birthday;
		private $_occupation;
		private $_sports;
		private $_otherConditions;
		private $_emergencyContactName;
		private $_emergencyContactNo;
		private $_findings; // array of ClientFinding
		private $_conditions; // array of ClientCondition
		private $_createDateTime;
		private $_createUser;
		private $_updateDateTime;
		private $_updateUser;
		private $_voidDateTime;
		private $_voidUser;
		
		public function Client($id)
		{
			$this->setID($id);
		}
		
		public function setID($id)
		{
			$this->_id = $id;
		}
		
		public function getID()
		{
			return $this->_id;
		}
		
		public function setMembershipNo($membershipNo)
		{
			$this->_membershipNo = $membershipNo;
		}
		
		public function getMembershipNo()
		{
			return $this->_membershipNo;
		}
		
		public function setPatientID($patientID)
		{
			$this->_patientID = $patientID;
		}
		
		public function getPatientID()
		{
			return $this->_patientID;
		}
		
		public function setHealthFundID($healthFundID)
		{
			$this->_healthFundID = $healthFundID;
		}
		
		public function getHealthFundID()
		{
			return $this->_healthFundID;
		}
		
		public function setFirstName($firstName)
		{
			$this->_firstName = $firstName;
		}
		
		public function getFirstName()
		{
			return $this->_firstName;
		}
		
		public function setLastName($lastName)
		{
			$this->_lastName = $lastName;
		}
		
		public function getLastName()
		{
			return $this->_lastName;
		}
		
		public function setGender($gender)
		{
			$this->_gender = $gender;
		}
		
		public function getGender()
		{
			return $this->_gender;
		}
		
		public function setAddress($address)
		{
			$this->_address = $address;
		}
		
		public function getAddress()
		{
			return $this->_address;
		}
		
		public function setPostCode($postcode)
		{
			$this->_postcode = $postcode;
		}
		
		public function getPostCode()
		{
			return $this->_postcode;
		}
		
		public function setEmail($email)
		{
			$this->_email = $email;
		}
		
		public function getEmail()
		{
			return $this->_email;
		}
		
		public function setContactNo($contactNo)
		{
			$this->_contactNo = $contactNo;
		}
		
		public function getContactNo()
		{
			return $this->_contactNo;
		}
		
		public function setBirthday($birthday)
		{
			$this->_birthday = $birthday;
		}
		
		public function getBirthday()
		{
			return $this->_birthday;
		}
		
		public function setOccupation($occupation)
		{
			$this->_occupation = $occupation;
		}
		
		public function getOccupation()
		{
			return $this->_occupation;
		}
		
		public function setSports($sports)
		{
			$this->_sports = $sports;
		}
		
		public function getSports()
		{
			return $this->_sports;
		}
		
		public function setOtherConditions($otherConditions)
		{
			$this->_otherConditions = $otherConditions;
		}
		
		public function getOtherConditions()
		{
			return $this->_otherConditions;
		}
		
		public function setEmergencyContactName($emergencyContactName)
		{
			$this->_emergencyContactName = $emergencyContactName;
		}
		
		public function getEmergencyContactName()
		{
			return $this->_emergencyContactName;
		}
		
		public function setEmergencyContactNo($emergencyContactNo)
		{
			$this->_emergencyContactNo = $emergencyContactNo;
		}
		
		public function getEmergencyContactNo()
		{
			return $this->_emergencyContactNo;
		}
		
		public function setFindings($findings)
		{
			$this->_findings = $findings;
		}
		
		public function getFindings()
		{
			return $this->_findings;
		}
		
		public function setConditions($conditions)
		{
			$this->_conditions = $conditions;
		}
		
		public function getConditions()
		{
			return $this->_conditions;
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




