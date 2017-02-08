<?php
	require_once '../controller/DataAccess.php';
	
	class ReportDataMapper
	{
		private $_dataAccess;
		
		public function ReportDataMapper()
		{
			$this->_dataAccess = new DataAccess();
		}
		
		public function getDailyCommission($date)
		{
			$sql_details = "
			select therapist.therapist_name, sum(massage_record.massage_record_commission) as massage_record_commission, sum(massage_record_request_reward) as massage_record_request_reward, sum(massage_record.massage_record_commission) + sum(massage_record_request_reward) as massage_record_commission_total
			from massage_record
			join therapist on massage_record.therapist_id = therapist.therapist_id
			where massage_record_date = '{$date}'
				and massage_record_void_user = 0
			group by therapist.therapist_name
			union
            select concat(therapist.therapist_name, ' (Reception)') as therapist_name, sum(reception_record.reception_record_std_com), sum(reception_record.reception_record_extra_com), sum(reception_record.reception_record_total_com)
            from reception_record
            join therapist on therapist.therapist_id = reception_record.therapist_id
            where reception_record.reception_record_date = '{$date}'
            	and reception_record.reception_record_void_user = 0
            group by therapist.therapist_name";
			
			$sql_total = "
					select 'Total', null, null, ifnull(sum(massage_record_commission_total), 0)
					from (
						{$sql_details}
					) as details";
						
			$sql = "
					{$sql_details}
					union
					{$sql_total}";
				
			return $this->_dataAccess->select($sql);
		}
		
		public function getDailyIncome($date)
		{
			$sql = "
					select paid_by, sum(amount) as amount
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
						union
			            select 'Cash' as paid_by, ifnull(sum(sale_cash), 0) as amount
						from sale
						where sale_date = '{$date}'
							and sale_void_user = 0
						union
						select 'Credit' as paid_by, ifnull(sum(sale_credit), 0) as amount
						from sale
						where sale_date = '{$date}'
							and sale_void_user = 0
					) as income_details
			        group by paid_by";
				
			$income = $this->_dataAccess->select($sql);
			$total_amount = $this->getDailyIncomeSummary($date)[0]['amount'];
			array_push($income, ["paid_by" => "Total", "amount" => $total_amount]);
			
			return $income;
		}
		
		public function getDailyRelatedIncomeInfo($date)
		{
			$sql = "
					select 'Redeemed Voucher' as info, ifnull(sum(massage_record_voucher), 0) as amount
					from massage_record
					where massage_record_date = '{$date}'
						and massage_record_void_user = 0
					union
					select 'Used Free Stamp' as info, ifnull(sum(massage_record_stamp), 0) as amount
					from massage_record
					where massage_record_date = '{$date}'
						and massage_record_void_user = 0
					";
			
			return $this->_dataAccess->select($sql);
		}
		
		public function getDailyIncomeSummary($date)
		{
			/*$sql = "
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
					) as income_sum
			";*/
			
			$sql = "
					select ifnull(sum(amount), 0) as amount
					from (
					    select ifnull(sum(massage_record_cash + massage_record_credit + massage_record_hicaps), 0) as amount
					    from massage_record
					    where  massage_record_date = '{$date}'
					        and massage_record_void_user = 0
					    union
					    select ifnull(sum(sale_cash + sale_credit), 0) as amount
					    from sale
					    where sale_date = '{$date}'
					        and sale_void_user = 0
					) as income_sum
					";
			
			return $this->_dataAccess->select($sql);
		}
		
		public function getClientContacts()
		{
			$sql = "
					select concat(client_first_name, ' ', client_last_name) as client_name, client_contact_no, client_email
					from client
					order by client_first_name, client_last_name";
			
			return $this->_dataAccess->select($sql);
		}
	}
?>













