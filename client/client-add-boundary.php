<?php
	require_once '../controller/ClientFunction.php';
	
	if (!empty($_POST['mode']))
	{
		$mode = $_POST['mode'];
		$clientFunction = new ClientFunction();
		
		if ($mode == 'ADD_CLIENT')
		{
			try {
				$clientInfo = $_POST['data'];
				$isSuccess = $clientFunction->addClient($clientInfo);
			}
			catch(Exception $e)
			{
				//$isSuccess = false;
				$isSuccess = array('error' => array('msg' => $e->getMessage(),'code' => $e->getCode()));
			}
		
			echo json_encode($isSuccess);
		}
		else
		{
			throw new Exception('Mode not found');
			//echo json_encode('Mode not found');
		}
	}
	else
	{
		throw new Exception('Mode is empty');
		//echo json_encode('Mode is empty');
	}
	
	exit;
?>









