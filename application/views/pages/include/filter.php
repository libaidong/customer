<div ng-controller="filterCtrl">
	<div class="btn-group">
		<a class="btn btn-success dropdown-toggle" data-toggle="dropdown" href="#">
			<i class="fa fa-filter"></i>
			{{selectedTagPrefix.name || 'Everything'}}
			<i class="fa fa-caret-down"></i>
		</a>
		<ul class="dropdown-menu">
			<li><a href="#" ng-click="setTagPrefix()">Everything</a></li>
			<li ng-repeat="prefix in tagPrefixes"><a href="#" ng-click="setTagPrefix(prefix)">{{prefix.name}}</a></li>
		</ul>
	</div>
	<a ng-click="setSelected(0)" ng-class="!selectedTag ? 'btn-primary' : 'btn-default'" href="#" class="btn">
		<i class="fa fa-asterisk"></i> All schedules
	</a>
	<a ng-click="setSelected(tag)" ng-class="tag.tagid == selectedTag.tagid && selectedTag ? 'btn-primary' : 'btn-default'" ng-repeat="tag in tags | filter:tagPrefixFilter" href="#" class="btn">
		<i ng-class="selectedTagPrefix.icon"></i>
		{{tag.name | format:tagFormat}}
	</a>
</div>
