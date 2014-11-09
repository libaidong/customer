<div ng-controller="userIndex">
	<!-- Modals {{{ -->
	<div class="modal fade" id="csvExport">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title">User CSV Export</h4>
				</div>
				<div class="modal-body">
					<p>This will create a CSV file of the database users table. To use:<ol>
						<li>Click export below:</li>
						<li>When presented with the csv text file, please click 'save as' in your browser's dialogue box.</li>
					</ol>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					<a class="btn btn" href="/users/exportCSV" data-toggle="modal"><i class="fa fa-plus"></i> CSV export</a>
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
	<div id="csv" class="modal fade"> 
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title">CSV Import</h4>
				</div>
				<div class="modal-body">
					<form action="/users/importCSV" enctype="multipart/form-data" method="POST">
						<h4>Important!</h4>
						<p>CSV Import will only work if the schema is matched exactly, both in column number and position. 
						The import file should be formatted without heading columns. 
						It should use commas for field delimiters. It should not contain unescaped special-characters</p>
						<p>Eg.</p>
						<p>
							<a class="label label-success" href="/data/fields.xlsx">.xlsx Field listing</a>
							<a class="label label-success" href="/data/fields.csv">Sample file CSV file</a>
						</p>
						<input type="file" name="file">
						<button type="submit" class="btn pull-right btn-primary">Import CSV</button>
					</form>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
	<!-- }}} Modals --> 

	<div ng-show="loading" class="alert alert-info">
		<p class="pull-center"><img src="/img/ajax-loader.gif"> Loading... </p>
	</div>

	<div ng-show="!users.length && !loading" class="alert alert-warning">
		<h3>No users found</h3>
		<p>No users match your search criteria.</p>
		<div class="pull-center"><a href="/config/users" class="btn btn-default btn-large">Show all users</a></div>
	</div>

	<table ng-show="users.length" class="table table-bordered table-striped">
		<thead>
			<tr>
				<th width="40px">&nbsp;</th>
				<th>Username</th>
				<th>Email</th>
				<th width="25px">Role</th>
			</tr>
		</thead>
		<tbody>
			<tr ng-repeat="user in users">
				<td><div class="btn-group">
					<a class="btn btn-default dropdown-toggle" data-toggle="dropdown" href="#"><i class="fa fa-user"></i> <span class="caret"></span></a>
					<ul class="dropdown-menu">
						<li><a href="/config/users/edit/{{user.userid}}"><i class="fa fa-pencil"></i> Edit</a></li>
						<li ng-show="activeUser.isAdmin"><a ng-click="rmUser(user)"><i class="fa fa-trash-o"></i> Delete</a></li>
					</ul>
				</div></td>
				<td><a href="/config/users/edit/{{user.userid}}">{{user.username}}</a></td>
				<td><a href="/config/users/edit/{{user.userid}}">{{user.email}}</a></td>
				<td><a href="/config/users/edit/{{user.userid}}">{{user.role | ucwords}}</a></td>
			</tr>
		</tbody>
	</table>

	<!-- Pager -->
	<ul class="pager">
		<li ng-show="page > 0" class="previous"><a ng-click="goPage(-1)" href="#">&larr; Previous</a></li>
		<li ng-show="users.length >= <?=DEFAULT_PAGE_LIMIT?>" class="next"><a ng-click="goPage(1)" href="#">Next &rarr;</a></li>
	</ul>

	<div class="pull-center">
		<a class="btn btn-primary" href="/config/users/edit/new"><i class="fa fa-plus"></i> Add new user</a>
		<a ng-if="can('importCSV')" class="btn btn" href="#csv" data-toggle="modal"><i class="fa fa-plus"></i> CSV Import</a>
		<a ng-if="can('exportCSV')" class="btn btn" href="#csvExport" data-toggle="modal"><i class="fa fa-plus"></i> CSV Export</a>
	</div>
</div>
