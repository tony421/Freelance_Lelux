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
					, massage_record_id
					, therapist.therapist_id, therapist.therapist_active
					, massage_record_date
					, therapist.therapist_name, massage_record_requested, massage_record_minutes
					, date_format(massage_record_time_in, '%H:%i') as massage_record_time_in
					, date_format(massage_record_time_out, '%H:%i') as massage_record_time_out
					, massage_record_time_in as massage_record_date_time_in 
					, massage_record_time_out as massage_record_date_time_out
					, concat(date_format(massage_record_time_in, '%l:%i %p'), ' - ', date_format(massage_record_time_out, '%l:%i %p')) as massage_record_time_in_out
					, massage_record_stamp, massage_record_cash, massage_record_promotion
					, massage_record_credit, massage_record_hicaps, massage_record_voucher
					, massage_record_cash + massage_record_credit + massage_record_hicaps + massage_record_voucher as massage_record_paid_total 
					, massage_record_commission, massage_record_request_reward
					, massage_record_commission + massage_record_request_reward as massage_record_commission_total
					, massage_type.massage_type_id, massage_type.massage_type_name, massage_type.massage_type_active, massage_type.massage_type_commission
					, room_no
					, booking_item.booking_item_id, booking.booking_name, booking.booking_tel
				from massage_record
				join therapist on therapist.therapist_id = massage_record.therapist_id
				join massage_type on massage_type.massage_type_id = massage_record.massage_type_id
				left join booking_item on booking_item.booking_item_id = massage_record.booking_item_id
				left join booking on booking.booking_id = booking_item.booking_id
				where massage_record_date = '$date'
					and massage_record_void_user = 0
				order by massage_record_time_in, massage_record_time_out, massage_record_create_datetime asc;";
				
				return $this->_dataAccess->select($sql);
			}
			catch(Exception $e) {
				throw $e;
			}
		} // getRecords()
		
		public function addRecord($recordInfo, $bookingItemID = 0)
		{
			$sql = "
					insert into massage_record (
						therapist_id
						, massage_type_id
						, massage_record_minutes
						, massage_record_requested, massage_record_request_reward
						, massage_record_promotion, massage_record_commission
						, massage_record_cash, massage_record_credit, massage_record_hicaps
						, massage_record_voucher, massage_record_stamp, massage_record_date
						, massage_record_create_user, massage_record_create_datetime
						, massage_record_time_in, massage_record_time_out
						, room_no
						, booking_item_id
					)
					values (
						{$recordInfo['therapist_id']}
						, {$recordInfo['massage_type_id']}
						, {$recordInfo['massage_record_minutes']}
						, {$recordInfo['massage_record_requested']}, {$recordInfo['massage_record_request_reward']}
						, {$recordInfo['massage_record_promotion']}, {$recordInfo['massage_record_commission']}
						, {$recordInfo['massage_record_cash']}, {$recordInfo['massage_record_credit']}, {$recordInfo['massage_record_hicaps']}
						, {$recordInfo['massage_record_voucher']}, {$recordInfo['massage_record_stamp']}, '{$recordInfo['massage_record_date']}'
						, {$recordInfo['massage_record_create_user']}, '{$recordInfo['massage_record_create_datetime']}'
						, '{$recordInfo['massage_record_time_in']}', '{$recordInfo['massage_record_time_out']}'
						, {$recordInfo['room_no']}
						, {$bookingItemID}
					)";
			
			return $this->_dataAccess->insert($sql);
		} // addRecord
		
		public function updateRecord($recordInfo)
		{
			$sql = "update massage_record
					set therapist_id = {$recordInfo['therapist_id']}
						, massage_type_id = {$recordInfo['massage_type_id']}
						, massage_record_requested = {$recordInfo['massage_record_requested']}
						, massage_record_minutes = {$recordInfo['massage_record_minutes']}
						, massage_record_stamp = {$recordInfo['massage_record_stamp']}
						, massage_record_cash = {$recordInfo['massage_record_cash']}
						, massage_record_promotion = {$recordInfo['massage_record_promotion']}
						, massage_record_credit = {$recordInfo['massage_record_credit']}
						, massage_record_hicaps = {$recordInfo['massage_record_hicaps']}
						, massage_record_voucher = {$recordInfo['massage_record_voucher']}
						, massage_record_commission = {$recordInfo['massage_record_commission']}
						, massage_record_request_reward = {$recordInfo['massage_record_request_reward']}
						, massage_record_update_user = {$recordInfo['massage_record_update_user']}
						, massage_record_update_datetime = '{$recordInfo['massage_record_update_datetime']}'
						, massage_record_time_in = '{$recordInfo['massage_record_time_in']}'
						, massage_record_time_out = '{$recordInfo['massage_record_time_out']}'
						, room_no = {$recordInfo['room_no']}
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
		
		public function deleteRecordByBookingItem($bookingItemID)
		{
			$sql = "
				delete from massage_record
				where booking_item_id = {$bookingItemID}
			";
			
			return $this->_dataAccess->delete($sql);
		}
		
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
					select 'Voucher' as paid_by, ifnull(sum(massage_record_voucher), 0) as amount
					from massage_record
					where massage_record_date = '{$date}'
						and massage_record_void_user = 0
					union
					select 'Free Stamp' as paid_by, ifnull(sum(massage_record_stamp), 0) as amount
					from massage_record
					where massage_record_date = '{$date}'
						and massage_record_void_user = 0";
			
			return $this->_dataAccess->select($sql);
		} // getIncomeDailyReport
	} // class
?>






