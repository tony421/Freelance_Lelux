<?php
	require_once '../controller/DataAccess.php';
	
	class RoomDataMapper
	{
		private $_dataAccess;
		
		public function RoomDataMapper()
		{
			$this->_dataAccess = new DataAccess();
		}
		
		public function getAllRooms()
		{
			$sql = "select room_no, concat(room_no, ' ', room_remark) as room_desc, 1 as room_available from room";
		
			return $this->_dataAccess->select($sql);
		}
		
		public function getDoubleRooms()
		{
			$sql = "
					select room_double_no, room_no_1, room_no_2
					from room_double
					order by room_double_no";
				
			return $this->_dataAccess->select($sql);
		}
		
		public function getDoubleRoomTotalAmount()
		{
			$sql = "
					select count(distinct room_double_no) as room_double_total_amt
					from room_double";
				
			return $this->_dataAccess->select($sql);
		}
	}
?>