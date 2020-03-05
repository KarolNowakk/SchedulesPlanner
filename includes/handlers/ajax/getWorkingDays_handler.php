<?php 
require_once('../../config.php');
require_once('../../classes/ScheduleOperations.php');

if(isset($_POST['date'])){
	
	$date = $_POST['date'];
	//$date = '2020-2';
	$id = $_SESSION['id'];

	$schedule = new ScheduleOperations($con, $date);
	$days = $schedule->getAllShiftsOfWorker($id);
    
	echo json_encode($days);

}