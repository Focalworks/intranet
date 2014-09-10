var articleApp = angular.module('articleApp', ['Comments']);

articleApp.config(function($interpolateProvider) {
  $interpolateProvider.startSymbol('([');
  $interpolateProvider.endSymbol('])');

});

articleApp.controller("mainCntrl", function($rootScope, $scope) {
	// alert(1);
});