$(document).ready(function() {
		$("#confirmPwd").click(function() {
	 			$("#cfrPwdMsg").hide();
	 	});
	
	 $("#register").click(function() {
	 		if($("#confirmPwd").val()!=$("#password").val()) {
	 			$("#cfrPwdMsg").show();
	 			return false;
	 		} 	
	 	});
         

});
var app = angular.module('app', ['ngResource', 'ui', 'ui.bootstrap.timepicker', 'ui.select2', 'angularFileUpload']);
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

				window.location = '/';
		}
	};

	$scope.createUser = function() {
		User.create($scope.user).$promise.then(function(){
			window.location = '/config/users';
		});
	};

	
	// Operations
	var loadUser = function(id){
		if(id)	{
			User.get({userid: id}).$promise.then(function(data){
				$scope.user = data;	
				loadTags(id);
			});
		}
		else {
			// Do nothing without valid id
		}
	};
	
	// Loads up all the tags for that user and creates the tag input field
	var loadTags = function(userid){
		Tag.getByUser({userid: userid}).$promise.then(function(data){
			$scope.user.tags = data;
			
			var tags = _.pluck(data, 'name');

			// If user role is accessing tags, render it in readonly mode
			if($scope.activeUser.role == 'user') {
				var tagsHtml = '';
				for (var i = 0; i < tags.length; i++) {
					tagsHtml += '<span class="example-tag">'+ tags[i] +'</span>';
				}
				$('#tag-view').html(tagsHtml);
			} else {
				$('#tag-input').importTags(tags.join(','));
			}
		});
	};
	
	ActiveUser.get().$promise.then(function(data){
		$scope.activeUser = data;
	}).then(function() {
		if($location.absUrl().match(/profile$/))
			loadUser($scope.activeUser.userid);
		else
			loadUser($scope.filterId);
	});
	
});
		
