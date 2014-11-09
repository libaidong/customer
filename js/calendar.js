function loadCalendar(element, filters, basehours) {

	$.calElement = $(element);
	$.calFilters = filters;
	$.calElement.fullCalendar('destroy');
	$.calElement.fullCalendar({
		height: 400,
		firstDay: 1,
		defaultView: 'agendaWeek',
		allDaySlot: false,
		header: {
			left: 'prev,next today',
			center: 'title',
			right: 'month,agendaWeek,agendaDay'
		},
		allDayDefault: false,
		events: {
			url: '/api/calendar/list',
			type: 'POST',
			data: filters,
			error: function(e) {
				console.error('Error fetching events', e);
				alert('there was an error while fetching events!');
			},
		},
		dayClick: function(date, allDay, jsevent) {
			jsevent.preventDefault();
			angular.element($('#eventCtrl')).scope().setSchedule(filters && filters.deviceid ? {deviceid: filters.deviceid} : {});
			$('#modal-event').modal('show');
		},
		eventClick: function(ev, jsevent) {
			jsevent.preventDefault();
			angular.element($('#eventCtrl')).scope().setScheduleId(ev.id.substr(3)); // Extract ID from calendar click then load it
			$('#modal-event').modal('show');
		},
		eventAfterAllRender: function(view){
			if(view.name == 'agendaWeek') {
				if (! $('#calendar .fc-day-div').length) { // Not already applied the base hours
					for(var i = 0; i < 48; i++) {  //for each of the agenda slots
						for(var j = 0; j < 7; j++) {
							$('.fc-slot'+i+' .fc-widget-content').children().first().css('height', '100%');
							$('.fc-slot'+i+' .fc-widget-content').children().first().append('<div class="fc-day-div fc-vert-'+i+' fc-day-' + j + '">');
						}
					}
					// Convert the base hours epoch into hours before passing as arguments
					for(var i = 0; i < 7; i++ ) {
						setBaseHours(basehours[i], i);
					}
				}
			}
		}
	});

	
	setTimeline();
}

/**
 * Draw the base hours for the selected location given the start and end hours
 */
function setBaseHours(basehours, day) {
	
	var startSlot = (basehours.start / 60 / 60) * 2;
	var endSlot = (basehours.end / 60 / 60) * 2;

	for (var i = startSlot; i < endSlot; i++) {
		$(".fc-vert-" + i + ".fc-day-" + day).css("background", "#D0D0D0");
	}
}

/**
* Draw a red bar indicating the current time
*/
function setTimeline() {
	var parentDiv = $(".fc-agenda-slots:visible").parent();
	var timeline = parentDiv.children(".timeline");
	if (timeline.length == 0) { //if timeline isn't there, add it
		timeline = $("<hr>").addClass("timeline");
		parentDiv.prepend(timeline);
	}

	var curTime = new Date();

	var curCalView = $.calElement.fullCalendar("getView");
	if (curCalView.visStart < curTime && curCalView.visEnd > curTime) {
		timeline.show();
	} else {
		timeline.hide();
	}

	var curSeconds = (curTime.getHours() * 60 * 60) + (curTime.getMinutes() * 60) + curTime.getSeconds();
	var percentOfDay = curSeconds / 86400; //24 * 60 * 60 = 86400, # of seconds in a day
	var topLoc = Math.floor(parentDiv.height() * percentOfDay);

	timeline.css("top", topLoc + "px");

	if (curCalView.name == "agendaWeek") { //week view, don't want the timeline to go the whole way across
		var dayCol = $(".fc-today:visible");
		var left = dayCol.position().left + 1;
		var width = dayCol.width();
		timeline.css({
			left: left + "px",
			width: width + "px"
		});
	}
}
