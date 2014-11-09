<? $this->site->view('lib/datetimepicker') ?>
<script>
app
	.controller('eventCtrl', function($scope, $rootScope, Schedule, Device, User, $timeout, ActiveLocation, ActiveUser, $http) {

		$scope.select2Options = {};
		$scope.eventMeta = Schedule.meta();
		$scope.loading = true;
		$scope.activeLocation = ActiveLocation.get();
		$scope.activeUser = ActiveUser.get();

		$scope.loadDevices = function(filter) {
			$scope.loading = true;
			filter = filter || {};
			filter.type = 'device';

			Device.query(filter).$promise.then(function(data) {
				$scope.devices = data;
				$scope.loading = false;
			});
		};

		// {{{ Operations
		var createNewSchedule = function(event){
			$scope.schedule = event || {};
			console.log('opening schedule with ', event, $scope.schedule);

			var start = moment().add('hours', 1);
			$scope.schedule.startText = start.format('DD-MM-YYYY hh:mm a');
			var end = moment().add('hours', 2);
			$scope.schedule.endText = end.format('DD-MM-YYYY hh:mm a');
			$scope.schedule.repeat_type = 'none';
			$scope.schedule.eventtype = 'device schedule'; 
		};
		var createNewEvent = function(event) {

			$scope.schedule = event || {};

			$scope.schedule.isValid = false;

			var start = moment().set('hour', 0).set('minute', 0);
			$scope.schedule.startText = start.format('DD-MM-YYYY');

			start.add('days',1);

			$scope.schedule.endText = start.format('DD-MM-YYYY');

			$scope.schedule.eventtype = 'calendar event'; 
			$scope.schedule.repeat_type = 'yearly';// remove the old data after save
		};

		var loadSchedule = function(id){
			$scope.loading = true;
			Schedule.get({eventid: id})
			       .$promise.then(function(s) {
					$scope.loading = false;
					angular.element($('#eventCtrl')).scope().setSchedule(s);
			       });

   			// Read in the repeat pattern
			if ($scope.schedule.repeat_type == 'weekly') {
				_.forEach($scope.schedule.repeatParams.split(','), function(day) {
					console.log(day);
					switch (day) {
						case 'MN':
							$scope.repeatPatternWeek.monday = true;
							break;
						case 'TU':
							$scope.repeatPatternWeek.tuesday = true; 
							break;
						case 'WD':
							$scope.repeatPatternWeek.wednesday = true;
							break;
						case 'TH':
							$scope.repeatPatternWeek.thursday = true;
							break;
						case 'FR':
							$scope.repeatPatternWeek.friday = true;
							break;
						case 'SA':
							$scope.repeatPatternWeek.saturday = true;
							break;
						case 'SU':
							$scope.repeatPatternWeek.sunday = true;
							break;
					}
				});
			}
		};

		/**
		 * Checks that the user can perform the operation and sets the $scope.schedule.isValid flag
		 */
		var validate = function(){

			if(!$scope.schedule)//don't throw errors
				return null;

			if($scope.schedule.eventtype == 'calendar event'){
				$scope.schedule.isValid = true;
				return true;
			}

			$scope.schedule.isValid = false;//default setting once allocated

			if($scope.schedule.startText && $scope.schedule.endText) {
				$scope.schedule.start = moment($scope.schedule.startText, 'DD-MM-YYYY hh:mm a').unix();
				$scope.schedule.end = moment($scope.schedule.endText, 'DD-MM-YYYY hh:mm a').unix();

				if($scope.schedule.end < $scope.schedule.start) {
					$scope.schedule.end = $scope.schedule.start;
					$scope.schedule.endText = $scope.schedule.startText;
				}
			}
			else {
				return null; //don't throw errors if there's no dates selected
			}

			if($scope.activeLocation.basehours != null) {//if there's base hours specified, do a check to see if the user can create events

		
				//Get a fresh set of dates by parsing the strings
				var eventStart = getRelTS($scope.schedule.start);	
				var eventEnd = getRelTS($scope.schedule.end);

				//Get the day of the week to select from the baseHours. Remember the basehours start from 0;
				//And.. momentjs starts with Sunday as day 0, whereas the internal data storage with monday as day zero. 
				var startDOW = (+moment($scope.schedule.startText, 'DD-MM-YYYY hh:mm a').format('d')) -1;
				var endDOW = (+moment($scope.schedule.endText, 'DD-MM-YYYY hh:mm a').format('d')) -1;
				if(startDOW==-1)
					startDOW=6;
				if(endDOW==-1)
					endDOW=6; //some stupidity in fixing off-by-one errors

				//These are the basehours for the location. They are the specified block in which only managers can allocate.
				var locStart = $scope.activeLocation.basehours[startDOW].start;
				var locEnd = $scope.activeLocation.basehours[endDOW].end;

				if(intersect(eventStart, eventEnd, locStart, locEnd) && $scope.activeUser.role == 'user') { //Obviously this only applies to the user role
					$scope.schedule.isValid = false;
				}
				else { //user can save...
					$scope.schedule.isValid = true;
				}

				//Fetch public holidays from the server. If it finds any, validate. 
				//To do this: fetch from server all calendar events during that day... 
				//if there's a result, then validate
				$http({url: '/calendar/events', params: 
						{
							//This is tricky: despite the before/after parameter names, what 
							//This is doing is fetching calendar events (public holidays) which are
							//Starting at or after midnight of the day of the event and finishing 
							//at the end of the day of the scheduled event end.

							//Therefore, this is not after the scheduled start - but after midnight of the scheduled start's day (ie before the scheduled start):
							afterTS: moment($scope.schedule.startText, 'DD-MM-YYYY').hours(0).minutes(0).unix(), 
							beforeTS: moment($scope.schedule.endText, 'DD-MM-YYYY').add('day', 1).hours(0).minutes(0).subtract('minutes', 1).unix(),//get 11:59 of the day
							getBetween: true, 
							eventtype: 'calendar event'}, 
					method: "GET"}).success(function(result){
						if(result.length > 0) {

							//Need absolute time values here, so parse them from text again
							var absStart = moment($scope.schedule.startText, 'DD-MM-YYYY hh:mm a').unix();
							var absEnd = moment($scope.schedule.endText, 'DD-MM-YYYY hh:mm a').unix();

							var ok = false; 
							for(var i = 0; i < result.length; i++ ) {
								var calStart = parseInt(result[i].start);
								var calEnd = parseInt(result[i].end);

								//See if they cover the event
								if(calStart < absStart && calEnd > absEnd)
									ok = true;
							}
							if(ok) //override the basehours if a public holiday is found, else do nothing
								$scope.schedule.isValid = true;//if it finds any intersection, validate
						}
					});
			}
			else { //There's no base hours to check, so let it through
				console.log('No base hours');
				$scope.schedule.isValid = true;
			}
		};
		// }}}
		// {{{ Events
		
		/*
		 * Watches when a schedule is first set and defaults the end time to one hour after the start time. 
		 */
		$scope.$watch('schedule.startText', function(start){
			validate();
		});
		$scope.$watch('schedule.endText', function(start){
			validate();
		});

		$scope.setScheduleId = function(scheduleid) {
			loadSchedule(scheduleid);
		};

		/*
		 * Initialises repeat pattern values for schedule edit form
		 */
		$scope.initRepeatPatterns = function() {
			// Used only if the current schedule is to have a weekly repeat pattern
			$scope.repeatPatternWeek = {
				monday: false,
				tuesday: false,
				wednesday: false,
				thursday: false,
				friday: false,
				saturday: false,
				sunday: false,
			};
		 };

		$scope.save = function() {
			$scope.schedule.start = moment($scope.schedule.startText, 'DD-MM-YYYY hh:mm a').unix();

			// Process repeat pattern depending on repeat type
			if ($scope.schedule.repeat_type == 'weekly') {
				$scope.schedule.repeatParams = 	($scope.repeatPatternWeek.monday ? 'MN,': '') + 
												($scope.repeatPatternWeek.tuesday ? 'TU,': '') +
												($scope.repeatPatternWeek.wednesday ? 'WD,': '') +
												($scope.repeatPatternWeek.thursday ? 'TH,': '') +
												($scope.repeatPatternWeek.friday ? 'FR,': '') +
												($scope.repeatPatternWeek.saturday ? 'SA,': '') +
												($scope.repeatPatternWeek.sunday ? 'SU': '');
			}

			//Quick fix: if the type is a calendar event, make it go for the entire day
			//why? Because the calendar events don't allow the user to pick time as well. It defaults to 1 hour after start time which is not desirable
			if($scope.schedule.eventtype == 'calendar event') {
				$scope.schedule.end = moment($scope.schedule.endText, 'DD-MM-YYYY hh:mm a').hours(23).minutes(59).unix();
			}
			else { //leave as is
				$scope.schedule.end = moment($scope.schedule.endText, 'DD-MM-YYYY hh:mm a').unix();
			}

			Schedule.save({eventid: $scope.schedule.eventid}, $scope.schedule).$promise.then(function(){
				$rootScope.$emit('scheduleSaved');
				$scope.schedule = {}; //clear away any old data
			});

			$scope.initRepeatPatterns(); // Reset repeat pattern variable to default values
		};

		$scope.delete = function(){
			Schedule.delete({eventid: $scope.schedule.eventid}).$promise.then(function(data){
				$rootScope.$emit('scheduleSaved');
			})
		}

		$rootScope.$on('openNewEvent', function(event, data){
			createNewEvent(data);
		});

		$rootScope.$on('openSchedule', function(event, schedule){
			createNewSchedule(schedule);
		});

		/**
		 * The schedule loader, This loads the data into the modal.
		 */
		$scope.setSchedule = function(schedule) {
			// Load devices
			$scope.loadDevices();

			$scope.schedule = schedule;
			if ($scope.schedule.editedid)
				$scope.schedule.editor = User.get({userid: $scope.schedule.editedid});

			// Deal with start/end times if we have them {{{
			var start;
			if ($scope.schedule.start) {
				$scope.schedule.startText = moment.unix($scope.schedule.start).format('DD/MM/YYYY hh:mm a');
			} 
			else {
				start = moment()
					.add('hours', 1)
					.minute(0);
				$scope.schedule.startText = start.format('DD/MM/YYYY hh:mm a');
			}

			var end;
			if ($scope.schedule.end) {
				$scope.schedule.endText = moment.unix($scope.schedule.end).format('DD/MM/YYYY hh:mm a');
			} else {
				end = start.add('hours', 1);
				$scope.schedule.endText = end.format('DD/MM/YYYY hh:mm a');
			}
			// }}}
		};
		// }}} 

		// Close the schedule modal
		$scope.closeScheduleModal = function() {
			$('#modal-schedule').modal('hide');
		};

		$scope.initRepeatPatterns();
	});
</script>
<div id="eventCtrl" ng-controller="eventCtrl">
	<div id="modal-event" class="modal fade" style="z-index: 1200">
		<form class="modal-dialog" name="eventForm">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close pad-left" data-dismiss="modal" aria-hidden="true">&times;</button>
					<div class="pull-right">
						{{schedule.editedid ? 'Last edited by ' + schedule.editor.fname + ' ' + schedule.editor.lname : ''}}
						<div ng-show="schedule.edited" class="font-small text-right">{{schedule.edited | fromEpoc | date}}</div>
					</div>
					<h4 ng-if="schedule.eventtype != 'calendar event'" class="modal-title">Edit Schedule</h4>
					<h4 ng-if="schedule.eventtype == 'calendar event'" class="modal-title">Edit Public Holiday</h4>
				</div>
				<div class="modal-body">
					<div class="form-horizontal">
							<div ng-show="schedule.eventtype != 'calendar event'" class="form-group">
								<label class="col-sm-2 control-label">Resource</label>
								<div class="col-sm-10">
									<select class="form-control" ui-select2='select2Options' class="select2El" ng-model="schedule.deviceid">
										<option required ng-repeat="device in devices" ng-value="device.deviceid">{{device.name}}</option>
									</select>
								</div>
							</div>
							<!-- Disabled for now but left for future implementation - MC 2014-03-09
							<div class="form-group" ng-show="0">
								<label class="col-sm-2 control-label">Action</label>
								<div class="col-sm-10">
									<div class="input-group">
										<select class="form-control" ng-model="command" ng-options="r.value as r.text for r in commands" ng-change="setCommand()"></select>
										<a href="#" class="input-group-addon" data-toggle="modal" data-target="#modal-action"><i class="fa fa-cog"></i></a>
									</div>
								</div>
							</div>
							-->
							<div class="form-group" ng-show="schedule.eventtype == 'device schedule'">
								<label class="col-sm-2 control-label">Start at</label>
								<div class="col-sm-10">
									<div class="input-group">
										<input required type="text" class="form-control pick-datetime" ng-model="schedule.startText"/>
										<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
									</div>
								</div>
							</div>
							<div class="form-group" ng-show="schedule.eventtype == 'device schedule'">
								<label class="col-sm-2 control-label">End at</label>
								<div class="col-sm-10">
									<div class="input-group">
										<input required type="text" class="form-control pick-datetime" ng-model="schedule.endText"/>
										<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
									</div>
								</div>
							</div>
							<div class="form-group" ng-show="schedule.eventtype == 'calendar event'">
								<label class="col-sm-2 control-label">Start at</label>
								<div class="col-sm-10">
									<div class="input-group">
										<input required type="text" class="form-control pick-date" ng-model="schedule.startText"/>
										<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
									</div>
								</div>
							</div>
							<div class="form-group" ng-show="schedule.eventtype == 'calendar event'">
								<label class="col-sm-2 control-label">End at</label>
								<div class="col-sm-10">
									<div class="input-group">
										<input required type="text" class="form-control pick-date" ng-model="schedule.endText"/>
										<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label">Repetition</label>
								<div class="col-sm-10">
									<select required ng-model="schedule.repeat_type" class="form-control" ng-options="k as v for (k,v) in eventMeta.repeat_type.options"></select>
								</div>
							</div>
							<div class="form-group" ng-if="schedule.repeat_type == 'weekly'">
								<label class="col-sm-2 control-label">Repetition Pattern</label>
								<div class="col-sm-10">
									<div class="repetition-pattern" ng-repeat="(index, day) in ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday']">
										<input type="checkbox" ng-name="day" ng-model="repeatPatternWeek[day]" ng-change="updateRepeatPattern(day)"> {{day | ucfirst}}
									</div>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label">Details</label>
								<div class="col-sm-10">
									<input ng-if="schedule.eventtype != 'calendar event'" type="text" class="form-control" name="description" placeholder="E.g. Sales Meeting" ng-model="schedule.description"/>
									<input ng-if="schedule.eventtype == 'calendar event'" type="text" class="form-control" name="description" placeholder="E.g. Bank Holiday" ng-model="schedule.description"/>
								</div>
							</div>
					</div>
				</div>
				<div class="modal-footer">
					<div class="pull-left" ng-show="schedule.eventid">
						<a href="#" ng-click="delete()" class="btn btn-danger"><i class="fa fa-trash-o"></i> Delete</a>	
					</div>
					<button type="button" class="btn btn-default" data-dismiss="modal" ng-click="closeScheduleModal()">Cancel</button>
					<div class="pull-right" ng-show="!schedule.isValid">
						<a class="btn btn-warning" ng-click="alert('Please consult an admin to allocate during these hours')"><i class="fa fa-exclamation"></i> Cannot allocate during these hours</a>
					</div>
					<div class="pull-right" ng-show="schedule.isValid">
						<button ng-disabled="eventForm.$invalid" ng-click="save()" class="btn btn-success"><i class="fa fa-check"></i> Confirm</button>
					</div>
				</div>
			</div>
		</form>
	</div>
	<div id="modal-action" class="modal fade" style="z-index: 1210">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title">Configure Action</h4>
				</div>
				<div class="modal-body">
					<div class="panel-group" id="accordion">
						<div class="panel panel-default" ng-repeat="occurs in event_occurs">
							<div class="panel-heading">
								<h4 class="panel-title"><a data-toggle="collapse" data-parent="#accordion" href="#collapse-{{occurs.value}}">Schedule {{occurs.text}}</a></h4>
							</div>
							<div id="collapse-{{occurs.value}}" class="panel-collapse collapse in">
								<div class="panel-body">
									<div class="form-horizontal">
										<div class="form-group">
											<label class="col-sm-2 control-label">Command</label>
											<div class="col-sm-10">
												<input type="text" class="form-control" ng-value="getCommand(occurs.value)"/>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" data-dismiss="modal" class="btn btn-primary">Close</button>
				</div>
			</div>
		</div>
	</div>
</div>
