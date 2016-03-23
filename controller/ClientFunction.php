<?php
	require_once '../model/Client.php';
	require_once '../controller/ClientDataMapper.php';
	require_once '../controller/Utilities.php';
	
	class ClientFunction
	{
		const MODE_ADD = 1;
		const MODE_UPDATE = 2;
		const MODE_VOID = 3;
		
		private $_dataMapper;
		
		public function ClientFunction()
		{
			$this->_dataMapper = new ClientDataMapper();
		}
		
		public function addClient($clientInfo)
		{
			$client = $this->generateClientModel($clientInfo, self::MODE_ADD);
			
			// test
			//return print_r($client->getFindings());
			
			if ($this->_dataMapper->isClientExist($client))
			{
				return Utilities::getResponseResult(false, 'Membership Number ['.$client->getMembershipNo().'] and Patient ID ['.$client->getPatientID().'] already existed, please check the infotmation.');
			}
			else
			{
				// test
				//return $this->_dataMapper->insertFindings($client);
				//return $this->_dataMapper->insertCondition($client);
				
				$affectedRow = $this->_dataMapper->insertClient($client);
				$this->_dataMapper->insertFindings($client);
				$this->_dataMapper->insertConditions($client);
				
				//return Utilities::getResponseResult(true, 'New client has been inserted successfully.');
				return Utilities::getResponseResult(true, 'New client has been inserted successfully.', $client->getID());
				
				/*
				if ($affectedRow > 0)
						return Utilities::getResponseResult(true, 'New client has been inserted successfully.');
					else
						return Utilities::getResponseResult(false, 'Inserting new client has failed!');
				*/
			}
			
			//return print_r($client->getConditions());
			//return 'Example Return';
		} // addClient
		
		public function searchClients($search)
		{
			$result = $this->_dataMapper->searchClients($search);
			$countResult = count($result);
			
			if ($countResult > 0)
			{
				$msg = ($countResult > 1) ? 'The '.$countResult.' clients are found' : 'The only 1 client is found';
				return Utilities::getResponseResult(true, $msg, $result);
			}
			else 
			{
				return Utilities::getResponseResult(false, 'The search has not found!');
			}
		} // searchClient
		
		public function getClientInfo($clientID)
		{
			$result = $this->_dataMapper->getClientInfo($clientID);
			
			if (count($result) > 0) {
				//$client = $this->generateClientModel($result[0], true);
				//return print_r($client);
				
				$clientInfo = $result[0];
				$clientInfo['client_birthday'] = $clientInfo['client_birthday'] != '0000-00-00' ? Utilities::convertDateForDisplay($clientInfo['client_birthday']) : "";
				$clientInfo['client_findings'] = $this->_dataMapper->getFindingsInfo($clientID);
				$clientInfo['client_conditions'] = $this->_dataMapper->getConditionsInfo($clientID);
				
				//return print_r(Utilities::getResponseResult(true, '', $client));
				return Utilities::getResponseResult(true, '', $clientInfo);
			}
			else {
				return Utilities::getResponseResult(false, 'There is no any clients who has id['.$clientID.'].');
			}
		} // getClientInfo
		
		public function updateClient($editedClientInfo)
		{
			$client = $this->generateClientModel($editedClientInfo, self::MODE_UPDATE);
			
			$affectedRow = $this->_dataMapper->updateClient($client);
			$affectedRow = $this->_dataMapper->updateClientFindings($client->getFindings());
			
			return Utilities::getResponseResult(true, 'test return | updated row: '.$affectedRow);
		} // updateClient
		
		private function generateClientModel($clientInfo, $mode = self::MODE_ADD)
		{
			$client;
			
			switch ($mode) {
				case self::MODE_ADD :
					$client = new Client(Utilities::getUniqueID());
					$client->setCreateUser('default');
					$client->setCreateDateTime(Utilities::getDateTimeNowForDB());
					break;
				case self::MODE_UPDATE:
					$client = new Client($clientInfo['client_id']);
					$client->setUpdateUser('default');
					$client->setUpdateDateTime(Utilities::getDateTimeNowForDB());
					break;
				case self::MODE_VOID:
					$client = new Client($clientInfo['client_id']);
					$client->setVoidUser('default');
					$client->setVoidDateTime(Utilities::getDateTimeNowForDB());
					break;
			}
			
			$client->setFindings($this->generateClientFindingModels($client->getID(), $clientInfo['client_findings']));
			$client->setConditions($this->generateClientConditionModels($client->getID(), $clientInfo['client_conditions']));
			$client->setPatientID(empty($clientInfo['client_patient_id']) ? 1 : $clientInfo['client_patient_id']);
			$client->setBirthday(empty($clientInfo['client_birthday']) ? "" : Utilities::convertDateForDB($clientInfo['client_birthday']));
			
			$client->setMembershipNo($clientInfo['client_membership_no']);
			$client->setHealthFundID($clientInfo['health_fund_id']);
			$client->setFirstName($clientInfo['client_first_name']);
			$client->setLastName($clientInfo['client_last_name']);
			$client->setGender($clientInfo['client_gender']);
			$client->setAddress($clientInfo['client_address']);
			$client->setPostCode($clientInfo['client_postcode']);
			$client->setEmail($clientInfo['client_email']);
			$client->setContactNo($clientInfo['client_contact_no']);
			$client->setOccupation($clientInfo['client_occupation']);
			$client->setSports($clientInfo['client_sports']);
			$client->setOtherConditions($clientInfo['client_other_conditions']);
			$client->setEmergencyContactName($clientInfo['client_emergency_contact_name']);
			$client->setEmergencyContactNo($clientInfo['client_emergency_contact_no']);
			
			return $client;
		} // generateClientModel
		
		private function generateClientFindingModels($clientID, $clientFindingsInfo)
		{
			$clientFindings = array();
			$clientFinding;
			
			foreach ($clientFindingsInfo as $finding) {
				$clientFinding = new ClientFinding($clientID, $finding['finding_type_id']);
				
				//$checked = ($finding['client_finding_checked'] === 'true') ? true : false;
				//$clientFinding->setChecked($checked);
				$clientFinding->setChecked($finding['client_finding_checked']);
				
				if ($clientFinding->getChecked() === 'true')
					$clientFinding->setRemark($finding['client_finding_remark']);
				
				array_push($clientFindings, $clientFinding);
			}
			
			return $clientFindings;
		} // generateClientFindingModels
		
		private function generateClientConditionModels($clientID, $clientConditionsInfo)
		{
			$clientConditions = array();
			$clientCondition;
				
			foreach ($clientConditionsInfo as $condition) {
				$clientCondition = new ClientCondition($clientID, $condition['condition_type_id']);
				
				//$checked = ($condition['client_condition_checked'] === 'true') ? true : false;
				//$clientCondition->setChecked($checked);
				$clientCondition->setChecked($condition['client_condition_checked']);
				
				if ($clientCondition->getChecked() === 'true')
					$clientCondition->setRemark($condition['client_condition_remark']);
		
				array_push($clientConditions, $clientCondition);
			}
				
			return $clientConditions;
		} // generateClientConditionModels
	}
?>









