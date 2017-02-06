<?php
	require_once '../controller/DataAccess.php';
	
	class MassageTypeDataMapper
	{
		private $_dataAccess;
		
		public function MassageTypeDataMapper()
		{
			$this->_dataAccess = new DataAccess();
		}
		
		public function getMassageTypes()
		{
			$sql = "select * from massage_type where massage_type_active = 1 order by massage_type_id";
				
			return $this->_dataAccess->select($sql);
		}
		
		public function getMassageTypesDisplay()
		{
			$sql = "select massage_type_id, massage_type_name, concat('$', massage_type_commission) as massage_type_commission 
					from massage_type
					where massage_type_active = 1
					order by massage_type_id";
		
			return $this->_dataAccess->select($sql);
		}
		
		public function addMassageType($massageTypeInfo)
		{
			$sql_format = "
					insert into massage_type
						(massage_type_name
							, massage_type_commission
							, massage_type_active)
					values ('%s', %d, 1)";
			
			$sql = sprintf($sql_format
					, $massageTypeInfo['massage_type_name']
					, $massageTypeInfo['massage_type_commission']);
			
			return $this->_dataAccess->insert($sql);
		} // addMassageType
		
		public function updateMassageType($massageTypeInfo)
		{
			$sql_format = "
					update massage_type
					set massage_type_name = '%s'
						, massage_type_commission = %d
						, massage_type_update_datetime = NOW()
					where massage_type_id = %d";
			
			$sql = sprintf($sql_format
					, $massageTypeInfo['massage_type_name']
					, $massageTypeInfo['massage_type_commission']
					, $massageTypeInfo['massage_type_id']);
			
			return $this->_dataAccess->update($sql);
		} // updateMassageType
		
		public function deleteMassageType($massageTypeInfo)
		{
			$sql_format = "
					update massage_type
					set massage_type_active = 0
						, massage_type_update_datetime = NOW()
					where massage_type_id = %d";
				
			$sql = sprintf($sql_format
					, $massageTypeInfo['massage_type_id']);
				
			return $this->_dataAccess->update($sql);
		} // deleteMassageType
	}
?>