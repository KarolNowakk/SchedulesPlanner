<?php 
	
	//------ THIS CLASS IS RESPONSIBLE FOR SCHEDULE GENERATION -----------------------
	class ScheduleGenerator{

	private $con;
	public $errors;
	private $date;
	private $dateDate;
	private $year;
	private $month;
	private $monthId;
	private $msgArr;
	private $weekDaysWorkingHours;
	private $reqWorkersOnShift;
	
	public function __construct($con,$date,$wdWorkHours,$reqWorkersOnShift){
		$this->con = $con;
		$this->date = $date;
		$this->errors = [];
		$this->dateDate = strtotime($date);
		$this->weekDaysWorkingHours = $wdWorkHours;
		$this->reqWorkersOnShift = $reqWorkersOnShift;

		$this->year = (int)date('y',$this->dateDate);
		$this->month = (int)date('n',$this->dateDate);
	}

	private function setMonthId(){
		$query = mysqli_query($this->con, "SELECT * FROM months WHERE year='$this->year' AND monthNum='$this->month'");
		$row = $query->fetch_assoc();
		$this->monthId = (int)$row['id'];
		
	}


//---- MAIN FUNCTION GENERATING SCHEDULE
	public function generateSchedule($workersIds,$hoursReq,$daysOff){
		//print_r($hoursReq);
		$numOfDays = $this->getMonthData()[0];
		$year = $this->getMonthData()[1];
		$monthNum = $this->getMonthData()[2];
		$hoursKeepTrack = [];
		
		//--------- adding month --------------------------------------------------------------
		if($this->monthExist()){
			array_push($this->errors,"FUNCTION generateSchedule(): SCHEDULE FOR THIS MONTH ALLREADY EXIST");
			return false;
		}

		if($this->addMonth($numOfDays,$year,$monthNum)){
			$monthId = $this->getMonthId($year,$monthNum);	//month id for day data table
		}else{
			array_push($this->errors,"FUNCTION generateSchedule(): ADDING MONTH FAILED");
			return false;
		}

		//--------- adding days ---------------------------------------------------------------
		if($this->addAllDaysOfMonth($numOfDays,$monthId)){ 
			$allDays = $this->getAllDays($monthId); //array with all dys data
		}else{
			array_push($this->errors,"FUNCTION generateSchedule(): ADDING DAYS FAILED");
			return false;
		}

		//-------- adding workers days -----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

		//loop for adding workers in 
		foreach ($allDays as $day) {
			$shiftStart = $this->getHours($day['dayOfWeek'])[0];
			$shiftEnd = $this->getHours($day['dayOfWeek'])[1];
			$workersOnShift = $this->getNumOfWorkersOnShift($day['dayOfWeek']); // variable with info of how many people should be on one shift
			$workersAlreadyWorkingThisDay = [];
			$addAnyway = 0;
			$workersAlreadyOnShift = 0;
			$k = 0;
			$i = 0;
			$iterateThruoughtWorkers = [];

			while ($i<=$workersOnShift AND $addAnyway < 1) {
				$arrIndex = rand(0,count($workersIds)-1);
				$workerId = $workersIds[$arrIndex];

				// ---- check if worker is already assign to this day -----
				if (in_array($workerId,$workersAlreadyWorkingThisDay)) {
					continue;
				}

				//---- check if worker can work this day -----
				if(in_array($day['dayDate'],$daysOff[$arrIndex])){

					if(!in_array($workerId,$iterateThruoughtWorkers)){
						array_push($iterateThruoughtWorkers,$workerId);
					}
					
					if(count($iterateThruoughtWorkers) == count($workersIds)){
						break;
					}else{
						continue;
					}
				}

				// ---- check if worker has enough hours ----------
				if(!$this->checkForHours($hoursReq,$hoursKeepTrack,$arrIndex)){
					$k++;
					if($k > 70){
						$addAnyway = 2;
					}
					continue;
				}

				// ---- check how many workers are already on shift ---------------------------------------
				if($workersOnShift == $workersAlreadyOnShift){
					break;
				}

				//---- if everythig end well add working day ---
				if(!$this->addWorkerDay($day['id'], $workerId,$shiftStart,$shiftEnd,$monthId)){
					array_push($this->errors,"FUNCTION generateSchedule(): ADDING WORKING DAYS FAILED");
					return false;
				}else{
					$workersAlreadyOnShift++;
					$roznica = (strtotime($shiftEnd) - strtotime($shiftStart))/60/60;
					$hoursKeepTrack[$arrIndex] = $hoursKeepTrack[$arrIndex] + ((strtotime($shiftEnd) - strtotime($shiftStart))/60/60);
					array_push($workersAlreadyWorkingThisDay,$workerId);
				}

				$i++;
			}

		}
		//print_r($hoursKeepTrack);
		return true;
	}


//---- ADDING THINGS FUNCTIONS ----	
	private function addMonth($numberOfDays,$year,$monthNum){
		return mysqli_query($this->con, "INSERT INTO months VALUES('','$numberOfDays','$year', '$monthNum')"); 
	}

	private function addDay($monthId,$dayDate,$dayOfWeek){
		return mysqli_query($this->con,"INSERT INTO day VALUES('','$monthId','$dayDate','$dayOfWeek')"); 
	}

	private function addAllDaysOfMonth($numOfDays,$monthId){
		for ($i=1; $i <= $numOfDays; $i++) { 
			$date = strtotime($this->date."-".(string)$i);
			$dayOfWeek = (int)date('w',$date);

			if(!$this->addDay($monthId,$i,$dayOfWeek)){
				array_push($this->errors, "FUNCTION: addAllDaysOfMonth(): not in addDay()");
				return false;
			}
		}
		return true;
	}

	private function addWorkerDay($dayId,$workerId,$start,$end,$monthId){
		$workingHours = (strtotime($end) - strtotime($start)) / 60 / 60;
		return mysqli_query($this->con,"INSERT INTO dayworker VALUES('','$dayId','$monthId','$workerId','$start','$end','$workingHours','false')"); 
	}

//---- SELECT FROM DATABASE FUNCTIONS ---- 
	private function getMonthId($yr,$mn){
		$query = mysqli_query($this->con,"SELECT id FROM months WHERE year='$yr' AND monthNum='$mn'");
		return $query->fetch_assoc()['id'];
	}

	private function getAllDays($mn){
		$query = mysqli_query($this->con,"SELECT * FROM day WHERE month='$mn'");
		$table = [];
		while($row = $query->fetch_assoc()){
			array_push($table,$row);
		}
		return $table;
	}

//---- GETTING THINGS FUNCTIONS ----
 	private function getMonthData(){
 		$date = strtotime($this->date);
 		$numOfDays = (int)date('t',$date);
 		$year = (int)date('y',$date);
 		$monthNum = (int)date('n',$date);

 		return array($numOfDays,$year,$monthNum);
 	}	

 	private function getHours($dn){
 		if($dn == 0){
 			return $this->weekDaysWorkingHours[6];
 		}else{
			$dn = $dn - 1;
			return $this->weekDaysWorkingHours[$dn];
		 }
 		
 	}
 	private function getNumOfWorkersOnShift($dn){
		if($dn == 0){
			return $this->reqWorkersOnShift[6];
		}else{
		   $dn = $dn - 1;
		   return $this->reqWorkersOnShift[$dn];
		}
 	}
//---- PUBLIC FUNCTIONS GETTING DATA FROM CLASS ----
 	public function getErrors(){
 		return $this->errors;
 	}
//----- CHECKING FUNCTION -----
 	private function checkForHours($wH,$kT,$id){
 		return $wH[$id] > $kT[$id];	
	 }
	 
	 private function monthExist(){
		$query = mysqli_query($this->con,"SELECT id FROM months WHERE year='$this->year' AND monthNum='$this->month'");
		$arr = $query->fetch_assoc();
		print_r($arr);
		return !empty($arr);
	 }

//----- FUCTION USEFUL WHILE DEVELOPING ----
 	public function clear(){
 		
 		$this->setMonthId();
   //----------------------------------------------------------
 		$dayId = mysqli_query($this->con, "SELECT id FROM day WHERE month='$this->monthId'");
 		
 		$dayIdArr = [];
 		while($row = $dayId->fetch_assoc()){
 			array_push($dayIdArr, $row['id']);
 		}
  //-----------------------------------------------
		
 		$clearMonth = mysqli_query($this->con, "DELETE FROM months WHERE id='$this->monthId'");
 		$clearDay = mysqli_query($this->con,"DELETE FROM day WHERE month='$this->monthId'");
  //------------------------------------------------------------- 		
 		foreach ($dayIdArr as $id) {
 			$clearDayWorker = mysqli_query($this->con, "DELETE FROM dayworker WHERE dayId='$id'");
 		}
  //-------------------------------------------
 		mysqli_query($this->con,"ALTER TABLE day AUTO_INCREMENT=0");
 		mysqli_query($this->con,"ALTER TABLE dayworker AUTO_INCREMENT=0");
 		mysqli_query($this->con,"ALTER TABLE months AUTO_INCREMENT=0");
 	}

}

 ?>

