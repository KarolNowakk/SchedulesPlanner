$(document).ready(function () {
  var d = new Date();
  var year = d.getFullYear();
  var month = d.getMonth()+1;
  document.getElementById("selectMonth").value = month;
  reloadInfoBar(year+"-"+month);
  createMonth(year+"-"+month);
});

function getMonthFromSelector() {
  var year = document.querySelector("#year").getAttribute("value");
  var month = document.getElementById("selectMonth").value;
  return year + "-" + month;
}

function changeMonthToShow() {
  var date = getMonthFromSelector();
  //console.log(date);
  reloadInfoBar(date);
  createMonth(date);
}
//------------PART OF CODE HANDALING USER HOURS AND ESTIMATED PAY ------------

function reloadInfoBar(date) {
  $.post(
    "includes/handlers/ajax/getAllWorkersOfMonth_handler.php",
    { date: date },
    function (data) {
      var workerData = JSON.parse(data);

      var workerInfo = document.getElementById("workerInfo");
      var fragment = document.createDocumentFragment();
      for (i = 0; i < workerData.length; i++) {
        var singleWorkerInfo = document.createElement("div");
        singleWorkerInfo.classList.add("singleWorkerInfo");

        singleWorkerInfo.innerHTML =  "<span class='name'>" + workerData[i]["firstName"] + " " + workerData[i]["lastName"] + "</span>" +
                                      "<div class='infoLine'><span class='worker'>Hours in month: </span>" +
                                      "<span class='worker'>" + workerData[i]["workersHoursPerMonth"] + " h</span></div>" +
                                      "<div class='infoLine'><span class='worker'>Estimated pay: </span>" +
                                      "<span class='worker'>" + workerData[i]["workersPay"] +" zł</span></div>" + "</div>";

        fragment.appendChild(singleWorkerInfo);
      }
      workerInfo.innerHTML = "";
      workerInfo.appendChild(fragment);
    }
  );
}

// ----------- LEFT SIDE --------------
function addClicked(e) {
  var elem = getElemData(e.parentElement);
  updateDatabase(elem[0], elem[1], elem[2], elem[3]);
  updateButtons(e);
  reloadInfoBar(getMonthFromSelector());
}

function delClicked(e) {
  var elem = getElemData(e);
  deleteDayworker(elem[0], elem[1]);
  delField(e);
  reloadInfoBar(getMonthFromSelector());
}

function sendClicked(e) {
  var elem = getElemData(e);
  updateDatabase(elem[0], elem[1], elem[2], elem[3]);
  reloadInfoBar(getMonthFromSelector());
}

function getElemData(e) {
  var start = e.children[0].value;
  var end = e.children[1].value;
  var wId = e.dataset.wid;
  var dayId = e.dataset.dayid;
  return [wId, dayId, start, end];
}

function addField(e, start = "07:30", end = "21:00") {
  e.classList = "dayWorker";
  e.innerHTML = "";
  e.innerHTML =
    "<input type='time' value='" +
    start +
    "'>" +
    "<input type='time' value='" +
    end +
    "'>" +
    "<div class='buttons'>" +
    "<button class='update' onclick='addClicked(this.parentElement)'>Add</button>" +
    "<button class='delete' onclick='delField(this.parentElement.parentElement)'>X</button>" +
    "</div>";
}

function updateButtons(e) {
  e.innerHTML = "";
  e.innerHTML = "<button class='update' onclick='sendClicked(this.parentElement.parentElement)'>Send</button>" +
                "<button class='delete' onclick='delClicked(this.parentElement.parentElement)'>Del</button>";
}

function delField(e) {
  e.innerHTML = "";
  e.innerHTML = "<button onclick='addField(this.parentElement)' class='add'>Add</button>";
}

function updateDatabase(wId, dayId, start, end) {
  var date = getMonthFromSelector();
  //console.log(dayId);
  $.post("includes/handlers/ajax/updateDayWorker_handler.php",{date: date,update: true, workerId: wId,dayId: dayId,start: start,end: end}, function (data) {
      var msg = JSON.parse(data);
      alert(msg["msg"]);
    }
  );
}

function deleteDayworker(wId, dayId) {
  var date = getMonthFromSelector();
  $.post("includes/handlers/ajax/updateDayWorker_handler.php",{ date: date, delete: true, workerId: wId, dayId: dayId }, function (data) {
      var msg = JSON.parse(data);
      alert(msg["msg"]);
    }
  );
}

//------------------- FUNCTIONS RELATED TO SHOWING CALENDAR ----------------
function addNoDay() {
  var field = document.createElement("div");

  field.innerHTML = "<span>  </span>";
  return field;
}

function addEmptyField(dayId, wId) {
  var field = document.createElement("div");
  field.setAttribute("data-dayid", dayId);
  field.setAttribute("data-wid", wId);

  field.innerHTML =
    "<button onclick='addField(this.parentElement)' class='add'>Add</button>";
  return field;
}

function addInfoField(dayId, wId, start = "07:30", end = "21:00") {
  var field = document.createElement("div");
  field.setAttribute("data-dayid", dayId);
  field.setAttribute("data-wid", wId);
  field.classList = "dayWorker";

  field.innerHTML = "<input type='time' value='" + start + "'>" +
                    "<input type='time' value='" + end + "'>" +
                    "<div class='buttons'>" +
                    "<button class='update' onclick='sendClicked(this.parentElement.parentElement)'>Send</button>" +
                    "<button class='delete' onclick='delClicked(this.parentElement.parentElement)'>Del</button>" +
                    "</div>";
  return field;
}

function createWeekdaysLabel() {
  var row = document.createElement("div");
  row.classList = "weekDays";

  row.innerHTML = "<div><span>---</span></div>" +
                  "<div><span>Mon</span></div>" +
                  "<div><span>Tue</span></div>" +
                  "<div><span>Wdn</span></div>" +
                  "<div><span>Thr</span></div>" +
                  "<div><span>Fri</span></div>" +
                  "<div><span>Sat</span></div>" +
                  "<div><span>Sun</span></div>";
  return row;
}

function createDayDatesLabel(dayNums) {
  var row = document.createElement("div");
  row.classList = "weekDates";

  row.innerHTML = "<div><span>Worker</span></div>" +
                  "<div><span>" +
                  dayNums[0] +
                  "</span></div>" +
                  "<div><span>" +
                  dayNums[1] +
                  "</span></div>" +
                  "<div><span>" +
                  dayNums[2] +
                  "</span></div>" +
                  "<div><span>" +
                  dayNums[3] +
                  "</span></div>" +
                  "<div><span>" +
                  dayNums[4] +
                  "</span></div>" +
                  "<div><span>" +
                  dayNums[5] +
                  "</span></div>" +
                  "<div><span>" +
                  dayNums[6] +
                  "</span></div>";
  return row;
}

function createMonth(date) {
  var calendar = document.querySelector("#calendar");
  calendar.innerHTML = "";
  var month = document.createDocumentFragment();
  $.post("includes/handlers/ajax/getScheduleInfo_handler.php", { date: date }, function (data) {
      var weekData = JSON.parse(data);
      //console.log(weekData);
      var docFragment = document.createDocumentFragment();

      for (j = 0; j < weekData["weeks"].length; j++) {
        var week = document.createElement("div");
        week.classList = "week";

        var weekDaysLabel = createWeekdaysLabel();
        week.appendChild(weekDaysLabel);

        var listOfDayDates = [];
        var listOfDayIds = [];

        //---------- LIST OF DAY DATES ------------------------------------------------------------------------------
        for (i = 0; i < weekData["weeks"][j].length; i++) {
          if (Object.keys(weekData["weeks"][j][i]).length < 1) {
            listOfDayDates.push("---");
          } else {
            listOfDayDates.push(weekData["weeks"][j][i]["dayDate"]);
          }
        }
        //---------- LIST OF DAY IDS ------------------------------------------------------------------------------------
        for (i = 0; i < weekData["weeks"][j].length; i++) {
          if (Object.keys(weekData["weeks"][j][i]).length < 1) {
            listOfDayIds.push("---");
          } else {
            listOfDayIds.push(weekData["weeks"][j][i]["id"]);
          }
        }
       //console.log(listOfDayIds);
        var dayDateLabel = createDayDatesLabel(listOfDayDates);
        week.appendChild(dayDateLabel);
        //---------- CREATING WORKER ROWS  --------------------------------------
        for (k = 0; k < weekData["workers"].length; k++) {
          var oneWeekWorkerData = weekData["workers"][k];
          var row = createWorkerLabel(oneWeekWorkerData,listOfDayIds);
          week.appendChild(row);
        }
        // var addNewWorkerBtn = createAddNewWorkerToSchedulePanel();
        // week.appendChild(addNewWorkerBtn);
        docFragment.appendChild(week);
      }
      month.appendChild(docFragment);
      calendar.appendChild(month);
    }
  );
}

function createWorkerLabel(wData,listOfDayIds){
  var row = document.createElement("div");
  row.classList = "workerRow";

  var workerName = wData["firstName"] + " " + wData["lastName"];
  var nameDiv = document.createElement("div");
  nameDiv.classList = "nameDiv";
  nameDiv.innerHTML = "<span>" + workerName + "</span>";
  row.appendChild(nameDiv);
  for (i = 0; i < listOfDayIds.length; i++) {

    if (wData["listOfIds"].includes(listOfDayIds[i])) {
      var index = wData["listOfIds"].indexOf(listOfDayIds[i]);
     
      var div = addInfoField(listOfDayIds[i],wData["id"],wData["wDaysInfo"][index]["start"],wData["wDaysInfo"][index]["end"]);
      row.appendChild(div);

    } else if (listOfDayIds[i] === "---") {
      var div = addNoDay();
      row.appendChild(div);

    } else {
      var div = addEmptyField(listOfDayIds[i], wData["id"]);
      row.appendChild(div);
    }
  }
  return row;
}

// function createAddNewWorkerToSchedulePanel(){
//   var panel = document.createElement("div");
//   panel.classList = "addNewWorker";
  
//   panel.innerHTML = "<div>"+
//                         "<input type='text' class='singleWorker' onfocusout='hideResulsts()' onfocus='showResulsts()' oninput='searchForWorker(this.value)'>"+
//                         "<div class='searchResults'></div>"+
//                     "</div>"+
//                     "<span> + Add new worker to schedule</span>";
//   return panel;
// }

// function createEmptyNewWorkerRow(){
  
// }