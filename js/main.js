/* initialize the calendar
-----------------------------------------------------------------*/
var date = new Date();
var d = date.getDate();
var m = date.getMonth();
var y = date.getFullYear();

$('#calendar').fullCalendar({
	header: {
		left: 'prev,next',
		center: 'title',
		right: 'month,agendaWeek,agendaDay'
	},
	editable: true,
	droppable: true, // this allows things to be dropped onto the calendar !!!
	drop: function(date, allDay) { // this function is called when something is dropped

		// retrieve the dropped element's stored Event Object
		var originalEventObject = $(this).data('eventObject');

		// we need to copy it, so that multiple events don't have a reference to the same object
		var copiedEventObject = $.extend({}, originalEventObject);

		// assign it the date that was reported
		copiedEventObject.start = date;
		copiedEventObject.allDay = allDay;

		// render the event on the calendar
		// the last `true` argument determines if the event "sticks" (http://arshaw.com/fullcalendar/docs/event_rendering/renderEvent/)
		$('#calendar').fullCalendar('renderEvent', copiedEventObject, true);

		// is the "remove after drop" checkbox checked?
		if ($('#drop-remove').is(':checked')) {
			// if so, remove the element from the "Draggable Events" list
			$(this).remove();
		}

	},
	events: [
		{
			title: 'All Day Event',
			start: new Date(y, m, 1)
		},
		{
			title: 'Long Event',
			start: new Date(y, m, d-5),
			end: new Date(y, m, d-2),
		},
		{
			id: 999,
			title: 'Repeating Event',
			start: new Date(y, m, d-3, 16, 0),
			allDay: false,
		},
		{
			id: 999,
			title: 'Repeating Event',
			start: new Date(y, m, d+4, 16, 0),
			allDay: false
		},
		{
			title: 'Meeting',
			start: new Date(y, m, d, 10, 30),
			allDay: false
		},
		{
			title: 'Lunch',
			start: new Date(y, m, d, 12, 0),
			end: new Date(y, m, d, 14, 0),
			allDay: false
		},
		{
			title: 'Birthday Party',
			start: new Date(y, m, d+1, 19, 0),
			end: new Date(y, m, d+1, 22, 30),
			allDay: false
		},
		{
			title: 'Click for Google',
			start: new Date(y, m, 28),
			end: new Date(y, m, 29),
			url: 'http://google.com/'
		}
	],
});

var app = angular.module('app', ['ngResource', 'ui', 'ui.bootstrap.timepicker', 'ui.select2', 'angularFileUpload']);

app.factory('task', function($resource){
	return $resource('/task/taskid', {}, {
		create: {url: '/task/create', method: 'POST'},
		meta: {url: '/task/meta'},
	});
});

app.controller('taskIndex', function($scope, Task, $filter){
	$scope.page = 0;
	$scope.tasks = [];
	$scope.loading = true; 

	$scope.refresh = function(filter) {
		// Before loading, put a *LOADING* message there
		$scope.loading = true;
		filter = filter || {};
		filter.page = $scope.page;

		Task.query(filter).$promise.then(function(data){
			$scope.tasks = data;
			$scope.loading = false; 
		});
	};

	$scope.goPage = function(offset) {
		$scope.page += offset;
		$scope.refresh();
	};

	$scope.refresh();

});
app.controller('userEdit', function($scope, User, ActiveUser, Tag, $location){
	$scope.userMeta = User.meta();
	$scope.filterId = $location.absUrl().match(/[0-9]*$/)[0];
	$scope.activeUser = {};
	$scope.user = {
		tags: [],
	};

	$scope.saveUser = function() {
		if($scope.user.userid) {
			$scope.user.tags = $('#tag-input').val().split(',');
			User.save({userid: $scope.user.userid}, $scope.user);

			// Only admin or manager will be return to user index
			if($scope.activeUser.isAdmin || $scope.activeUser.role == 'manager')
				window.location = '/config/users';
			else
				window.location = '/';
		}
	};

});

app.run(function(ActiveUser, $rootScope, $q, permissions){
	
	$rootScope.user = sessionUser; //see head.php

	$rootScope.can = function (action) {
		if(_.isObject(permissions[$rootScope.user.role])) {
			return permissions[$rootScope.user.role][action];
		}
		else {
			//override for users like root which always return true
			return permissions[$rootScope.user.role];
		}
	};
	
});