<?php 
require_once("../../config.php");
require_once("../../classes/ScheduleGenerator.php");


if(isset($_POST['date'])) {
	
	$date = $_POST['date'];
	$workersIds = $_POST['workersIds'];
	$workersHours = $_POST['workersHours'];
	$daysOff = $_POST['daysOff'];
	$wdWorkHours = $_POST['weekDaysWorkingHours'];
	$reqWorkersOnShift = $_POST['reqWorkersOnShift'];
	// $date = "2020-1";
	// $workersIds = [1,2,3,4,5,6,7,8];
	// $workersHours = [150,150,150,150,150,150,150,150];
	// $daysOff = [[15],[15],[15],[15],[15],[15],[15],[15]];
	// $wdWorkHours = [["07:30", "20:00"],["07:30", "20:00"],["07:30", "20:00"],["07:30", "20:00"],["07:30", "21:00"],["08:30", "21:00"],["09:30", "21:00"]];

	$schedule = new ScheduleGenerator($con,$date,$wdWorkHours,$reqWorkersOnShift);
	//$schedule->clear();
	
//----------------GENERATE SCHEDULE_________________________________________
	if($schedule->generateSchedule($workersIds,$workersHours,$daysOff)){
		echo json_encode($schedule->getErrors());
		
	}else{
		echo json_encode($schedule->getErrors());
	}
}
?>

<!-- <form method="POST">
 	<input action="generateSchedule_handler.php" type="submit" name="date">
</form> -->