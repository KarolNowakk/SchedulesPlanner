<?php 
	
	class Account {
		private $con;
		private $errors;
		public $dataArray;

		public function __construct($con){
			$this->con = $con;
			$this->errors = [];
		}

//---- LOG IN FUNCTION ----
		public function login($em,$ps){
			$em = htmlentities($em,ENT_QUOTES,"UTF-8");

			//---- CHECKS IF PASSED EMAIL EXIST IN DATABASE THEN CHECKS FOR PASSWORD MATCH -----
			if(mysqli_num_rows($this->checkEmail($em)) != 0 ){
				$row = $this->getUserData($em)->fetch_assoc();

				if(password_verify($ps, $row['password'])){
					return true;
				}else{
					array_push($this->errors,"Email or password are incorrect");
					return false;
				}
			}else{
				array_push($this->errors,"Email or password are incorrect");
					return false;
			}
		}

//---- REGISTRATION FUNCTION -----
		public function register($fn,$ln,$em,$ps1,$ps2){
			$ps1 = $this->sanitizePassword($ps1);
			$ps2 = $this->sanitizePassword($ps2);
			$fn = $this->sanitizeName($fn);
			$ln = $this->sanitizeName($ln);

			$this->validateName($fn);
			$this->validateName($ln);
			$this->validateEmail($em);
			$this->validatePassword($ps1,$ps2);

			if(empty($this->errors)){
				return $this->addUser($fn,$ln,$em,$ps1);
			}else{
				return false;
			}
		}

//------FUNCTIONS HANDLING DATABASE OPERATIONS----	
		private function checkEmail($em){
			$query = mysqli_query($this->con, sprintf("SELECT id FROM workers WHERE email='$em'",mysqli_real_escape_string($this->con,$em))); 
			return $query;
		}

		private function getUserData($em){
			$query = mysqli_query($this->con, sprintf("SELECT * FROM workers WHERE email='$em'",mysqli_real_escape_string($this->con,$em))); 
			return $query;
		}

		private function addUser($fn,$ln,$em,$ps){
			$ps = password_hash($ps, PASSWORD_DEFAULT);
			$salary = 15;
			return mysqli_query($this->con,"INSERT INTO workers VALUES ('','$em','$ps','$fn','$ln','$salary','1')");
		}

//---- VALIDATION FUNCTIONS FOR REGISTERATION ------
		
		private function validateName($name){
			if(strlen($name) < 4 OR strlen($name) > 30){
				array_push($this->errors,"First/Last name must be beetween 4 and 30 charachters");
				return;
			}
		}
		
		private function validateEmail($em){
			if(!filter_var($em, FILTER_VALIDATE_EMAIL)){
				array_push($this->errors,"Double check your email");
				return;
			}
			if((mysqli_num_rows($this->checkEmail($em))) != 0){
				array_push($this->errors,"Email already exist in database");
				return;
			}
		}

		private function validatePassword($pass1, $pass2){
			if($pass1 != $pass2){
				array_push($this->errors,"Passwords don't match");
				return;
			}
			if(strlen($pass1) < 8 OR strlen($pass1) > 25){
				array_push($this->errors,"Password must be beetween 8 and 25 charachters");
				return;
			}
			if(preg_match('/[^A-Za-z0-9]/', $pass1)){
				array_push($this->errors,"Password must contain only letters and numbers");
				return;
			}
		}

//---- STRING SANITIZING FUNCTIONS FOR REGISTRATION -------
		
		private function sanitizePassword($textIn){
			$textIn = htmlentities($textIn,ENT_QUOTES,"UTF-8");
			return $textIn;
		}

		private function sanitizeName($textIn){
			$textIn = htmlentities($textIn,ENT_QUOTES,"UTF-8");
			$textIn = str_replace(" ","",$textIn);
			$textIn = ucfirst(strtolower($textIn));
			return $textIn;
		}
//------- GETTING THINGS -------
		public function getErrors(){
			return $this->errors;
		}
		public function getData($em){
			$this->dataArray = $this->getUserData($em)->fetch_assoc();
		}
	}

 ?>



