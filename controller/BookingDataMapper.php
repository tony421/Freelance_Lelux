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
			$sql = "
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
order by booking.booking_time_in, booking.booking_time_out, booking.booking_create_datetime";
			
			return $this->_dataAccess->select($sql);
		}
	}
?>









