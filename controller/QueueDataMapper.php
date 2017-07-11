<?php
	require_once '../controller/DataAccess.php';
	
	class QueueDataMapper
	{
		private $_dataAccess;
		
		public function QueueDataMapper()
		{
			$this->_dataAccess = new DataAccess();
		}
		
		public function getTherapistsOnQueue($date)
		{
			$sql = "
					select therapist.therapist_id, therapist.therapist_name
						, lastest_timeout.therapist_timeout, 1 as therapist_available
					from (
						select therapist_id, max(therapist_timeout) as therapist_timeout
						from (
							select therapist_id, shift_create_datetime as therapist_timeout
							from shift
							where shift_date = '{$date}'
								and shift_working = 1
							union
							select therapist_id, massage_record_time_out as therapist_timeout
							from massage_record
							where massage_record_date = '{$date}'
								and massage_record_void_user = 0
						) as get_all_timeout
						group by therapist_id
					) as lastest_timeout
					join therapist on therapist.therapist_id = lastest_timeout.therapist_id
					order by therapist_timeout";
		
			return $this->_dataAccess->select($sql);
		}
		
		// get being used "therapists" and "rooms" during the selected time period
		public function getRecords($timeIn, $timeOut)
		{
			$sql = "
					select therapist_id, room_no
					from massage_record
					where (
					    	('{$timeIn}' > massage_record_time_in and '{$timeIn}' < massage_record_time_out)
							or ('{$timeOut}' > massage_record_time_in and '{$timeOut}' < massage_record_time_out)
					    	or (massage_record_time_in > '{$timeIn}' and massage_record_time_in < '{$timeOut}')
					    	or (massage_record_time_out > '{$timeIn}' and massage_record_time_out < '{$timeOut}')
					    	or ('{$timeIn}' = massage_record_time_in and '{$timeOut}' = massage_record_time_out)
					    )
					    and massage_record_void_user = 0";
			
			return $this->_dataAccess->select($sql);
		}
		
		// get bookings during the selected time period
		public function getBookings($timeIn, $timeOut)
		{
			/*$sql = "
					select booking_id
					from booking
					where (
					    	('{$timeIn}' > booking_time_in and '{$timeIn}' < booking_time_out)
					    	or ('{$timeOut}' > booking_time_in and '{$timeOut}' < booking_time_out)
					    	or (booking_time_in > '{$timeIn}' and booking_time_in < '{$timeOut}')
					    	or (booking_time_out > '{$timeIn}' and booking_time_out < '{$timeOut}')
					    )
					    and booking_status_id = 1";*/
			$sql = "
					select booking.booking_id, booking_item.booking_item_id, booking_item.therapist_id
					from booking
					join booking_item on booking.booking_id = booking_item.booking_id
					where (
							('{$timeIn}' > booking_time_in and '{$timeIn}' < booking_time_out)
							or ('{$timeOut}' > booking_time_in and '{$timeOut}' < booking_time_out)
							or (booking_time_in > '{$timeIn}' and booking_time_in < '{$timeOut}')
							or (booking_time_out > '{$timeIn}' and booking_time_out < '{$timeOut}')
							or ('{$timeIn}' = booking_time_in and '{$timeOut}' = booking_time_out)
						)
						and booking_status_id = 1";
			
			return $this->_dataAccess->select($sql);
		}
		
		// get requested therapists for bookings during the selected time period 
		public function getTherapistsOnBookings($timeIn, $timeOut)
		{
			$sql = "
					select booking.booking_id, booking_item.therapist_id
					from booking
					join booking_item on booking.booking_id = booking_item.booking_id
					where (
					    	('{$timeIn}' > booking_time_in and '{$timeIn}' < booking_time_out)
					    	or ('{$timeOut}' > booking_time_in and '{$timeOut}' < booking_time_out)
					    	or (booking_time_in > '{$timeIn}' and booking_time_in < '{$timeOut}')
					    	or (booking_time_out > '{$timeIn}' and booking_time_out < '{$timeOut}')
					    	or ('{$timeIn}' = booking_time_in and '{$timeOut}' = booking_time_out)
					    )
					    and booking_status_id = 1
					    and booking_item.therapist_id != 0";
			
			return $this->_dataAccess->select($sql);
		}
		
		// get double rooms required for booking during the selected time period
		public function getDoubleRoomsNeeded($timeIn, $timeOut)
		{
			$neededAmt = $this->getDoubleRoomsNeededAmount($timeIn, $timeOut);
					
			$sql = "
				select room_double_no, room_no_1, room_no_2
				from room_double
				where room_double_no <= {$neededAmt}";
				
			return $this->_dataAccess->select($sql);
		}
		
		// get the amount double room required for bookings during the selected time period
		public function getDoubleRoomsNeededAmount($timeIn, $timeOut)
		{
			$sql = "
					select ifnull(sum(booking_room_amount), 0) as room_double_needed_amt
					from booking
					join booking_room on booking_room.booking_id = booking.booking_id
					where (
					    	('{$timeIn}' > booking_time_in and '{$timeIn}' < booking_time_out)
					    	or ('{$timeOut}' > booking_time_in and '{$timeOut}' < booking_time_out)
					    	or (booking_time_in > '{$timeIn}' and booking_time_in < '{$timeOut}')
					    	or (booking_time_out > '{$timeIn}' and booking_time_out < '{$timeOut}')
					    	or ('{$timeIn}' = booking_time_in and '{$timeOut}' = booking_time_out)
					    )
					    and booking_status_id = 1
					    and room_type_id = 2";
			
			$result = $this->_dataAccess->select($sql);
			
			if (count($result) > 0)
				return $result[0]['room_double_needed_amt'];
			else
				return 0;
		}
		
		public function getSingleRoomsNeededAmount($timeIn, $timeOut)
		{
			$sql = "
			select ifnull(sum(booking_room_amount), 0) as room_single_needed_amt
			from booking
			join booking_room on booking_room.booking_id = booking.booking_id
				where (
						('{$timeIn}' > booking_time_in and '{$timeIn}' < booking_time_out)
						or ('{$timeOut}' > booking_time_in and '{$timeOut}' < booking_time_out)
						or (booking_time_in > '{$timeIn}' and booking_time_in < '{$timeOut}')
						or (booking_time_out > '{$timeIn}' and booking_time_out < '{$timeOut}')
						or ('{$timeIn}' = booking_time_in and '{$timeOut}' = booking_time_out)
					)
					and booking_status_id = 1
					and room_type_id = 1";
				
			$result = $this->_dataAccess->select($sql);
				
			if (count($result) > 0)
				return $result[0]['room_single_needed_amt'];
			else
				return 0;
		}
	}
?>









