<?php
	require_once '../controller/DataAccess.php';
	
	class ReportDataMapper
	{
		private $_dataAccess;
		
		public function ReportDataMapper()
		{
			$this->_dataAccess = new DataAccess();
		}
		
		public function getClientYearOption()
		{
			$sql = "
					select distinct date_format(client.client_create_datetime, '%Y') as year
					from client
					order by year desc";
			
			return $this->_dataAccess->select($sql);
		}
		
		public function getDailyCommission($date)
		{
			$sql_details = "
			select seq, com_details.therapist_id, therapist_name
				, therapist_guarantee * ifnull(shift_type.shift_type_rate, 1) as therapist_guarantee
				, sum(massage_record_minutes) as massage_record_minutes
				, sum(massage_record_commission) as massage_record_commission
				, sum(massage_record_request_reward) as massage_record_request_reward
				, sum(massage_record_commission_total) as massage_record_commission_total
			from (
				select 1 as seq, therapist.therapist_id, therapist.therapist_name, therapist_guarantee, massage_record_minutes, massage_record_commission, massage_record_request_reward, massage_record_commission + massage_record_request_reward as massage_record_commission_total
				from massage_record
				join therapist on massage_record.therapist_id = therapist.therapist_id
				where massage_record_date = '{$date}'
					and massage_record_void_user = 0
				union all
	            select 9 as seq, therapist.therapist_id, therapist.therapist_name as therapist_name, reception_record_total_com as therapist_guarantee, null, reception_record_std_com, reception_record_extra_com, reception_record_total_com
	            from reception_record
	            join therapist on therapist.therapist_id = reception_record.therapist_id
	            where reception_record.reception_record_date = '{$date}'
	            	and reception_record.reception_record_void_user = 0
	        ) as com_details
	        left join shift on shift.therapist_id = com_details.therapist_id and shift.shift_date = '{$date}'
	        left join shift_type on shift_type.shift_type_id = shift.shift_type_id
	        group by therapist_name
	        ";
			
			$sql_total = "
					select 99 as seq, null, 'Total', null, null, null, null, sum(massage_record_commission_total)
					from (
						{$sql_details}
					) as details
					order by seq, therapist_name";
						
			$sql = "
					{$sql_details}
					union
					{$sql_total}";
				
			return $this->_dataAccess->select($sql_details);
		}
		
		public function getDailyIncome($date)
		{
			$sql = "
					select paid_by, sum(amount) as amount
			        from (
			            select 'Cash' as paid_by, ifnull(massage_record_cash, 0) as amount
						from massage_record
						where massage_record_date = '{$date}'
							and massage_record_void_user = 0
						union all
						select 'Credit' as paid_by, ifnull(massage_record_credit, 0) as amount
						from massage_record
						where massage_record_date = '{$date}'
							and massage_record_void_user = 0
						union all
						select 'HICAPS' as paid_by, ifnull(massage_record_hicaps, 0) as amount
						from massage_record
						where massage_record_date = '{$date}'
							and massage_record_void_user = 0
						union all
			            select 'Cash' as paid_by, ifnull(sale_cash, 0) as amount
						from sale
						where sale_date = '{$date}'
							and sale_void_user = 0
						union all
						select 'Credit' as paid_by, ifnull(sale_credit, 0) as amount
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
					select info, sum(amount) as amount
					from (
						select 'Redeemed Voucher' as info, massage_record_minutes as amount
						from massage_record
						where massage_record_date = '{$date}'
							and massage_record_void_user = 0
							and massage_record_voucher != 0
						union all
						select 'Used Free Stamp' as info, massage_record_stamp as amount
						from massage_record
						where massage_record_date = '{$date}'
							and massage_record_void_user = 0
					) as details
					group by info
					";
			
			return $this->_dataAccess->select($sql);
		}
		
		public function getDailyIncomeSummary($date)
		{
			// MySQL => "union"/"union all" sum statements directly result in incorrect result!!
			// Solution => need to union all statements as sub query and sum then by the outer statement
			//
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
					select sum(amount) as amount
					from (
					    select massage_record_cash + massage_record_credit + massage_record_hicaps as amount
					    from massage_record
					    where  massage_record_date = '{$date}'
					        and massage_record_void_user = 0
					    union all
					    select sale_cash + sale_credit as amount
					    from sale
					    where sale_date = '{$date}'
					        and sale_void_user = 0
					) as income_sum
					";
			
			return $this->_dataAccess->select($sql);
		}
		
		public function getClientContacts($year, $month)
		{
			if ($year == null || $month == null) {
				$sql = "
					select concat(client_first_name, ' ', client_last_name) as client_name, client_contact_no, client_email
					from client
					order by client_first_name, client_last_name";
			} else {
				$sql = "
					select concat(client_first_name, ' ', client_last_name) as client_name, client_contact_no, client_email
					from client
					where client_create_datetime like '{$year}-{$month}%'
					order by client_first_name, client_last_name";
					
			}
			
			return $this->_dataAccess->select($sql);
		}
	}
?>













