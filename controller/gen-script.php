<?php
	require_once '../controller/Authentication.php';
	require_once '../controller/Utilities.php';
	require_once '../controller/TherapistDataMapper.php';

	if (Authentication::userExists()) {
		if(!empty($_GET['name'])) {
			$name = $_GET['name'];
			
			if ($name == 'default_shift') {
				if(!empty($_GET['start']) && !empty($_GET['end'])) {
					$start = date_create($_GET['start']);
					$end = date_create($_GET['end']);
					
					$mapper = new TherapistDataMapper();
					$therapists = $mapper->getTherapistsOffShift($start->format('Y-m-d'));
					
					$createDatetime = date_create(Utilities::getDateTimeNow());
					$script = "";
					while($start <= $end) {
						$script .= "insert into shift (shift_date, therapist_id, shift_type_id, shift_working, shift_create_datetime) values ";
						for ($i = 0; $i < count($therapists) && $i < 5; $i++) {
							if ($i != 0)
								$script .= ",";
							
							$script .= "('{$start->format('Y-m-d')}', {$therapists[$i]['therapist_id']}, 1, 1, '{$createDatetime->format('Y-m-d H:i:s')}')";
							$createDatetime->add(new DateInterval('PT1S'));
						}
						
						$script .= ";<br>";
						$start->add(new DateInterval('P1D'));
					}
					
					echo $script;
				} else {
					echo "enter start date and end date";
				}
			} else if ($name == 'delete_shift') {
				if(!empty($_GET['start']) && !empty($_GET['end'])) {
					$start = $_GET['start'];
					$end = $_GET['end'];
					
					$script = "
							delete from shift 
							where shift_date >= '{$start}'
								and shift_date <= '{$end}'";
					
					echo $script;
				} else {
					echo "enter start date and end date";
				}
			}
		} else {
			echo "no name";
		}
	} else {
		echo 'no right';
	}
?>