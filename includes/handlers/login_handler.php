<?php 
	
	if(isset($_POST['submitLogin'])){
		$email = $_POST['loginEmail'];
		$password = $_POST['loginPassword'];

		$success = $account->login($email,$password);

		if($success){
			$account->getData($email);
			$_SESSION['logedIn'] = true;
			$_SESSION['name'] = $account->dataArray['firstName'];
			$_SESSION['id'] = $account->dataArray['id'];
			$_SESSION['jobTitle'] = $account->dataArray['jobTitle'];
			header("Location: index.php");
		}else{
			$mssgs = $account->getErrors();
			$color = "red";
		}
	}

 ?>