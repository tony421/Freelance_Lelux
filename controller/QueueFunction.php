<?php
	require_once '../controller/Session.php';
	require_once '../controller/QueueDataMapper.php';
	require_once '../controller/BookingFunction.php';
	require_once '../controller/BookingDataMapper.php';
	require_once '../controller/TherapistFunction.php';
	require_once '../controller/RoomDataMapper.php';
	require_once '../controller/RoomFunction.php';
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
				
				// exclude single rooms needed for bookings
				$availableSingleRooms = $this->getAvailableSingleRooms($availableRooms);
				$singleRoomsNeededAmt = $this->_dataMapper->getSingleRoomsNeededAmount($timeIn, $timeOut);
				
				$isAnySingleRoomAvailable = $this->isAnySingleRoomAvailable($availableSingleRooms, $singleRoomsNeededAmt);
				if (!$isAnySingleRoomAvailable) {
					$availableRooms = $this->disableSingleRooms($availableRooms, $availableSingleRooms);
				}
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
				
				// *** No need to disable needed single rooms, becuase they must be enable to be choosen  
				
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
		
		public function searchAvailabilityForBookingV0($searchInfo)
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
						
					// returning ['available'] & ['unavailable_therapists']
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
						//$doubleRoomsNeededAmt = $this->_dataMapper->getDoubleRoomsNeededAmount($timeIn, $timeOut, $exceptedBookingID);
						$dblConsecUse = $this->getDoubleRoomsConsecutiveUseForBooking($timeIn, $timeOut, $exceptedBookingID);
						$doubleRoomsNeededAmt = count($dblConsecUse) + $doubleRoomAmt;
		
						Utilities::logInfo("QueueFunction.searchAvailabilityForBooking() | Needed Double Rooms: ".$doubleRoomsNeededAmt);
		
						if (count($availableDoubleRooms) >= $doubleRoomsNeededAmt) {
							$result['available'] = true;
							$result['remark'] = "Booking is available";
								
							$availableRooms = $this->excludeDoubleRoomsNeeded($availableRooms, $availableDoubleRooms, $doubleRoomsNeededAmt);
								
							// exclude single rooms needed for bookings
							$availableSingleRooms = $this->getAvailableSingleRooms($availableRooms);
							//$singleRoomsNeededAmt = $this->_dataMapper->getSingleRoomsNeededAmount($timeIn, $timeOut);
							$sglConsecUse = $this->getSingleRoomsConsecutiveUseForBooking($timeIn, $timeOut, $dblConsecUse, $exceptedBookingID);
							$singleRoomsNeededAmt = count($sglConsecUse) + $singleRoomAmt;
								
							Utilities::logInfo("QueueFunction.searchAvailabilityForBooking() | Single Room Consecutive Use Amount: ".count($sglConsecUse));
							Utilities::logInfo("QueueFunction.searchAvailabilityForBooking() | Single Room Booking Amount: ".$singleRoomAmt);
							Utilities::logInfo("QueueFunction.searchAvailabilityForBooking() | Needed Single Rooms: ".$singleRoomsNeededAmt);
								
							if (count($availableSingleRooms) >= $singleRoomsNeededAmt) {
								$result['available'] = true;
								$result['remark'] = "Booking is available";
							} else {
								$result['available'] = false;
								$result['remark'] = "Not enough room";
							}
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
		
		public function searchAvailabilityForBookingV1($searchInfo)
		{
			try {
				$date = $searchInfo['date'];
				$timeIn = $searchInfo['time_in'];
				$timeOut = $searchInfo['time_out'];
				$singleRoomAmt = $searchInfo['single_room_amount'];
				$doubleRoomAmt = $searchInfo['double_room_amount'];
				$requestedTherapists = $searchInfo['therapists'];
				
				// setting [clientAmt] & [requestedTherapists] for a booking being updated 
				if (isset($searchInfo['booking_id'])) {
					$exceptedBookingID = $searchInfo['booking_id'];
						
					// must get updated client amount => count items only that have "booking_item_status" == 1
					$bookingMapper = new BookingDataMapper();
					$result = $bookingMapper->getBookingItemsForSearching($exceptedBookingID);
					
					if (count($result) > 0) {
						$clientAmt = count($result);
						$requestedTherapists = $result;
					} else {
						$clientAmt= $searchInfo['client_amount'];		
					}
				} else {
					$exceptedBookingID = "";
					$clientAmt= $searchInfo['client_amount'];
				}
				
				
				/*
				 *** client amount must be minus according to number of recorded booking items
				 */
				
				// init $result values with $searchInfo
				$result = $searchInfo;
				
				// Therapist Availability
				//
				// creating dummy booking items and allocate them to the Booking timeline to excessive items
				// if there is any excessive item, it means NOT AVAILABLE
				$dummyBookingItems = array();
				for ($i = 0; $i < $clientAmt; $i++) {
					$bookingItem = array();
					
					// dummy booking item
					$bookingItem['booking_item_id'] = 0;
					$bookingItem['booking_item_status'] = 1;
					$bookingItem['booking_name'] = "Dummy";
					$bookingItem['booking_tel'] = "Dummy";
					$bookingItem['booking_client'] = "Dummy";
					$bookingItem['massage_type_name'] = "Dummy";
					$bookingItem['booking_remark'] = "Dummy";
					$bookingItem['booking_group_total'] = 0;
					$bookingItem['booking_group_item_no'] = 0;
					
					$bookingItem['booking_id'] = $exceptedBookingID;
					$bookingItem['booking_date'] = $date;
					$bookingItem['booking_time_in'] = $timeIn;
					$bookingItem['booking_time_out'] = $timeOut;
					$bookingItem['therapist_id'] = $requestedTherapists[$i]['therapist_id'];
					$bookingItem['therapist_name'] = $requestedTherapists[$i]['therapist_name'];
					$bookingItem['single_room_amount'] = $singleRoomAmt;
					$bookingItem['double_room_amount'] = $doubleRoomAmt;
					
					array_push($dummyBookingItems, $bookingItem);
				}
				
				$bookingFunction = new BookingFunction();
				$arrangedBookings = $bookingFunction->arrangeBookingTimeline($date, true, $exceptedBookingID, $dummyBookingItems);
				
				Utilities::logInfo('============= Timeline Groups ========= | '.var_export($arrangedBookings['timeline_groups'], true));
				Utilities::logInfo('============= Excessive Groups ========= | '.var_export($arrangedBookings['excessive_groups'], true));
				
				// Check availability by counting a number of excessive items, if more than 0 = NOT AVAILABLE
				//
				if (count($arrangedBookings['excessive_groups']) > 0) {
					// *** NOT AVAILABLE
					$result['available'] = false;
					
					$notAvailableTherapists = array();
					for ($i = 0; $i < count($arrangedBookings['excessive_groups']); $i++) {
						if ($arrangedBookings['excessive_groups'][$i]['therapist_id'] != 0)
							array_push($notAvailableTherapists, $arrangedBookings['excessive_groups'][$i]['therapist_name']);
					}
					
					if (count($notAvailableTherapists) > 0)
						$result['remark'] = implode(', ', $notAvailableTherapists).' not available';
					else
						$result['remark'] = "Not enough therapist";
				} else {
					// Checking Room Availability
					//
					$records = $this->_dataMapper->getRecords($timeIn, $timeOut);
					
					$roomDataMapper = new RoomDataMapper();
					$allRooms = $roomDataMapper->getAllRooms();
					$allDoubleRooms = $roomDataMapper->getDoubleRooms();
					
					// Excluding rooms on records
					$availableRooms = $this->excludeRoomsOnRecords($allRooms, $records);
					
					// Excluding double rooms needed for bookings
					$availableDoubleRooms = $this->getAvailableDoubleRooms($availableRooms, $allDoubleRooms);
					$doubleRoomsNeededAmt = $this->_dataMapper->getDoubleRoomsNeededAmount($timeIn, $timeOut, $exceptedBookingID);
					$doubleRoomsNeededAmt += $doubleRoomAmt;
					
					if (count($availableDoubleRooms) >= $doubleRoomsNeededAmt) {							
						$availableRooms = $this->excludeDoubleRoomsNeeded($availableRooms, $availableDoubleRooms, $doubleRoomsNeededAmt);
						
						// Excluding single rooms needed for bookings
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
					} else {
						$result['available'] = false;
						$result['remark'] = "Not enough double room";
					}
				}
				
				return Utilities::getResponseResult(true, '', $result);
			} catch(Exception $e) {
				return Utilities::getResponseResult(false, 'Getting availability for booking is failed');
			}
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
					
					// returning ['available'] & ['unavailable_therapists']
					$checkResult = $this->isRequestedTherapistAvailable($availableTherapists, $requestedTherapists);
					$result['available'] = $checkResult['available'];
					$result['unavailable_therapists'] = $checkResult['unavailable_therapists'];
					
					if ($result['available']) {
						// Room Availability
						//
						$roomDataMapper = new RoomDataMapper();
						$allRooms = $roomDataMapper->getAllRooms();
						$allDoubleRooms = $roomDataMapper->getDoubleRooms();
						
						// A. Finding double room availability
						//	A.1. adding double rooms that is being used (on records) into consecutive list
						//	A.2. adding double rooms that is needed for bookings in the system into consecutive list
						//	A.3. calculating the availability 
						//		by (count(consec list) + needed amt) <= count(all double room)
						
						// A.1
						$dblConsecUse = $this->getDoubleRoomsConsecutiveUseForRecord($timeIn, $timeOut);
						
						// A.2
						$dblConsecUse = $this->getDoubleRoomsConsecutiveUseForBooking($timeIn, $timeOut, $dblConsecUse, $exceptedBookingID);
						
						// A.3
						$doubleRoomsNeededAmt = count($dblConsecUse) + $doubleRoomAmt;
						
						Utilities::logInfo("QueueFunction.searchAvailabilityForBooking() | Needed Double Rooms: ".$doubleRoomsNeededAmt);
						Utilities::logInfo("QueueFunction.searchAvailabilityForBooking() | All Double Rooms: ".count($allDoubleRooms));
						
						if ($doubleRoomsNeededAmt <= count($allDoubleRooms)) {
							$result['available'] = true;
							$result['remark'] = "Booking is available";
							
							// B. Finding single room availability
							//	B.1. adding single rooms that is being used (on records) into consecutive list of double room
							//	B.2. adding single rooms that is needed for bookings in the system into the consecutive list
							//	B.3. calculating the availability
							//		by (count(consec list) + needed amt) <= count(all double room)
							
							// B.1
							$sglConsecUse = $this->getSingleRoomsConsecutiveUseForRecord($timeIn, $timeOut, $dblConsecUse);
							
							// B.2
							$sglConsecUse = $this->getSingleRoomsConsecutiveUseForBooking($timeIn, $timeOut, $sglConsecUse, $exceptedBookingID);
							
							// B.3
							$singleRoomsNeededAmt = count($sglConsecUse) + $singleRoomAmt;
							
							Utilities::logInfo("QueueFunction.searchAvailabilityForBooking() | Single Room Consecutive Use Amount: ".count($sglConsecUse));
							Utilities::logInfo("QueueFunction.searchAvailabilityForBooking() | Single Room Booking Amount: ".$singleRoomAmt);
							Utilities::logInfo("QueueFunction.searchAvailabilityForBooking() | Needed Single Rooms: ".$singleRoomsNeededAmt);
							
							$allSingleRooms = $roomDataMapper->getSingleRooms();
							
							Utilities::logInfo("QueueFunction.searchAvailabilityForBooking() | All Single Rooms: ".count($allSingleRooms));
							
							if ($singleRoomsNeededAmt <= count($allSingleRooms)) {
								$result['available'] = true;
								$result['remark'] = "Booking is available";
							} else {
								$result['available'] = false;
								$result['remark'] = "Not enough room";
							}
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
		
		private function isTherapistAvailableV1($availableTherapists, $bookings, $clientAmt)
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
				
			//Utilities::logInfo('QueueingFunction.isTherapistAvailable() | '.var_export($consecutiveBookings, true));
				
			$availableAmt = $availableTherapistsAmt - $therapistNeededForBookingsAmt;
			Utilities::logInfo("QueueFunction.isTherapistAvailable() | \$availableTherapistsAmt[{$availableTherapistsAmt}] - \$therapistNeededForBookingsAmt[{$therapistNeededForBookingsAmt}] = {$availableAmt}");
				
			if ($availableAmt >= $clientAmt)
				return true;
				else
					return false;
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
			
			//Utilities::logInfo('QueueingFunction.isTherapistAvailable() | '.var_export($consecutiveBookings, true));
			
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
					} else {
						array_push($consecutiveBookings[$consecutiveItemIndex]['consecutive_bookings'], $bookings[$i]);
					}
				}
			}
			
			//Utilities::logInfo('QueueingFunction.getConsecutiveBookings() | '.var_export($consecutiveBookings, true));
			return $consecutiveBookings;
		}
		
		private function getConsecutiveBookingsForRoomAmount($bookings, $consecutiveRoomUse = array()) {
			$consecutiveBookings = $consecutiveRoomUse;
				
			for ($i = 0; $i < count($bookings); $i++) {
				for ($j = 0; $j < $bookings[$i]['booking_room_amount']; $j++) {
					if ($i == 0 && $j == 0 && count($consecutiveRoomUse) == 0) {
						$consecutiveBookings = $this->addMainConsecutiveBooking($consecutiveBookings, $bookings[$i]);
					} else {
						$time_start = $bookings[$i]['booking_time_in'];
						$time_end = $bookings[$i]['booking_time_out'];
					
						$consecutiveItemIndex = $this->getConsecutiveSlot($consecutiveBookings, $time_start, $time_end);
						//Utilities::logInfo("consecutiveItemIndex = {$consecutiveItemIndex}");
					
						if (is_null($consecutiveItemIndex)) {
							$consecutiveBookings = $this->addMainConsecutiveBooking($consecutiveBookings, $bookings[$i]);
						} else {
							array_push($consecutiveBookings[$consecutiveItemIndex]['consecutive_bookings'], $bookings[$i]);
						}
					}	
				}
			}
				
			//Utilities::logInfo('QueueingFunction.getConsecutiveBookings() | '.var_export($consecutiveBookings, true));
			return $consecutiveBookings;
		}
		
		private function getConsecutiveRecordsForRoomAmount($records, $consecutiveRoomUse = array()) {
			$consecutiveBookings = $consecutiveRoomUse;
			
			for ($i = 0; $i < count($records); $i++) {
				$booking = array();
				$booking['booking_id'] = 'R-'.$records[$i]['massage_record_id'];
				$booking['booking_time_in'] = $records[$i]['massage_record_time_in'];
				$booking['booking_time_out'] = $records[$i]['massage_record_time_out'];
				
				if ($i == 0 && count($consecutiveRoomUse) == 0) {
					$consecutiveBookings = $this->addMainConsecutiveBooking($consecutiveBookings, $booking);
				} else {
					$time_start = $booking['booking_time_in'];
					$time_end = $booking['booking_time_out'];
						
					$consecutiveItemIndex = $this->getConsecutiveSlot($consecutiveBookings, $time_start, $time_end);
					//Utilities::logInfo("consecutiveItemIndex = {$consecutiveItemIndex}");
						
					if (is_null($consecutiveItemIndex)) {
						$consecutiveBookings = $this->addMainConsecutiveBooking($consecutiveBookings, $booking);
					} else {
						array_push($consecutiveBookings[$consecutiveItemIndex]['consecutive_bookings'], $booking);
					}
				}
			}
			
			//Utilities::logInfo('QueueingFunction.getConsecutiveRecordsForRoomAmount() | '.var_export($consecutiveBookings, true));
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
						$allRooms[$j]['room_reserved'] = 1;
						$allRooms[$j]['room_remark'] = '<b><span class=\'text-danger\'> (Reserved)</span></b>';
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
						// if a room is part of double room, both must be available
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
		
		private function isAnySingleRoomAvailable($availableSingleRooms, $singleRoomsNeededAmt)
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
		
		private function getDoubleRoomsConsecutiveUseForRecord($timeIn, $timeOut)
		{
			$roomFunc = new RoomFunction();
			
			$records = $this->_dataMapper->getRecords($timeIn, $timeOut, 2);
			$noDoubleRoomDupRecords = array();
			
			for ($i = 0; $i < count($records); $i++) {
				if (count($noDoubleRoomDupRecords) > 0) {
					//$roomNo = intval($records[$i]['room_no']);
					$roomNo = $records[$i]['room_no'];
					
					// Table: room_double
					// room_double_no	room_no_1	room_no_2
					//		1			4.1			4.2
					//		2			2.1			2.2
					//		3			6			8
					$doubleRoomNo = $roomFunc->getDoubleRoomNo($roomNo);
					
					if ($doubleRoomNo > 0) {
						for ($j = 0; $j < count($noDoubleRoomDupRecords); $j++) {
							if ($doubleRoomNo != $noDoubleRoomDupRecords[$j]['room_no']) {
								array_push($noDoubleRoomDupRecords, $records[$i]);
								break;
							}
						}	
					} else {
						Utilities::logInfo("QueueFunction.getDoubleRoomsConsecutiveUseForRecord() | Room no:".$roomNo." is not double room");
					}
				} else {
					array_push($noDoubleRoomDupRecords, $records[$i]);
				}
			}
			
			$consecDblRoomRecords = $this->getConsecutiveRecordsForRoomAmount($noDoubleRoomDupRecords);
		
			return $consecDblRoomRecords;
		}
		
		private function getSingleRoomsConsecutiveUseForRecord($timeIn, $timeOut, $doubleRoomsConsecutiveUse)
		{
			$records = $this->_dataMapper->getRecords($timeIn, $timeOut, 1);
			$consecDblRoomRecords = $this->getConsecutiveRecordsForRoomAmount($records, $doubleRoomsConsecutiveUse);
		
			return $consecDblRoomRecords;
		}
		
		private function getDoubleRoomsConsecutiveUseForBooking($timeIn, $timeOut, $doubleRoomsConsecutiveUse, $exceptedBookingID = "")
		{
			$dblRoomBookings = $this->_dataMapper->getDoubleRoomsNeededForBookings($timeIn, $timeOut, $exceptedBookingID);
			$consecDblRoomBookings = $this->getConsecutiveBookingsForRoomAmount($dblRoomBookings, $doubleRoomsConsecutiveUse);
		
			return $consecDblRoomBookings;
		}
		
		// getting the consecutive usage of double rooms, and use them as a signle room
		//
		private function getSingleRoomsConsecutiveUseForBooking($timeIn, $timeOut, $doubleRoomsConsecutiveUse, $exceptedBookingID = "")
		{
			$sglRoomBookings = $this->_dataMapper->getSingleRoomsNeededForBookings($timeIn, $timeOut, $exceptedBookingID);
			$consecSglRoomBookings = $this->getConsecutiveBookingsForRoomAmount($sglRoomBookings, $doubleRoomsConsecutiveUse);
			
			return $consecSglRoomBookings;
		}
	}
?>









