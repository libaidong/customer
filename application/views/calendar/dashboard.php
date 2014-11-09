<? $this->load->view('lib/fullcalendar') ?>
<script src="/js/calendar.js"></script>
<script>
// {{{ 
app.controller('dashCtrl', function($scope, $rootScope, Device, Location, Tag, ActiveLocation){
	//Initial assignments
	$scope.page = 0;
	$scope.lastFilters = {};
	$scope.filterName = null;
	$scope.scheduleTitle = null;

	$scope.loadDevices = function(filter) {
		// Before loading, put a *LOADING* message there
		$scope.loading = true;
		filter = filter || {};
		filter.page = $scope.page;
		filter.type = 'device';

		Device.query(filter).$promise.then(function(data){
			$scope.devices = data;
			$scope.loading = false; 
		});
	};

	// Used for pagination
	$scope.goPage = function(offset) {
		$scope.page += offset;
		$scope.loadDevices();
	};

	var loadLocation = function(){
		ActiveLocation.get().$promise
		
			.then(function(data){
				$scope.activeLocation = data;
			})
			.then(function(){
				Location.get({locationid: $scope.activeLocation.locationid}).$promise.then(function(data){
					$scope.location = data;		
					loadCalendar('#calendar', $scope.selectedTag ? {tagid: $scope.selectedTag.tagid} : null, data.basehours);
				});
			});
	};

	$scope.$on('filterDevices', function(e, filter, filterName) {
		$scope.page = 0; // Reset page offset for new filtered result set
		filter.page = $scope.page;
		filter.type = 'device';
		$scope.devices = Device.query(filter);
		$scope.filterName = filterName;
	});

	$scope.toggleStar = function(device) {
		device.starred = !device.starred;
		Device.save({deviceid: device.deviceid}, device);
	};

	// Open the combined Schedule modal
	$scope.openSchedule = function(filters, title, locStart, locEnd){
		var DEFAULT_START = 32400;
		var DEFAULT_END = 61200;
		// Assign default location base hours if not passed
		locStart = typeof locStart !== 'undefined' ? locStart : DEFAULT_START;
		locEnd = typeof locEnd !== 'undefined' ? locEnd : DEFAULT_END;
		
		$scope.lastFilters = filters;
		$scope.scheduleTitle = title;
		$('#modal-schedule').modal('toggle').one('shown.bs.modal', function(){
			// Pass in location object containing start and end base hours
			loadLocation();
		});
	};
	$scope.openNewSchedule = function() {
		$rootScope.$emit('openSchedule');
		$('#modal-event').modal('toggle');
	};
	$scope.openNewEvent = function() {
		$rootScope.$emit('openNewEvent');
		$('#modal-event').modal('toggle');
	};

	$scope.openCombinedSchedule = function() {
		$('#modal-schedule').modal('toggle').on('shown.bs.modal', function(){
			loadCalendar('#calendar', $scope.selectedTag ? {tagid: $scope.selectedTag.tagid} : null, $scope.location.basehours);
		});
	};
	// }}} 

	$rootScope.$on('scheduleSaved', function(){
		$('#modal-event').modal('toggle'); // Close modal on save
		loadCalendar('#calendar', $scope.lastFilters, $scope.location.basehours);
	});

	// Load first devices page on load
	$scope.loadDevices();
});
</script>

<? $this->load->view('calendar/event') ?>
<div class="page-schedule">
	<div class="row" ng-show="!loading" ng-controller="dashCtrl">
		<? $this->load->view('/calendar/schedule') ?>
		<div class="col-md-2 btn-sidebar" ng-include="'/include/filter'"></div>
		<div class="col-md-10">
			<div class="ss-list">
				<div class="pull-right"><a class="btn btn-info" ng-click="openNewEvent()"><i class="fa fa-plus"></i> Create Public Holiday</a></div>
				<div class="pull-right" style="padding-right:10px">
					<a class="btn btn-success" 
						ng-click="openSchedule(null,'Combined schedule')">
						<i class="fa fa-calendar"></i> {{selectedTag.name ? "All " + tagFormat(selectedTag.name) + " schedules" : "All schedules"}}
					</a>
				</div>
				<h1>{{filterName || 'All Schedules'}}</h1>
			</div>
			<ul class="ss-list">
				<li ng-repeat="device in devices" ng-class="device.isActive ? 'status-on' : 'status-off'">
					<a href="#" ng-click="openSchedule({deviceid: device.deviceid}, device.name)">
						<i class="fa-lg" ng-class="{unknown: 'fa fa-question-circle', ac: 'mff-snow', light: 'mff-bulb', hotwater: 'mff-drip', 'meter': 'mff-seg-circle', 'gas': 'fa fa-fire'}[device.type]"></i>
						{{device.name}}
					</a>
					<div class="pull-right">
						<a href="#" ng-click="toggleStar(device)"><i class="fa fa-lg" ng-class="device.starred ? 'fa-star fg-yellow' : 'fa-star-o fg-muted'"></i></a>
					</div>
				</li>
			</ul>

			<!-- Pager -->			
			<ul class="pager">
				<li ng-show="page > 0" class="previous"><a ng-click="goPage(-1)" href="#">&larr; Previous</a></li>
				<li ng-show="devices.length >= <?=DEFAULT_PAGE_LIMIT?>" class="next"><a ng-click="goPage(1)" href="#">Next &rarr;</a></li>
			</ul>
		</div>
	</div>
	<div class="row" ng-show="loading">
		<div class="alert alert-info"><img src="/img/ajax-loader.gif"</div>
	</div>
</div>
