function showResulsts(){
	var results = document.querySelector(".searchResults");
	results.setAttribute("style", "display: block");
}

function hideResulsts(){
	var results = document.querySelector(".searchResults");
	results.setAttribute("style", "display: none");
}

function addFoundedWorkerToList(firstName, lastName,id){
	var worker = document.createElement("div");
	worker.classList = "result";
	worker.innerHTML = "<div data-wid='"+id+"' onmousedown='setNameAndId(this.dataset.wid,this.textContent)'>"+
							"<span id='firstName'>"+firstName+" </span>"+
			  	  			"<span id='lastName'>"+lastName+"</span>"+
			  	  		"</div>";

	return worker;
}

function setNameAndId(id,text){
	var nameField = document.querySelector(".singleWorker");
	nameField.value = text;
	nameField.dataset.wid = id;
}

function createNoMatches(){
    var worker = document.createElement("div");
    worker.classList = "result";
    worker.innerHTML = "<span style='font-style: italic;'> No matches</span>";
    return worker;
}

function searchForWorker(text){
	showResulsts();
	if(text.length > 0){
	$.post("includes/handlers/ajax/workerSearch_handler.php", { text: text }, function(data){
		var foundedWorkers = JSON.parse(data);	
		
		var list = document.querySelector(".searchResults");
		var tempDf = document.createDocumentFragment();
		if(foundedWorkers.length > 0){
			for(i=0; i<foundedWorkers.length; i++){
				var singleWorker = addFoundedWorkerToList(foundedWorkers[i]["firstName"],foundedWorkers[i]["lastName"],foundedWorkers[i]["id"]);
				tempDf.appendChild(singleWorker);
			}
		}else{
			var noMatches = createNoMatches();
				tempDf.appendChild(noMatches);
		}

		list.innerHTML = "";
		list.appendChild(tempDf);
	});
	}
}