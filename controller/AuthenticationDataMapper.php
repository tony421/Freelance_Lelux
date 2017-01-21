<?php
	require_once '../controller/DataAccess.php';
	
	class AuthenticationDataMapper
	{
		private $_dataAccess;
	
		public function AuthenticationDataMapper()
		{
			$this->_dataAccess = new DataAccess();
		}
		
		public function verifyUser($loginInfo)
		{
			$sql_format = "select * from therapist where lower(therapist_name) = lower('%s') and lower(therapist_password) = lower('%s')";
				
			$sql = sprintf($sql_format
					, $loginInfo['therapist_username']
					, $loginInfo['therapist_password']);
				
			return $this->_dataAccess->select($sql);
		} // verifyUser
		
		public function changePassword($passwordInfo)
		{
			$sql_format = "
					update therapist
					set therapist_password = '%s'
						, therapist_update_datetime = NOW()
					where therapist_id = '%s'
						and lower(therapist_password) = lower('%s')";
			
			$sql = sprintf($sql_format
					, $passwordInfo['therapist_new_password']
					, $passwordInfo['therapist_id']
					, $passwordInfo['therapist_old_password']);
			
			return $this->_dataAccess->update($sql);
		} // changePassword
	}
?>










