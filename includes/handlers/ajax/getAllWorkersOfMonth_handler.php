<?php
	require_once('../../config.php');
	require_once('../../classes/ScheduleOperations.php');

	if(isset($_POST['date'])){
		$date = $_POST['date'];//'2020-3';

		$schedule = new ScheduleOperations($con,$date);

		$workersInMonth = $schedule->getWorkersIds();
		$formatedData = [];

		foreach ($workersInMonth as $worker) {
			$workersHoursPerMonth = $schedule->getWorkersHours($worker);	
			$payPerH = $schedule->getWorkersPay($worker);
			$workersPay = $payPerH * $workersHoursPerMonth;
			$name = $schedule->getWorkersName($worker);
			$lastName = $schedule->getWorkersLastName($worker);

			array_push($formatedData,['workersHoursPerMonth' => $workersHoursPerMonth,
									'payPerH' => (int)$payPerH,
									'workersPay' => $workersPay,
									'firstName' => $name,
									'lastName' => $lastName]);

		}
				
		echo json_encode($formatedData);
	}



	
?>	