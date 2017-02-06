<?php
	require_once '../controller/DataAccess.php';
	
	class ReportDataMapper
	{
		private $_dataAccess;
		
		private $_sql_shop_income = "
				";
		
		public function ReportDataMapper()
		{
			$this->_dataAccess = new DataAccess();
		}
		
		public function getDailyCommission($date)
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
		}
		
		public function getDailyIncome($date)
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
			select 'Redeemed Voucher' as paid_by, ifnull(sum(massage_record_voucher), 0) as amount
			from massage_record
			where massage_record_date = '{$date}'
			and massage_record_void_user = 0
			union
			select 'Free Stamp' as paid_by, ifnull(sum(massage_record_stamp), 0) as amount
			from massage_record
			where massage_record_date = '{$date}'
			and massage_record_void_user = 0";
				
			return $this->_dataAccess->select($sql);
		}
		
		public function getDailyShopIncomeSummary($date)
		{
			$sql = "
					select sum(amount) as amount
					from (
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
					) as shop_income_details
			";
			
			return $this->_dataAccess->select($sql);
		}
	}
?>













