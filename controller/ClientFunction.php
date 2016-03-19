<?php
	require_once '../model/Client.php';
	require_once '../controller/Utilities.php';
	
	class ClientFunction
	{
		public function addClient($clientInfo)
		{
			$client = $this->generateClientModel($clientInfo);
			
		}
		
		private function generateClientModel($clientInfo)
		{
			$client = new Client(Utilities::getUniqueID());
			$client->setMembershipNo($clientInfo['client_membership_no']);
			$client->setPatientID($clientInfo['client_patient_id']);
			$client->setHealthFundID($clientInfo['health_fund_id']);
			$client->setFirstName($clientInfo['client_first_name']);
			$client->setLastName($clientInfo['client_last_name']);
			$client->setGender($clientInfo['client_gender']);
			$client->setAddress($clientInfo['client_address']);
			$client->setPostCode($clientInfo['client_postcode']);
			$client->setEmail($clientInfo['client_email']);
			$client->setContactNo($clientInfo['client_contact_no']);
			$client->setBirthday($clientInfo['client_birthday']);
			$client->setOccupation($clientInfo['client_occupation']);
			$client->setSports($clientInfo['client_sports']);
			$client->setOtherConditions($clientInfo['client_other_conditions']);
			$client->setEmergencyContanctName($clientInfo['client_emergency_contact_name']);
			$client->setEmergencyContactNo($clientInfo['client_emergency_contact_no']);
			$client->setFindings($this->generateClientFindingModels($client->getID(), $clientInfo['client_findings']));
			$client->setConditions($this->generateClientConditionModels($client->getID(), $clientInfo['client_conditions']));
			
			return $client;
		}
		
		private function generateClientFindingModels($clientID, $clientFindingsInfo)
		{
			$clientFindings = array();
			$clientFinding;
			
			foreach ($clientFindingsInfo as $finding) {
				$clientFinding = new ClientFinding($clientID, $finding['finding_type_id']);
				$clientFinding->setChecked($finding['client_finding_checked']);
				$clientFinding->setRemark($finding['client_finding_remark']);
				
				array_push($clientFindings, $clientFinding);
			}
			
			return $clientFindings;
		}
		
		private function generateClientConditionModels($clientID, $clientConditionsInfo)
		{
			$clientConditions = array();
			$clientCondition;
				
			foreach ($clientConditionsInfo as $condition) {
				$clientCondition = new ClientCondition($clientID, $condition['condition_type_id']);
				$clientCondition->setChecked($condition['client_condition_checked']);
				$clientCondition->setRemark($condition['client_condition_remark']);
		
				array_push($clientConditions, $clientCondition);
			}
				
			return $clientConditions;
		}
	}
?>









