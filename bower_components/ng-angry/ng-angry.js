/**
* Angry-Angular
*
* MC's why-the-hell-isnt-this-part-of-core Angular hacks
*
* @url https://github.com/hash-bang/Angry-ng
* @author Matt Carter <m@ttcarter.com>
*
*/
// Template global 'app' object {{{
/*
var app = angular.module('app', ['restangular', 'ngResource']);

app.controller('appController', function($scope) {
});
*/
// }}}
// Filter: format - General text formatting via callback {{{
/**
* Provides a simple format filter that allows you to easily format output
* e.g. {{item.name | format:aFunctionSomewhere}} - aFunctionSomewhere will get called with the piped value and return a formatted string.
*
* For example:
*
* In your controller:
*	$scope.ucfirst = function(value) {
*		return value.substr(0,1).strToUpper() + value.substr(1);
*	}
*
*
* In your templating system somewhere:
*
* 	{{item.name | format:ucfirst}}
*
*/
app.filter('format', function() {
	return function(value, callback) {
		if (value)
			return callback(value);
	};
});
// }}}
// Filter: ucwords - First letter caps on all words in a sentence {{{
/**
* Provides a simple filter to transform text so that all first letters in words is in upper case
*
* For example:
*
* In your controller:
*	$scope.foo = 'hello world'
*
* In your templating system:
*	{{foo | ucwords}}
*
* Will output: 'Hello World'
*/
app.filter('ucwords', function() {
	return function(value) {
		if (!value)
			return;
		return value.replace(/\b([a-z])/g, function(all,first) {
			return first.toUpperCase();
		});
	};
});
// }}}
// Filter: fromEpoc - Convert Unix epocs into data objects {{{
/**
* Converts a Unix epoc into a Javascript data object
* This function is usually used in pipelines to format an incomming epoc value with a suitable date format
* In your controller:
*	$scope.foo = 1397274589;
*
* In your templating system:
*	{{foo | fromEpoc | date}}
*
* Will output: 'Apr 12, 2014' (see the 'date' filter docs for other formats)
*/
app.filter('fromEpoc', function() {
	return function(value) {
		if (!value)
			return;
		var date = new Date(value*1000);
		return date;
	};
});
// }}}
// Directive: ng-finished {{{
/**
* Runs a function within scope when rendering has completed
*
* For example:
*
* In your controller:
*	...
*	$scope.finishedRendering = function() {
*		console.log('Done rendering!');
*	};
*
* In your templating system:
*
*	<div ng-repeat="user in users" ng-finished="finishedRendering()"></div>
*
*
* This directive will also emit the 'onFinished' event so it can also be watched in the controller like this:
*
*	$scope.$on('onFinished', function(e) {
*		console.log('Done rendering!');
*	});
*/
app.directive('ngFinished', function($timeout) {
	return {
		restrict: 'A',
		link: function (scope, element, attrs) {
			if (scope.$last === true) {
				$timeout(function () {
					scope.$eval(attrs.ngFinished);
					scope.$emit('onFinished');
				});
			}
		}
	};
});
// }}}
