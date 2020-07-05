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
			// adding [room_reserve] & [room_remark] attributes in case to show a room in the list but remark with "reserved"
			//
			
			$sql = "select room_no
						, concat(room_no, ' ', room_remark) as room_desc
						, 1 as room_available
						, 0 as room_reserved
						, '' as room_remark
					from room";
		
			return $this->_dataAccess->select($sql);
		}
		
		public function getSingleRooms($order = "asc")
		{
			$sql = "
			select distinct convert(room_no, signed) as room_no
			from room
			order by room_no {$order}";
		
			return $this->_dataAccess->select($sql);
		}
		
		public function getDoubleRooms($order = "asc")
		{
			$sql = "
					select room_double_no, room_no_1, room_no_2
					from room_double
					order by room_double_no {$order}";
				
			return $this->_dataAccess->select($sql);
		}
		
		public function getDoubleRoomTotalAmount()
		{
			$sql = "
					select count(distinct room_double_no) as room_double_total_amt
					from room_double";
				
			return $this->_dataAccess->select($sql);
		}
		
		public function getDoubleRoomNo($roomNo)
		{
			$sql = "
			select room_double_no
			from room_double
			where room_no_1 = {$roomNo} or room_no_2 = {$roomNo}";
		
			return $this->_dataAccess->select($sql);
		}
	}
?>