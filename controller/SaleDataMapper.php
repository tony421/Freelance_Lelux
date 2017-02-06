<?php
	require_once '../controller/DataAccess.php';
	
	class SaleDataMapper
	{
		private $_dataAccess;
	
		public function SaleDataMapper()
		{
			$this->_dataAccess = new DataAccess();
		}
		
		public function getSalesForDisplay($date)
		{
			$sql = "
					select sale.sale_id, sale.sale_uid, sale.sale_date
						, sale.sale_time, sale.sale_total, sale.sale_cash, sale.sale_credit
					    , item.sale_item_id, item.sale_item_amount, item.sale_item_price, item.sale_item_total
					    , product.product_id, product.product_name, product.product_price_changeable, product.product_active
					from sale
					join sale_item item on item.sale_uid = sale.sale_uid
					join product on product.product_id = item.product_id
					where sale.sale_date = '{$date}'
						and sale.sale_void_user = 0
					    and item.sale_item_void_user = 0
					order by sale.sale_create_datetime desc, product.product_id
					";
			
			return $this->_dataAccess->select($sql);
		}
		
		public function getSales($date)
		{
			$sql = "
					select sale_id, sale_uid, sale_date, sale_time, sale_total, sale_cash, sale_credit
					from sale
					where sale.sale_date = '{$date}' 
						and sale.sale_void_user = 0
					";
			
			return $this->_dataAccess->select($sql);
		}
		
		public function getSale($uid)
		{
			$sql = "
			select sale_id, sale_uid, sale_date, sale_time, sale_total, sale_cash, sale_credit
			from sale
			where sale.sale_uid = '{$uid}'
			";
				
			return $this->_dataAccess->select($sql);
		}
		
		public function getSaleItems($uid)
		{
			$sql = "
					select item.sale_uid, item.sale_item_id
						, item.sale_item_amount, item.sale_item_price, item.sale_item_total
					    , product.product_id, product.product_name, product.product_price_changeable, product.product_active
					    , item.sale_item_create_user, item.sale_item_void_user, 0 as sale_item_new_update
					from sale_item item
					join product on item.product_id = product.product_id
					where item.sale_uid = '{$uid}'
						and item.sale_item_void_user = 0
					order by product.product_id
					";
			
			return $this->_dataAccess->select($sql);
		}
		
		public function addSale($saleInfo)
		{
			$sql = "insert into sale (
						sale_uid, sale_date, sale_time
						, sale_total, sale_cash, sale_credit
						, sale_create_user, sale_create_datetime
					)
					values (
						'{$saleInfo['sale_uid']}', '{$saleInfo['sale_date']}', '{$saleInfo['sale_time']}'
						, {$saleInfo['sale_total']}, {$saleInfo['sale_cash']}, {$saleInfo['sale_credit']}
						, {$saleInfo['sale_create_user']}, '{$saleInfo['sale_create_datetime']}'
					)";
			
			return $this->_dataAccess->insert($sql);
		}
		
		public function updateSale($saleInfo)
		{
			$sql = "
					update sale
					set sale_total = {$saleInfo['sale_total']}
						, sale_cash = {$saleInfo['sale_cash']}
						, sale_credit = {$saleInfo['sale_credit']}
						, sale_update_user = {$saleInfo['sale_update_user']}
						, sale_update_datetime = '{$saleInfo['sale_update_datetime']}'
					where sale_id = {$saleInfo['sale_id']} 
					";
			
			return $this->_dataAccess->update($sql);
		}
		
		public function addSaleItem($saleItemInfo, $uid)
		{
			$sql = "insert into sale_item (
						sale_uid, product_id
						, sale_item_amount, sale_item_price, sale_item_total
						, sale_item_create_user, sale_item_create_datetime
					)
					values (
						'{$uid}', {$saleItemInfo['product_id']}
						, {$saleItemInfo['sale_item_amount']}, {$saleItemInfo['sale_item_price']}, {$saleItemInfo['sale_item_total']}
						, {$saleItemInfo['sale_item_create_user']}, '{$saleItemInfo['sale_item_create_datetime']}'
					)";
			
			return $this->_dataAccess->insert($sql);
		}
		
		public function updateSaleItem($saleItemInfo)
		{
			$sql = "
					update sale_item
					set sale_item_amount = {$saleItemInfo['sale_item_amount']}
						, sale_item_price = {$saleItemInfo['sale_item_price']}
						, sale_item_total = {$saleItemInfo['sale_item_total']}
						, product_id = {$saleItemInfo['product_id']}
						, sale_item_update_user = {$saleItemInfo['sale_item_update_user']}
						, sale_item_update_datetime = '{$saleItemInfo['sale_item_update_datetime']}' 
					where sale_item_id = {$saleItemInfo['sale_item_id']}
					";
			
			return $this->_dataAccess->update($sql);
		}
		
		public function voidSale($saleInfo)
		{
			$sql = "update sale
					set sale_void_user = {$saleInfo['sale_void_user']}
						, sale_void_datetime = '{$saleInfo['sale_void_datetime']}'
					where sale_uid = '{$saleInfo['sale_uid']}'";
			
			return $this->_dataAccess->update($sql);
		}
		
		public function voidAllSaleItems($saleItemInfo)
		{
			$sql = "update sale_item
					set sale_item_void_user = {$saleItemInfo['sale_item_void_user']}
						, sale_item_void_datetime = '{$saleItemInfo['sale_item_void_datetime']}'
					where sale_uid = '{$saleItemInfo['sale_uid']}'";
			
			return $this->_dataAccess->update($sql);
		}
		
		public function voidSaleItem($saleItemInfo)
		{
			$sql = "
					update sale_item
					set sale_item_void_user = {$saleItemInfo['sale_item_void_user']}
						, sale_item_void_datetime = '{$saleItemInfo['sale_item_void_datetime']}' 
					where sale_item_id = {$saleItemInfo['sale_item_id']}
					";
			
			return $this->_dataAccess->update($sql);
		}
		
		public function rollbackSaleInfo($uid) 
		{
			$sql = "delete from sale where sale_uid = '{$uid}';
				delete from sale_item where sale_uid = '{$uid}'";
			
			return $this->_dataAccess->delete($sql);
		}
	}
?>