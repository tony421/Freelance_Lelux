<?php
	require_once '../controller/DataAccess.php';
	
	class ProductDataMapper
	{
		private $_dataAccess;
		
		public function ProductDataMapper()
		{
			$this->_dataAccess = new DataAccess();
		}
		
		public function getProducts()
		{
			$sql = "select * from product where product_active = 1 order by product_id";
				
			return $this->_dataAccess->select($sql);
		}
		
		public function getProductsDisplay()
		{
			$sql = "select product_id, product_name, product_price from product order where product_active = 1 by product_id";
		
			return $this->_dataAccess->select($sql);
		}
		
		public function addProduct($productInfo)
		{
			$sql_format = "
					insert into product
						(product_price, product_name, product_price_changeable, product_active, product_update_datetime)
					values (%2f, '%s', %s, true, NOW())";
			
			$sql = sprintf($sql_format
					, $productInfo['product_price']
					, $productInfo['product_name']
					, $productInfo['product_price_changeable']);
			
			return $this->_dataAccess->insert($sql);
		} // addProduct
		
		public function updateProduct($productInfo)
		{
			$sql_format = "
					update product
					set product_price = %2f
						, product_name = '%s'
						, product_price_changeable = %s
						, product_update_datetime = NOW()
					where product_id = %d";
			
			$sql = sprintf($sql_format
					, $productInfo['product_price']
					, $productInfo['product_name']
					, $productInfo['product_price_changeable']
					, $productInfo['product_id']);
			
			return $this->_dataAccess->update($sql);
		} // updateProduct
		
		public function deleteProduct($productInfo)
		{
			$sql_format = "
					update product
					set product_active = false
						, product_update_datetime = NOW()
					where product_id = %d";
				
			$sql = sprintf($sql_format
					, $productInfo['product_id']);
				
			return $this->_dataAccess->update($sql);
		} // deleteProduct
	}
?>