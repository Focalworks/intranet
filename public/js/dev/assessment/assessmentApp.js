/**
 * Created by amitav on 11/6/14.
 */
var assessment = angular.module('assessment', ['ngRoute']);

/**
 * Handling the routes for the application
 */
assessment.config(['$routeProvider', function($routeProvider) {
    $routeProvider.when('/list', {
        templateUrl: base_url + "assessment/template/land",
        controller: 'listingCtrl'
    }).otherwise({
        redirectTo:'/list'
    });
}]);