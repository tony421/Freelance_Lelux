<?php
	require_once '../controller/DataAccess.php';
	
	class MassageDataMapper
	{
		private $_dataAccess;
		
		public function MassageDataMapper()
		{
			$this->_dataAccess = new DataAccess();
		}
		
		public function getRecords($date)
		{
			try {
				// Using set @rowno=0; and query cause an error on parsing json, need to generate row_no manually
				// the same as using multiple queries
				
				$sql = "select 0 as row_no
					, massage_record_id, therapist.therapist_id, massage_record_date
					, therapist.therapist_name, massage_record_requested, massage_record_minutes
					, massage_record_stamp, massage_record_cash, massage_record_promotion
					, massage_record_credit, massage_record_hicaps
					, massage_record_commission, massage_record_request_reward
					, massage_record_commission + massage_record_request_reward as massage_record_commission_total
				from massage_record
				join therapist on therapist.therapist_id = massage_record.therapist_id
				where massage_record_date = '$date'
					and massage_record_void_user = 0
				order by massage_record_create_datetime asc;";
				
				return $this->_dataAccess->select($sql);
			}
			catch(Exception $e) {
				throw $e;
			}
		} // getRecords()
		
		public function addRecord($recordInfo)
		{
			$sql = "
					insert into massage_record (
						therapist_id, massage_record_minutes
						, massage_record_requested, massage_record_request_reward
						, massage_record_promotion, massage_record_commission
						, massage_record_cash, massage_record_credit, massage_record_hicaps
						, massage_record_stamp, massage_record_date
						, massage_record_create_user, massage_record_create_datetime
					)
					values (
						{$recordInfo['therapist_id']}, {$recordInfo['massage_record_minutes']}
						, {$recordInfo['massage_record_requested']}, {$recordInfo['massage_record_request_reward']}
						, {$recordInfo['massage_record_promotion']}, {$recordInfo['massage_record_commission']}
						, {$recordInfo['massage_record_cash']}, {$recordInfo['massage_record_credit']}, {$recordInfo['massage_record_hicaps']}
						, {$recordInfo['massage_record_stamp']}, '{$recordInfo['massage_record_date']}'
						, {$recordInfo['massage_record_create_user']}, '{$recordInfo['massage_record_create_datetime']}'
					)";
			
			return $this->_dataAccess->insert($sql);
		} // addRecord
		
		public function updateRecord($recordInfo)
		{
			$sql = "update massage_record
					set therapist_id = {$recordInfo['therapist_id']}
						, massage_record_requested = {$recordInfo['massage_record_requested']}
						, massage_record_minutes = {$recordInfo['massage_record_minutes']}
						, massage_record_stamp = {$recordInfo['massage_record_stamp']}
						, massage_record_cash = {$recordInfo['massage_record_cash']}
						, massage_record_promotion = {$recordInfo['massage_record_promotion']}
						, massage_record_credit = {$recordInfo['massage_record_credit']}
						, massage_record_hicaps = {$recordInfo['massage_record_hicaps']}
						, massage_record_commission = {$recordInfo['massage_record_commission']}
						, massage_record_request_reward = {$recordInfo['massage_record_request_reward']}
						, massage_record_update_user = {$recordInfo['massage_record_update_user']}
						, massage_record_update_datetime = '{$recordInfo['massage_record_update_datetime']}'
					where massage_record_id = {$recordInfo['massage_record_id']}";
			
			return $this->_dataAccess->update($sql);
		} // updateRecord
		
		public function voidRecord($recordInfo)
		{
			$sql = "update massage_record
					set massage_record_void_user = {$recordInfo['massage_record_void_user']}
						, massage_record_void_datetime = '{$recordInfo['massage_record_void_datetime']}'
					where massage_record_id = {$recordInfo['massage_record_id']}";
			
			return $this->_dataAccess->update($sql);
		} // voidRecord
		
		public function getCommissionDailyReport($date)
		{
			$sql = "
					select therapist.therapist_name, sum(massage_record.massage_record_commission) as massage_record_commission, sum(massage_record_request_reward) as massage_record_request_reward, sum(massage_record.massage_record_commission) + sum(massage_record_request_reward) as massage_record_commission_total
					from massage_record
					join therapist on massage_record.therapist_id = therapist.therapist_id
					where massage_record_date = '{$date}'
						and massage_record_void_user = 0
					group by therapist.therapist_name
					union
					select 'Total', null, null, ifnull(sum(massage_record.massage_record_commission) + sum(massage_record_request_reward), 0)
					from massage_record
					where massage_record_date = '{$date}'
						and massage_record_void_user = 0";
			
			return $this->_dataAccess->select($sql);
		} // getCommissionDailyReport
		
		public function getIncomeDailyReport($date)
		{
			$sql = "
					select 'Cash' as paid_by, ifnull(sum(massage_record_cash), 0) as amount
					from massage_record
					where massage_record_date = '{$date}'
						and massage_record_void_user = 0
					union
					select 'Credit' as paid_by, ifnull(sum(massage_record_credit), 0) as amount
					from massage_record
					where massage_record_date = '{$date}'
						and massage_record_void_user = 0
					union
					select 'HICAPS' as paid_by, ifnull(sum(massage_record_hicaps), 0) as amount
					from massage_record
					where massage_record_date = '{$date}'
						and massage_record_void_user = 0
					union
					select 'Stamp' as paid_by, ifnull(sum(massage_record_stamp), 0) as amount
					from massage_record
					where massage_record_date = '{$date}'
						and massage_record_void_user = 0";
			
			return $this->_dataAccess->select($sql);
		} // getIncomeDailyReport
	} // class
?>






