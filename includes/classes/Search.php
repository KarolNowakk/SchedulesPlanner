<?php 

class Search {

	private $con;

	public function __construct($con){
		$this->con = $con;

	}
	public function searchForWorker($value){

		$pattern =  '/^'.$value.'.*\w/i'; 
		$table = [];
		$query = mysqli_query($this->con,"SELECT * FROM workers");

		while($row = $query->fetch_assoc()){
			if(preg_match($pattern, $row['firstName']) OR preg_match($pattern, $row['lastName'])){
				array_push($table, ["id" => $row['id'],
								"firstName" => $row['firstName'],
								"lastName" => $row['lastName']]);
			}
		}
		return $table;
	}

	public function getAllWorkers(){
		$table = [];
		$query = mysqli_query($this->con,"SELECT * FROM workers");
		while ($row = $query->fetch_assoc()) {
			array_push($table, ["id" => $row['id'],
								"firstName" => $row['firstName'],
								"lastName" => $row['lastName']]);
			sort($table);
		}
		return $table;
	}
}
?>