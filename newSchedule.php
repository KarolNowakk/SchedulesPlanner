<?php 

require_once('includes/header.php'); 

if(!isset($_SESSION['logedIn']) OR $_SESSION['jobTitle'] < 2){
	header("Location: index.php");
	exit();
}

?>

	<script src="assets/js/newSchedule.js"></script>
	<div id="scheduleGeneratorContainer">
		<div id="allSettings">			
			<h1>GENERATE SCHEDULE FOR NEW MONTH</h1>
			<div id="dateSelector">
				<select name="selectMonth" id="selectMonth">
					<option value="1">January</option>
					<option value="2">February</option>
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
				<input type="number" id="year" name="year" value=2020>
				<button name="add" onclick="sendDataToScheduleGenerator()">Generate</button>
			</div>
			<div id="weekDaysWorkingHours">
				<div class="oneDayLabel">
					<label for="monday">Monday:</label>
					<input type="time" id="mondayStart" name="monday" value="07:30">
					<input type="time" id="mondayEnd" name="monday" value="20:30">
					<label for="friday">Workers <br>on shift:</label>
					<input type="number" id="fridayStart" name="friday" value=2>
				</div>
				<div class="oneDayLabel">
					<label for="tuesday">Tuesday:</label>
					<input type="time" id="tuesdayStart" name="tuesday" value="07:30">
					<input type="time" id="tuesdayEnd" name="tuesday" value="20:30">
					<label for="friday">Workers <br>on shift:</label>
					<input type="number" id="fridayStart" name="friday" value=2>
				</div>
				<div class="oneDayLabel">
					<label for="wendesday">Wendesday:</label>
					<input type="time" id="wendesdayStart" name="wendesday" value="07:30">
					<input type="time" id="wendesdayEnd" name="wendesday" value="20:30">
					<label for="friday">Workers <br>on shift:</label>
					<input type="number" id="fridayStart" name="friday" value=2>
				</div>
				<div class="oneDayLabel">
					<label for="thursday">Thursday:</label>
					<input type="time" id="thursdayStart" name="thursday" value="07:30">
					<input type="time" id="thursdayEnd" name="thursday" value="20:30">
					<label for="friday">Workers <br>on shift:</label>
					<input type="number" id="fridayStart" name="friday" value=2>
				</div>
				<div class="oneDayLabel">
					<label for="friday">Friday:</label>
					<input type="time" id="fridayStart" name="friday" value="07:30">
					<input type="time" id="fridayEnd" name="friday" value="21:30">
					<label for="friday">Workers <br>on shift:</label>
					<input type="number" id="fridayStart" name="friday" value=2>
				</div>
				<div class="oneDayLabel">
					<label for="saturday">Saturday:</label>
					<input type="time" id="saturdayStart" name="saturday" value="08:30">
					<input type="time" id="saturdayEnd" name="saturday" value="21:30">
					<label for="friday">Workers <br>on shift:</label>
					<input type="number" id="fridayStart" name="friday" value=3>
				</div>
				<div class="oneDayLabel">
					<label for="sunday">Sunday:</label>
					<input type="time" id="sundayStart" name="sunday" value="09:30">
					<input type="time" id="sundayEnd" name="sunday" value="21:30">
					<label for="friday">Workers <br>on shift:</label>
					<input type="number" id="fridayStart" name="friday" value=4>
				</div>
			</div>
			<div id="workersList">
			 	<div class="singleWorkerSettings">
			      	<div id="singleWorkerLabel">
			      		<label for="singleWorker">Worker name:</label>
			  	  		<input type="search" class="singleWorker" name="singleWorker" onfocusout="hideResulsts()" onfocus="showResulsts()" oninput="searchForWorker(this.value)" placeholder="Search">
			  	  		<div class="searchResults"></div>
			      	</div>
			      	<div id="singleWorkerLabel">
			      		<label for="singleWorkerHours">Workers required hours:</label>
					  	<input type="number" id="singleWorkerHours" name="singleWorkerHours" placeholder="160">
			      	</div>
			      	<div id="singleWorkerLabel">
			      		<label for="singleWorkerDaysOff">Workers days Off:</label>
					  	<input type="text" id="singleWorkerDaysOff" name="singleWorkerDaysOff" placeholder="1,14,24,31...">
			      	</div>
	    		</div> 
				<div id="addNextWorkerToSchedule">
    				<button id="add" onclick="schedule.addWorkerSettings()">Add</button>
    			</div>
			</div>
<?php require_once('includes/footer.php'); ?>	    	

