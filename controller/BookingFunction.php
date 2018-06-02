<?php
	require_once '../controller/Session.php';
	require_once '../controller/BookingDataMapper.php';
	require_once '../controller/QueueDataMapper.php';
	require_once '../controller/RoomDataMapper.php';
	require_once '../controller/TherapistDataMapper.php';
	require_once '../controller/MassageDataMapper.php';
	require_once '../controller/MassageTypeDataMapper.php';
	require_once '../controller/Utilities.php';
	require_once '../controller/FieldSorter.php';
	require_once '../config/Color_Config.php';
	require_once '../config/Queue_Config.php';
	
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
			
			$massageTypeMapper = new MassageTypeDataMapper();
			$massageTypes = $massageTypeMapper->getMassageTypes();
			
			$result['therapist_amount'] = count($therapists);
			$result['therapists'] = $therapists;
			$result['massage_types'] = $massageTypes;
			
			return Utilities::getResponseResult(true, '', $result);
		}
		
		public function addBooking($bookingInfo)
		{
			$id = Utilities::getUniqueID();
			
			$affectedRow = $this->_dataMapper->addBooking($id, $bookingInfo);
			
			if ($affectedRow > 0) {
				$affectedRow = $this->_dataMapper->addBookingItems($id, $bookingInfo['therapists'], $bookingInfo['massage_types']);
				
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
		
		public function updateBooking($bookingInfo)
		{
			$affectedRow = $this->_dataMapper->updateBooking($bookingInfo);
			
			if ($affectedRow > 0) {
				$result['booking_move_to'] = $bookingInfo['time_in'];
			
				return Utilities::getResponseResult(true, 'Updating the booking is succeeded.', $result);
			} else {
				return Utilities::getResponseResult(false, 'Updating the booking is failed!');
			}
		}
		
		public function getBookings($date, $exceptedBookingID = "")
		{
			$bookings = $this->_dataMapper->getBookingItems($date, $exceptedBookingID);
			$bookingGroups = $this->_dataMapper->getBookingGroups($date);
			$bookings = $this->countBookingItemsInGroup($bookings, $bookingGroups);
			
			$bookingRooms = $this->_dataMapper->getBookingRooms($date);
			$bookings = $this->combineBookingData($bookings, $bookingRooms);
			
			return $bookings;
		}
		
		public function getBookingItem($bookingID, $bookingItemID)
		{
			$bookings = $this->_dataMapper->getBookingItem($bookingItemID);
			$bookingRooms = $this->_dataMapper->getBookingRoom($bookingID);
			$bookings = $this->combineBookingData($bookings, $bookingRooms);
			
			return $bookings[0];
				
		}
		
		public function getBookingTimeline($date)
		{			
			$resultGroups = $this->arrangeBookingTimeline($date);
			
			$timelineTherapistGroups = $resultGroups['timeline_groups'];
			// Merge excessive items into excessive groups 
			$timelineTherapistGroups = $this->getTimelineBookingItems($resultGroups['excessive_groups'], $timelineTherapistGroups);
			
			// Get available gaps between timeline items in each group
			$timelineGroups = $this->getTimelineAvailableGapItems($timelineTherapistGroups);
			
			$result['bookings'] = $resultGroups['bookings'];
			$result['timeline_groups'] = $timelineGroups;
			
			return Utilities::getResponseResult(true, '', $result);
		}
		
		// This function will be used for creating Booking Timeline and calculating availability when making a booking
		// returning
		// 		1. $resultGroups['bookings']			
		//		2. $resultGroups['timeline_groups']		*for records and bookings allocation
		//		3. $resultGroups['excessive_groups']	*for any excessive bookings (NOT enough staff)
		//
		public function arrangeBookingTimeline($date, $exceptedBookingID = "", $dummyBookings = array()) {
			//$queueMapper = new QueueDataMapper();
			$therapistMapper = new TherapistDataMapper();
			$massageMapper = new MassageDataMapper();
				
			$therapists = $therapistMapper->getTherapistsOnShift($date, false);
			$records = $massageMapper->getRecords($date);
			$bookings = $this->getBookings($date, $exceptedBookingID);
			
			$bookings = array_merge($bookings, $dummyBookings);
			
			//Utilities::logInfo('QueueingFunction.arrangeBookingTimeline() | $bookings | '.var_export($bookings, true));
			
			// Therapists are to be created as a group
			$timelineTherapistGroups = $this->getTimelineTherapistGroups($therapists);
			
			// Records and requested bookings will be added into the group of each therapist
			$timelineTherapistGroups = $this->getTimelineRecordItems($records, $bookings, $timelineTherapistGroups);
				
			// Add non-requested bookings into groups
			$resultGroups = $this->mergeNonRequestedBookingIntoTherapistGroups($timelineTherapistGroups, $bookings);
			 
			return $resultGroups;
		}
		
		public function deleteBooking($bookingID)
		{
			$affectedRow = $this->_dataMapper->deleteBooking($bookingID);
			
			if ($affectedRow > 0)
				return Utilities::getResponseResult(true, 'The booking has been deleted.');
			else
				return Utilities::getResponseResult(true, 'Deleting booking is failed!');
		}
		
		private function countBookingItemsInGroup($bookings, $bookingGroups)
		{
			for ($i = 0; $i < count($bookings); $i++) {
				$bookings[$i]['booking_group_total'] = 0;
				$bookings[$i]['booking_group_item_no'] = 0;
				
				for ($j = 0; $j < count($bookingGroups); $j++) {
					if ($bookings[$i]['booking_id'] == $bookingGroups[$j]['booking_id']) {
						$bookings[$i]['booking_group_total'] = $bookingGroups[$j]['booking_group_total'];
						$bookings[$i]['booking_group_item_no'] = ++$bookingGroups[$j]['booking_group_item_no'];
						break;
					}
				}
			}
			
			return $bookings;
		}
		
		private function combineBookingData($bookings, $bookingRooms)
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
			$timeIn = Utilities::formatTime($bookingInfo['time_in']);
			$timeOut = Utilities::formatTime($bookingInfo['time_out']);
			
			return "The booking for <b><u>{$bookingInfo['client_amount']}</b></u> client for <b><u>{$bookingInfo['minutes']}</u></b> minutes from <b><u>{$timeIn}</b></u> to <b><u>{$timeOut}</b></u> is added.";
		}
		
		private function getTimelineGroups($therapists)
		{
			$groups = array();
			
			for ($i = 0; $i < count($therapists); $i++) {
				$group_item['id'] = $i + 1;
				$group_item['content'] = 'Booking #'.($i + 1);
				$group_item['style'] = 'font-weight: bold;';
				$group_item['items'] = array();
				
				array_push($groups, $group_item);
			}
			
			return $groups;
		}
		
		private function getTimelineTherapistGroups($therapists, $groupSize = 0)
		{
			$groups = array();
				
			for ($i = 0; $i < count($therapists); $i++) {
				//Utilities::logInfo('Therapist name : '.$therapists[$i]['therapist_name']);
				$group_item['id'] = $groupSize + $i + 1;
				$group_item['therapist_id'] = $therapists[$i]['therapist_id'];
				$group_item['shift_type_id'] = $therapists[$i]['shift_type_id'];
				$group_item['shift_time_start'] = $therapists[$i]['shift_time_start'];
				$group_item['shift_create_datetime'] = $therapists[$i]['shift_create_datetime'];
				$group_item['shift_working'] = $therapists[$i]['shift_working'];
				$group_item['content'] = $therapists[$i]['therapist_name'];
				$group_item['items'] = array();
				//$group_item['style'] = 'font-weight: bold;';
				
				if ($i < count(Color_Config::SHIFT_BGs)) {
					$group_item['style'] = Color_Config::SHIFT_FGs[$i].Color_Config::SHIFT_BGs[$i];
				}
				else {
					$group_item['style'] = Color_Config::BOLD;
				}
			
				array_push($groups, $group_item);
			}
				
			return $groups;
		}
		
		private function getExcessiveTherapistGroup($groupSize)
		{
			$group_item['id'] = $groupSize + 1;
			$group_item['content'] = 'Booking #'.($groupSize + 1);
			$group_item['style'] = Color_Config::BOOKING_BG;
			//$group_item['style'] = 'font-weight: bold; color: red;';
			//$group_item['title'] = 'Need On-Call Therapist!!!';
			$group_item['shift_working'] = 1;
			$group_item['items'] = array();
			
			return $group_item;
		}
		
		private function getTimelineRecordItems($records, $bookings, $timelineGroups)
		{			
			foreach($records as $record) {
				$therapistID = $record['therapist_id'];
				
				foreach($timelineGroups as $key => $group) {	
					if ($group['therapist_id'] == $therapistID) {
						array_push($timelineGroups[$key]['items'], $this->getTimelineRecordItem($record, $group['id'], $group['style']));
						break;
					}
				}
			}
			
			foreach($bookings as $booking) {
				if ($booking['booking_item_status'] == 1) {
					$therapistID = $booking['therapist_id'];
					
					if ($therapistID != 0) {
						foreach($timelineGroups as $key => $group) {
							if ($group['therapist_id'] == $therapistID) {
								//Utilities::logDebug("Booking Item ID [{$booking['booking_item_id']}] is being added");
								array_push($timelineGroups[$key]['items'], $this->getTimelineRequestedBookingItem($booking, $group['id'], $group['style']));
								break;
							}
						}
					}
				}
			}
			
			// sort items, so that they can be searched for a gaps correctly
			$this->sortTimelineItemsInGroups($timelineGroups);
			
			return $timelineGroups;
		}
		
		//private function getTimelineBookingItems($bookings, $therapists, $timelineGroups)
		private function getTimelineBookingItems($bookings, $timelineGroups)
		{
			$timelineItems = array();
			$excessiveGroupIDs = array();
			
			// which group each booking item should go in?
			//
			foreach ($bookings as $booking) {
				// Add a booking item to the timeline if the status is 1 (coming) 
				if ($booking['booking_item_status'] == 1) {
					$isItemAddedToGroup = false;
					
					/*
					$isRequestedTherapistWorking = false;
					foreach ($therapists as $therapist) {
						if ($booking['therapist_id'] == $therapist['therapist_id']) {
							$isRequestedTherapistWorking = true;
							break;
						}
					}
					*/
					
					//if (!$isRequestedTherapistWorking) {
					foreach ($timelineGroups as $key => $group) {
						//Utilities::logDebug('Group #'.$group['id'].' | Item Amount:'.count($group['items']));
						
						if ($group['shift_working'] == 1) {
							// if there is no any item in group, then add the booking in the group
							//
							if (count($group['items']) > 0) {
								// loop items in the group, check whether the booking item is really unique in the group
								//
								foreach ($group['items'] as $item) {
									$isUniqueItem = true;
									
									if ($this->isDuplicateBookingTime($booking, $item)) {
										// if booking item is duplicate with any existing items, then check with the next one
										$isUniqueItem = false;
										break;
									}
								}
								
								if($isUniqueItem) {
									array_push($timelineGroups[$key]['items'], $this->getTimelineBookingItem($booking, $group['id'], $excessiveGroupIDs));
									$isItemAddedToGroup = true;
								}
								
							} else {
								array_push($timelineGroups[$key]['items'], $this->getTimelineBookingItem($booking, $group['id'], $excessiveGroupIDs));
								$isItemAddedToGroup = true;
							}
						}
						
						if ($isItemAddedToGroup)
							break;
					}
						
					// if a booking item is not added into any group, then create a new group and add the item into it
					//
					if (!$isItemAddedToGroup) {
						$groupSize = count($timelineGroups);
						
						array_push($excessiveGroupIDs, $groupSize + 1);
						
						array_push($timelineGroups, $this->getExcessiveTherapistGroup($groupSize));
						array_push($timelineGroups[$groupSize]['items'], $this->getTimelineBookingItem($booking, $timelineGroups[$groupSize]['id'], $excessiveGroupIDs));
					}
					//}
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
			
			//Utilities::logDebug('BookingFunction.isDuplicateBookingTime | Booking Time: '.$booking['booking_time_in'].' to '.$booking['booking_time_out'].' | Item Time: '.$timelineItem['start'].' to '.$timelineItem['end']);
			
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
		
		private function getTimelineRecordItem($recordItem, $groupID, $style)
		{
			$item['item_type'] = 'record';
			$item['group'] = $groupID;
			$item['id'] = $recordItem['massage_record_id'];
			$item['start'] = $recordItem['massage_record_date_time_in'];
			$item['end'] = $recordItem['massage_record_date_time_out'];
			//$item['remark'] = 'R';
			
			$item['title'] = $this->getTimelineItemTitle($item['start'], $item['end'], "Record"
				, $recordItem['booking_name']
				, $recordItem['booking_tel']
				, ''
				, $recordItem['therapist_name']
				, $recordItem['room_no']
				, $recordItem['massage_type_name']);
			
			//$item['style'] = Color_Config::RECORD_FG_DEFAULT.Color_Config::RECORD_BG_DEFAULT;
			$item['style'] = $style;
			
			if (empty($recordItem['booking_name'])) {
				$item['content'] = "<span style=\"cursor: pointer;\"><b>WALK-IN</b></span>";
			} else {
				$item['content'] = "<span style=\"cursor: pointer;\"><b>{$recordItem['booking_name']}</b></span>";
			}
			
			return $item;
		}
		
		private function getTimelineRequestedBookingItem($bookingItem, $groupID, $style = Color_Config::BOOKING_BG)
		{
			$item['item_type'] = 'booking';
			$item['group'] = $groupID;
			$item['id'] = $bookingItem['booking_item_id'];
			$item['start'] = $bookingItem['booking_time_in'];
			$item['end'] = $bookingItem['booking_time_out'];
			//$item['remark'] = 'B';
			
			$item['title'] = $this->getTimelineItemTitle($item['start'], $item['end'], "Booking"
				, $bookingItem['booking_name']
				, $bookingItem['booking_tel']
				, $bookingItem['therapist_name']
				, ''
				, ''
				, $bookingItem['massage_type_name']
				, $bookingItem['booking_client']
				, $bookingItem['single_room_amount'], $bookingItem['double_room_amount']
				, $bookingItem['booking_remark']);
			
			$item['style'] = $style.Color_Config::BOOKING_BORDER;
			
			$item['content'] = $this->getTimelineItemContent($bookingItem, true);
			
			return $item;
		}
		
		private function getTimelineBookingItem($bookingItem, $groupNumber, $excessiveGroupIDs = array())
		{
			$item['item_type'] = 'booking';
			$item['group'] = $groupNumber;
			$item['id'] = $bookingItem['booking_item_id'];
			$item['therapist_id'] = 0;			
			$item['start'] = $bookingItem['booking_time_in'];
			$item['end'] = $bookingItem['booking_time_out'];
			/*$start = new DateTime($bookingItem['booking_time_in']);
			$end = new DateTime($bookingItem['booking_time_out']);
			$item['start'] = $start->format(DateTime::ATOM);
			$item['end'] = $end->format(DateTime::ATOM);*/
			
			//$item['remark'] = 'B';
			
			$item['title'] = $this->getTimelineItemTitle($item['start'], $item['end'], "Booking"
					, $bookingItem['booking_name']
					, $bookingItem['booking_tel']
					, ''
					, ''
					, ''
					, $bookingItem['massage_type_name']
					, $bookingItem['booking_client']
					, $bookingItem['single_room_amount'], $bookingItem['double_room_amount']
					, $bookingItem['booking_remark']);
			
			if ($bookingItem['booking_item_status'] == 2) {
				// BG is gray if the client is already come
				$item['style'] = 'background-color: #F0ECE4;';
			} else {
				// if the client has not come, then check whether the item is in an excessive group?
				/*for ($i = 0; $i < count($excessiveGroupIDs); $i++) {
					if ($groupNumber == $excessiveGroupIDs[$i]) {
						$item['style'] = Color_Config::BOOKING_BG;
						//$item['style'] = 'background-color: #FF2525;';
						//$item['title'] += ' (Need On-Call Therapist!)';
						break;
					}
				}*/
				$item['style'] = Color_Config::BOOKING_BG;
			}
			
			$item['content'] = $this->getTimelineItemContent($bookingItem, true);
			
			return $item;
		}
		
		private function getTimelineItemTitle($start, $end, $header, $clientName = '', $tel = '', $reqTherapistName = '', $therapistName = '', $roomNo = '', $msgType = '', $clientAmt = 1, $singleRoomAmt = 0, $doubleRoomAmt = 0, $remark = "")
		{
			$title = "";
			
			$minutes = Utilities::dateDiff($start, $end);
			$start = Utilities::formatTime($start);
			$end = Utilities::formatTime($end);
			
			if (!empty(($header)))
				$title .= "<b><u>{$header}</u></b>";
			
			$title .= "<br><b>{$minutes} minutes</b> ({$start} to {$end})";
			
			if (!empty($clientName))
				$title .= '<br><b>Client</b>: '.$clientName;
			
			if (!empty($tel))
				$title .= ' ('.$tel.')';
			
			if (!empty($reqTherapistName))
				$title .= '<br><b>Request</b>: '.$reqTherapistName;
			
			if (!empty($therapistName))
				$title .= '<br><b>Massage with</b>: '.$therapistName;
			
			if (!empty($roomNo))
				$title .= '<br><b>Room #</b>: '.$roomNo;
			
			if (!empty($msgType))
				$title .= '<br><b>Massage Type</b>: '.$msgType;
			
			if ($clientAmt > 1) {
				if ($singleRoomAmt > 0)
					$title .= '<br><b>Single Room</b>: '.$singleRoomAmt;
				
				if ($doubleRoomAmt > 0)
					$title .= '<br><b>Double Room</b>: '.$doubleRoomAmt;
			}
			
			if (!empty($remark))
				$title .= '<br><b>Remark</b>: '.$remark;
			
			return $title;
		}
		
		
		private function getTimelineItemContent($bookingItem, $isBookingItem)
		{
			$content = "<span style=\"cursor: pointer;\">";
					
			if ($isBookingItem)
				$content .= "<b>B# {$bookingItem['booking_name']}</b>";
			else
				$content .= "<b>{$bookingItem['booking_name']}</b>";
			
			if ($bookingItem['therapist_id'] != 0) {
				$content .= "<span class=\"text-sub\"> (Req)</span>";
			}
			
			if ($bookingItem['booking_group_total'] > 0)
				$content .= " (<span class=\"text-mark\">{$bookingItem['booking_group_item_no']}</span> of <span class=\"text-mark\">{$bookingItem['booking_group_total']}</span>)";
			
			if (!empty($bookingItem['booking_remark']))
				$content .= " (*{$bookingItem['booking_remark']})";
				
			$content .= "</span>";
				
			return $content;
		}
		
		private function getTimelineAvailableGapItems($timelineGroups)
		{
			// sort items, so that they can be searched for a gaps correctly
			$this->sortTimelineItemsInGroups($timelineGroups);
			
			foreach ($timelineGroups as $key => $therapistGroup) {
				if (count($therapistGroup['items']) > 1) {
					$previousEnd = $therapistGroup['items'][0]['end'];
					
					// start loop with the second item
					for ($i = 1; $i < count($therapistGroup['items']); $i++) {
						$currentStart = $therapistGroup['items'][$i]['start'];
						
						// if having the gap between items, then adding the gap item
						if ($currentStart > $previousEnd) {
							$gapItem = $this->getTimetimeGapItem($therapistGroup, $previousEnd, $currentStart);
							array_push($timelineGroups[$key]['items'], $gapItem);
						}
						
						$previousEnd = $therapistGroup['items'][$i]['end'];
					}
				}
			}
			
			return $timelineGroups;
		}
		
		private function getTimetimeGapItem($group, $start, $end)
		{
			$item['item_type'] = 'gap';
			$item['group'] = $group['id'];
			$item['id'] = Utilities::getUniqueID(rand());
			$item['start'] = $start;
			$item['end'] = $end;
			$item['content'] = 'X';
			$item['title'] = $this->getTimelineItemTitle($item['start'], $item['end'], "Gap");
			$item['style'] = Color_Config::GAP;
				
			return $item;
		}
		
		private function sortTimelineItemsInGroups(&$timelineGroups) 
		{
			$sorter = new FieldSorter('start');
			foreach ($timelineGroups as $key => $group) {
				usort($timelineGroups[$key]['items'], array($sorter, "compare"));
			}
		}
		
		private function sortTimelineItemsInFullDayTherapistGroup(&$timelineGroups)
		{
			$sorter = new FieldSorter('item_amount');
			usort($timelineGroups, array($sorter, "compare"));
		}
		
		private function mergeNonRequestedBookingIntoTherapistGroups($timelineTherapistGroups, $bookings)
		{
			$result['timeline_groups'] = array();
			$result['excessive_groups'] = array();
			$result['bookings'] = $bookings;
			
			foreach ($bookings as $booking) {
				// loop for a booking that is non-requested and active
				if ($booking['booking_item_status'] == 1 
					&& $booking['therapist_id'] == 0)
				{
					$isBookingItemAdded = false;
					$refinedQueue = $this->getRefinedQueueOfTherapists($timelineTherapistGroups, $booking['booking_time_in']);
					
					// Find queue for booking
					foreach ($refinedQueue as $queue) {
						$therapistID = 0;
						$isBookingDuplicated = false;
						
						// loop to find therapist according to the queue and find empty slot for booking item
						foreach ($timelineTherapistGroups as $key => $group) {
							// therapist must be in working status
							
							if ($group['shift_working'] == 1) {
								if ($queue['therapist_id'] == $group['therapist_id']) {
									$therapistID = $group['therapist_id'];
									
									// Find available slot for booking
									foreach ($timelineTherapistGroups[$key]['items'] as $item) {
										if ($this->isDuplicateBookingTime($booking, $item)) {
											// if find any duplicate item, then stop loop
											$isBookingDuplicated = true;
											break;
										}
									}
									
									// If slot is found, then add the item into the group
									if(!$isBookingDuplicated) {
										$isBookingItemAdded = true;
										// add item into the group
										array_push($timelineTherapistGroups[$key]['items'], $this->getTimelineBookingItem($booking, $group['id']));
									}
									
									break;
								}
							}
						}
						
						// Stop queue loop if the item is added
						if($isBookingItemAdded) {							
							break;
						}
					}
					
					// if item is not added, then push item into the excessive group
					if(!$isBookingItemAdded) {
						array_push($result['excessive_groups'], $booking);
					}
				}
			}
			
			
			//Utilities::logDebug('Excessive => '.var_export($result['excessive_groups'], true));
			$result['timeline_groups'] = $timelineTherapistGroups;
			
			return $result;
		}
		
		private function getRefinedQueueOfTherapists($timelineTherapistGroups, $bookingTimeIn)
		{
			
			$timelineGroups = $this->getSortedQueueOfTherapists($timelineTherapistGroups, $bookingTimeIn);
			$timelineGroups = $this->getQueueOfTherapistsUnderHalfDayCondition($timelineGroups, $bookingTimeIn);
			
			return $timelineGroups;
		}
		
		private function getSortedQueueOfTherapists($timelineTherapistGroups, $bookingTimeIn)
		{
			// 1. find the lastest timeout of each therapist in the group
			//		1.1 but the lastest time must be less than or equal to "booking_time_in"
			// 2. sort the queue of therapists by timeout with ascending order
			//		*** First Out, First In 
			// 3. create new array and add therapist according to the queue
			
			$queues = array();
			$newTimelineGroups = array();
			
			// 1.
			foreach ($timelineTherapistGroups as $group) {
				$queue = array('therapist_name' => $group['content']
						, 'therapist_id' => $group['therapist_id']
						, 'lastest_time_out' => $group['shift_time_start']
						, 'shift_create_datetime' => $group['shift_create_datetime']
				);
				
				if (count($group['items']) > 0) {
					foreach ($group['items'] as $item) {
						// 1.1
						if ($item['end'] > $queue['lastest_time_out']
							&& $item['end'] <= $bookingTimeIn
						) {
							$queue['lastest_time_out'] = $item['end'];
						}
					}
				} else {
					// if there is no item yet, use "shift_time_start" as "lastes_time_out"
					$queue['lastest_time_out'] = $group['shift_time_start'];
				}
				
				array_push($queues, $queue);
			}
			
			// 2.
			//Utilities::logDebug('Queue before sort => '.var_export($queues, true));
			/*
			 * This method does not provide a correct sorting result
			 * in the case that values in each array element are equal
			$sorter = new FieldSorter('lastest_time_out');
			usort($queues, array($sorter, "compare"));
			*/
			$queues = $this->sortQueue($queues);
			//Utilities::logDebug('Queue after sort => '.var_export($queues, true));
			
			// 3.
			foreach ($queues as $q) {
				foreach ($timelineTherapistGroups as $group) {
					if ($group['therapist_id'] == $q['therapist_id']) {
						array_push($newTimelineGroups, $group);
						break;
					}
				}
			}
			
			//Utilities::logDebug("Sorted Queue for booking at {$bookingTimeIn}");
			//Utilities::logDebug(var_export($queues, true));
			
			return $newTimelineGroups;
		}
		
		private function sortQueue($queues)
		{
			$sortingQueues = $queues;
			
			foreach ($sortingQueues as $key => $q) {
				$timeout[$key]  = $q['lastest_time_out'];
				$createDatetime[$key] = $q['shift_create_datetime'];
			}
			
			// Sort the data with timeout ascending, create_datetime ascending
			// Add $data as the last parameter, to sort by the common key
			array_multisort($timeout, SORT_ASC, $createDatetime, SORT_ASC, $sortingQueues);
			
			return $sortingQueues;
		}
		
		private function getQueueOfTherapistsUnderHalfDayCondition($timelineTherapistGroups, $bookingTimeIn)
		{	
			// 1. count amount of customers of each full-day therapist that time_out is less then "booking_time_in"
			// 2. check if any therapist has less than 2 customers before the "booking_time_in"
			// 3. check the result
			//		3.1	if, there is any
			//			3.2.1 split therapists into 2 groups; full-day & half-day
			//			3.2.2 move the list of half-day therapists to the bottom
			//			3.2.3 return the sorted groups
			//		3.2 if, there is nothing
			//			3.2.1 return the same groups
			
			$fullDayTherapists = array();
			$fullDayTherapistsNotReachMin = array();
			$fullDayTherapistsReachMin = array();
			$halfDayTherapists = array();
			
			$doAllFullDayTherapistsReachMin = true;
			$minTurn = Queue_Config::MIN_TURN_FULL_DAY;
			
			if ($minTurn > 0) {
				// check whether every full-day therapist has turns more than the minimun turn or not
				foreach ($timelineTherapistGroups as $key => $group) {
					// if any full-day therapist has turn < min turn, then swap half-day ones to the bottom
					if ($group['shift_type_id'] == 1) {
						$sumAmount = 0;
						//$sumAmount = count($group['items']);
						//$timelineTherapistGroups[$key]['item_amount'] = $sumAmount;
						
						// 1.
						foreach ($group['items'] as $item) {
							if ($item['end'] <= $bookingTimeIn) {
								$sumAmount++;
							}
						}
						
						// check if any full-therapist does not reach minimun turn
						//Utilities::logDebug("Therapist Name [{$timelineTherapistGroups[$key]['content']}] got {$sumAmount} customers before {$bookingTimeIn}");
						if ($sumAmount < $minTurn) {
							$doAllFullDayTherapistsReachMin = false;
							//array_push($fullDayTherapistsNotReachMin, $group);
						} else {
							//array_push($fullDayTherapistsReachMin, $group);
						}
						
						// store full-day therapists into another group
						array_push($fullDayTherapists, $group);
						//break;
					} else {
						// store half-day therapists into another group
						array_push($halfDayTherapists, $group);
					}
				}
				
				// if any full-day therapist has turn < min turn, then swap half-day ones to the bottom
				if (!$doAllFullDayTherapistsReachMin) {
					// merge full-day therapists (same queue) and half-day therapists together
					$timelineTherapistGroups = array_merge($fullDayTherapists, $halfDayTherapists);
					
					//$timelineTherapistGroups = array_merge($fullDayTherapistsNotReachMin, $fullDayTherapistsReachMin, $halfDayTherapists);
					
					/*
					foreach ($timelineTherapistGroups as $group) {
						if ($group['shift_type_id'] == 1) {
							array_push($fullDayTherapists, $group);
						} else {
							array_push($halfDayTherapists, $group);
						}
					}
					*/
					
					//$this->sortTimelineItemsInFullDayTherapistGroup($fullDayTherapists);
					//$timelineTherapistGroups = array_merge($fullDayTherapists, $halfDayTherapists);
				}
			}
			
			return $timelineTherapistGroups;
		}
	}
?>








