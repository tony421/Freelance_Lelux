<?php
	require_once '../controller/DataAccess.php';
	require_once '../config/Booking_Config.php';
	
	class BookingSmsDataMapper {
		private $_dataAccess;
		
		public function BookingSmsDataMapper()
		{
			$this->_dataAccess = new DataAccess();
		}
		
		public function getBookings($date)
		{
			$openHour = Booking_Config::OPEN_HOUR;
				
			$sql = "
select *
from booking
where booking.booking_date = '{$date}'
and booking.booking_time_in >= '{$date} {$openHour}'
order by booking.booking_time_in, booking.booking_create_datetime
";
		
			return $this->_dataAccess->select($sql);
		}
		
		public function addBookingSms($bookingId, $bookingSmsResult)
		{
			$sql = "
insert into booking_sms (booking_id, booking_sms_result)
values ('{$bookingId}', '{$bookingSmsResult}')
";
			
			return $this->_dataAccess->insert($sql);
		}
	}
?>






