<?php 
require_once('../../config.php');
require_once('../../classes/ScheduleOperations.php');

if(isset($_POST['date'])){
	$date = $_POST['date'];

	// $date = '2020-1';z
	
	$schedule = new ScheduleOperations($con, $date);
	$days = $schedule->getDays();
	$week = [[],[],[],[],[],[],[]];
	$month = [];

	$workersInMonth = $schedule->getWorkersIds();
	$workers = [];

	foreach ($workersInMonth as $id) {
		array_push($workers,['firstName'=>$schedule->getWorkersName($id),
							'lastName' =>$schedule->getWorkersLastName($id),
							'id' =>$id,
							'listOfIds'=>$schedule->getDaysIds($id),
							'wDaysInfo'=>$schedule->getListWithWorkingDays($id)]);
	}

	
	foreach ($days as $day) {
		$week[$day['dayOfWeek']] =  $day;

		if($day['dayOfWeek'] == 6 OR ((int)$day['dayDate']-count($days)) == 0 ){
			array_push($month, $week);
			$week = [[],[],[],[],[],[],[]];
		}
	}

	$formatedData = ['workers' => $workers, 'weeks' => $month];

	echo json_encode($formatedData);

 }

?>