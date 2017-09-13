<?php
	require_once '../controller/DataAccess.php';
	require_once '../config/Booking_Config.php';
	
	class RosterDataMapper
	{
		private $_dataAccess;
		
		public function RosterDataMapper()
		{
			$this->_dataAccess = new DataAccess();
		}
		
		public function getShifts($from, $to, $allTherapists = true) {
			$joinCondition;
			
			if ($allTherapists)
				$joinCondition = 'left';
			else
				$joinCondition = '';

			$sql = "
select shift.shift_date, therapist.therapist_name, therapist.therapist_id, therapist.therapist_permission
	, shift.shift_type_id, shift.shift_time_start
    , ifnull(nested.shift_count, 0) as shift_count
from therapist
{$joinCondition} join shift on therapist.therapist_id = shift.therapist_id
	and shift.shift_date between '{$from}' and '{$to}'
left join (
    select therapist.therapist_id, count(shift.shift_date) as shift_count
	from therapist
	left join shift on therapist.therapist_id = shift.therapist_id
    where 1
        and shift.shift_date between '{$from}' and '{$to}'
        and therapist.therapist_active = 1
        and therapist.therapist_permission not in (0, 7)
    group by therapist.therapist_id
) as nested on nested.therapist_id = therapist.therapist_id
where 1
	and therapist.therapist_active = 1
    and therapist.therapist_permission not in (0, 7)
order by nested.shift_count desc, therapist.therapist_name, shift.shift_date
";
			
			return $this->_dataAccess->select($sql);
		}
		
		public function deleteShift($therapistID, $date) {
			$sql = "
delete from shift
where therapist_id = {$therapistID}
	and shift_date = '{$date}'
";
			
			return $this->_dataAccess->delete($sql);
		}
		
		public function updateShift($therapistID, $date, $shiftTypeID, $shiftTimeStart) {
			$sql = "
update shift
set shift_type_id = {$shiftTypeID}
	, shift_time_start = '{$shiftTimeStart}'
	, shift_update_datetime = now()
where therapist_id = {$therapistID}
	and shift_date = '{$date}'
";
			
			return $this->_dataAccess->update($sql);
		}
		
		public function addShift($therapistID, $date, $shiftTypeID, $shiftTimeStart) {
			$sql = "
insert into shift (therapist_id, shift_date, shift_type_id, shift_time_start, shift_working)
values ({$therapistID}, '{$date}', {$shiftTypeID}, '{$shiftTimeStart}', 1)
";
			
			return $this->_dataAccess->insert($sql);
		}
	}
?>

















