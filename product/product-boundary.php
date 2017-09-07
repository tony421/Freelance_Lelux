<?php
	require_once '../controller/Authentication.php';
	require_once '../controller/ProductFunction.php';
	//require_once '../controller/Utilities.php';
	
	Utilities::handleError(); // when an error happens you use this function to catch the error
	
	if (Authentication::userExists()) {
		if (!empty($_POST['mode'])) {
			$result;
		
			try {
				$mode = $_POST['mode'];
				Utilities::logInfo('Product-Boundary | mode: '.$mode);
					
				$productFunction = new ProductFunction();
					
				if ($mode == "GET_PRODUCT") {
					$result = $productFunction->getProducts();
				}
				else if ($mode == "GET_PRODUCT_DISPLAY") {
					$result = $productFunction->getProductsDisplay();
				}
				else if ($mode == "ADD_PRODUCT") {
					$productInfo = $_POST['data'];
					Utilities::logInfo('Product-Boundary | data[productInfo]: '.var_export($productInfo, true));
					
					$result = $productFunction->addProduct($productInfo);
				}
				else if ($mode == "UPDATE_PRODUCT") {
					$productInfo = $_POST['data'];
					Utilities::logInfo('Product-Boundary | data[productInfo]: '.var_export($productInfo, true));
					
					$result = $productFunction->updateProduct($productInfo);
				}
				else if ($mode == "DELETE_PRODUCT") {
					$productInfo = $_POST['data'];
					Utilities::logInfo('Product-Boundary | data[productInfo]: '.var_export($productInfo, true));
					
					$result = $productFunction->deleteProduct($productInfo);
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
			
			Utilities::logInfo('Product-Boundary | result: '.var_export($result, true));
			echo json_encode($result);
		}
		else {
			echo json_encode('Mode is empty');
		}
	} else {
		$result = Utilities::getTimeoutResponseResult();
		
		echo json_encode($result);
	}
		
	exit;
?>