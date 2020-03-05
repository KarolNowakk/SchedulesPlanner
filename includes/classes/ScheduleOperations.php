<?php 
class ScheduleOperations{

	private $con;
	public $errors;
	private $date;
	private $year;
	private $month;
	private $monthId;

	public function __construct($con,$date){
		$this->con = $con;
		$this->date = strtotime($date);
		$this->errors = [];

		$this->setYearAndMonth();
		$this->setMonthId();
	}

	private function setYearAndMonth(){
		$this->year = (int)date('y',$this->date);
		$this->month = (int)date('n',$this->date);
	}

	private function setMonthId(){
		$query = mysqli_query($this->con, "SELECT * FROM months WHERE year='$this->year' AND monthNum='$this->month'");
		$row = $query->fetch_assoc();
		$this->monthId = (int)$row['id'];	
	}

	public function getDays(){
		$table = [];
		$query = mysqli_query($this->con, "SELECT * FROM day WHERE month='$this->monthId' ORDER BY dayDate");
		while ($row = $query->fetch_assoc()) {
			if($row['dayOfWeek'] == 0){
				$row['dayOfWeek'] = 6;
			}else{
				$row['dayOfWeek'] = $row['dayOfWeek'] - 1;
			}
			array_push($table, $row);
		}
		return $table;
	}

	// public function getWorkersOfDay($dayId){
	// 	$table = [];
	// 	$query = mysqli_query($this->con, "SELECT * FROM dayworker WHERE dayId='$dayId'");
	// 	while ($row = $query->fetch_assoc()) {
	// 		array_push($table, $row);
	// 	}
	// 	return $table;
	// }

	public function getWorkersName($wrId){
		$table = [];
		$query = mysqli_query($this->con, "SELECT firstName FROM workers WHERE id='$wrId'");
		while ($row = $query->fetch_assoc()) {
			array_push($table, $row);
		}
		return $table[0]['firstName'];
	}

	public function getWorkersLastName($wrId){
		$table = [];
		$query = mysqli_query($this->con, "SELECT lastName FROM workers WHERE id='$wrId'");
		while ($row = $query->fetch_assoc()) {
			array_push($table, $row);
		}
		return $table[0]['lastName'];
	}

	public function getWorkersHours($wrId){
		$hours = 0;
		$query = mysqli_query($this->con, "SELECT * FROM dayworker WHERE workerId='$wrId' AND monthId='$this->monthId'");
		while ($row = $query->fetch_assoc()) {
			$hours = $hours + $row['workingHours'];
		}
		return $hours;
	}

	public function getWorkersPay($wrId){
		$query = mysqli_query($this->con, "SELECT * FROM workers WHERE id='$wrId'");
		$row = $query->fetch_assoc();
		return $row['salary'];
	}

	public function getWorkersIds(){
		$table = [];
		$query = mysqli_query($this->con,"SELECT DISTINCT workerId FROM dayworker WHERE monthId='$this->monthId'");
		while ($row = $query->fetch_assoc()) {
			array_push($table, $row['workerId']);
			sort($table);
		}
		return $table;
	}

	public function getAllShiftsOfWorker($wId){
		$table = [];
		$query = mysqli_query($this->con,"SELECT DISTINCT dayId FROM dayworker WHERE monthId='$this->monthId' AND workerId='$wId'");
		while ($row = $query->fetch_assoc()) {
			array_push($table, $this->getDayDate($row['dayId']));
			sort($table);
		}
		return $table;
	}

	public function getDayDate($dId){
		$query = mysqli_query($this->con,"SELECT  dayDate FROM day WHERE id='$dId'");
		return $row = $query->fetch_assoc()['dayDate'];
	}

	
	private function getDayId($dayDate){
		$query = mysqli_query($this->con, "SELECT id FROM day WHERE month='$this->monthId' AND dayDate='$dayDate'");
		$row = $query->fetch_assoc();
		return $row['id'];
	}

	public function getOneDayInfo($dayDate){
		$table = [];
		$dayId = $this->getDayId($dayDate);
		$query = mysqli_query($this->con, "SELECT * FROM dayworker WHERE dayId='$dayId'");
		while ($row = $query->fetch_assoc()) {
			array_push($table, ['firstName' => $this->getWorkersName($row['workerId']),
								'lastName' => $this->getWorkersLastName($row['workerId']),
								'start' => $row['start'],
								'end' => $row['end']]);
		}
		return $table;
	}
//-------------- UNUSED ---------------------------


	// public function getDayIdsAndDate(){
	// 	$table = [];
	// 	$query = mysqli_query($this->con, "SELECT id, dayDate FROM day WHERE month='$this->monthId'");
	// 	while ($row = $query->fetch_assoc()) {
	// 		array_push($table,['id' => $row['id'],
	// 						   'dayDate' => $row['dayDate']]);
	// 	}
	// 	return $table;
	// }


//---------------------------------------------



	public function getListWithWorkingDays($num){
		$query = mysqli_query($this->con, "SELECT id,dayId,end,start FROM dayworker WHERE workerId='$num' AND monthId='$this->monthId' ORDER BY dayId");
		$table = [];
		while($row = $query->fetch_assoc()){
			array_push($table,$row);
		}
		return $table;
	}

	public function getDaysIds($num){
		$query = mysqli_query($this->con, "SELECT dayId FROM dayworker WHERE workerId='$num' AND monthId='$this->monthId' ORDER BY dayId");
		$table = [];
		while($row = $query->fetch_assoc()){
			array_push($table,$row['dayId']);
		}
		return $table;	
	}

	// ------------------------------- UPDATING METHODS -------------------------------------------------
	public function updateDay($dayId,$wId,$start,$end){

		if($this->dayworkerExist($dayId,$wId)){
			$this->dayworkerDelete($dayId,$wId);
		}


		$workingHours = (strtotime($end) - strtotime($start)) / 60 / 60;
		mysqli_query($this->con,"INSERT INTO dayworker VALUES('','$dayId','$this->monthId','$wId','$start','$end','$workingHours','false')"); 

	}

	private function dayworkerExist($dayId,$wId){

		$query = mysqli_query($this->con, "SELECT id FROM dayworker WHERE dayId='$dayId' AND workerId='$wId'");
		$list = $query->fetch_assoc();
		
		return !empty($list);
	}

	public function dayworkerDelete($dayId,$wId){

		$query = mysqli_query($this->con, "DELETE FROM dayworker WHERE dayId='$dayId' AND workerId='$wId'");
		mysqli_query($this->con,"ALTER TABLE dayworker AUTO_INCREMENT=0");

	}

}
?>
