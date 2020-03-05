$(document).ready(function(){
    var d = new Date();
    var year = d.getFullYear();
    var month = d.getMonth()+1;
    document.querySelector("#selectMonth").value = month;
    document.querySelector("#year").value = year;  
    var d = getDateFromSelector(),
        month = d[0],
        year = d[1];

    createMonth(month,year);
    createOneDayInfo(month,year,1);
});

function dayClicked(e){
    var date = getDateFromSelector();
    date[0]--;
    createOneDayInfo(date[0],date[1],e.textContent);
}

function showClicked(){
    var date = getDateFromSelector();
    //console.log(date);
    createMonth(date[0],date[1]);
    createOneDayInfo(date[0],date[1],1);
}

function getDateFromSelector(){
    var month = parseInt(document.querySelector("#selectMonth").value);
    var year = parseInt(document.querySelector("#year").value);
    return [month,year];
}

function getDaysInMonth() {
    var d = getDateFromSelector();
        month = d[0] - 1,
        year = d[1];
    var date = new Date(year, month, 1);
    var days = [];
    var dayDate = 0;
    while (date.getMonth() === month) {
        dayDate ++;
        var d = new Date(date);
        days.push(dayDate);
        date.setDate(date.getDate() + 1);
    }
    return days;
}
  
function getFirstDayOfMonth(){
    var d = getDateFromSelector();
        month = d[0] - 1,
        year = d[1];
    
    var date = new Date(year, month, 1);
    //console.log(date.getDay(),year,month);
    return date.getDay();
}

function createMonth(month,year){
    var days = getDaysInMonth(month,year);
    var daysInCalendar = document.querySelector("#daysInCalendar");
    daysInCalendar.innerHTML = "<span class='weekDayShortcut'>Mon</span>"+
                                "<span class='weekDayShortcut'>Tue</span>"+
                                "<span class='weekDayShortcut'>Wnd</span>"+
                                "<span class='weekDayShortcut'>Thr</span>"+
                                "<span class='weekDayShortcut'>Fri</span>"+
                                "<span class='weekDayShortcut'>Sat</span>"+
                                "<span class='weekDayShortcut'>Snd</span>";
    var docFragment = document.createDocumentFragment();
    docFragment.appendChild(createEmptyDays());
    var workingDays = getWorkingDays(month,year);
    //console.log(workingDays);

    for(i = 0; i < days.length; i++){
        var span = document.createElement("span");

        if(workingDays.includes(days[i].toString())){
            span.classList = "working";
        }
        span.textContent = days[i];
        span.addEventListener("click", function(){
            dayClicked(this);
        });
        docFragment.appendChild(span);
    }
    
    daysInCalendar.appendChild(docFragment);
}

function createEmptyDays(){
    var docFragment = document.createDocumentFragment();
    var emptyDays = getFirstDayOfMonth()-1;
    //console.log(emptyDays);
    if(emptyDays < 1){
        emptyDays = 6;
    }
    for(i = 0; i < emptyDays; i++){
        var span = document.createElement("span");
        span.textContent = " ";
        span.classList="emptyDay";
        docFragment.appendChild(span);
    }
    return docFragment;
}

//-------------------------------------- RIGHT SIDE ------------------------------------------------------------
function createOneDayInfo(month,year,dayDate=1){
    var oneDaySchedule = document.querySelector("#selectedDaySchedule");
    oneDaySchedule.innerHTML = "";
    var month = getDateFromSelector()[0];
    var docFragment = document.createDocumentFragment(),
        span = document.createElement("span"),
        shifts = createWorkersShifts(month,year,dayDate);
    span.setAttribute("id","month");
    span.textContent = dayDate + " " + getMonthName(month);
    

    docFragment.appendChild(span);
    docFragment.appendChild(shifts);
    oneDaySchedule.appendChild(docFragment);
}

function createWorkersShifts(month,year,dayDate){
    var oneDayInfo = getOneDayInfo(month,year,dayDate);
    var docFragment = document.createDocumentFragment();

    oneDayInfo.forEach(function(worker,index,oneDay){
        
        var div = document.createElement("div");
        div.setAttribute("id","workerShift");
        div.innerHTML = "<span>"+ worker["firstName"]+" "+ worker["lastName"]+"</span>"+
                        "<span>"+ worker["start"].substr(0,5)+"-"+ worker["end"].substr(0,5)+"</span>";

        docFragment.appendChild(div);
   });
   return docFragment;
}

function getMonthName(num){
    var names = ["January","February","March","April","May","Juni","Juli","August","September","October","November","December"];
    return names[num-1];
}
//------------------------------------ GETTING JSON-----------------------------------------------------------
function getWorkingDays(month,year){
    
    var date = year+"-"+month;
    var xhr = new XMLHttpRequest(),
        data = new FormData();
    //console.log(date);
    data.append("date", date);
    xhr.open("POST","includes/handlers/ajax/getWorkingDays_handler.php", false);
    xhr.send(data);   
    
    return JSON.parse(xhr.response);
}

function getOneDayInfo(month,year,dayDate){
    
    var date = year+"-"+month;
    
    var xhr = new XMLHttpRequest(),
        data = new FormData();
    data.append("date", date);
    data.append("dayDate", dayDate);
    xhr.open("POST","includes/handlers/ajax/getOneDayInfo_handler.php", false);
    xhr.send(data);   
    
    return JSON.parse(xhr.response);
}