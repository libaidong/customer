<? if ($this->User->IsAdmin() || $this->User->isRole('manager')) { ?>
<a href="/config/users" class="callout callout-info">
	<h4>Manage Users</h4>
	Manage existing user accounts and security.
</a>

<a href="/config/dcus" class="callout callout-info">
	<h4>DCUs</h4>
	Configure DCUs 
</a>

<a href="/config/tags" class="callout callout-info">
	<h4>Tags</h4>
	Create, update and delete site tags.
</a>

<a href="/config/locations" class="callout callout-info">
	<h4>Locations</h4>
	Create, update and delete locations.
</a>

<a ng-show="can('use api')" href="/config/api" class="callout callout-info">
	<h4>API testing kit</h4>
	Send raw API calls to DCUs and simulate the returns to the server.
</a>
<? } else { ?>
<div class="alert alert-warning">
	You dont have permission to configure anything.
</div>
<? } ?>
