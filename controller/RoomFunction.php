<?php
	require_once '../controller/Session.php';
	require_once '../controller/RoomDataMapper.php';
	require_once '../controller/Utilities.php';
	
	class RoomFunction
	{
		private $_dataMapper;
	
		public function RoomFunction()
		{
			$this->_dataMapper = new RoomDataMapper();
		}
		
		public function getAllRooms()
		{
			$result = $this->_dataMapper->getAllRooms();
		
			if (!(count($result) > 0)) {
				Utilities::logInfo("There is no room data in the system.");
			}
			
			return Utilities::getResponseResult(true, '', $result);
		}
		
		public function getDoubleRoomTotal()
		{
			$result = $this->_dataMapper->getDoubleRoomTotal();
			
			if (count($result) > 0) {
				return $result[0]['room_double_total_amt '];
			} else {
				return 0;
			}
		}
	}
?>






