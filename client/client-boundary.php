<?php
	require_once '../controller/ClientFunction.php';
	
	if (!empty($_POST['mode']))
	{
		$result;
		
		try {
			$mode = $_POST['mode'];
			$clientFunction = new ClientFunction();
			
			if ($mode == 'ADD_CLIENT') {
					$clientInfo = $_POST['data'];
					$result = $clientFunction->addClient($clientInfo);
			}
			else if ($mode == 'SEARCH_CLIENT') {
					$search = $_POST['data'];
					$result = $clientFunction->searchClient($search);
			}
			else {
				throw new Exception('Mode not found');
			}
		}
		catch(Exception $e) {
			//$isSuccess = false;
			$result = array('msg' => $e->getMessage(),'code' => $e->getCode());
		}
		
		echo json_encode($result);
	}
	else {
		//throw new Exception('Mode is empty');
		echo json_encode('Mode is empty');
	}
	
	exit;
?>









