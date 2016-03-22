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
		
		public function isClientExist($client)
		{
			try {
				$sql_format = "
						select client_id
						from client
						where client_membership_no = '%s'
							and client_patient_id = %d";
					
				$sql = sprintf($sql_format,
						$client->getMembershipNo(),
						$client->getPatientID());
					
				$result = $this->_dataAccess->select($sql);
					
				if (count($result) > 0)
						return true;
					else
						return false;
		
			} catch (Exception $e) {
				throw $e;
			}
		} // isClientExist
		
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
				
				$sql = sprintf($sql_format, join(",", $conditionValues));
				
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
from client
join health_fund on client.health_fund_id = health_fund.health_fund_id";
				
				if ($search['search_membership'] == "true") {
					$sql_format .= "
where client_membership_no like '%%%s%%'";
					
					$sql = sprintf($sql_format, $search['search_text']);
				}
				else {
					$sql_format .= "
where client_first_name like '%%%s%%'
	or client_last_name like '%%%s%%'
	or concat(client_first_name, ' ', client_last_name) like '%%%s%%'";
					
					$sql = sprintf($sql_format, $search['search_text'], $search['search_text'], $search['search_text']);
				}
				
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
				$sql_format = "select * from client where client_id = '%s'";
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
	}
?>









