<div class="modal fade" id="modal-schedule" style="z-index: 1100">
	<div class="modal-dialog" style="width:80%">
		<div class="modal-content">
			<div class="modal-header">
				<div class="pull-right pad-left">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				</div>
				<div class="pull-right">
					<h4 class="modal-title">{{scheduleTitle}}</h4>
				</div>
				<a ng-click="openNewSchedule()" class="btn btn-sm btn-success"><i class="fa fa-plus"></i> Add schedule</a>
				<a ng-show="can('create public holidays')" ng-click="openNewEvent()" class="btn btn-sm"><i class="fa fa-plus"></i> Create Public Holiday</a>
			</div>
			<div class="modal-body">
				<div class="dcu-agenda" id="calendar"></div>
			</div>
		</div>
	</div>
</div>

