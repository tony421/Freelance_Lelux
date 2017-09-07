<?php
	require_once '../controller/Authentication.php';
	require_once '../controller/SaleDataMapper.php';
	require_once '../controller/Config.php';
	require_once '../controller/Utilities.php';

	class SaleFunction
	{
		const MODE_ADD = 1;
		const MODE_UPDATE = 2;
		const MODE_VOID = 3;
		const MODE_VOID_ALL = 4;
		
		private $_dataMapper;
		
		public function SaleFunction()
		{
			$this->_dataMapper = new SaleDataMapper();
		}
		
		public function getSales($date)
		{
			$salesDisplay = $this->_dataMapper->getSalesForDisplay($date);
			$sales = $this->_dataMapper->getSales($date);
			
			$previousID = 0;
			for ($i = 0, $rowNo = count($sales); $i < count($salesDisplay); $i++) {
				$salesDisplay[$i]['row_no'] = '';
				$salesDisplay[$i]['sale_date'] = Utilities::convertDateForDisplay($salesDisplay[$i]['sale_date']);
				
				if ($salesDisplay[$i]['sale_id'] != $previousID) {
					$salesDisplay[$i]['row_no'] = $rowNo--;
				}
				else {
					$salesDisplay[$i]['row_no'] = $rowNo + 1;
				}
				
				$previousID = $salesDisplay[$i]['sale_id'];
			}
			
			for ($i = 0; $i < count($sales); $i++) {
				$items = $this->_dataMapper->getSaleItems($sales[$i]['sale_uid']);
				
				$sales[$i]['sale_items'] = $items;
			}
			
			$result['sales_display'] = $salesDisplay;
			$result['sales'] = $sales;
			
			return Utilities::getResponseResult(true, '', $result);
		}
		
		public function getSaleReceipt($uid)
		{
			$sale = $this->_dataMapper->getSale($uid)[0]; // index 0 to get the only 1 sale
			$sale['sale_items'] = $this->_dataMapper->getSaleItems($uid);
			
			return $sale;
		}
		
		public function addSale($saleInfo)
		{
			$uid = Utilities::getUniqueID();
			$saleInfo['sale_uid'] = $uid;
			
			// if the sale added on the current date, then set the current time, otherwise set default (00:00:00)
			if ($saleInfo['sale_date'] == Utilities::getDateNowForDB())
				$saleInfo['sale_time'] = Utilities::getTimeNow();
			else
				$saleInfo['sale_time'] = '00:00:00';
			
			$saleInfo['sale_total'] = $this->calSaleTotal($saleInfo['sale_items']);
			
			$affectedRow = $this->pushSaleToDB($saleInfo, self::MODE_ADD);
				
			if ($affectedRow > 0) {
				for ($i = 0; $i < count($saleInfo['sale_items']); $i++) {
					$affectedRow = $this->pushSaleItemToDB($saleInfo['sale_items'][$i], self::MODE_ADD, $uid);
					
					// Debuging section
					//Utilities::logDebug('$affectedRow = '.$affectedRow); 
					
					// if any error occurs during adding, updating or voiding sale items, then delete all info related to the UID
					if ($affectedRow < 1) {
						$this->_dataMapper->rollbackSaleInfo($uid);
						return Utilities::getResponseResult(false, 'Adding a new sale has failed!');
					}
				}
				
				return Utilities::getResponseResult(true, 'The sale has been added successfully.');
			}
			else {
				return Utilities::getResponseResult(false, 'Adding a new sale has failed!');
			}
		}
		
		public function updateSale($saleInfo)
		{
			$saleInfo['sale_total'] = $this->calSaleTotal($saleInfo['sale_items']);
			$affectedRow = $this->pushSaleToDB($saleInfo, self::MODE_UPDATE);
			
			if ($affectedRow > 0) {
				// update each items
				
				$uid = $saleInfo['sale_uid'];
				for ($i = 0; $i < count($saleInfo['sale_items']); $i++) {
					// if sale_item_create_user == 0, add
					// else 
					//		if sale_item_void_user != 0, void
					// 		else if sale_item_new_update != 0, update
					if ($saleInfo['sale_items'][$i]['sale_item_create_user'] == 0) {
						// Add
						Utilities::logDebug('SALE ITEM >>>>> ADD');
						$affectedRow = $this->pushSaleItemToDB($saleInfo['sale_items'][$i], self::MODE_ADD, $uid);
					} else {
						if ($saleInfo['sale_items'][$i]['sale_item_void_user'] != 0) {
							// void
							Utilities::logDebug('SALE ITEM >>>>> VOID');
							$affectedRow = $this->pushSaleItemToDB($saleInfo['sale_items'][$i], self::MODE_VOID);
						} else {
							// update, if there is any
							Utilities::logDebug('SALE ITEM >>>>> UPDATE');
							$affectedRow = $this->pushSaleItemToDB($saleInfo['sale_items'][$i], self::MODE_UPDATE);
						}
					}
					
					if ($affectedRow < 1)
						Utilities::logInfo('Sale item id [' + $saleInfo['sale_uid'] + ":" + $saleInfo['sale_items'][$i]['sale_item_id'] + '] was executed unsuccessfully!!!');
				}
				
				return Utilities::getResponseResult(true, 'Updating the sale has been successful.');
			}
			else {
				return Utilities::getResponseResult(false, 'Updating the sale has failed!');
			}
		}
		
		public function voidSale($uid) {
			$saleInfo['sale_uid'] = $uid;
			$affectedRow = $this->pushSaleToDB($saleInfo, self::MODE_VOID);
			
			if ($affectedRow > 0) {
				$saleItemInfo['sale_uid'] = $uid;
				$affectedRow = $this->pushSaleItemToDB($saleItemInfo, self::MODE_VOID_ALL);
				
				if ($affectedRow < 1)
					Utilities::logInfo('Sale item id [' + $saleInfo['sale_uid'] + ":" + $saleInfo['sale_items'][$i]['sale_item_id'] + '] was executed unsuccessfully!!!');
				
				return Utilities::getResponseResult(true, 'Updating the sale has been successful.');
			} else {
				return Utilities::getResponseResult(false, 'Deleting the sale has failed!');
			}
		}
		
		private function calSaleTotal($saleItems)
		{
			$total = 0.0;
			for ($i = 0; $i < count($saleItems); $i++) {
				// Do not calculate total, if an item is deleted 
				if ($saleItems[$i]['sale_item_void_user'] == 0)
					$total += $saleItems[$i]['sale_item_total'];
			}
			
			return $total;
		}
		
		private function pushSaleToDB($saleInfo, $mode = self::MODE_ADD)
		{
			switch($mode) {
				case self::MODE_ADD :
					$saleInfo['sale_create_user'] = Authentication::getUser()->getID();
					$saleInfo['sale_create_datetime'] = Utilities::getDateTimeNowForDB();
					return $this->_dataMapper->addSale($saleInfo);
						
				case self::MODE_UPDATE :
					$saleInfo['sale_update_user'] = Authentication::getUser()->getID();
					$saleInfo['sale_update_datetime'] = Utilities::getDateTimeNowForDB();
					return $this->_dataMapper->updateSale($saleInfo);
						
				case self::MODE_VOID :
					$saleInfo['sale_void_user'] = Authentication::getUser()->getID();
					$saleInfo['sale_void_datetime'] = Utilities::getDateTimeNowForDB();
					return $this->_dataMapper->voidSale($saleInfo);
			}
		}
		
		private function pushSaleItemToDB($saleItemInfo, $mode = self::MODE_ADD, $uid = 'x')
		{
			switch($mode) {
				case self::MODE_ADD :
					// Insert it, if it is not deleted from the cart
					if ($saleItemInfo['sale_item_void_user'] == 0) {
						$saleItemInfo['sale_item_create_user'] = Authentication::getUser()->getID();
						$saleItemInfo['sale_item_create_datetime'] = Utilities::getDateTimeNowForDB();
						return $this->_dataMapper->addSaleItem($saleItemInfo, $uid);
					} else {
						return 1;
					}
			
				case self::MODE_UPDATE :
					// Update it, if it is updated from the cart
					Utilities::logDebug('NEW SALE ITEM UPDATE >>>>> '.$saleItemInfo['sale_item_new_update']);
					
					if ($saleItemInfo['sale_item_new_update'] != 0) {
						$saleItemInfo['sale_item_update_user'] = Authentication::getUser()->getID();
						$saleItemInfo['sale_item_update_datetime'] = Utilities::getDateTimeNowForDB();
						return $this->_dataMapper->updateSaleItem($saleItemInfo);
					} else {
						return 1;
					}
			
				case self::MODE_VOID :
					$saleItemInfo['sale_item_void_user'] = Authentication::getUser()->getID();
					$saleItemInfo['sale_item_void_datetime'] = Utilities::getDateTimeNowForDB();
					return $this->_dataMapper->voidSaleItem($saleItemInfo);
					
				case self::MODE_VOID_ALL :
					$saleItemInfo['sale_item_void_user'] = Authentication::getUser()->getID();
					$saleItemInfo['sale_item_void_datetime'] = Utilities::getDateTimeNowForDB();
					return $this->_dataMapper->voidAllSaleItems($saleItemInfo);
			}
		}
	}
?>














