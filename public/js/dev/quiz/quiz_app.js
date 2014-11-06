/**
 * Created by amitav on 27/10/14.
 */
var quizApp = angular.module('quizApp', ['ngRoute']);

quizApp.config(['$routeProvider', function($routeProvider) {
    $routeProvider.when("/quiz/list", {templateUrl: "quiz-api/templates/quiz-list", pageTitle: "List of Quiz"});
    $routeProvider.otherwise({pageTitle: "List of Quiz", redirectTo: "/quiz/list"});
}]);