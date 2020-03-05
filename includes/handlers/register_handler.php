<?php 
	
if(isset($_POST['submitRegister'])){

//------ IF IS POSTED DEFINE VARIABLES----
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $email = $_POST['email'];
    $password1 = $_POST['password1'];
    $password2 = $_POST['password2'];

//---- REGISTER ----
    $success = $account->register($firstName,$lastName,$email,$password1,$password2);

//----IF EVERYTHING IS OK -----
    if($success){
      $mssgs = ["Registration Succesfull please log in."];
			$color = "green";
      //header("Location: register.php");
    }else{
      $mssgs = $account->getErrors();
			$color = "red";
    } 
}


 ?>