<?php
	require_once '../controller/Session.php';
	require_once '../controller/QueueDataMapper.php';
	require_once '../controller/TherapistFunction.php';
	require_once '../controller/RoomDataMapper.php';
	require_once '../controller/Utilities.php';
	
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
				$allTherapists = $this->_dataMapper->getTherapistsOnQueue($date);
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
				
				$isSingleRoomsAvailableForWalkIn = $this->isSingleRoomsAvailableForWalkIn($availableSingleRooms, $singleRoomsNeededAmt);
				if (!$isSingleRoomsAvailableForWalkIn) {
					$availableRooms = $this->disableSingleRooms($availableRooms, $availableSingleRooms);
				}
				//
				// END  - Room Availability
				
				$result = $this->getResponseInfo($isTherapistAvailableForWalkIn, $searchInfo, $availableTherapists, $availableRooms);
				
				return Utilities::getResponseResult(true, '', $result);
			} catch(Exception $e) {
				return Utilities::getResponseResult(false, 'Getting queue info for walk-in is failed');
			}
		}
		
		public function getQueueForBooking($searchInfo)
		{
			
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
				$date = $searchInfo['date'];
				$timeIn = $searchInfo['time_in'];
				$timeOut = $searchInfo['time_out'];
				$clientAmt= $searchInfo['client_amount'];
				$singleRoomAmt = $searchInfo['single_room_amount'];
				$doubleRoomAmt = $searchInfo['double_room_amount'];
				$requestedTherapists = $searchInfo['therapists'];
				
				// init $result values with $searchInfo
				$result = $searchInfo;
				
				// Therapist Availability
				//
				$allTherapists = $this->_dataMapper->getTherapistsOnQueue($date);
				
				$records = $this->_dataMapper->getRecords($timeIn, $timeOut);
				$availableTherapists = $this->excludeTherapistsOnRecords($allTherapists, $records);
				
				$bookings = $this->_dataMapper->getBookings($timeIn, $timeOut);
				
				$isTherapistAvailable = $this->isTherapistAvailable($availableTherapists, $bookings, $clientAmt);
				if ($isTherapistAvailable) {
					$availableTherapists = $this->excludeTherapistsOnBookings($availableTherapists, $bookings);
					// including ['available'] & ['unavailable_therapists']
					$checkResult = $this->checkRequestedTherapistAvailability($availableTherapists, $requestedTherapists);
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
						$doubleRoomsNeededAmt = $this->_dataMapper->getDoubleRoomsNeededAmount($timeIn, $timeOut);
						$doubleRoomsNeededAmt += $doubleRoomAmt;
						
						if (count($availableDoubleRooms) >= $doubleRoomsNeededAmt) {
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
								$result['remark'] = "Single room is not enough for the booking";
							}
						} else {
							$result['available'] = false;
							$result['remark'] = "Double room is not enough for the booking";
						}
					} else {
						$result['remark'] = '';
						foreach ($result['unavailable_therapists'] as $therapist)
							$result['remark'] .= '['.$therapist.'] ';
						
						$result['remark'] .= "not available for the booking";
					}
				} else {
					// cannot book
					$result['available'] = false;
					$result['remark'] = "There is no therapist available";
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
		
		private function excludeTherapistsOnRecords($allTherapists, $records)
		{
			for ($i = 0; $i < count($records); $i++) {
				for ($j = 0; $j < $allTherapists; $j++) {
					if ($records[$i]['therapist_id'] == $allTherapists[$j]['therapist_id']) {
						$allTherapists[$j]['therapist_available'] = 0;
						$allTherapists[$j]['therapist_remark'] = 'Doing Massage';
						break;
					}
				}	
			}
			
			return $allTherapists;
		}
		
		private function isTherapistAvailable($availableTherapists, $bookings, $clientAmt)
		{
			$availableTherapistsAmt = 0;
			
			for ($i = 0; $i < count($availableTherapists); $i++) {
				if ($availableTherapists[$i]['therapist_available'] == 1)
					$availableTherapistsAmt++;
			}
			
			Utilities::logInfo('QueueFunction.isTherapistAvailableForWalkIn() | Amount of Therapists : '.count($availableTherapists));
			Utilities::logInfo('QueueFunction.isTherapistAvailableForWalkIn() | Amount of Aailable Therapists : '.$availableTherapistsAmt);
			Utilities::logInfo('QueueFunction.isTherapistAvailableForWalkIn() | Amount of Bookings : '.count($bookings));
			
			$availableAmt = $availableTherapistsAmt - count($bookings);
			Utilities::logInfo('QueueFunction.isTherapistAvailableForWalkIn() | Possible amount of customers who can get massage: '.$availableAmt);
			
			if ($availableAmt >= $clientAmt)
				return true;
			else
				return false;
		}
		
		private function checkRequestedTherapistAvailability($availableTherapists, $requestedTherapists)
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
					for ($j = 0; $j < $availableTherapists; $j++) {
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
			$result['time_in'] = $searchInfo['time_in'];
			$result['client_out'] = $searchInfo['time_out'];
			$result['therapists'] = $availableTherapists;
			$result['rooms'] = $availableRooms;
			
			return $result;
		}
	}
?>









