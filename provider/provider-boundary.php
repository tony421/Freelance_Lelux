<?php
	require_once '../controller/ProviderFunction.php';
	require_once '../controller/Utilities.php';
	
	if (!empty($_POST['mode'])) {
		$result;
	
		try {
			$mode = $_POST['mode'];
			Utilities::logInfo('Provider-Boundary | mode: '.$mode);
				
			$providerFunction = new ProviderFunction();
				
			if ($mode == "GET_PROVIDER") {
				$result = $providerFunction->getProviders();
			}
			else if ($mode == "GET_PROVIDER_DISPLAY") {
				$result = $providerFunction->getProvidersDisplay();
			}
			else if ($mode == "ADD_PROVIDER") {
				$providerInfo = $_POST['data'];
				$result = $providerFunction->addProvider($providerInfo);
			}
			else if ($mode == "UPDATE_PROVIDER") {
				$providerInfo = $_POST['data'];
				$result = $providerFunction->updateProvider($providerInfo);
			}
			else if ($mode == "DELETE_PROVIDER") {
				$providerInfo = $_POST['data'];
				$result = $providerFunction->deleteProvider($providerInfo);
			}
			else {
				throw new Exception('Mode not found');
			}
		}
		catch(Exception $e) {
			Utilities::logError(sprintf("Error Code: %s\nMessage: %s\nFile: %s\nLine: %s\nStack Trace:%s"
					, $e->getCode(), $e->getMessage(), $e->getFile(), $e->getLine(), var_export($e->getTrace(), true)));
				
			$result = Utilities::getResponseResult(false, 'System error occurred!, please contact admin.');
		}
		
		Utilities::logInfo('Provider-Boundary | result: '.var_export($result, true));
		echo json_encode($result);
	}
	else {
		echo json_encode('Mode is empty');
	}
		
	exit;
?>