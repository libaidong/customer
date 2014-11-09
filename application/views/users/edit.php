<script>
	$(document).ready(function() {
		// Tag viewing/editing
		$('#tag-input').tagsInput({
			'width': '100%',
			'height': 'auto',
			'autocomplete_url': '/tags/autocomplete',
			'removeWithBackspace' : false,
		});
	});
</script>

<style>
	input.ng-invalid.ng-dirty {
	    background-color: rgb(255, 182, 182);
	}
</style>

<div class="row" ng-controller="userEdit">
<div ng-show="!user" class="alert alert-warning">
	<h3>No users found</h3>
	<p>No user matches your search criteria.</p>
	<div class="pull-center"><a href="/config/users" class="btn btn-default btn-large">Show all users</a></div>
</div>
<form ng-show="user" name="userEditForm" class="form-horizontal">
	<div class="form-group">
		<legend>General information</legend>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label">Username</label>
		<div class="col-sm-10">
			<input type="text" class="form-control" ng-model="user.username"/>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label">First name</label>
		<div class="col-sm-10">
			<input type="text" class="form-control" ng-model="user.fname"/>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label">Last name</label>
		<div class="col-sm-10">
			<input type="text" class="form-control" ng-model="user.lname"/>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label">Email</label>
		<div class="col-sm-4">
			<input type="email" name="email" class="form-control" ng-model="user.email"/>
		</div>
		<label class="col-sm-2 control-label">Phone Number</label>
		<div class="col-sm-4">
			<input type="text" class="form-control" ng-model="user.phone"/>
		</div>
	</div>
	<div ng-if="activeUser.role == 'admin' || activeUser.role == 'root'" class="form-group">
		<label class="col-sm-2 control-label">Role</label>
		<div class="col-sm-10">
			<select class="form-control" ng-model="user.role" ng-options="k as v for (k,v) in userMeta.role.options"></select>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label">Tags</label>
		<div ng-show="can('edit user')" class="col-sm-10">
			<input id="tag-input" class="form-control" name="tags" />
			<div class="help-block">Press ENTER to complete a tag input. Examples: <span class="example-tag">tenant:ANP</span> or <span class="example-tag">level:Level 35</span></div>
		</div>
		<div ng-show="activeUser.role == 'user'" class="col-sm-10">
			<div id="tag-view" class="form-control" name="tagsReadOnly" style="width: 100%; height: auto;"></div>
		</div>
	</div>
	<div class="form-group" ng-show="user.created || user.edited">
		<legend>Entity Information</legend>
	</div>
	<div class="form-group" ng-show="user.created">
		<label class="col-sm-2 control-label">Created</label>
		<div class="col-sm-10">
			<p class="form-control-static">{{user.created | fromEpoc | date}}</p>
		</div>
	</div>
	<div class="form-group" ng-show="user.edited">
		<label class="col-sm-2 control-label">Last edited</label>
		<div class="col-sm-10">
			<p class="form-control-static">{{user.edited | fromEpoc | date}}</p>
		</div>
	</div>
	<div class="pull-right">
		<a href="/logs?userid={{user.userid}}" ng-show="(activeUser.role == 'root' || activeUser.role == 'admin')" class="btn btn-default"><i class="fa fa-clock-o"></i> User Logs</a>
		<button ng-show="user.userid" class="btn btn-success" ng-click="saveUser()"><i class="fa fa-check"></i> Save user details</button>
		<button ng-show="!user.userid && (activeUser.role == 'root' || activeUser.role == 'admin')" class="btn btn-success" ng-click="createUser()"><i class="fa fa-plus"></i> Create user account</button>
		<button ng-show="!user.userid && (activeUser.role == 'user')" class="btn btn-success" ng-click="createUser()"><i class="fa fa-save"></i> Save</button>
	</div>
<form>
</div>
