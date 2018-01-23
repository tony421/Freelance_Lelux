<?php
	require_once '../controller/Session.php';
	require_once '../controller/RosterDataMapper.php';
	require_once '../controller/TherapistDataMapper.php';
	require_once '../controller/Authentication.php';
	require_once '../controller/FieldSorter.php';
	require_once '../controller/Utilities.php';
	require_once '../config/Booking_Config.php';
	
	class RosterFunction
	{
		private $_dataMapper;
		
		public function RosterFunction()
		{
			$this->_dataMapper = new RosterDataMapper();
		}
		
		public function getRoster($days)
		{
			$from = $days[0];
			$to = $days[count($days) - 1];
			
			$shifts;
			
			// Get shifts of every therapist but receptions to make data of roster 
			//
			if (Authentication::isAdmin() || Authentication::isManager() || Authentication::isReception()) {
				// if the user is admin
				// then, show all therapist of the shop, so that admin can manage
				//
				//$therapistMapper = new TherapistDataMapper();
				//$therapists = $therapistMapper->getOnlyTherapists();
				
				$shifts = $this->_dataMapper->getShifts($from, $to);
			} else {
				// if the user is therapist or reception
				// then, show only therapists on the roster
				//
				$shifts = $this->_dataMapper->getShifts($from, $to, false);
			}
			
			//Utilities::logDebug('Roster | $shifts => '.var_export($shifts, true));
			
			// $pivotRows = therapist_id, therapist_name
			// $pivotCol = shift_date
			// $aggCol = shift_type_id
			//
			$pivot = $this->convertToPivotTable($shifts, array('therapist_id', 'therapist_name'), 'shift_date', $days, 'shift_type_id');
			
			// if logged-in user is therapist, then move its roster to at the top
			// 1. Find the user in the pivot data
			//		1.1 if the user is found
			//			1.1.1 move it to at index[0] of the array
			//		1.2 if the user is not found
			//			1.2.1 create a new item of pivot
			//			1.2.2 add the item at index[0] of the array
			//
			if (!(Authentication::isAdmin() || Authentication::isManager() || Authentication::isReception())) {
				$userID = Authentication::getUser()->getID();
				$userName = Authentication::getUser()->getName();
				
				$isLoggedInUserInPivot = false;
				foreach ($pivot as $key => $row) {
					if ($row['therapist_id'] == $userID) {
						$loggedInUser[0] = $pivot[$key];
						
						array_splice($pivot, $key, 1);
						array_splice($pivot, 0, 0, $loggedInUser);
						
						$isLoggedInUserInPivot = true;
					}
				}
				
				if (!$isLoggedInUserInPivot) {
					$newRow = array();
					$newRow[0]['therapist_id'] = $userID;
					$newRow[0]['therapist_name'] = $userName;
					
					foreach ($days as $val) {
						$newRow[0][$val] = 0;
					}
					
					Utilities::logDebug('Roster | $newRow => '.var_export($newRow, true));
					array_splice($pivot, 0, 0, $newRow);
				}
			}
			
			//Utilities::logDebug('Roster | $pivot => '.var_export($pivot, true));

			// sort rows by 'therapist_name'
			
			$permission = Authentication::getPermission();
			
			$result['permission'] = $permission;
			$result['days'] = $days;
			$result['roster'] = $pivot;
			
			return Utilities::getResponseResult(true, 'Testing a new function!', $result); 
		}
		
		private function convertToPivotTable($data, $pivotRows, $pivotCol, $pivotColValues, $aggCol, $aggregation = '') 
		{
			$pivotTable = array(); 
			
			// generate pivot rows by key columns
			foreach ($data as $d) {
				$keyValues = array();
				foreach ($pivotRows as $pRow) {
					array_push($keyValues, $d[$pRow]);
				}
				
				if (!$this->isKeyRowExisted($pivotTable, $pivotRows, $keyValues)) {
					$row = array();
					foreach ($pivotRows as $pRow) {
						$row[$pRow] = $d[$pRow];
					}
					
					array_push($pivotTable, $row);
				}
			}
			
			// generate the aggregating columns of pivot rows
			// now generate by the list of column names, but can be modified for specific column 
			foreach ($pivotTable as $key => $row) {
				foreach ($pivotColValues as $val) {
					$pivotTable[$key][$val] = 0;
				}
			}
			
			// put all data in the pivot table
			foreach ($data as $d) {
				// find the row that it keys are matched with the data
				foreach ($pivotTable as $key => $row) {
					$isMatched = false;
					for ($i = 0; $i < count($pivotRows); $i++) {		
						if ($row[$pivotRows[$i]] == $d[$pivotRows[$i]]) {
							$isMatched = true;
						} else {
							$isMatched = false;
						}
					}
					
					if ($isMatched) {
						$aggVal = $d[$aggCol];
						$pivotTable[$key][$d[$pivotCol]] = $aggVal;
						break;
					}
				}
			}
			
			return $pivotTable;
		}
		
		private function isKeyRowExisted($pivotTable, $keyCols, $keyValues)
		{
			if (count($pivotTable) > 0) {
				// compare the values with all the data in pivot rows 
				//
				foreach ($pivotTable as $row) {
					$isUnique = false;
					//$log = '';
					
					// each row, compare the key values with the corresponding key columns
					for ($i = 0; $i < count($keyCols); $i++) {
						//$log .= $row[$keyCols[$i]].' == '.$keyValues[$i].'  |  ';
						
						if ($row[$keyCols[$i]] == $keyValues[$i]) {
							$isUnique = true;
						} else {
							// if find any unmatch value, then it is not unique
							$isUnique = false;
						}
					}
					
					//Utilities::logDebug($log);
					
					// if the new key values are unique, then it is gonna be added
					// if not, compare the values with the next row
					if ($isUnique)
						break;
				}
				
				/*
				if (!$isUnique)
					Utilities::logDebug('It is gonna be added!');
				else
					Utilities::logDebug('===== None ====');
				*/
				
				return $isUnique;
			} else {
				return false;
			}
		}
		
		public function manageRoster($shiftInfo) {
			// this can be create, update or delete shift info
			$therapistID = $shiftInfo['therapist_id'];
			$date = $shiftInfo['shift_date'];
			$shiftTypeID = $shiftInfo['shift_type_id'];
			
			$affectedRow = 0;
			$success = false;
			$msg = '';
			
			if ($shiftTypeID == 0) {
				// shift_type_id = 0, then delete it from the table
				//
				$affectedRow = $this->_dataMapper->deleteShift($therapistID, $date);
				if ($affectedRow > 0) {
					$success = true;
					$msg = 'Deleting shift is succeeded.';
				}
				else {
					$success = true; // just only write log
					$msg = 'Deleting shift is failed!';
				}
			} else {
				// if not 0, then try to update first
				// if updating is not succeeded. This means there is an existing data, then add a new shift 
				//
				$shiftTimeStart = $date.' ';
				if ($shiftTypeID == 1) {
					// if the shift if full-day, then check the date is weekend or weekday?
					if (Utilities::isWeekend($date))
						$shiftTimeStart .= Booking_Config::DEFAULT_TIME_START_WEEKEND;
					else
						$shiftTimeStart .= $shiftInfo['shift_type_time_start'];
				} else {
					$shiftTimeStart .= $shiftInfo['shift_type_time_start'];
				}
				
				// if shift_type_id = 5 (On-Call), then set shift_working as 0, otherwise 1
				$shiftWorking = 0;
				if ($shiftInfo['shift_type_id'] == 1)
					$shiftWorking = 1;
					
				$affectedRow = $this->_dataMapper->updateShift($therapistID, $date, $shiftTypeID, $shiftTimeStart, $shiftWorking);
				if ($affectedRow > 0) {
					$success = true;
					$msg = 'Updating shift is succeeded.';
				} else {
					$affectedRow = $this->_dataMapper->addShift($therapistID, $date, $shiftTypeID, $shiftTimeStart, $shiftWorking);
					if ($affectedRow > 0) {
						$success = true;
						$msg = 'Adding shift is succeeded.';
					} else {
						$success = false;
						$msg = 'Adding shift is failed!';
					}
				}
			}
			
			return Utilities::getResponseResult($success, $msg);
		}
	}
?>










