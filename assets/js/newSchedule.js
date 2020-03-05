$(document).ready(function(){
    var d = new Date();
    var year = d.getFullYear();
    var month = d.getMonth()+2;
    document.querySelector("#selectMonth").value = month;
	document.querySelector("#year").value = year;  
});

class ScheduleInput {

	constructor(){
		this.nameList = [];
		this.ids = [];
		this.reqHours = [];
		this.daysOff = [];
		this.weekDaysWorkingHours = [];
		this.reqWorkersOnShift = [];
		this.date = "";
	}

	updateName(name){
		if(!this.nameList.includes(name)){
			this.nameList.push(name);
		}
	}

	updateIds(id){
		this.ids.push(parseInt(id));
		console.log(this.ids);
	}

	checkId(id){
		id = parseInt(id);
		if(!this.ids.includes(id) && !id=="" && !id==0){			
			return true;
		}else{
			return false;
		}	
	}

	updateReqhHurs(hours){
		if(hours == "" || hours == 0){
			this.reqHours.push(150);
		}else{
			this.reqHours.push(parseInt(hours));
		}
	}

	updateDaysOff(daysOff){
		var intTable = [];
		for(i=0; i < daysOff.split(",").length; i++){
			intTable.push(parseInt(daysOff.split(",")[i]));
		}

		this.daysOff.push(intTable);
	}

	addWorkerSettings(){
		var name = document.querySelector(".singleWorker");
		var workerHours = document.querySelector("#singleWorkerHours");
		var daysOff = document.querySelector("#singleWorkerDaysOff");

		if(this.checkId(name.dataset.wid)){
			this.updateIds(name.dataset.wid);
			this.updateName(name.value);
			this.updateReqhHurs(workerHours.value);
			this.updateDaysOff(daysOff.value);
			showAlreadyAdded();
		}else{
			alert("You cannot add the same worker twice!");
		}
		name.value = "";
		name.id = "";
		workerHours.value = "";
		daysOff.value = "";	
	}

	setDate(){
		var month = document.querySelector("#selectMonth").value;
		var year = document.querySelector("#year").value;

		this.date = year+"-"+month;
	}

	updateDaysWorkingHours(){
		var weekDaysHoursDiv = document.querySelector("#weekDaysWorkingHours");
		
		for(var i=0; i < weekDaysHoursDiv.children.length; i++){
			this.weekDaysWorkingHours.push([weekDaysHoursDiv.children[i].children[1].value,weekDaysHoursDiv.children[i].children[2].value]);
		}
		//console.log(this.weekDaysWorkingHours);
	}

	updateReqWorkersOnShift(){
		var reqWorkersOnShiftDiv = document.querySelector("#weekDaysWorkingHours");
		
		for(var i=0; i < reqWorkersOnShiftDiv.children.length; i++){
			this.reqWorkersOnShift.push(reqWorkersOnShiftDiv.children[i].children[4].value);
		}
		//console.log(this.reqWorkersOnShift);
	}

}

var schedule = new ScheduleInput();

function showAlreadyAdded(){
	var docFragment = document.createDocumentFragment();
	var workersList = document.getElementById("workersList");
	var singleWorkerSettings = document.createElement("div");
	singleWorkerSettings.classList = "singleWorkerSettingsAdded";
	for(i=0; i < schedule.nameList.length; i++){
		singleWorkerSettings.innerHTML =    "<div id='singleWorkerLabelAdded'>"+
						      				"<label for='singleWorker'>Worker name:</label>"+
						  	  				"<span type='text' id='singleWorker' name='singleWorker'>"+schedule.nameList[i]+"</span>"+
					  	  			    "</div>"+

								      	"<div id='singleWorkerLabelAdded'>"+
								      		"<label for='singleWorkerHours'>Workers required hours:</label>"+
										  	"<span type='number' id='singleWorkerHours' name='singleWorkerHours'>"+schedule.reqHours[i]+"</span>"+
								      	"</div>"+

								      	"<div id='singleWorkerLabelAdded'>"+
								      		"<label for='singleWorkerDaysOff'>Workers days Off:</label>"+
										  	"<span type='text' id='singleWorkerDaysOff' name='singleWorkerDaysOff'>"+schedule.daysOff[i]+"</span>"+
								      	"</div>";		    			
		docFragment.appendChild(singleWorkerSettings);

	}
	workersList.insertBefore(docFragment, workersList.querySelector("workersList:nth-child(1)"));
}

function xyz(){
	schedule.updateReqWorkersOnShift();
}

function sendDataToScheduleGenerator(){
	schedule.setDate();
	schedule.updateDaysWorkingHours();
	schedule.updateReqWorkersOnShift();
	if(isMoreWorkersOnShiftThanInSchedule()){
		alert("Number of workers on Shift cannot be bigger than number of workers included in the schedule!");
	}else{
		confirm("Are You sure You wont to generate new Schedule?");

		//console.log(schedule.date);
		// console.log(schedule.ids);
		// console.log(schedule.reqHours);
		// console.log(schedule.daysOff);
		// console.log(schedule.weekDaysWorkingHours);
		//console.log(schedule.reqWorkersOnShift);
	
		$.post("includes/handlers/ajax/generateSchedule_handler.php",
		{
			date: schedule.date, 
			workersIds: schedule.ids,
			workersHours: schedule.reqHours,
			daysOff: schedule.daysOff,
			weekDaysWorkingHours: schedule.weekDaysWorkingHours,
			reqWorkersOnShift: schedule.reqWorkersOnShift
		}, 
		function(){
			redirect();
		});
	}	
}

function isMoreWorkersOnShiftThanInSchedule(){
	
	if(schedule.ids.length < 1){ return true; }

	for (i= 0; i < schedule.reqWorkersOnShift.length; i++) {
		
		if(parseInt(schedule.reqWorkersOnShift[i]) > schedule.ids.length){
			return true;
		}
	}
	return false;
}

function redirect(){
	window.location.replace("http://localhost/SchedulePlanner/scheduleChanges.php");
}