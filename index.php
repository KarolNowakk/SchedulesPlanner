
<?php require_once("includes/header.php"); ?>
<script src="assets/js/yourSchedule.js"></script>

<div id="singleWorkerScheduleContainer">
    <div id="oneMonthCalendar">
        <div>
            <select id="selectMonth">
                <option value="1">January</option>
                <option value="2">February</option>
                <option value="3">March</option>
                <option value="4">April</option>
                <option value="5">May</option>
                <option value="6">Juni</option>
                <option value="7">Juli</option>
                <option value="8">August</option>
                <option value="9">September</option>
                <option value="10">October</option>
                <option value="11">November</option>
                <option value="12">December</option>
            </select>
            <input type="number" id="year" value=2020>
            <button id="showMonth" onclick="showClicked()">Show</button>
        </div>
        <div id="daysInCalendar">
            <span class="weekDayShortcut">Pon</span>
            <span class="weekDayShortcut">Wt</span>
            <span class="weekDayShortcut">Sr</span>
            <span class="weekDayShortcut">Czw</span>
            <span class="weekDayShortcut">Pt</span>
            <span class="weekDayShortcut">So</span>
            <span class="weekDayShortcut">Ndz</span>
        </div>
    </div>
    <div id="selectedDaySchedule">
    </div>
</div>
<?php require_once("includes/footer.php");?>