var app = angular.module('app', ['ngResource', 'ui', 'ui.bootstrap.timepicker', 'ui.select2', 'angularFileUpload']);

app.factory('AreaSeries', function($http){
	return {
		get: function(deviceid, interval){
			return $http.get('/devices/getSeries/'+deviceid+'/'+interval);
		}	
	}
});

app.controller('taskIndex', function($scope, Task, $filter){
	$scope.page = 0;
	$scope.activeUser = ActiveUser.get();
	$scope.users = [];
	$scope.userMeta = User.meta();
	$scope.loading = true; 

	$scope.refresh = function(filter) {
		// Before loading, put a *LOADING* message there
		$scope.loading = true;
		filter = filter || {};
		filter.page = $scope.page;

		User.query(filter).$promise.then(function(data){
			$scope.users = data;
			$scope.loading = false; 
		});
	};

	$scope.goPage = function(offset) {
		$scope.page += offset;
		$scope.refresh();
	};

	$scope.rmUser = function(user) {
		User.delete({userid: user.userid}).$promise.then(function() {
			$scope.refresh(); 	
		});
	};

	$scope.refresh();

});

$(document).ready(function(){

	$('#loading-example-btn').click(function () {
		btn = $(this);
		simpleLoad(btn, true)
		simpleLoad(btn, false)
	});
});
$("#editTask").click(function(){
	$("#taskEditDIV").show();
});

$("#taskEditCancle").click(function(){
	$("#taskEditDIV").hide();
});


function simpleLoad(btn, state) {
	if (state) {
		btn.children().addClass('fa-spin');
		btn.contents().last().replaceWith(" Loading");
	} else {
		setTimeout(function () {
			btn.children().removeClass('fa-spin');
			btn.contents().last().replaceWith(" Refresh");
		}, 2000);
	}
}
