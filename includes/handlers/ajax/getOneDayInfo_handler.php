<?php 
require_once('../../config.php');
require_once('../../classes/ScheduleOperations.php');

if(isset($_POST['date'])){
	$date = $_POST['date'];

	//$date = '2020-2';
	$dayDate = $_POST["dayDate"];

	$schedule = new ScheduleOperations($con, $date);
	$info = $schedule->getOneDayInfo($dayDate);
    
	echo json_encode($info);

}