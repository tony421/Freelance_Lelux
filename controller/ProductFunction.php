<?php
	require_once '../controller/Session.php';
	require_once '../controller/ProductDataMapper.php';
	require_once '../controller/Utilities.php';
	
	class ProductFunction
	{
		private $_dataMapper;
	
		public function ProductFunction()
		{
			$this->_dataMapper = new ProductDataMapper();
		}
		
		public function getProducts()
		{
			$result = $this->_dataMapper->getProducts();
		
			if (count($result) > 0) {
				return Utilities::getResponseResult(true, '', $result);
			}
			else {				
				Utilities::logInfo("There is no product data in the system.");
				return Utilities::getResponseResult(true, 'There is no product data in the system!', []);
			}
		} // getProducts
		
		public function getProductsDisplay()
		{
			$result = $this->_dataMapper->getProductsDisplay();
		
			if (count($result) > 0) {
				return Utilities::getResponseResult(true, '', $result);
			}
			else {				
				Utilities::logInfo("There is no product data in the system.");
				return Utilities::getResponseResult(true, 'There is no product data in the system!', []);
			}
		} // getProductsDisplay
		
		public function addProduct($productInfo)
		{				
			$affectedRow = $this->_dataMapper->addProduct($productInfo);
				
			if ($affectedRow > 0) {
				return Utilities::getResponseResult(true, 'New product has been inserted successfully.');
			}
			else {
				return Utilities::getResponseResult(false, 'Adding new product has failed!');
			}
		} // addProduct
		
		public function updateProduct($productInfo)
		{				
			$affectedRow = $this->_dataMapper->updateProduct($productInfo);
		
			if ($affectedRow > 0) {
				return Utilities::getResponseResult(true, 'Updating product has been successful.');
			}
			else {
				return Utilities::getResponseResult(false, 'Updating product has failed!');
			}
		} // updateProduct
		
		public function deleteProduct($productInfo)
		{
			$affectedRow = $this->_dataMapper->deleteProduct($productInfo);
		
			if ($affectedRow > 0) {
				return Utilities::getResponseResult(true, 'Deleting product has been successful.');
			}
			else {
				return Utilities::getResponseResult(false, 'Deleting product has failed!');
			}
		} // deleteProduct
	}
?>







