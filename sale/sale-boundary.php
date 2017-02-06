<?php
	require_once '../controller/SaleFunction.php';
	require_once '../controller/Utilities.php';
	
	//Utilities::handleError(); // when an error happens you use this function to catch the error
	
	if (!empty($_POST['mode']))
	{
		$result;
	
		try {
			$mode = $_POST['mode'];
			Utilities::logInfo('Sale-Boundary | mode: '.$mode);
	
			$saleFunction = new SaleFunction();
	
			if ($mode == 'GET_SALES') {
				$date = $_POST['data'];
				Utilities::logInfo('Sale-Boundary | data[Date]: '.var_export($date, true));
				
				$result = $saleFunction->getSales($date);
			}
			else if ($mode == 'ADD_SALE') {
				$saleInfo = $_POST['data'];
				Utilities::logInfo('Sale-Boundary | data[saleInfo]: '.var_export($saleInfo, true));
			
				$result = $saleFunction->addSale($saleInfo);
			}
			else if ($mode == 'UPDATE_SALE') {
				$saleInfo = $_POST['data'];
				Utilities::logInfo('Sale-Boundary | data[saleInfo]: '.var_export($saleInfo, true));
					
				$result = $saleFunction->updateSale($saleInfo);
			}
			else if ($mode == 'DELETE_SALE') {
				$uid = $_POST['data'];
				Utilities::logInfo('Sale-Boundary | data[uid]: '.var_export($uid, true));
					
				$result = $saleFunction->voidSale($uid);
			}
		}
		catch(Exception $e) {
			Utilities::logError(sprintf("Error Code: %s\nMessage: %s\nFile: %s\nLine: %s\nStack Trace:%s"
					, $e->getCode(), $e->getMessage(), $e->getFile(), $e->getLine(), var_export($e->getTrace(), true)));
		
			$result = Utilities::getResponseResult(false, 'System error occurred!, please contact admin.');
		}
		
		Utilities::logInfo('Sale-Boundary | result: '.var_export($result, true));
		echo json_encode($result);
	}
	else {
		echo json_encode('Mode is empty');
	}
	
	exit;
?>