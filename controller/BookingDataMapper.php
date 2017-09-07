<?php
	require_once '../controller/DataAccess.php';
	require_once '../config/Booking_Config.php';
	
	class BookingDataMapper
	{
		private $_dataAccess;
		
		public function BookingDataMapper()
		{
			$this->_dataAccess = new DataAccess();
		}
		
		public function getBookingItems($date)
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
			$openHour = Booking_Config::OPEN_HOUR;
			
			$sql = "
				select booking.booking_id, booking.booking_date, booking.booking_time_in, booking.booking_time_out, booking.booking_name
					, booking.booking_tel, booking.booking_client, booking.booking_status_id
					, booking_item.booking_item_id, booking_item.booking_item_status
					, therapist.therapist_id, case therapist.therapist_id when 0 then '[Any]' else therapist.therapist_name end as therapist_name
					, massage_type.massage_type_id, massage_type.massage_type_name
					, booking.booking_remark
				from booking
				join booking_item on booking_item.booking_id = booking.booking_id
				join massage_type on massage_type.massage_type_id = booking_item.massage_type_id
				left join therapist on therapist.therapist_id = booking_item.therapist_id
				where booking.booking_date = '{$date}'
					and booking.booking_time_in >= '{$date} {$openHour}'
				order by booking.booking_time_in, booking.booking_time_out, booking.booking_create_datetime
					, booking.booking_id, booking_item.booking_item_status, booking_item.therapist_id desc
			";
			
			// ordering sequence::
			//		- item with "coming status"
			//		- item with "requested therapsit" 
			
			return $this->_dataAccess->select($sql);
		}
		
		// the same query as .getBookingItems()
		public function getBookingGroups($date)
		{
			$sql = "
				select booking.booking_id, count(booking_item.booking_item_id) as booking_group_total
					, 0 as booking_group_item_no
				from booking
				join booking_item on booking_item.booking_id = booking.booking_id
				where booking.booking_date = '{$date}'
				group by booking.booking_id
				having count(booking_item.booking_item_id) > 1
			";
				
			return $this->_dataAccess->select($sql);
		}
		
		public function getBookingItem($bookingItemID)
		{
			$sql = "
				select booking.booking_id, booking.booking_date, booking.booking_time_in, booking.booking_time_out, booking.booking_name
					, booking.booking_tel, booking.booking_client, booking.booking_status_id
					, booking_item.booking_item_id, booking_item.booking_item_status
					, therapist.therapist_id, case therapist.therapist_id when 0 then '[Any]' else therapist.therapist_name end as therapist_name
					, massage_type.massage_type_id, massage_type.massage_type_name
					, booking.booking_remark
				from booking
				join booking_item on booking_item.booking_id = booking.booking_id
				join massage_type on massage_type.massage_type_id = booking_item.massage_type_id
				left join therapist on therapist.therapist_id = booking_item.therapist_id
				where booking_item.booking_item_id = {$bookingItemID}
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
		
		public function getBookingRoom($bookingID)
		{
			$sql = "
			select booking.booking_id, booking_room.room_type_id, booking_room.booking_room_amount
			from booking
			join booking_room on booking_room.booking_id = booking.booking_id
			where booking.booking_id = '{$bookingID}' 
			";
				
			return $this->_dataAccess->select($sql);
		}
		
		public function getBookingClientAmount($bookingID) 
		{
			$sql = "
					select count(booking_item.booking_item_id) as client_amount
					from booking
					join booking_item on booking_item.booking_id = booking.booking_id
						and booking_item.booking_item_status = 1
					where booking.booking_id = '{$bookingID}'
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
					, booking_remark
					, booking_status_id)
				values ('{$id}'
					, '{$bookingInfo['date']}'
					, '{$bookingInfo['time_in']}', '{$bookingInfo['time_out']}'
					, '{$bookingName}', '{$bookingInfo['client_tel']}'
					, {$bookingInfo['client_amount']}
					, '{$bookingInfo['remark']}'
					, 1)
			";
			
			return $this->_dataAccess->insert($sql);
		}
		
		public function updateBooking($bookingInfo)
		{
			$sql = "
				update booking
				set booking_time_in = '{$bookingInfo['time_in']}'
					, booking_time_out = '{$bookingInfo['time_out']}'
					, booking_name = '{$bookingInfo['client_name']}'
					, booking_tel = '{$bookingInfo['client_tel']}'
					, booking_remark = '{$bookingInfo['remark']}'
					, booking_update_datetime = now()
				where booking_id = '{$bookingInfo['booking_id']}'
			";
				
			return $this->_dataAccess->update($sql);
		}
		
		public function addBookingItems($id, $therapists, $massageTypes)
		{
			$values = "";
			
			// **the size of $therapists and $massageTypes must be equal
			for ($i = 0; $i < count($therapists); $i++) {
				if (!empty($values))
					$values .= ", ";
				
				$values .= "('{$id}', {$therapists[$i]['therapist_id']}, {$massageTypes[$i]['massage_type_id']}, 1)";
			}
			
			$sql = "
				insert into booking_item (booking_id, therapist_id, massage_type_id, booking_item_status)
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
		
		public function confirmArrivalBookingItem($bookingID, $bookingItemID)
		{
			$sql = "
				update booking_item
				set booking_item_status = 2
				where booking_item_id = {$bookingItemID};
				
				update booking
				set booking_status_id = 2
				where booking_id = '{$bookingID}';
			";
			
			return $this->_dataAccess->update($sql);
		}
		
		public function reverseBookingItemStatus($bookingItemID)
		{
			$sql = "
				update booking_item
				set booking_item_status = 1
				where booking_item_id = {$bookingItemID}
			";
			
			return $this->_dataAccess->update($sql);
		}
	}
?>









