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
			$sql = "select * from therapist where therapist_id != 0 and therapist_active = 1 order by therapist_name";
				
			return $this->_dataAccess->select($sql);
		}
		
		public function getOnlyTherapists() {
			// do not select receptions
			$sql = "
select * 
from therapist 
where therapist_active = 1
	and therapist_permission != 7 -- Receptions
	and therapist_permission != 0 -- Unknown
order by therapist_name
";
			
			return $this->_dataAccess->select($sql);
		}
		
		public function getTherapistsForManagement()
		{
			$sql = "select * from therapist where therapist_active = 1 and therapist_permission != 9 and therapist_permission != 0 order by therapist_name";		
			return $this->_dataAccess->select($sql);
		}
		
		public function getTherapistsWithUnknown()
		{
			$sql = "select * from therapist where therapist_active = 1 order by therapist_name";
		
			return $this->_dataAccess->select($sql);
		}
		
		public function getTherapistsOffShift($date)
		{
			$sql = "
					select therapist.therapist_id, therapist.therapist_name
					from therapist 
					where therapist.therapist_active = 1
						and therapist.therapist_id != 0
						and therapist.therapist_id not in (
							select shift.therapist_id
							from shift
							where shift.shift_date = '{$date}'
						)
					order by therapist.therapist_name";
		
			return $this->_dataAccess->select($sql);
		}
		
		public function getTherapistsOnShift($date, $showAllStaff = true, $receptionIncluded = true)
		{
			$conditionReception = '';
			if (!$receptionIncluded)
				$conditionReception = 'and shift.shift_type_id != 6';
			
			$conditionShowAllStaff = '';
			if (!$showAllStaff)
				$conditionShowAllStaff = 'and shift.shift_working = 1';
				
			$sql = "
					select therapist.therapist_id, therapist.therapist_name, therapist.therapist_guarantee
						, shift.shift_id, shift.shift_working
						, shift_type.shift_type_id, shift_type.shift_type_name, shift_type.shift_type_rate
						, shift_time_start, shift_create_datetime, shift_type_color
					from therapist
					join shift on therapist.therapist_id = shift.therapist_id
					join shift_type on shift.shift_type_id = shift_type.shift_type_id
					where shift.shift_date = '{$date}'
						{$conditionReception}
						{$conditionShowAllStaff}
					order by shift.shift_time_start, shift.shift_create_datetime";
		
			return $this->_dataAccess->select($sql);
		}
		
		public function getTherapistsWorkingOnShift($date)
		{
			$sql = "
			select therapist.therapist_id, therapist.therapist_name, therapist.therapist_guarantee
				, shift.shift_id, shift.shift_working
				, shift_type.shift_type_name, shift_type.shift_type_rate
			from therapist
			join shift on therapist.therapist_id = shift.therapist_id
			join shift_type on shift.shift_type_id = shift_type.shift_type_id
			where shift.shift_date = '{$date}'
				and shift.shift_working = 1
				and shift.shift_type_id != 6
			order by therapist.therapist_name";
		
			return $this->_dataAccess->select($sql);
		}
		
		public function addTherapist($therapistInfo)
		{
			$sql_format = "
					insert into therapist
						(therapist_name, therapist_password, therapist_guarantee, therapist_permission, therapist_active)
					values ('%s', '%s', %.2f, 1, 1)";
			
			$sql = sprintf($sql_format
					, $therapistInfo['therapist_name']
					//, $therapistInfo['therapist_username']
					, $therapistInfo['therapist_password']
					, $therapistInfo['therapist_guarantee']);
			
			return $this->_dataAccess->insert($sql);
		} // addTherapist
		
		public function updateTherapist($therapistInfo)
		{
			$sql_format = "
					update therapist
					set therapist_name = '%s'
						, therapist_password = '%s'
						, therapist_guarantee= %.2f
						, therapist_update_datetime = NOW()
					where therapist_id = %d";
			
			$sql = sprintf($sql_format
					, $therapistInfo['therapist_name']
					//, $therapistInfo['therapist_username']
					, $therapistInfo['therapist_password']
					, $therapistInfo['therapist_guarantee']
					, $therapistInfo['therapist_id']);
			
			return $this->_dataAccess->update($sql);
		} // updateTherapist
		
		public function deleteTherapist($therapistInfo)
		{
			$sql_format = "
					update therapist
					set therapist_active = 0
						, therapist_update_datetime = NOW()
					where therapist_id = %d";
				
			$sql = sprintf($sql_format
					, $therapistInfo['therapist_id']);
				
			return $this->_dataAccess->update($sql);
		} // updateTherapist
		
		public function isExistedTherapistName($therapistInfo)
		{
			$sql_format = "
						select therapist_id
						from therapist
						where (
							lower(therapist_name) = lower('%s')
							and therapist_active = 1
							and therapist_id != %d
						)";
				
			$sql = sprintf($sql_format
					, $therapistInfo['therapist_name']
					//, $therapistInfo['therapist_username']
					, $therapistInfo['therapist_id']);
		
			$result = $this->_dataAccess->select($sql);
		
			if (count($result) > 0)
				return true;
			else
				return false;
		} // isExistedTherapistName
		
		public function getShiftType()
		{
			$sql = "select * from shift_type order by shift_type_seq";
			
			return $this->_dataAccess->select($sql);
		}
		
		public function addTherapistToShift($shiftInfo, $shiftWorking)
		{
			$sql = "
					insert into shift (shift_date, therapist_id, shift_type_id, shift_time_start, shift_working)
					values ('{$shiftInfo['shift_date']}', {$shiftInfo['therapist_id']}, {$shiftInfo['shift_type_id']}, '{$shiftInfo['shift_time_start']}', {$shiftWorking})";
			
			return $this->_dataAccess->insert($sql);
		}
		
		public function updateTherapistOnShift($shiftInfo)
		{
			$sql = "
					update shift
					set shift_update_datetime = now()
						, shift_type_id = {$shiftInfo['shift_type_id']}
						, shift_time_start = '{$shiftInfo['shift_time_start']}'
					where shift_id = {$shiftInfo['shift_id']}
					";
			
			return $this->_dataAccess->update($sql);
		}
		
		public function workTherapistOnShift($shiftID)
		{
			$sql = "
			update shift
			set shift_working = 1
			where shift_id = {$shiftID}";
				
			return $this->_dataAccess->update($sql);
		}
		
		public function absentTherapistOnShift($shiftID)
		{
			$sql = "
					update shift
					set shift_working = 0
					where shift_id = {$shiftID}";
			
			return $this->_dataAccess->update($sql);
		}
		
		public function deleteTherapistOnShift($shiftID)
		{
			$sql = "
			delete from shift
			where shift_id = {$shiftID}";
				
			return $this->_dataAccess->delete($sql);
		}
		
		public function deleteAllTherapistOnShift($date)
		{
			$sql = "
			delete from shift
			where shift_date = '{$date}'";
		
			return $this->_dataAccess->delete($sql);
		}
	}
?>








