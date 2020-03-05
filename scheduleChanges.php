<?php 

require_once('includes/header.php'); 

if(!isset($_SESSION['logedIn']) OR $_SESSION['jobTitle'] < 2){
	header("Location: index.php");
	exit();
}

?>
<script src="assets/js/scheduleChanges.js"></script>
<div id="infoContainer" class="infoContainer">
	
	<div class="leftSide">
		
		<div class="selectMonthContainer">
			<select id="selectMonth">
			    <option value=1>January</option>
			    <option value=2>February</option>
			    <option value="3">March</option>
			    <option value="4">April</option>
			    <option value="5">May</option>
			    <option value="6">June</option>
			    <option value="7">Juli</option>
			    <option value="8">August</option>
			    <option value="9">September</option>
				<option value="10">October</option>
				<option value="11">November</option>
				<option value="12">December</option>
			</select>
			<input type="number" id="year" value="2020">
			<button id="showMonth" onclick="changeMonthToShow()">Show</button>
		</div>
		<div id="calendar"></div>	
	</div>

	<div id="workerInfo"></div>

<?php require_once('includes/footer.php'); ?>