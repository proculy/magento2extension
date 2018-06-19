
define([
    "jquery",
    "jsgantt"
], function($) {
    "use strict";
    var resultOut = {};

    function printHTML(cusTaskArr) {
        var jsChart = new JSGantt.GanttChart(document.getElementById('TaskDiv'), 'day');

        if( jsChart.getDivId() != null ) {
            jsChart.setDayColWidth(30);
            jsChart.setWeekColWidth(56);
            jsChart.setMonthColWidth(56);
            jsChart.setCaptionType('Complete');
            jsChart.setQuarterColWidth(36);
            jsChart.setDateTaskDisplayFormat('day dd month yyyy');
            jsChart.setDayMajorDateDisplayFormat('mm/yyyy - Week ww');
            jsChart.setWeekMinorDateDisplayFormat('dd mon');
            jsChart.setShowTaskInfoLink(1);
            jsChart.setShowEndWeekDate(0);
            jsChart.setUseSingleCell(25000);
            jsChart.setFormatArr('Day', 'Week', 'Month', 'Quarter');
            jsChart.setShowStartDate(0);
            jsChart.setShowEndDate(0);

            var subDate = new Date();
            subDate.setDate(subDate.getDate() - 11);
            var scrollDate = subDate.getFullYear() + "-" 
                + ('0' + (subDate.getMonth() + 1)).slice(-2) + "-" 
                + ('0' + subDate.getDate()).slice(-2);
            
            jsChart.setScrollTo(scrollDate);

            var taskColorArr = [];
            var colorArr = ["gtaskblue","gtaskred","gtaskgreen","gtaskyellow","gtaskpurple","gtaskpink"];
            var colorIdx = 0;
            var isDuplicate = false;

            for(var i in cusTaskArr) {
                isDuplicate = false;
                var username = cusTaskArr[i].assignname;

                for(var j in taskColorArr) {
                    if(taskColorArr[j].userid == username){
                        isDuplicate = true;
                        break;
                    }
                }

                if(!isDuplicate){
                    taskColorArr.push({ 
                        userid : username,
                        color  : colorArr[colorIdx]
                    });

                    colorIdx = colorIdx + 1;

                    if(colorIdx == (colorArr.length - 1)){
                        colorIdx = 0;
                    }
                }
            }

            for(var i in cusTaskArr) {    
                var item = cusTaskArr[i];
                var taskId = item.task_id;
                var taskName = item.task_name;
                var taskAssign = item.assignname;
                var taskDetailUrl = item.taskDetailUrl;
                var dateFrom = new Date(item.start_date);
                var taskFrom = dateFrom.getFullYear() + "-" 
                    + ('0' + (dateFrom.getMonth() + 1)).slice(-2) + "-" 
                    + ('0' + dateFrom.getDate()).slice(-2);
                var dateTo = new Date(item.end_date);
                var taskTo = dateTo.getFullYear() + "-" 
                    + ('0' + (dateTo.getMonth() + 1)).slice(-2) + "-" 
                    + ('0' + dateTo.getDate()).slice(-2);
                var taskPercent = item.progress;
                var taskDescription = item.description;
                var taskColor = "gtaskblue";

                if (taskPercent == "100") {
                    taskColor = "gtaskgrey";
                } else {
                    for(var j in taskColorArr) {
                        if(taskAssign == taskColorArr[j].userid){
                            taskColor = taskColorArr[j].color;
                            break;
                        }
                    };
                }

                jsChart.AddTaskItem(
                    new JSGantt.TaskItem(
                        parseInt(taskId),// ID
                        taskName, // task_name
                        taskFrom, // start_date
                        taskTo, // end_date
                        taskColor, // '' --CSS
                        taskDetailUrl, // '' --Link
                        0, // '' --Mile
                        taskAssign, // '' --Resource
                        parseInt(taskPercent), // Progress
                        0, // '' --Group
                        0, // '' --Parent Task
                        0, // '' --Open
                        '', // '' --Depend
                        '', // '' --Caption
                        taskDescription, // '' --Note
                        jsChart // '' --Gantt
                    )
                );
            }

            jsChart.Draw();
        }
        else
        {
            alert("Error when generate new chart!");
        }
    }

    return resultOut = {
        printHTML: printHTML
    };
});