<?php
	require_once '../controller/DataAccess.php';
	
	class ReceptionDataMapper
	{
		private $_dataAccess;
		
		public function ReceptionDataMapper()
		{
			$this->_dataAccess = new DataAccess();
		}
		
		public function getRecords($date)
		{
			$sql = "
					select 0 as row_no, reception_record_id
						, therapist.therapist_id, therapist.therapist_name, therapist.therapist_active
					    , reception_record_date, reception_record_late_night
					    , reception_record_whole_day, reception_record_hour
					    , reception_record_shop_income, reception_record_std_com
					    , reception_record_extra_com, reception_record_total_com
					    , DATE_FORMAT(reception_record_update_datetime, '%e/%m/%Y %T') as reception_record_update_datetime
					from reception_record
					join therapist on therapist.therapist_id = reception_record.therapist_id
					where reception_record_date = '{$date}'
						and reception_record_void_user = 0
					order by reception_record_create_datetime asc;
					";
			
			return $this->_dataAccess->select($sql);
		}
		
		public function addRecord($recordInfo)
		{
			$sql = "
					insert into reception_record (
						therapist_id
						, reception_record_date
						, reception_record_late_night
						, reception_record_whole_day
						, reception_record_hour
						, reception_record_shop_income
						, reception_record_std_com
						, reception_record_extra_com
						, reception_record_total_com
						, reception_record_create_user
						, reception_record_create_datetime
						, reception_record_update_user
						, reception_record_update_datetime
					)
					values (
						{$recordInfo['therapist_id']}
						, '{$recordInfo['reception_record_date']}'
						, {$recordInfo['reception_record_late_night']}
						, {$recordInfo['reception_record_whole_day']}
						, {$recordInfo['reception_record_hour']}
						, {$recordInfo['reception_record_shop_income']}
						, {$recordInfo['reception_record_std_com']}
						, {$recordInfo['reception_record_extra_com']}
						, {$recordInfo['reception_record_total_com']}
						, {$recordInfo['reception_record_create_user']}
						, '{$recordInfo['reception_record_create_datetime']}'
						, {$recordInfo['reception_record_create_user']}
						, '{$recordInfo['reception_record_create_datetime']}'
					)
					";
			
			return $this->_dataAccess->insert($sql);
		}
		
		public function updateRecord($recordInfo)
		{
			$sql = "update reception_record
					set therapist_id = {$recordInfo['therapist_id']}
						, reception_record_late_night = {$recordInfo['reception_record_late_night']}
						, reception_record_whole_day = {$recordInfo['reception_record_whole_day']}
						, reception_record_hour = {$recordInfo['reception_record_hour']}
						, reception_record_shop_income = {$recordInfo['reception_record_shop_income']}
						, reception_record_std_com = {$recordInfo['reception_record_std_com']}
						, reception_record_extra_com = {$recordInfo['reception_record_extra_com']}
						, reception_record_total_com = {$recordInfo['reception_record_total_com']}
						, reception_record_update_user = {$recordInfo['reception_record_update_user']}
						, reception_record_update_datetime = '{$recordInfo['reception_record_update_datetime']}'
					where reception_record_id = {$recordInfo['reception_record_id']}";
			
			return $this->_dataAccess->update($sql);
		}
		
		public function voidRecord($recordInfo)
		{
			$sql = "update reception_record
					set reception_record_void_user = {$recordInfo['reception_record_void_user']}
						, reception_record_void_datetime = '{$recordInfo['reception_record_void_datetime']}'
					where reception_record_id = {$recordInfo['reception_record_id']}";
			
			return $this->_dataAccess->update($sql);
		}
		
		public function getReceptionistOnShift($date)
		{
			$sql = "
				select therapist.therapist_id, therapist.therapist_name, therapist.therapist_guarantee
					, shift.shift_id, shift.shift_working
					, shift_type.shift_type_id, shift_type.shift_type_name, shift_type.shift_type_rate
					, shift_time_start, shift_create_datetime, shift_type_color
				from therapist
				join shift on therapist.therapist_id = shift.therapist_id
				join shift_type on shift.shift_type_id = shift_type.shift_type_id
				where shift.shift_date = '{$date}'
					and shift.shift_type_id = 6
				order by shift.shift_time_start, shift.shift_create_datetime";
			
			return $this->_dataAccess->select($sql);
		}
		
		public function getAssignedReceptionistRoleAmt($therapistID)
		{
			$receptionist_type_id = 6;
			
			$sql = "
					select count(shift.shift_id) as amt
					from shift
					where shift.therapist_id = {$therapistID}
						and shift.shift_type_id = {$receptionist_type_id}";
			
			return $this->_dataAccess->select($sql);
		}
		
		public function grantReceptionistPermission($therapistID)
		{
			$receptionist_permission = 7;
			
			$sql = "
					update therapist
					set therapist_permission = {$receptionist_permission}
					where therapist_id = {$therapistID}
						and therapist_permission not in (9, 8)";
			
			return $this->_dataAccess->update($sql);
		}
		
		public function revokeReceptionistPermission($therapistID)
		{
			$sql = "
					update therapist
					set therapist_permission = 1
					where therapist_id = {$therapistID}
						and therapist_permission not in (9, 8)";
			
			return $this->_dataAccess->update($sql);
		}
	}
?>

















