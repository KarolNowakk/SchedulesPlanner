<?php 

require_once('../../config.php');
require_once('../../classes/ScheduleOperations.php');

if(isset($_POST['start'])){


	$date = $_POST['date'];
	$dayId = $_POST['dayId'];
	$wId = $_POST['workerId'];
	$start = $_POST['start'];
	$end = $_POST['end'];

	//$table = ['msg'=>[$date,$dayId,$wId,$start,$end]];	

	$schedule = new ScheduleOperations($con,$date);

	$schedule->updateDay($dayId,$wId,$start,$end);

	$msg = ['msg'=>"UPDATE HAS BEEN DONE SUCCESFULLY"];
	echo json_encode($msg);

}	

if(isset($_POST['delete'])){

	$date = $_POST['date'];
	$dayId = $_POST['dayId'];
	$wId = $_POST['workerId'];

	$schedule = new ScheduleOperations($con,$date);

	$schedule->dayworkerDelete($dayId,$wId);

	$msg = ['msg'=>"ITEM HAS BEEN DONE DELETED"];
	echo json_encode($msg);
}	


?>

