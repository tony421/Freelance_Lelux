<?php
	require_once '../controller/DataAccess.php';
	
	class TherapistDataMapper
	{
		private $_dataAccess;
		
		public function TherapistDataMapper()
		{
			$this->_dataAccess = new DataAccess();
		}
		
		public function getTherapists()
		{
			$sql = "select * from therapist order by therapist_name";
				
			return $this->_dataAccess->select($sql);
		}
		
		public function getTherapistsForManagement()
		{
			$sql = "select * from therapist where therapist_permission != 9 order by therapist_name";
		
			return $this->_dataAccess->select($sql);
		}
		
		public function addTherapist($therapistInfo)
		{
			$sql_format = "
					insert into therapist
						(therapist_name, therapist_username, therapist_password, therapist_permission)
					values ('%s', '%s', '%s', 1)";
			
			$sql = sprintf($sql_format
					, $therapistInfo['therapist_name']
					, $therapistInfo['therapist_username']
					, $therapistInfo['therapist_password']);
			
			return $this->_dataAccess->insert($sql);
		} // addTherapist
		
		public function updateTherapist($therapistInfo)
		{
			$sql_format = "
					update therapist
					set therapist_name = '%s'
						, therapist_username = '%s'
						, therapist_password = '%s'
						, therapist_update_datetime = NOW()
					where therapist_id = '%s'";
			
			$sql = sprintf($sql_format
					, $therapistInfo['therapist_name']
					, $therapistInfo['therapist_username']
					, $therapistInfo['therapist_password']
					, $therapistInfo['therapist_id']);
			
			return $this->_dataAccess->update($sql);
		} // updateTherapist
		
		public function isExistedTherapistName($therapistInfo)
		{
			$sql_format = "
						select therapist_id
						from therapist
						where (
							(therapist_name = '%s' or therapist_username = '%s')
							and therapist_id != '%s'
						)";
				
			$sql = sprintf($sql_format
					, $therapistInfo['therapist_name']
					, $therapistInfo['therapist_username']
					, $therapistInfo['therapist_id']);
		
			$result = $this->_dataAccess->select($sql);
		
			if (count($result) > 0)
				return true;
			else
				return false;
		} // isExistedTherapistName
	}
?>