<?php
	require_once '../controller/Session.php';
	require_once '../controller/BookingDataMapper.php';
	require_once '../controller/QueueDataMapper.php';
	require_once '../controller/RoomDataMapper.php';
	require_once '../controller/Utilities.php';
	
	class BookingFunction
	{
		private $_dataMapper;
	
		public function BookingFunction()
		{
			$this->_dataMapper = new BookingDataMapper();
		}
		
		public function getConfig($date)
		{
			$queueMapper = new QueueDataMapper();
			$therapists = $queueMapper->getTherapistsOnQueue($date);
			
			$result['therapist_amount'] = count($therapists);
			$result['therapists'] = $therapists;
			
			return Utilities::getResponseResult(true, '', $result);
		}
		
		public function addBooking($bookingInfo)
		{
			$id = Utilities::getUniqueID();
			
			$affectedRow = $this->_dataMapper->addBooking($id, $bookingInfo);
			
			if ($affectedRow > 0) {
				$affectedRow = $this->_dataMapper->addBookingItems($id, $bookingInfo['therapists']);
				
				if ($affectedRow > 0) {
					$affectedRow = $this->_dataMapper->addBookingRoom($id, $bookingInfo['single_room_amount'], $bookingInfo['double_room_amount']);
				}
			}
			
			if ($affectedRow > 0) {
				$result['booking_move_to'] = $bookingInfo['time_in'];
				
				return Utilities::getResponseResult(true, $this->getSuccessfulAddingMessage($bookingInfo), $result);
			} else {
				$this->_dataMapper->deleteBooking($id);
				return Utilities::getResponseResult(false, 'Adding the new booking is failed!');
			}
		}
		
		public function getBookings($date)
		{
			$bookings = $this->_dataMapper->getBookings($date);
			$result['bookings'] = $bookings;
				
			return Utilities::getResponseResult(true, '', $result);
			
		}
		
		public function getBookingTimeline($date)
		{
			$queueMapper = new QueueDataMapper();
			
			$therapists = $queueMapper->getTherapistsOnQueue($date);
			
			$bookings = $this->_dataMapper->getBookings($date);
			$bookingRooms = $this->_dataMapper->getBookingRooms($date);
			$bookings = $this->combineBookingRooms($bookings, $bookingRooms);
			
			$timelineGroups = $this->getTimelineGroups($therapists);
			$timelineGroups = $this->getTimelineItems($bookings, $timelineGroups); // items will be added into a group
			
			$result['bookings'] = $bookings;
			$result['timeline_groups'] = $timelineGroups;
			
			return Utilities::getResponseResult(true, '', $result);
		}
		
		private function combineBookingRooms($bookings, $bookingRooms)
		{
			for ($i = 0; $i < count($bookings); $i++) {
				$bookings[$i]['single_room_amount'] = 0;
				$bookings[$i]['double_room_amount'] = 0;
				
				for ($j = 0; $j < count($bookingRooms); $j++) {
					if ($bookings[$i]['booking_id'] == $bookingRooms[$j]['booking_id']) {
						if ($bookingRooms[$j]['room_type_id'] == 1) {
							$bookings[$i]['single_room_amount'] = $bookingRooms[$j]['booking_room_amount'];
						} else {
							$bookings[$i]['double_room_amount'] = $bookingRooms[$j]['booking_room_amount'];
						}
					}
				}				
			}
			
			return $bookings;
		}
		
		private function getSuccessfulAddingMessage($bookingInfo)
		{
			$timeIn = split(' ', $bookingInfo['time_in'])[1];
			$timeOut = split(' ', $bookingInfo['time_out'])[1];
			
			return "The booking for <b><u>{$bookingInfo['client_amount']}</b></u> client from <b><u>{$timeIn}</b></u> to <b><u>{$timeOut}</b></u> is added.";
		}
		
		private function getTimelineGroups($therapists)
		{
			$groups = array();
			
			for ($i = 0; $i < count($therapists); $i++) {
				$group_item['id'] = $i + 1;
				$group_item['content'] = 'Therapist #'.($i + 1);
				$group_item['style'] = 'font-weight: bold;';
				$group_item['items'] = array();
				
				array_push($groups, $group_item);
			}
			
			return $groups;
		}
		
		private function getExcessiveTherapistGroup($groupSize)
		{
			$group_item['id'] = $groupSize + 1;
			$group_item['content'] = 'Therapist #'.($groupSize + 1);
			$group_item['style'] = 'font-weight: bold; color: red;';
			$group_item['title'] = 'Need On-Call Therapist!!!';
			$group_item['items'] = array();
			
			return $group_item;
		}
		
		private function getTimelineItems($bookings, $timelineGroups)
		{
			$timelineItems = array();
			
			// which group each booking item should go in?
			//
			foreach ($bookings as $booking) {
				$isItemAddedToGroup = false;
				
				foreach ($timelineGroups as $key => $group) {
					Utilities::logDebug('Group #'.$group['id'].' | Item Amount:'.count($group['items']));
					
					// if there is no any item in group, then add the booking in the group
					//
					if (count($group['items']) > 0) {
						// loop items in the group, check whether the booking item is really unique in the group
						//
						foreach ($group['items'] as $item) {
							$isUniqueItem = false;
							
							if ($this->isDuplicateBookingTime($booking, $item)) {
								// if a booking item is duplicate with an existing item, then check with the next one
								$isUniqueItem = false;
							} else {
								$isUniqueItem = true;
							}
						}
						
						if($isUniqueItem) {
							array_push($timelineGroups[$key]['items'], $this->getTimelineItem($booking, $group['id']));
							$isItemAddedToGroup = true;
						}
						
					} else {
						array_push($timelineGroups[$key]['items'], $this->getTimelineItem($booking, $group['id']));
						$isItemAddedToGroup = true;
					}
					
					if ($isItemAddedToGroup)
						break;
				}
				
				// if a booking item is not added into any group, then create a new group and add the item into it
				//
				if (!$isItemAddedToGroup) {
					$groupSize = count($timelineGroups);
					array_push($timelineGroups, $this->getExcessiveTherapistGroup($groupSize));
					array_push($timelineGroups[$groupSize]['items'], $this->getTimelineItem($booking, $timelineGroups[$groupSize]['id'], $singleRoomAmt, $doubleRoomAmt));
				}
			}
			
			return $timelineGroups;
		}
		
		private function isDuplicateBookingTime($booking, $timelineItem)
		{
			$booking_time_in = strtotime($booking['booking_time_in']);
			$booking_time_out = strtotime($booking['booking_time_out']);
			$item_time_in = strtotime($timelineItem['start']);
			$item_time_out = strtotime($timelineItem['end']);
			
			Utilities::logDebug('BookingFunction.isDuplicateBookingTime | Booking Time: '.$booking['booking_time_in'].' to '.$booking['booking_time_out'].' | Item Time: '.$timelineItem['start'].' to '.$timelineItem['end']);
			
			if (($booking_time_in > $item_time_in && $booking_time_in < $item_time_out)
					|| ($booking_time_out > $item_time_in && $booking_time_out < $item_time_out)
					|| ($item_time_in > $booking_time_in && $item_time_in < $booking_time_out)
					|| ($item_time_out > $booking_time_in && $item_time_out < $booking_time_out)
					|| ($booking_time_in == $item_time_in && $booking_time_out == $item_time_out)
				) {
				//Utilities::logDebug('BookingFunction.isDuplicateBookingTime | Duplicate: YES');
				return true;
			} else {
				//Utilities::logDebug('BookingFunction.isDuplicateBookingTime | Duplicate: NO');
				return false;
			}
		}
		
		private function getTimelineItem($bookingItem, $groupNumber)
		{
			$item['id'] = $bookingItem['booking_item_id'];
			$item['content'] = $bookingItem['booking_name'];
			$item['start'] = $bookingItem['booking_time_in'];
			$item['end'] = $bookingItem['booking_time_out'];
			$item['group'] = $groupNumber;
			
			return $item;
		}
	}
?>








