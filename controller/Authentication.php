<?php
	require_once '../controller/Session.php';
	require_once '../controller/AuthenticationDataMapper.php';
	require_once '../controller/Utilities.php';
	require_once '../controller/ReceptionFunction.php';
	
	require_once '../model/Therapist.php';
	
	class Authentication
	{
		private $_dataMapper;
		
		public function Authentication()
		{
			$this->_dataMapper = new AuthenticationDataMapper();
		}
		
		public function login($loginInfo)
		{
			$therapistInfo = $this->_dataMapper->verifyUser($loginInfo);
		
			if (count($therapistInfo) > 0) {
				$therapist = $this->generateTherapistModel($therapistInfo[0]);
				
				$receptionFunction = new ReceptionFunction();
				if ($receptionFunction->isReceptionist($therapist->getID())) {
					if ($receptionFunction->grantReceptionistPermission($therapist->getID())) {
						$therapist->setPermission(7);
					}
				} else {
					if ($receptionFunction->revokeReceptionistPermission($therapist->getID())) {
						$therapist->setPermission(1);
					}
				}
				
				Authentication::setUser($therapist);
				Utilities::logInfo('Therapist: '.$therapist->getName().'(ID:'.$therapist->getID().') logged in the system.');
				
				return Utilities::getResponseResult(true, 'The login has succeeded.', $therapist->getPermission());
			}
			else {
				return Utilities::getResponseResult(false, 'Username or password is not correct, please try again!');
			}
		} // login
		
		private function generateTherapistModel($therapistInfo)
		{
			$therapist = new Therapist();
			$therapist->setID(Utilities::getVal($therapistInfo, 'therapist_id'));
			$therapist->setName(Utilities::getVal($therapistInfo, 'therapist_name'));
			//$therapist->setUsername(Utilities::getVal($therapistInfo, 'therapist_username'));
			$therapist->setPermission(Utilities::getVal($therapistInfo, 'therapist_permission'));
// 			$therapist->setID($therapistInfo['therapist_id']);
// 			$therapist->setName($therapistInfo['therapist_name']);
// 			$therapist->setUsername($therapistInfo['therapist_username']);
// 			$therapist->setPermission($therapistInfo['therapist_permission']);
				
			return $therapist;
		} // generateTherapistModel
		
		public function logout()
		{
			$therapist = Authentication::getUser();
			Utilities::logInfo('Therapist: '.$therapist->getName().'(ID:'.$therapist->getID().') logged out the system.');
			
			Session::destroy();
		
			return Utilities::getResponseResult(true, 'Loging out succeeded.');
		} // logoff
		
		public function changePassword($passwordInfo)
		{
			$therapist = Authentication::getUser();
			
			$passwordInfo['therapist_id'] = $therapist->getID();
			$passwordInfo['therapist_username'] = $therapist->getUsername();
			
			$affectedRow = $this->_dataMapper->changePassword($passwordInfo);
			
			if ($affectedRow > 0) {
				return Utilities::getResponseResult(true, 'The password has been changed, please try to login again.');
			}
			else {
				return Utilities::getResponseResult(false, 'Changing password has failed!, please re-enter the old password.');
			}
		} // changePassword
		
		public static function authenticateUser()
		{
			if (!Session::userExists())
				Utilities::redirect('../login/');
		}
		
		public static function authenticateAdminUser()
		{
			if (!Authentication::isAdmin())
				Utilities::redirect('../client/client-add.php');
		}
	
		public static function userExists()
		{
			return Session::userExists();
		}
	
		public static function setUser($user)
		{
			Session::setUser($user);
		}
	
		public static function getUser()
		{
			return Session::getUser();
		}
	
		public static function isAdmin()
		{
			if (Session::userExists())
			{
				$therapist = Session::getUser();
					
				if ($therapist->getPermission() == 9)
					return true;
				else 
					return false;
			}
			else
				throw new Exception('Session is expired!');
		}
		
		public static function isManager()
		{
			if (Session::userExists())
			{
				$therapist = Session::getUser();
					
				if ($therapist->getPermission() == 8)
					return true;
				else
					return false;
			}
			else
				throw new Exception('Session is expired!');
		}
		
		public static function isReception()
		{
			if (Session::userExists())
			{
				$therapist = Session::getUser();
					
				if ($therapist->getPermission() == 7)
					return true;
				else
					return false;
			}
			else
				throw new Exception('Session is expired!');
		}
		
		public static function getPermission()
		{
			if (Session::userExists())
			{
				return Session::getUser()->getPermission();
			}
			else
				throw new Exception('Session is expired!');
		}
		
		public static function permissionCheck($file)
		{
			$isAllowed = false;
			
			switch ($file) {
				case "therapist-manage.php":
				case "client-contact.php":
				case "request-amount.php":
					if (Authentication::isAdmin())
						$isAllowed = true;
					break;
				case "client-add.php":
				case "client-search.php":
				case "client-report.php":
				case "client-booking-history.php":
				case "daily-record.php":
				case "hicap.php":
					if (Authentication::isAdmin() || Authentication::isManager() || Authentication::isReception())
						$isAllowed = true;
					break;
				case "roster.php":
					$isAllowed = true;
					break;
				case "":
			}
			
			if (!$isAllowed)
				Utilities::redirect('../login/');
		}
	}
?>






