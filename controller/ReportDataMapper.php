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
						select 1 as seq, shift.therapist_id, therapist.therapist_name, therapist.therapist_guarantee
							, ifnull(massage_record_minutes, 0) as massage_record_minutes
							, ifnull(massage_record_commission, 0) as massage_record_commission
							, ifnull(massage_record_request_reward, 0) as massage_record_request_reward
							, ifnull(massage_record_commission + massage_record_request_reward, 0) as massage_record_commission_total
						from shift
						left join massage_record on shift.therapist_id = massage_record.therapist_id
					    	and massage_record_date = '{$date}'
							and massage_record_void_user = 0
						join therapist on therapist.therapist_id = shift.therapist_id
						where shift.shift_date = '{$date}'
							and shift.shift_type_id != 6 -- not Reception!
						union all
						select 9 as seq, therapist.therapist_id, therapist.therapist_name as therapist_name, reception_record_total_com as therapist_guarantee, null, reception_record_std_com, reception_record_extra_com, reception_record_total_com
						from reception_record
						join therapist on therapist.therapist_id = reception_record.therapist_id
						where reception_record.reception_record_date = '{$date}'
							and reception_record.reception_record_void_user = 0
					) as com_details
					left join shift on shift.therapist_id = com_details.therapist_id and shift.shift_date = '{$date}'
					left join shift_type on shift_type.shift_type_id = shift.shift_type_id
					group by therapist_name";
			
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
		
		public function getHicapReport($dateStart, $dateEnd, $providers, $hicaps)
		{
			$provider_condition = "";
			$hicap_condition = "";
			
			// Providers and Hicaps received as string via GET method
			/*
			 * if (count($providers) > 0)
				$provider_condition = "and provider.provider_id in (".implode(",", $providers).")";
			
			if (count($hicaps) > 0)
				$hicap_condition = "and health_fund.health_fund_id in (".implode(",", $hicaps).")";
			*/
			
			$provider_condition = "and provider.provider_id in (".$providers.")";
			$hicap_condition = "and health_fund.health_fund_id in (".$hicaps.")";
			
			$sql = "
select report.report_date 
	, provider.provider_name, health_fund.health_fund_name
	, client.client_membership_no, client.client_patient_id
	, concat(client.client_first_name, ' ', client.client_last_name) as client_name
    , therapist.therapist_name
from report
join client on client.client_id = report.client_id
join health_fund on health_fund.health_fund_id = client.health_fund_id
join provider on provider.provider_id = report.provider_id
join therapist on therapist.therapist_id = report.therapist_id
where report.report_date between '{$dateStart}' and '{$dateEnd}'
	and report.report_void_user = 0
    {$provider_condition}
    {$hicap_condition}
order by report.report_date
	, provider.provider_id
    , health_fund.health_fund_name
    , client.client_membership_no, client.client_patient_id
    , client_name";
			
			return $this->_dataAccess->select($sql);
		}
		
		public function getRequestAmtReport($dateStart, $dateEnd, $therapists)
		{
			$therapist_condition = "and therapist.therapist_id in (".$therapists.")";
			
			$sql = "
select therapist_name, date, request_amt
from (
    select therapist_name
        , date_format(massage_record_date, '%b %Y') as date
    	, date_format(massage_record_date, '%y%m') as seq
        , sum(massage_record_requested) as request_amt
    from massage_record
    join therapist on therapist.therapist_id = massage_record.therapist_id
    where massage_record_date between '{$dateStart}' and '{$dateEnd}'
    	and massage_record.therapist_id != 0
    	{$therapist_condition}
    group by therapist.therapist_name, date, seq
    union all
    select therapist_name
        , '== Total =='as date
    	, 999999 as seq
        , sum(massage_record_requested) as request_amt
    from massage_record
    join therapist on therapist.therapist_id = massage_record.therapist_id
    where massage_record_date between '{$dateStart}' and '{$dateEnd}'
    	and massage_record.therapist_id != 0
    	{$therapist_condition}
    group by therapist.therapist_name
) as Request_Summary
order by therapist_name, seq";
			
			return $this->_dataAccess->select($sql);
		}
	}
?>













