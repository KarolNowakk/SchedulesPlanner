<?php 
	require_once("includes/config.php");
	require_once("includes/handlers/login_handler.php");
	if(!isset($_SESSION['logedIn'])){
		header("Location: register.php");
		exit();	
	}

 ?>	

 <!DOCTYPE html>
<html lang="en">
  <head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Schedule</title>
 
    <link href="assets/css/style.css" rel="stylesheet">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="assets/js/workerSearchBar.js"></script>
    

  </head>
  <body>
<!-- MAIN CONTAINER --->
	<div id="mainContainer">

		<!---NAVIGATION BAR -->		
		<nav id="navBar" class="navBar">

			<a href="/SchedulePlanner/index.php" class="navItem">Your Schedule</a>
			<a href="/SchedulePlanner/scheduleChanges.php" class="navItem">Schedule Changes</a>
			<a href="/SchedulePlanner/newSchedule.php" class="navItem">New Schedule</a>
			<a href="includes/handlers/logout_handler.php" class="navItem">Log Out</a>
		</nav>

		
