<?php
	require_once '../model/Client.php';
	require_once '../controller/DataAccess.php';
	
	class ClientDataMapper
	{
		private $_dataAccess;
		
		public function ClientDataMapper()
		{
			$this->_dataAccess = new DataAccess();
		}
		
		public function isExistedClientMember($client)
		{
			try {
				$sql_format = "
						select client_id
						from client
						where (
							health_fund_id = %d
							and lower(client_membership_no) = lower('%s')
							and client_patient_id = %d
							and client_id != '%s'
						)";
					
				$sql = sprintf($sql_format,
						$client->getHealthFundID(),
						$client->getMembershipNo(),
						$client->getPatientID(),
						$client->getID());
					
				$result = $this->_dataAccess->select($sql);
					
				if (count($result) > 0)
						return true;
					else
						return false;
		
			} catch (Exception $e) {
				throw $e;
			}
		} // isExistedClientMember
		
		public function isExistedClientName($client)
		{
			try {
				$sql_format = "
							select client_id
							from client
							where (
								lower(client_first_name) = lower('%s')
								and lower(client_last_name) = lower('%s')
								and health_fund_id = %d
								and lower(client_membership_no) = lower('%s')
								and client_patient_id = %d
								and client_id != '%s'
							)";
				
				$sql = sprintf($sql_format,
						$client->getFirstName(),
						$client->getLastName(),
						$client->getHealthFundID(),
						$client->getMembershipNo(),
						$client->getPatientID(),
						$client->getID());
					
				$result = $this->_dataAccess->select($sql);
					
				if (count($result) > 0)
					return true;
				else
					return false;
			} catch (Exception $e) {
				throw $e;
			}
		} // isExistedClientName
		
		public function insertClient($client)
		{
			try {
				$sql_format = "
insert into client (
	client_id, client_membership_no, client_patient_id, health_fund_id 
	, client_first_name, client_last_name, client_gender, client_address
	, client_postcode, client_email, client_contact_no, client_birthday
	, client_occupation, client_sports, client_other_conditions
	, client_emergency_contact_name, client_emergency_contact_no
	, client_create_user, client_create_datetime)
values (
	'%s', '%s', %d, %d
	,'%s', '%s', %d, '%s'
	,'%s', '%s', '%s', '%s'
	,'%s', '%s', '%s'
	,'%s', '%s'
	,'%s', '%s')";
				
				$sql = sprintf($sql_format
						, $client->getID(), $client->getMembershipNo(), $client->getPatientID(), $client->getHealthFundID()
						, $client->getFirstName(), $client->getLastName(), $client->getGender(), $client->getAddress()
						, $client->getPostcode(), $client->getEmail(), $client->getContactNo(), $client->getBirthday()
						, $client->getOccupation(), $client->getSports(), $client->getOtherConditions()
						, $client->getEmergencyContactName(), $client->getEmergencyContactNo()
						, $client->getCreateUser(), $client->getCreateDateTime());
				
				return $this->_dataAccess->insert($sql);
			} catch (Exception $e) {
				throw $e;
			}
		} // insertClient
		
		public function insertFindings($client)
		{
			try {
				$sql_format = "
insert into client_finding(finding_type_id, client_id, client_finding_remark, client_finding_checked)
values %s";
				
				$findings = $client->getFindings();
				$findingValues = [];
				
				foreach ($findings as $item) {
					array_push($findingValues, sprintf("(%d, '%s', '%s', %s)"
							, $item->getFindingTypeID()
							, $item->getClientID()
							, $item->getRemark()
							, $item->getChecked()));
				}
				
				$sql = sprintf($sql_format, join(",", $findingValues));
				
				//return $sql;
				return $this->_dataAccess->insert($sql);
			} catch (Exception $e) {
				throw $e;
			}
		} // insertFindings
		
		public function insertConditions($client)
		{
			try {
				$sql_format = "
insert into client_condition(condition_type_id, client_id, client_condition_remark, client_condition_checked)
values %s";
				
				$conditions = $client->getConditions();
				$conditionValues = [];
				
				foreach ($conditions as $item) {
					array_push($conditionValues, sprintf("(%d, '%s', '%s', %s)"
							, $item->getConditionTypeID()
							, $item->getClientID()
							, $item->getRemark()
							, $item->getChecked()));
				}
				
				$sql = sprintf($sql_format, join(", ", $conditionValues));
				
				//return $sql;
				return $this->_dataAccess->insert($sql);
			} catch (Exception $e) {
				throw $e;
			}
		} // insertCondition
		
		public function searchClients($search)
		{
			try {
				$sql = "";
				$sql_format = "
select health_fund.health_fund_name, client.client_membership_no, client_patient_id
	, concat(client_first_name, ' ', client_last_name) as client_name, client.client_id
	, client.client_contact_no
from client
join health_fund on client.health_fund_id = health_fund.health_fund_id";
				
				if ($search['search_name'] == "true") {
					$sql_format .= "
where client_first_name like '%%%s%%'
	or client_last_name like '%%%s%%'
	or concat(client_first_name, ' ', client_last_name) like '%%%s%%'";
					
					$sql = sprintf($sql_format, $search['search_text'], $search['search_text'], $search['search_text']);
				} else if ($search['search_tel'] == "true") {
					$sql_format .= "
where client_contact_no like '%%%s%%'";
					
					$sql = sprintf($sql_format, $search['search_text']);
				}
				else { // search by Member No.
					$sql_format .= "
where client_membership_no like '%%%s%%'";
						
					$sql = sprintf($sql_format, $search['search_text']);
				}
				
				$sql .= "
order by client_name";
				
				//return $sql;
				return $this->_dataAccess->select($sql);
			}
			catch(Exception $e) {
				throw $e;
			}
		} // searchClient
		
		public function getClientInfo($clientID)
		{
			try {
				$sql_format = "
						select client.client_id, client.health_fund_id, client.client_membership_no, client.client_patient_id, client.client_first_name, client.client_last_name, client.client_gender, client.client_address, client.client_postcode, client.client_email, client.client_contact_no, client.client_birthday, client.client_occupation, client.client_sports, client.client_other_conditions, client.client_emergency_contact_name, client.client_emergency_contact_no, client.client_create_datetime, client.client_create_user, client.client_update_datetime, client.client_update_user, client.client_void_datetime, client.client_void_user
						, health_fund.health_fund_name, health_fund.health_fund_provider_no
						, case client.client_gender when 0 then \"Male\" when 1 then \"Female\" end as client_gender_desc
						from client
						join health_fund on client.health_fund_id = health_fund.health_fund_id
						where client_id = '%s'";
				$sql = sprintf($sql_format, $clientID);
				
				return $this->_dataAccess->select($sql);
			}
			catch(Exception $e) {
				throw $e;
			}
		} // getClientInfo
		
		public function getFindingsInfo($clientID)
		{
			try {
				$sql_format = "select * 
						from client_finding
						join finding_type on client_finding.finding_type_id = finding_type.finding_type_id
						where client_id = '%s'";
				$sql = sprintf($sql_format, $clientID);
				
				return $this->_dataAccess->select($sql);
			}
			catch(Exception $e) {
				throw $e;
			}
		} // getFindingsInfo
		
		public function getConditionsInfo($clientID)
		{
			try {
				$sql_format = "select * 
						from client_condition
						join condition_type on client_condition.condition_type_id = condition_type.condition_type_id
						where client_id = '%s'";
				$sql = sprintf($sql_format, $clientID);
				
				return $this->_dataAccess->select($sql);
			}
			catch(Exception $e) {
				throw $e;
			}
		} // getConditionsInfo
		
		public function updateClient($client)
		{
			$sql_format = "
					update client 
					set client_first_name = '%s',
						client_last_name = '%s',
						client_gender = %d,
						client_address = '%s',
						client_postcode = '%s',
						client_email = '%s',
						client_contact_no = '%s',
						client_birthday = '%s',
						client_occupation = '%s',
						client_sports = '%s',
						client_other_conditions = '%s',
						client_emergency_contact_name = '%s',
						client_emergency_contact_no = '%s',
						client_update_user = '%s',
						client_update_datetime = '%s',
						health_fund_id = %d,
						client_membership_no = '%s',
						client_patient_id = %d
					where client_id = '%s'";
			
			$sql = sprintf($sql_format, 
					$client->getFirstName(),
					$client->getLastName(),
					$client->getGender(),
					$client->getAddress(),
					$client->getPostcode(),
					$client->getEmail(),
					$client->getContactNo(),
					$client->getBirthday(),
					$client->getOccupation(),
					$client->getSports(),
					$client->getOtherConditions(),
					$client->getEmergencyContactName(),
					$client->getEmergencyContactNo(),
					$client->getUpdateUser(),
					$client->getUpdateDateTime(),
					$client->getHealthFundID(),
					$client->getMembershipNo(),
					$client->getPatientID(),
					$client->getID());
			
			//return $sql;
			return $this->_dataAccess->update($sql);
		} // updateClient
		
		public function updateClientFindings($clientFindings)
		{
			$sql_format = "update client_finding 
					set client_finding_checked = %s,
						client_finding_remark = '%s'
					where client_id = '%s' and finding_type_id = %d";
			
			$sqlFindings = [];
			$sql = "";
			
			foreach ($clientFindings as $item) {
				$sql = sprintf($sql_format, 
						$item->getChecked(),
						$item->getRemark(),
						$item->getClientID(),
						$item->getFindingTypeID());
				
				array_push($sqlFindings, $sql);
			}
			
			$sql = join("; ", $sqlFindings);
			
			return $this->_dataAccess->update($sql);
		} // updateClientFindings
		
		public function updateClientConditions($clientConditions)
		{
			$sql_format = "update client_condition
					set client_condition_checked = %s,
						client_condition_remark = '%s'
					where client_id = '%s' and condition_type_id = %d";
				
			$sqlConditions = [];
			$sql = "";
			
			foreach ($clientConditions as $item) {
				$sql = sprintf($sql_format,
						$item->getChecked(),
						$item->getRemark(),
						$item->getClientID(),
						$item->getConditionTypeID());
			
				array_push($sqlConditions, $sql);
			}
				
			$sql = join("; ", $sqlConditions);
				
			return $this->_dataAccess->update($sql);
		} // updateClientConditions
		
		public function insertReport($reportInfo)
		{
			$sql_format = "insert into report (report_id,
						client_id, report_date, report_detail,
						report_recommendation, report_hour, therapist_id,
						report_create_user, report_create_datetime,
						report_update_user, report_update_datetime,
						provider_id
					) 
					values ('%s',
						'%s', '%s', '%s',
						'%s', %2f, %d,
						'%s', '%s',
						'%s', '%s',
						%d
					)";
			
			$sql = sprintf($sql_format, $reportInfo['report_id'],
					$reportInfo['client_id'], $reportInfo['report_date'], $reportInfo['report_detail'],
					$reportInfo['report_recommendation'], $reportInfo['report_hour'], $reportInfo['therapist_id'],
					$reportInfo['report_create_user'], $reportInfo['report_create_datetime'],
					$reportInfo['report_update_user'], $reportInfo['report_update_datetime'],
					$reportInfo['provider_id']);
			
			return $this->_dataAccess->insert($sql);
		} // insertReport
		
		public function getReports($clientID)
		{
			$sql_format = "
					select report_id, DATE_FORMAT(report_date, '%%e %%M %%Y') as report_date
						, report_detail, report_recommendation
						, CAST(report_hour * 60 as decimal(0)) report_hour
						, report.therapist_id
						, t.therapist_name
						, t.therapist_active
						, t_create.therapist_name as report_create_user
						, DATE_FORMAT(report_create_datetime, '%%e/%%m/%%Y %%T') as report_create_datetime
						, t_update.therapist_name as report_update_user
						, DATE_FORMAT(report_update_datetime, '%%e/%%m/%%Y %%T') as report_update_datetime
						, report.provider_id, provider.provider_active, provider.provider_name
					from report 
					join therapist t on report.therapist_id = t.therapist_id
					join therapist t_create on report.report_create_user = t_create.therapist_id
					join therapist t_update on report.report_update_user = t_update.therapist_id
					join provider on report.provider_id = provider.provider_id
					where client_id = '%s'
						and report.report_void_user = 0
					order by report.report_date desc, report.report_create_datetime desc";
			$sql = sprintf($sql_format, $clientID);
			
			return $this->_dataAccess->select($sql);
		} // getReports
		
		public function updateReportItem($reportItemInfo)
		{
			$sql_format = "update report
					set therapist_id = %d,
						report_hour = %2f,
						report_detail = '%s',
						report_recommendation = '%s',
						report_update_user = '%s',
						report_update_datetime = '%s',
						provider_id = %d
					where report_id = '%s'";
			
			$sql = sprintf($sql_format, 
					$reportItemInfo['therapist_id'],
					$reportItemInfo['report_hour'],
					$reportItemInfo['report_detail'],
					$reportItemInfo['report_recommendation'],
					$reportItemInfo['report_update_user'],
					$reportItemInfo['report_update_datetime'],
					$reportItemInfo['provider_id'],
					$reportItemInfo['report_id']
				);
			
			return $this->_dataAccess->update($sql);
		} // updateReportItem
		
		public function deleteReportItem($reportItemInfo)
		{
			$sql_format = "update report
					set report_void_user = '%s',
						report_void_datetime = '%s'
					where report_id = '%s'";
			
			$sql = sprintf($sql_format,
					$reportItemInfo['report_void_user'],
					$reportItemInfo['report_void_datetime'],
					$reportItemInfo['report_id']
					);
				
			return $this->_dataAccess->delete($sql);
		} // deleteReportItem
		
		public function getTherapists()
		{
			$sql = "select * from therapist order by therapist_name";
			
			return $this->_dataAccess->select($sql);
		}
		
		public function getBookingHistory($search)
		{
			try 
			{
				$dateStart = $search['date_start'];
				$dateEnd = $search['date_end'];
				$searchByName = $search['search_name'];
				$searchByTel = $search['search_tel'];
				$text = $search['search_text'];
					
				$sql = "
select b.booking_id, b.booking_name, b.booking_tel, booking_date, b.booking_time_in, b.booking_time_out, b.booking_remark
	, ifnull(t.therapist_name, '-') as therapist_name, ifnull(mt.massage_type_name, '-') as massage_type_name
	, date_format(b.booking_date, '%e %b %Y') as booking_date_formated
	, concat(date_format(b.booking_time_in, '%l:%i %p'), ' - ', date_format(b.booking_time_out, '%l:%i %p')) as booking_time_formated
from booking b
join booking_item bi on bi.booking_id = b.booking_id
left join massage_record m on m.booking_item_id = bi.booking_item_id
left join therapist t on t.therapist_id = m.therapist_id
left join massage_type mt on mt.massage_type_id = m.massage_type_id
where 1 = 1
	and b.booking_date between '{$dateStart}' and '{$dateEnd}'
	and (
		({$searchByName} and b.booking_name like '%{$text}%')
		or
		({$searchByTel} and b.booking_tel like '%{$text}%')
	)
order by b.booking_name, b.booking_date";
					
				return $this->_dataAccess->select($sql);
			}
			catch (Exception $e) {
				throw $e;
			}
		}
	}
?>









