/**
 * Created by amitav on 11/6/14.
 */
var assessment = angular.module('assessment', ['ngRoute']);

/**
 * Handling the routes for the application
 */
assessment.config(['$routeProvider', function($routeProvider) {
    /*$routeProvider.when('list',{
        controller: 'listingCtrl',
        templateUrl: base_url + "assessment/template/land",
        pageTitle: "List of Assessments"});*/

    $routeProvider.when('list', {
        controller: 'listingCtrl',
        templateUrl: base_url + "assessment/template/land"
    }).otherwise({
        redirectTo:'list'
    });

    $routeProvider.otherwise({redirectTo: "list"});
}]);