<?php
	require_once '../controller/DataAccess.php';
	
	class BookingDataMapper
	{
		private $_dataAccess;
		
		public function BookingDataMapper()
		{
			$this->_dataAccess = new DataAccess();
		}
		
		public function getBookings($date)
		{
			/*$sql = "
select booking.booking_id, booking.booking_date, booking.booking_time_in, booking.booking_time_out, booking.booking_name
	, booking.booking_tel, booking.booking_client, booking.booking_status_id
	, room_type.room_type_name, booking_room.booking_room_amount
	, booking_item.booking_item_id
	, therapist.therapist_id, case therapist.therapist_id when 0 then '[Any]' else therapist.therapist_name end as therapist_name
	, massage_type.massage_type_name
from booking
join booking_item on booking_item.booking_id = booking.booking_id
join booking_room on booking_room.booking_id = booking.booking_id
join room_type on room_type.room_type_id = booking_room.room_type_id
join massage_type on massage_type.massage_type_id = booking_item.massage_type_id
left join therapist on therapist.therapist_id = booking_item.therapist_id
where booking.booking_date = '{$date}'
order by booking.booking_time_in, booking.booking_time_out, booking.booking_create_datetime";*/
			$sql = "
				select booking.booking_id, booking.booking_date, booking.booking_time_in, booking.booking_time_out, booking.booking_name
					, booking.booking_tel, booking.booking_client, booking.booking_status_id
					, booking_item.booking_item_id
					, therapist.therapist_id, case therapist.therapist_id when 0 then '[Any]' else therapist.therapist_name end as therapist_name
					, massage_type.massage_type_name
				from booking
				join booking_item on booking_item.booking_id = booking.booking_id
				join massage_type on massage_type.massage_type_id = booking_item.massage_type_id
				left join therapist on therapist.therapist_id = booking_item.therapist_id
				where booking.booking_date = '{$date}'
				order by booking.booking_time_in, booking.booking_time_out, booking.booking_create_datetime
			";
			
			return $this->_dataAccess->select($sql);
		}
		
		public function getBookingRooms($date)
		{
			$sql = "
				select booking.booking_id, booking_room.room_type_id, booking_room.booking_room_amount
				from booking
				join booking_room on booking_room.booking_id = booking.booking_id
				where booking.booking_date = '{$date}'
			";
			
			return $this->_dataAccess->select($sql);
		}
		
		public function addBooking($id, $bookingInfo)
		{
			$bookingName = Utilities::upperFirstLetter($bookingInfo['client_name']);
			
			$sql = "
				insert into booking (booking_id
					, booking_date, booking_time_in, booking_time_out
					, booking_name, booking_tel
					, booking_client
					, booking_status_id)
				values ('{$id}'
					, '{$bookingInfo['date']}'
					, '{$bookingInfo['time_in']}', '{$bookingInfo['time_out']}'
					, '{$bookingName}', '{$bookingInfo['client_tel']}'
					, {$bookingInfo['client_amount']}
					, 1)
			";
			
			return $this->_dataAccess->insert($sql);
		}
		
		public function addBookingItems($id, $therapists)
		{
			$values = "";
			
			for ($i = 0; $i < count($therapists); $i++) {
				if (!empty($values))
					$values .= ", ";
				
				$values .= "('{$id}', {$therapists[$i]['therapist_id']}, 1)";
			}
			
			$sql = "
				insert into booking_item (booking_id, therapist_id, massage_type_id)
				values {$values}
			";
			
			return $this->_dataAccess->insert($sql);
		}
		
		public function addBookingRoom($id, $singleRoomAmt, $doubleRoomAmt)
		{
			$values = "";
			
			if ($singleRoomAmt > 0)
				$values .= "('{$id}', 1, {$singleRoomAmt})";
			
			if ($doubleRoomAmt > 0) {
				if (!empty($values))
					$values .= ", ";
				
				$values .= "('{$id}', 2, {$doubleRoomAmt})";
			}
			
			$sql = "
				insert into booking_room (booking_id, room_type_id, booking_room_amount)
				values {$values}
			";
			
			return $this->_dataAccess->insert($sql);
		}
		
		public function deleteBooking($id)
		{
			$sql = "
				delete from booking where booking_id = '{$id}';
				delete from booking_item where booking_id = '{$id}';
				delete from booking_room where booking_id = '{$id}';
			";
			
			return $this->_dataAccess->delete($sql);
		}
	}
?>









