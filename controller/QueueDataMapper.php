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
						, lastest_timeout.therapist_timeout
						, massage_record_create_datetime
						, shift.shift_type_id
						, 1 as therapist_available
					from (
						select therapist_id, max(therapist_timeout) as therapist_timeout, max(massage_record_create_datetime) as massage_record_create_datetime
						from (
							select therapist_id, shift_time_start as therapist_timeout, shift_create_datetime as massage_record_create_datetime
							from shift
							where shift_date = '{$date}'
								and shift_working = 1
							union
							select therapist_id, massage_record_time_out as therapist_timeout, massage_record_create_datetime
							from massage_record
							where massage_record_date = '{$date}'
								and massage_record_void_user = 0
								and therapist_id in (
									select therapist_id
									from shift
									where shift_date = '{$date}'
										and shift_working = 1
								)
						) as get_all_timeout
						group by therapist_id
					) as lastest_timeout
					join therapist on therapist.therapist_id = lastest_timeout.therapist_id
					join shift on shift.therapist_id = therapist.therapist_id and shift.shift_date = '{$date}'
					where shift.shift_type_id != 6
					order by therapist_timeout, massage_record_create_datetime";
		
			return $this->_dataAccess->select($sql);
		}
		
		public function getRecordsAndBookingsAmountOfTherapists($date, $timeIn, $timeOut) {
			$sql = "
					select shift.therapist_id
						, count(record.massage_record_id) as record_amount
						, count(booking.booking_item_id) as booking_amount
					from shift
					left join (
						select massage_record.therapist_id, massage_record.massage_record_id
						from massage_record
						where massage_record.massage_record_date = '{$date}'
							and (
								massage_record.massage_record_time_out <= '{$timeIn}'
								or
								(
									('{$timeIn}' > massage_record_time_in and '{$timeIn}' < massage_record_time_out)
									or ('{$timeOut}' > massage_record_time_in and '{$timeOut}' < massage_record_time_out)
							    	or (massage_record_time_in > '{$timeIn}' and massage_record_time_in < '{$timeOut}')
							    	or (massage_record_time_out > '{$timeIn}' and massage_record_time_out < '{$timeOut}')
							    	or ('{$timeIn}' = massage_record_time_in and '{$timeOut}' = massage_record_time_out)
								)
							)
					) as record 
						on record.therapist_id = shift.therapist_id
					left join (
						select booking_item.therapist_id, booking_item.booking_item_id
						from booking
						join booking_item on booking_item.booking_id = booking.booking_id
							and booking_item.therapist_id != 0
						where booking.booking_date = '{$date}'
							and (
								booking.booking_time_out <= '{$timeIn}'
								or
								(
									('{$timeIn}' > booking_time_in and '{$timeIn}' < booking_time_out)
									or ('{$timeOut}' > booking_time_in and '{$timeOut}' < booking_time_out)
									or (booking_time_in > '{$timeIn}' and booking_time_in < '{$timeOut}')
									or (booking_time_out > '{$timeIn}' and booking_time_out < '{$timeOut}')
									or ('{$timeIn}' = booking_time_in and '{$timeOut}' = booking_time_out)
								)
							)
					) as booking 
						on booking.therapist_id = shift.therapist_id
					where shift.shift_date = '{$date}'
						and shift.shift_working = 1
					group by shift.therapist_id
					";
			
			return $this->_dataAccess->select($sql);
		}
		
		// get being used "therapists" and "rooms" during the selected time period
		public function getRecords($timeIn, $timeOut, $roomTypeID = "")
		{
			$sql = "
					select therapist_id, massage_record.room_no
						, massage_record_id, massage_record_time_in, massage_record_time_out
					from massage_record
					join room on room.room_no = massage_record.room_no
					where (
					    	('{$timeIn}' > massage_record_time_in and '{$timeIn}' < massage_record_time_out)
							or ('{$timeOut}' > massage_record_time_in and '{$timeOut}' < massage_record_time_out)
					    	or (massage_record_time_in > '{$timeIn}' and massage_record_time_in < '{$timeOut}')
					    	or (massage_record_time_out > '{$timeIn}' and massage_record_time_out < '{$timeOut}')
					    	or ('{$timeIn}' = massage_record_time_in and '{$timeOut}' = massage_record_time_out)
					    )
					    and massage_record_void_user = 0";
			
			if (!empty($roomTypeID))
				$sql .= " and room.room_type_id = {$roomTypeID}";
			
			$sql .= " order by massage_record_time_in, massage_record_create_datetime";
			
			return $this->_dataAccess->select($sql);
		}
		
		// get bookings during the selected time period
		public function getBookings($timeIn, $timeOut, $exceptedBookingItemID = "", $exceptedBookingID = "")
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
						, booking.booking_time_in, booking.booking_time_out
						-- use array to store consecutive bookings instead
						-- '' as consecutive_booking_id
						-- 0 as consecutive_booking_item_id
						-- 0 as consecutive_therapist_id
						-- '' as consecutive_booking_time_in
						-- '' as consecutive_booking_time_out
					from booking
					join booking_item on booking.booking_id = booking_item.booking_id
					where (
							('{$timeIn}' > booking_time_in and '{$timeIn}' < booking_time_out)
							or ('{$timeOut}' > booking_time_in and '{$timeOut}' < booking_time_out)
							or (booking_time_in > '{$timeIn}' and booking_time_in < '{$timeOut}')
							or (booking_time_out > '{$timeIn}' and booking_time_out < '{$timeOut}')
							or ('{$timeIn}' = booking_time_in and '{$timeOut}' = booking_time_out)
						)
						and booking_item.booking_item_status = 1";
			
			if (!empty($exceptedBookingItemID))
				$sql .= " and booking_item.booking_item_id != {$exceptedBookingItemID}";

			if (!empty($exceptedBookingID))
				$sql .= " and booking.booking_id != '{$exceptedBookingID}'";
			
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
					    and booking_item.booking_item_status = 1
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
		public function getDoubleRoomsNeededAmount($timeIn, $timeOut, $exceptedBookingID = "")
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
			
			if (!empty($exceptedBookingID))
				$sql .= " and booking.booking_id != '{$exceptedBookingID}'";
			
			$result = $this->_dataAccess->select($sql);
			
			if (count($result) > 0)
				return $result[0]['room_double_needed_amt'];
			else
				return 0;
		}
		
		public function getDoubleRoomsNeededForBookings($timeIn, $timeOut, $exceptedBookingID = "")
		{
			$sql = "
			select booking.booking_id, booking.booking_time_in, booking.booking_time_out, booking_room.booking_room_amount
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
				
			if (!empty($exceptedBookingID))
				$sql .= " and booking.booking_id != '{$exceptedBookingID}'";
			
			$sql .= " order by booking.booking_time_in, booking.booking_create_datetime";
					
			return $this->_dataAccess->select($sql);
		}
		
		public function getSingleRoomsNeededAmount($timeIn, $timeOut, $exceptedBookingID = "")
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
			
			if (!empty($exceptedBookingID))
				$sql .= " and booking.booking_id != '{$exceptedBookingID}'";
				
			$result = $this->_dataAccess->select($sql);
				
			if (count($result) > 0)
				return $result[0]['room_single_needed_amt'];
			else
				return 0;
		}
		
		public function getSingleRoomsNeededForBookings($timeIn, $timeOut, $exceptedBookingID = "")
		{
			$sql = "
			select booking.booking_id, booking.booking_time_in, booking.booking_time_out, booking_room.booking_room_amount 
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
			
			if (!empty($exceptedBookingID))
				$sql .= " and booking.booking_id != '{$exceptedBookingID}'";
		
			$sql .= " order by booking.booking_time_in, booking.booking_create_datetime";
				
			return $this->_dataAccess->select($sql);
		}
	}
?>









