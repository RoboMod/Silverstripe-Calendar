// Copyright (c) 2013 Andreas Ihrig (RoboMod) Licensed under the MIT license

var dates = new Array();

function addEvent(id, year, month, day) {
    var nr = dates.length++;
    dates[nr] = new Object();
    dates[nr]['id'] = id;
    dates[nr]['year'] = year;
    dates[nr]['month'] = month;
    dates[nr]['day'] = day;
}

$(document).ready(function() {
    $('#calendar').fullCalendar({
        firstDay: 1,
        height: 450,
        contentHeight: 450,
        eventSources: [
            {
                url: window.location.protocol+"//"+window.location.host+window.location.pathname.replace(/\/\//, "/").replace(/\/$/, "")+"/json",
                color: '#2e3192',
                textColor: 'white',
                error: function() {
                    alert('Fehler beim Lesen der Daten\n');
                }
             }
        ],  
        loading: function(bool) {
            $('#calendar').toggleLoading({addText: "Lade..."});
        },
        eventMouseover: function(event) {
            jQuery('#event'+event.id).addClass("calendarentryhover");
        },
        eventMouseout: function(event) {
            jQuery('#event'+event.id).removeClass("calendarentryhover");
        },
        monthNames: [
            'Januar', 'Februar', 'März', 'April', 'Mai', 'Juni', 'Juli',
            'August', 'September', 'Oktober', 'November', 'Dezember'
        ],
        monthNamesShort: [
            'Jan', 'Feb', 'Mär', 'Apr', 'Mai', 'Jun', 'Jul', 'Aug', 
            'Sep', 'Okt', 'Nov', 'Dez'
        ],
        dayNames: [
            'Sonntag', 'Montag', 'Dienstag', 'Mittwoch', 'Donnerstag',
            'Freitag', 'Samstag'
        ],
        dayNamesShort: [
            'So', 'Mo', 'Di', 'Mi', 'Do', 'Fr', 'Sa'
        ],
        buttonText: {
            today: 'Heute',
            month: 'Monat',
            week: 'Woche',
            day: 'Tag'
        },
        weekNumbers: true,
        weekNumberTitle: "KW",
        columnFormat: {
            month: 'ddd',
            week: 'ddd, d.',
            day: "dddd, 'd'er d.MMMM"
        },
        titleFormat: {
            month: "MMM. yyyy",
            week: "MMM.[ yyyy<br/>]{ [- MMM.] yyyy}",
            day: ''
        },
        header: {
            left: 'prevYear,prev,next,nextYear',
            center: 'title',
            right: 'basicDay,basicWeek,month'
        },
        eventClick: function(event) { //open links in new window
            if (event.url) {
                window.open(event.url);
                return false;
            }
        }
    });
    
    $("div.calendarentry").click(function(event){
        if(event.target.nodeName !== "a" && event.target.nodeName !== "A") {
            var eventString = /event(\d*)/;
            eventString.exec($(this).attr('id'));
            for (var i = 0; i < dates.length; i++) {
                if (dates[i]['id'] - RegExp.$1 === 0) {
                    $('#calendar').fullCalendar('gotoDate',
                        dates[i]['year'],
                        dates[i]['month']-1,
                        dates[i]['day']
                        );
                    return;
                }
            }
        }
    });
    
});