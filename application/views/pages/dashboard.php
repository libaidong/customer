<script src="/js/calendar.js"></script>
<? $this->load->view('lib/fullcalendar') ?>

<div class="row page-dashboard" ng-controller="dashCtrl">
	<? $this->load->view('calendar/schedule') ?>
	<? $this->load->view('calendar/event') ?>
	<div class="col-md-4">
		<div class="row">
			<h1 class="pad-bottom-small bg-green">Quick Requests</h1>
			<div ng-show="loading" class="alert alert-info">
				<p class="pull-center"><img src="/img/ajax-loader.gif"> Loading... </p>
			</div>
			<div class="alert alert-warning" ng-show="!devices.length && !loading">
				<p>You don't have any starred items</p>
			</div>
			<div class="pad-bottom-small" ng-repeat="device in devices">
				<a href="#" ng-click="openSchedule({deviceid: device.deviceid})" class="btn btn-block btn-shortcut">
					<i class="mff-schedule"></i>
					<div>{{device.description || device.typeText}}</div>
					<span>{{device.name}}</span>
				</a>
			</div>
		</div>
		<div class="row">
			<h1 class="pad-bottom-small bg-blue">Quick links</h1>
			<? if ($this->User->IsAdmin()) { ?>
			<div class="pad-bottom-small"><a href="/config/users" class="btn btn-shortcut btn-block">
				<i class="mff-people"></i>
				<div>Edit user information</div>
				<span>Change user information like passwords and user levels</span>
			</a></div>
			<? } else { ?>
			<div class="pad-bottom-small"><a href="/profile" class="btn btn-shortcut btn-block">
				<i class="mff-people"></i>
				<div>Change your information</div>
				<span>Change information like passwords and general details.</span>
			</a></div>
			<? } ?>
			<div ng-if="can('view locations')" class="pad-bottom-small"><a href="/config/locations" class="btn btn-shortcut btn-block">
				<i class="mff-building"></i>
				<div>Edit location information</div>
				<span>Change location information like universal schedule and logo</span>
			</a></div>
			<div class="pad-bottom-small"><a href="/schedule" class="btn btn-shortcut btn-block">
				<i class="mff-schedule"></i>
				<div>Schedule information</div>
				<span>Edit building schedules for all resources</span>
			</a></div>
			<? if ($this->User->IsAdmin()) { ?>
			<div class="pad-bottom-small"><a href="/meters" class="btn btn-shortcut btn-block">
				<i class="mff-seg-circle"></i>
				<div>Metering information</div>
				<span>Show all building meters and filter by level and tenant</span>
			</a></div>
			<? } ?>
		</div>
		<div class="list-activities">
			<div class="row">
				<h1 class="pad-bottom-small bg-teal">Upcoming Public Holidays</h1>

				<div ng-show="loading" class="alert alert-info">
					<p class="pull-center"><img src="/img/ajax-loader.gif"> Loading... </p>
				</div>

				<div class="alert alert-warning" ng-show="!holidays.length && !loading">
					<p>No upcoming public holidays</p>
				</div>
				<div class="pad-bottom-small" ng-show="can('create public holidays')">
					<a href="#" ng-click="openCalendarEvent()" class="btn btn-shortcut btn-block">
						<i class="fa fa-calendar"></i>
						<div>Create new Public Holiday</div>
					</a>
				</div>

				<table class="table">
						<tr ng-repeat="holiday in holidays" ng-finished="refresh()">
							<td>
								<a href="#" ng-click="openCalendar({})" data-html="true" 
								data-tip="<p>Repeats {{holiday.repeatText}}</p>" 
								data-tip-placement="left">
								<i class="fa fa-refresh fa-lg" ng-show="holiday.repeat_type != 'none'"></i>
								</a>
							</td>
							<td>
								<span class="list-activities list-block">
									<a href="#" ng-click="openCalendar({})" data-html="true" 
									data-tip="<p>Repeats {{holiday.repeatText}}</p>" 
									data-tip-placement="left">
									{{holiday.description}}:
									{{holiday.start * 1000 | date : 'EEE, dd MMM yyyy'}} 
									</a>  
									<div ng-show="holiday.end - holiday.start > 86400">
										<!-- Show the end period only if its a day later than the start date - for multi-day holidays -->
										<a href="#" ng-click="openCalendar({})" data-html="true" >
											 - {{holiday.end * 1000 | date : 'EEE, dd MMM'}}
										</a>  
									</div>
								</span>
							</td>
						</tr>
				</table>
			</div>
		</div>
	</div>

	<? if ($this->User->IsAdmin()) { ?>
	<div class="col-md-4">
		<div ng-show="loading">
			<h1 class="bg-orange">Meters</h1>
			<div class="alert alert-info">
				<p class="pull-center"><img src="/img/ajax-loader.gif"> Loading... </p>
			</div>
		</div>
		<div ng-show="!meters.length && !loading">
			<h1 class="bg-orange">Meters</h1>
			<div class="alert alert-warning">
				<p>You don't have any starred items</p>
				<div class="pull-center pad-top-small">
					<a class="btn btn-default btn-large" href="/meters">
						Show all meters
					</a>
				</div>
			</div>
		</div>
		<div class="row" ng-repeat="meter in meters" ng-finished="refresh()">
			<h1 class="bg-orange">{{meter.description || meter.typeText}}</h1>
			<pichart data-tip="Daily Target: {{meter.usage_target}}" meter="meter"></pichart>
		</div>
	</div>
	<? } ?>

	<div class="<?=$this->User->IsAdmin() ? 'col-md-4' : 'col-md-8'?> list-activities">
		<h1 class="pad-bottom-small bg-red">Activity Feed - <?=date('d/m - g:ia')?></h1>
			<div ng-show="loading" class="alert alert-info">
				<p class="pull-center"><img src="/img/ajax-loader.gif"> Loading... </p>
			</div>

			<div class="row">
				<table class="table">
					<tr ng-repeat="event in schedules" ng-finished="refresh()">
						<td>
							<a href="#" ng-click="openCalendar({deviceid: event.deviceid})" data-html="true" data-tip="<p>{{event.device.name}}</p><p>{{event.isActive ? 'Active' : 'Inactive'}}<br/>Repeats {{event.repeatText}}</p>" data-tip-placement="left" ng-class="event.isActive ? 'fg-green' : 'text-muted'">
								<i class="fa-lg" ng-class="[{unknown: 'fa fa-question-circle', ac: 'mff-snow', light: 'mff-bulb', hotwater: 'mff-drip', 'meter': 'mff-seg-circle', 'gas': 'fa fa-fire'}[event.device.type]]"></i>
							</a>
						</td>
						<td>
							<a href="#" ng-click="openCalendar({deviceid: event.deviceid})" data-html="true" data-tip="<p>{{event.device.name}}</p><p>{{event.isActive ? 'Active' : 'Inactive'}}<br/>Repeats {{event.repeatText}}</p>" data-tip-placement="left" ng-class="event.isActive ? 'fg-green' : 'text-muted'">
								<i class="fa fa-refresh fa-lg" ng-show="event.repeat_type != 'none'"></i>
							</a>
						</td>
						<td>
							<span class="list-activities list-block">
								<a href="#" ng-click="openCalendar({deviceid: event.deviceid})" data-html="true" data-tip="<p>{{event.device.name}}</p><p>{{event.isActive ? 'Active' : 'Inactive'}}<br/>Repeats {{event.repeatText}}</p>" data-tip-placement="left" ng-class="event.isActive ? 'fg-green' : 'text-muted'">
								{{event.startText}} - 
								{{event.endText}} - 
								{{event.device.name}}
								</a>  
							</span>
						</td>
					</tr>
				</table>
			</div>
		</div>
	</div>
</div>
