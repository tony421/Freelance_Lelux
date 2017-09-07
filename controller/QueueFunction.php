<?php
	require_once '../controller/Session.php';
	require_once '../controller/QueueDataMapper.php';
	require_once '../controller/BookingFunction.php';
	require_once '../controller/BookingDataMapper.php';
	require_once '../controller/TherapistFunction.php';
	require_once '../controller/RoomDataMapper.php';
	require_once '../controller/Utilities.php';
	require_once '../config/Queue_Config.php';
	
	class QueueFunction
	{
		private $_dataMapper;
		
		public function QueueFunction()
		{
			$this->_dataMapper = new QueueDataMapper();
		}
		
		public function getQueueForWalkIn($searchInfo)
		{
			try {
				$date = $searchInfo['date'];
				$timeIn = $searchInfo['time_in'];
				$timeOut = $searchInfo['time_out'];
				$clientAmt= $searchInfo['client_amount'];
				
				// Therapist Availability
				//
				$allTherapists = $allTherapists = $this->getTherapistsOnQueueWithHalfDayCondition($date, $timeIn, $timeOut);
				$allTherapists = $this->addQueueSequence($allTherapists);
				
				$records = $this->_dataMapper->getRecords($timeIn, $timeOut);
				
				$availableTherapists = $this->excludeTherapistsOnRecords($allTherapists, $records);
				$bookings = $this->_dataMapper->getBookings($timeIn, $timeOut);
				
				$isTherapistAvailableForWalkIn = $this->isTherapistAvailable($availableTherapists, $bookings, $clientAmt);
				if ($isTherapistAvailableForWalkIn) {
					$availableTherapists = $this->excludeTherapistsOnBookings($availableTherapists, $bookings);
					//$result = $this->getResponseInfo(true, $searchInfo, $availableTherapists);
				} else {
					$availableTherapists = $this->disableTherapists($availableTherapists);
					//$result = $this->getResponseInfo(false, $searchInfo, $availableTherapists);
				}
				//
				// END - Therapist Availability
				
				// Room Availability
				//
				$roomDataMapper = new RoomDataMapper();
				$allRooms = $roomDataMapper->getAllRooms();
				$allDoubleRooms = $roomDataMapper->getDoubleRooms();
				
				$availableRooms = $this->excludeRoomsOnRecords($allRooms, $records);
				
				// exclude double rooms needed for bookings
				$availableDoubleRooms = $this->getAvailableDoubleRooms($availableRooms, $allDoubleRooms);
				$doubleRoomsNeededAmt = $this->_dataMapper->getDoubleRoomsNeededAmount($timeIn, $timeOut);				
				$availableRooms = $this->excludeDoubleRoomsNeeded($availableRooms, $availableDoubleRooms, $doubleRoomsNeededAmt);
				
				/*
				 * do not check the availability of single room for now
				// exclude single rooms needed for bookings
				$availableSingleRooms = $this->getAvailableSingleRooms($availableRooms);
				$singleRoomsNeededAmt = $this->_dataMapper->getSingleRoomsNeededAmount($timeIn, $timeOut);
				
				$isSingleRoomsAvailableForWalkIn = $this->isSingleRoomsAvailableForWalkIn($availableSingleRooms, $singleRoomsNeededAmt);
				if (!$isSingleRoomsAvailableForWalkIn) {
					$availableRooms = $this->disableSingleRooms($availableRooms, $availableSingleRooms);
				}
				*/
				//
				// END  - Room Availability
				
				$result = $this->getResponseInfo($isTherapistAvailableForWalkIn, $searchInfo, $availableTherapists, $availableRooms);
				
				return Utilities::getResponseResult(true, '', $result);
			} catch(Exception $e) {
				return Utilities::getResponseResult(false, 'Getting queue for walk-in is failed');
			}
		}
		
		public function getQueueForBooking($searchInfo)
		{
			try {
				$bookingFunction = new BookingFunction();
				
				$bookingID = $searchInfo['booking_id'];
				$bookingItemID = $searchInfo['booking_item_id'];
				
				$bookingItem = $bookingFunction->getBookingItem($bookingID, $bookingItemID);
				
				$date = $bookingItem['booking_date'];
				$timeIn = $bookingItem['booking_time_in'];
				$timeOut = $bookingItem['booking_time_out'];
				$requestedTherapist = $bookingItem['therapist_id'];
				
				$allTherapists = $this->getTherapistsOnQueueWithHalfDayCondition($date, $timeIn, $timeOut);
				
				// START - Therapist Availability
				$records = $this->_dataMapper->getRecords($timeIn, $timeOut);
				$availableTherapists = $this->excludeTherapistsOnRecords($allTherapists, $records);
				
				$bookings = $this->_dataMapper->getBookings($timeIn, $timeOut, $bookingItemID);
				$availableTherapists = $this->excludeTherapistsOnBookings($availableTherapists, $bookings);
				// END - Therapist Availability
				
				// START - Room Availability
				//
				$roomDataMapper = new RoomDataMapper();
				$allRooms = $roomDataMapper->getAllRooms();
				
				$availableRooms = $this->excludeRoomsOnRecords($allRooms, $records);
				
				$allDoubleRooms = $roomDataMapper->getDoubleRooms("desc");
				$availableDoubleRooms = $this->getAvailableDoubleRooms($availableRooms, $allDoubleRooms);
				$doubleRoomsNeededAmt = $this->_dataMapper->getDoubleRoomsNeededAmount($timeIn, $timeOut, $bookingID);
				$availableRooms = $this->excludeDoubleRoomsNeeded($availableRooms, $availableDoubleRooms, $doubleRoomsNeededAmt);
				//
				// END - Room Availability
				
				$result['therapists'] = $availableTherapists;
				$result['rooms'] = $availableRooms;
				
				return Utilities::getResponseResult(true, '', $result);
				
			} catch(Exception $e) {
				return Utilities::getResponseResult(false, 'Getting queue for booking is failed');
			}
		}
		
		public function getTherapistsOnQueue($date)
		{
			// ***Condition:: First Out, First Go
				
			// Select all therapists on the shift
			// with the ascending order of shift_create_datetime (default)
			// or massage_record_time_out (if newer)
			$therapists = $this->_dataMapper->getTherapistsOnQueue($date);
				
			$therapists = $this->addQueueSequence($therapists);
		
			if (count($therapists) <= 0) {
				Utilities::logInfo("There is no therapist on the queue {$date}");
			}
		
			return Utilities::getResponseResult(true, '', $therapists);
		}
		
		public function searchAvailabilityForBooking($searchInfo)
		{
			try {
				if (isset($searchInfo['booking_id'])) {
					$exceptedBookingID = $searchInfo['booking_id'];
					
					// must get updated client amount => count items only that have "booking_item_status" == 1
					$bookingMapper = new BookingDataMapper();
					$result = $bookingMapper->getBookingClientAmount($exceptedBookingID);
					if (count($result) > 0) {
						$clientAmt = $result[0]['client_amount'];
						
						if ($clientAmt < 1) {
							Utilities::logInfo('QueueFunction.searchAvailabilityForBooking() | "cleint_amount" must not be less than 1!!');
							$clientAmt = 1;
						}
					} else {
						$clientAmt= $searchInfo['client_amount'];
					}
				} else {
					$exceptedBookingID = "";
					$clientAmt= $searchInfo['client_amount'];
				}
				
				$date = $searchInfo['date'];
				$timeIn = $searchInfo['time_in'];
				$timeOut = $searchInfo['time_out'];
				$singleRoomAmt = $searchInfo['single_room_amount'];
				$doubleRoomAmt = $searchInfo['double_room_amount'];
				$requestedTherapists = $searchInfo['therapists'];
				
				/*
					*** client amount must be minus according to number of recorded booking items 
				*/
				
				// init $result values with $searchInfo
				$result = $searchInfo;
				
				// Therapist Availability
				//
				$allTherapists = $this->_dataMapper->getTherapistsOnQueue($date);
				
				$records = $this->_dataMapper->getRecords($timeIn, $timeOut);
				$availableTherapists = $this->excludeTherapistsOnRecords($allTherapists, $records);
				
				$bookings = $this->_dataMapper->getBookings($timeIn, $timeOut, "", $exceptedBookingID);
				
				$isTherapistAvailable = $this->isTherapistAvailable($availableTherapists, $bookings, $clientAmt);
				if ($isTherapistAvailable) {
					$availableTherapists = $this->excludeTherapistsOnBookings($availableTherapists, $bookings);
					// including ['available'] & ['unavailable_therapists']
					$checkResult = $this->isRequestedTherapistAvailable($availableTherapists, $requestedTherapists);
					$result['available'] = $checkResult['available'];
					$result['unavailable_therapists'] = $checkResult['unavailable_therapists'];
					
					if ($result['available']) {
						// Room Availability
						//
						$roomDataMapper = new RoomDataMapper();
						$allRooms = $roomDataMapper->getAllRooms();
						$allDoubleRooms = $roomDataMapper->getDoubleRooms();
						
						// exclude rooms on records
						$availableRooms = $this->excludeRoomsOnRecords($allRooms, $records);
						
						// exclude double rooms needed for bookings
						$availableDoubleRooms = $this->getAvailableDoubleRooms($availableRooms, $allDoubleRooms);
						$doubleRoomsNeededAmt = $this->_dataMapper->getDoubleRoomsNeededAmount($timeIn, $timeOut, $exceptedBookingID);
						$doubleRoomsNeededAmt += $doubleRoomAmt;
						
						if (count($availableDoubleRooms) >= $doubleRoomsNeededAmt) {
							$result['available'] = true;
							$result['remark'] = "Booking is available";
							/*
							 * do not check the availability of signle rooms for now
							$availableRooms = $this->excludeDoubleRoomsNeeded($availableRooms, $availableDoubleRooms, $doubleRoomsNeededAmt);
							
							// exclude single rooms needed for bookings
							$availableSingleRooms = $this->getAvailableSingleRooms($availableRooms);
							$singleRoomsNeededAmt = $this->_dataMapper->getSingleRoomsNeededAmount($timeIn, $timeOut);
							$singleRoomsNeededAmt += $singleRoomAmt;
							
							if (count($availableSingleRooms) >= $singleRoomsNeededAmt) {
								$result['available'] = true;
								$result['remark'] = "Booking is available";
							} else {
								$result['available'] = false;
								$result['remark'] = "Not enough room";
							}
							*/
						} else {
							$result['available'] = false;
							$result['remark'] = "Not enough double room";
						}
					} else {
						$result['remark'] = '';
						foreach ($result['unavailable_therapists'] as $therapist)
							$result['remark'] .= '['.$therapist.'] ';
						
						$result['remark'] .= "not available";
					}
				} else {
					// cannot book
					$result['available'] = false;
					$result['remark'] = "No therapist available";
				}
				
				return Utilities::getResponseResult(true, '', $result);
			} catch(Exception $e) {
				return Utilities::getResponseResult(false, 'Getting availability for booking is failed');
			}
		}
		
		private function addQueueSequence($therapists)
		{
			for ($i = 0; $i < count($therapists); $i++) {
				$therapists[$i]['row_no'] = $i + 1;
			}
			
			return $therapists;
		}
		
		private function getTherapistsOnQueueWithHalfDayCondition($date, $timeIn, $timeOut) {
			$therapists = $this->_dataMapper->getTherapistsOnQueue($date);
			$therapists = $this->manageTherapistQueueForHalfDayShiftCondition($therapists, $date, $timeIn, $timeOut);
			
			return $therapists;
		}
		
		private function manageTherapistQueueForHalfDayShiftCondition($therapists, $date, $timeIn, $timeOut) {
			$fullDayTherapists = array();
			$halfDayTherapists = array();
			
			$doAllFullDayTherapistsReachMin = true;
			
			$minTurn = Queue_Config::MIN_TURN_FULL_DAY;
			if ($minTurn > 0) {
				$amountList = $this->_dataMapper->getRecordsAndBookingsAmountOfTherapists($date, $timeIn, $timeOut);
				//Utilities::logDebug("HalfdayCondition => ".var_export($amountList, true));
				
				// check whether every full-day therapist has turns more than the minimun turn or not
				foreach ($therapists as $therapist) {
					if ($therapist['shift_type_id'] == 1) {
						foreach ($amountList as $amountItem) {
							if ($therapist['therapist_id'] == $amountItem['therapist_id']) {
								$sumAmount = $amountItem['record_amount'] + $amountItem['booking_amount'];
								if ($sumAmount < $minTurn) {
									$doAllFullDayTherapistsReachMin = false;
								}
									
								break;
							}
						}
						
						if (!$doAllFullDayTherapistsReachMin) break;
					}
				}
				
				// if any full-day therapist has turn < min turn, then swap half-day ones to the bottom
				if (!$doAllFullDayTherapistsReachMin) {
					foreach ($therapists as $therapist) {
						if ($therapist['shift_type_id'] == 1) {
							array_push($fullDayTherapists, $therapist);	
						} else {
							array_push($halfDayTherapists, $therapist);
						}
					}
					
					$therapists = array_merge($fullDayTherapists, $halfDayTherapists);
				}
			}
			
			return $therapists;
		}
		
		private function excludeTherapistsOnRecords($allTherapists, $records)
		{
			for ($i = 0; $i < count($records); $i++) {
				for ($j = 0; $j < count($allTherapists); $j++) {
					if ($records[$i]['therapist_id'] == $allTherapists[$j]['therapist_id']) {
						$allTherapists[$j]['therapist_available'] = 0;
						$allTherapists[$j]['massage_record_id'] = $records[$i]['massage_record_id'];
						$allTherapists[$j]['massage_record_time_in'] = $records[$i]['massage_record_time_in'];
						$allTherapists[$j]['massage_record_time_out'] = $records[$i]['massage_record_time_out'];
						$allTherapists[$j]['therapist_remark'] = 'Doing Massage';
						break;
					}
				}	
			}
			
			return $allTherapists;
		}
		
		private function isTherapistAvailable($availableTherapists, $bookings, $clientAmt)
		{	
			$consecutiveBookings = $this->getConsecutiveBookings($bookings);
			$therapistNeededForBookingsAmt = count($consecutiveBookings);
			
			$availableTherapistsAmt = 0;
			for ($i = 0; $i < count($availableTherapists); $i++) {
				if ($availableTherapists[$i]['therapist_available'] == 1) {
					$availableTherapistsAmt++;
				} else {
					// if therapist is not available, then check whether she can continue with any booking or not
					$time_start = $availableTherapists[$i]['massage_record_time_in'];
					$time_end = $availableTherapists[$i]['massage_record_time_out'];
					
					$consecutiveItemIndex = $this->getConsecutiveSlot($consecutiveBookings, $time_start, $time_end);
					
					if (is_null($consecutiveItemIndex)) {
						// the record does not match any booking, then do not count availability
					} else {
						$availableTherapistsAmt++;
						
						$virtualBooking = array();
						$virtualBooking['booking_id'] = $availableTherapists[$i]['massage_record_id'];
						$virtualBooking['booking_item_id'] = $availableTherapists[$i]['massage_record_id'];
						$virtualBooking['therapist_id'] = $availableTherapists[$i]['therapist_id'];
						$virtualBooking['booking_time_in'] = $availableTherapists[$i]['massage_record_time_in'];
						$virtualBooking['booking_time_out'] = $availableTherapists[$i]['massage_record_time_out'];
						
						array_push($consecutiveBookings[$consecutiveItemIndex]['consecutive_bookings'], $virtualBooking);
					}
				}
			}
			
			Utilities::logInfo('QueueingFunction.isTherapistAvailable() | '.var_export($consecutiveBookings, true));
			
			$availableAmt = $availableTherapistsAmt - $therapistNeededForBookingsAmt;	
			Utilities::logInfo("QueueFunction.isTherapistAvailable() | \$availableTherapistsAmt[{$availableTherapistsAmt}] - \$therapistNeededForBookingsAmt[{$therapistNeededForBookingsAmt}] = {$availableAmt}");
			
			if ($availableAmt >= $clientAmt)
				return true;
			else
				return false;
		}
		
		private function getConsecutiveBookings($bookings) {
			$consecutiveBookings = array();
			
			for ($i = 0; $i < count($bookings); $i++) {
				if ($i == 0) {
					$consecutiveBookings = $this->addMainConsecutiveBooking($consecutiveBookings, $bookings[$i]);
				} else {
					$time_start = $bookings[$i]['booking_time_in'];
					$time_end = $bookings[$i]['booking_time_out'];
					
					$consecutiveItemIndex = $this->getConsecutiveSlot($consecutiveBookings, $time_start, $time_end);
					//Utilities::logInfo("consecutiveItemIndex = {$consecutiveItemIndex}");
					
					if (is_null($consecutiveItemIndex)) {
						$consecutiveBookings = $this->addMainConsecutiveBooking($consecutiveBookings, $bookings[$i]);
						/*
						$bookings[$i]['consecutive_bookings'] = array();
						array_push($manipulatedBookings, $bookings[$i]);
						*/
					} else {
						array_push($consecutiveBookings[$consecutiveItemIndex]['consecutive_bookings'], $bookings[$i]);
						/*
						$manipulatedBookings[$consecutiveItemIndex]['consecutive_booking_id'] = $bookings[$i]['booking_id'];
						$manipulatedBookings[$consecutiveItemIndex]['consecutive_booking_item_id'] = $bookings[$i]['booking_item_id'];
						$manipulatedBookings[$consecutiveItemIndex]['consecutive_therapist_id'] = $bookings[$i]['therapist_id'];
						$manipulatedBookings[$consecutiveItemIndex]['consecutive_booking_time_in'] = $bookings[$i]['booking_time_in'];
						$manipulatedBookings[$consecutiveItemIndex]['consecutive_booking_time_out'] = $bookings[$i]['booking_time_out'];
						*/
					}
				}
			}
			
			//Utilities::logInfo('QueueingFunction.getConsecutiveBookings() | '.var_export($consecutiveBookings, true));
			return $consecutiveBookings;
		}
		
		private function addMainConsecutiveBooking($consecutiveBookings, $booking)
		{
			$consecBooking = array_merge(array(), $booking);
			$booking['consecutive_bookings'] = array();
				
			array_push($consecutiveBookings, $booking);
			array_push($consecutiveBookings[count($consecutiveBookings) - 1]['consecutive_bookings'], $consecBooking);
			
			return $consecutiveBookings;
		}
		
		private function getConsecutiveSlot($consecutiveBookings, $time_start, $time_end)
		{
			$consecutiveItemIndex = NULL;
			
			for ($j = 0; $j < count($consecutiveBookings); $j++) {
				$isDuplicateTime = false;
			
				for ($k = 0; $k < count($consecutiveBookings[$j]['consecutive_bookings']); $k++) {
					$time_start_b = $consecutiveBookings[$j]['consecutive_bookings'][$k]['booking_time_in'];;
					$time_end_b = $consecutiveBookings[$j]['consecutive_bookings'][$k]['booking_time_out'];
						
					$isDuplicateTime = $this->isDuplicateTime($time_start, $time_end, $time_start_b, $time_end_b);
					// if find any duplicate time, then stop the loop
					if ($isDuplicateTime) {
						break;
					}
				}
			
				// if found slot to add the item, then save index and stop the loop
				if (!$isDuplicateTime) {
					$consecutiveItemIndex = $j;
					break;
				}
				/*
				if ($manipulatedBookings[$j]['consecutive_booking_item_id'] == 0) {
					if ($manipulatedBookings[$j]['booking_time_out'] <= $bookings[$i]['booking_time_in']) {
				 		$consecutiveItemIndex = $j;
				 		break;
				 	} else if ($manipulatedBookings[$j]['booking_time_in'] >= $bookings[$i]['booking_time_out']) {
				 		$consecutiveItemIndex = $j;
				 		break;
				 	}
				 }
				 */
			}
			
			return $consecutiveItemIndex;
		}
		
		private function isDuplicateTime($time_start_a, $time_end_a, $time_start_b, $time_end_b)
		{
			$booking_time_in = strtotime($time_start_a);
			$booking_time_out = strtotime($time_end_a);
			$item_time_in = strtotime($time_start_b);
			$item_time_out = strtotime($time_end_b);
				
			if (($booking_time_in > $item_time_in && $booking_time_in < $item_time_out)
					|| ($booking_time_out > $item_time_in && $booking_time_out < $item_time_out)
					|| ($item_time_in > $booking_time_in && $item_time_in < $booking_time_out)
					|| ($item_time_out > $booking_time_in && $item_time_out < $booking_time_out)
					|| ($booking_time_in == $item_time_in && $booking_time_out == $item_time_out)
					) {
						return true;
					} else {
						return false;
					}
		}
		
		private function isRequestedTherapistAvailable($availableTherapists, $requestedTherapists)
		{
			$isAvailable = true;
			$unavailableTherapists = array();
			
			for ($i = 0; $i < count($requestedTherapists); $i++) {
				for ($j = 0; $j < count($availableTherapists); $j++) {
					if ($requestedTherapists[$i]['therapist_id'] == $availableTherapists[$j]['therapist_id']) {
						if ($availableTherapists[$j]['therapist_available'] == 0) {
							$isAvailable = false;
							array_push($unavailableTherapists, $availableTherapists[$j]['therapist_name']);
							break;
						}
					}
				}
			}
			
			$result['available'] = $isAvailable;
			$result['unavailable_therapists'] = $unavailableTherapists;
			
			return $result;
		}
		
		private function excludeTherapistsOnBookings($availableTherapists, $bookings)
		{
			$bookedTherapistsAmt = 0;
			
			for ($i = 0; $i < count($bookings); $i++) {
				if ($bookings[$i]['therapist_id'] != 0) {
					for ($j = 0; $j < count($availableTherapists); $j++) {
						if ($availableTherapists[$j]['therapist_id'] == $bookings[$i]['therapist_id']) {
							$availableTherapists[$j]['therapist_available'] = 0;
							$availableTherapists[$j]['therapist_remark'] = 'Requested for Booking';
							$bookedTherapistsAmt++;
							break;
						}
					}
				}
			}
			
			Utilities::logInfo('QueueFunction.excludeTherapistsOnBookings() | Amount of Booked Therapists: '.$bookedTherapistsAmt);
			
			return $availableTherapists;
		}
		
		private function disableTherapists($availableTherapists)
		{
			for ($i = 0; $i < count($availableTherapists); $i++) {
				if ($availableTherapists[$i]['therapist_available'] == 1) {
					$availableTherapists[$i]['therapist_available'] = 0;
					$availableTherapists[$i]['therapist_remark'] = 'Reserved For Booking';
				}
			}
			
			return $availableTherapists;
		}
		
		private function excludeRoomsOnRecords($allRooms, $records)
		{
			for ($i = 0; $i < count($records); $i++) {
				for ($j = 0; $j < count($allRooms); $j++) {
					if ($records[$i]['room_no'] == $allRooms[$j]['room_no']) {
						$allRooms[$j]['room_available'] = 0;
						$allRooms[$j]['room_remark'] = 'Being Used';
						break;
					}
				}
			}
				
			return $allRooms;
		}
		
		private function getAvailableDoubleRooms($allRooms, $allDoubleRooms)
		{
			$availableDoubleRooms = array();
			
			for ($i = 0; $i < count($allDoubleRooms); $i++) {
				for ($j = 0; $j < count($allRooms); $j++) {
					if ($allDoubleRooms[$i]['room_no_1'] == $allRooms[$j]['room_no']) {
						$allDoubleRooms[$i]['room_no_1_available'] = $allRooms[$j]['room_available'];
						continue;
					}
					
					if ($allDoubleRooms[$i]['room_no_2'] == $allRooms[$j]['room_no']) {
						$allDoubleRooms[$i]['room_no_2_available'] = $allRooms[$j]['room_available'];
						continue;
					}
				}
				
				if ($allDoubleRooms[$i]['room_no_1_available'] == 1 && $allDoubleRooms[$i]['room_no_2_available'] == 1) {
					Utilities::logInfo("QueueFunction.getAvailableDoubleRooms() | Available Double Room: No. {$allDoubleRooms[$i]['room_double_no']}");
					array_push($availableDoubleRooms, $allDoubleRooms[$i]);
				}
			}
			
			return $availableDoubleRooms;
		}
		
		private function excludeDoubleRoomsNeeded($allRooms, $availableDoubleRooms, $doubleRoomsNeededAmt)
		{
			Utilities::logInfo("QueueFunction.excludeDoubleRoomsNeeded() | Available Double Rooms: ".count($availableDoubleRooms));
			Utilities::logInfo("QueueFunction.excludeDoubleRoomsNeeded() | Needed Double Rooms: ".$doubleRoomsNeededAmt);
			
			// amount of double room must be more than needed amount of it 
			for ($i = 0; ($i < $doubleRoomsNeededAmt) && ($i < count($availableDoubleRooms)); $i++) {
				for ($j = 0; $j < count($allRooms); $j++) {
					if ($availableDoubleRooms[$i]['room_no_1'] == $allRooms[$j]['room_no']
							|| $availableDoubleRooms[$i]['room_no_2'] == $allRooms[$j]['room_no']) {
						Utilities::logInfo("QueueFunction.excludeDoubleRoomsNeeded() | Disabling Room No. ".$allRooms[$j]['room_no']);
						$allRooms[$j]['room_available'] = 0;
						$allRooms[$j]['room_remark'] = 'Reserved For Booking';
					}
				}
			}
			
			return $allRooms;
		}
		
		private function getAvailableSingleRooms($availableRooms)
		{
			$singleRooms = array();
			$availableSingleRooms = array();
			
			// find availability of each single room (including rooms that can be double e.g. 4.1 & 4.2)
			for ($i = 0; $i < count($availableRooms); $i++) {
				$singleRoom['room_no'] = intval($availableRooms[$i]['room_no']);
				$singleRoom['room_available'] = $availableRooms[$i]['room_available'];
				
				$roomExisted = false;
				for ($j = 0; $j < count($singleRooms); $j++) {
					if ($singleRooms[$j]['room_no'] == $singleRoom['room_no']) {
						$roomExisted = true;
						$singleRooms[$j]['room_available'] = $singleRooms[$j]['room_available'] && $singleRoom['room_available'];
						break;
					}
				}
				
				if (!$roomExisted) {
					array_push($singleRooms, $singleRoom);
				}
			}
			
			// if the room is available, then add to the list
			for ($i = 0; $i < count($singleRooms); $i++) {
				if ($singleRooms[$i]['room_available'] == 1) {
					Utilities::logInfo("QueueFunction.getAvailableSingleRooms() | Available Single Room: No. {$singleRooms[$i]['room_no']}");
					array_push($availableSingleRooms, $singleRooms[$i]);
				}
			}
			
			return $availableSingleRooms;
		}
		
		private function isSingleRoomsAvailableForWalkIn($availableSingleRooms, $singleRoomsNeededAmt)
		{
			Utilities::logInfo("QueueFunction.isSingleRoomsAvailableForWalkIn() | Needed Single Rooms: {$singleRoomsNeededAmt}");
			
			if (count($availableSingleRooms) > $singleRoomsNeededAmt)
				return true;
			else
				return false;
		}
		
		private function disableSingleRooms($availableRooms, $availableSingleRooms)
		{
			for ($i = 0; $i < count($availableRooms); $i++) {
				for ($j = 0; $j < count($availableSingleRooms); $j++) {
					if (intval($availableRooms[$i]['room_no']) == $availableSingleRooms[$j]['room_no']) {
						$availableRooms[$i]['room_available'] = 0;
						$availableRooms[$i]['room_remark'] = 'Reserved For booking';
						break;
					}
				}
			}
			
			return $availableRooms;
		}
		
		private function getResponseInfo($available, $searchInfo, $availableTherapists, $availableRooms)
		{
			$result['available'] = $available;
			$result['client_amount'] = $searchInfo['client_amount'];
			$result['date'] = $searchInfo['date'];
			$result['minutes'] = $searchInfo['minutes'];
			$result['time_in'] = $searchInfo['time_in'];
			$result['time_out'] = $searchInfo['time_out'];
			$result['therapists'] = $availableTherapists;
			$result['rooms'] = $availableRooms;
			
			return $result;
		}
	}
?>









