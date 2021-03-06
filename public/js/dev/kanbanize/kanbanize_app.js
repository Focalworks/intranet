/**
 * Created by amitav on 22/9/14.
 */
var kanbanize = angular.module('kanbanize', ['ngRoute']);

kanbanize.config(['$routeProvider', function($routeProvider) {
    $routeProvider.when("/kanban/projects", {templateUrl: "kanban-api/templates/project-list", pageTitle: "Your kanbanize projects"});
    $routeProvider.when("/kanban/:board/tickets", {templateUrl: "kanban-api/templates/tickets", pageTitle: "Tickets from this board"});
    $routeProvider.when("/kanban/import", {templateUrl: "admin/template/import", pageTitle: "Import expenses"});
    $routeProvider.otherwise({pageTitle: "Your kanbanize projects", redirectTo: "/kanban/projects"});
}]);

kanbanize.run(['$location', '$rootScope', function($location,$rootScope) {
    $rootScope.$on('$routeChangeSuccess', function(event, current, previous) {
        $rootScope.pageTitle = current.$$route.pageTitle;
    });
}]);

kanbanize.factory('projectsFactory', ['$http', function($http) {
    var projects = {};

    projects.data = "";

    /**
     * This function will be called if the data is not already loaded in factory object.
     * @returns {string}
     */
    projects.load = function() {
        this.data = $http.get(base_url + "kanban/all-projects").then(function(projectData) {
            return projectData;
        });
        return this.data;
    };

    /**
     * This function will check if the data is present in factory object.
     * If not present, we will query the Db and fetch
     * else return the already fetched data and save DB query
     * @returns {string}
     */
    projects.get = function() {
        return this.data === '' ? this.load() : this.data;
    };

    return projects;
}]);

kanbanize.factory('ticketsFactory',  ['$http', function($http) {
    var tickets = {};

    tickets.data = "";

    /**
     * function to get ticket list
     * @returns {string}
     */

    tickets.load = function(bid) {
        this.data = $http.get("http://localhost/focalworks-intranet/public/kanban/all-tickets?bid="+bid).then(function(ticketsData){
            return ticketsData;
        });
        return this.data;
    };

    tickets.get = function(bid) {

        if (this.data === '') {
            return this.load(bid);
        } else {
            return this.data;
        }
    };

    return tickets;
}]);

function projectCtrl($scope, projectsFactory) {
    projectsFactory.get().then(function(data) {
        $scope.projects = data.data;
    });
}
projectCtrl.$inject = ['$scope', 'projectsFactory'];

function ticketCtrl($scope, $routeParams, ticketsFactory) {
    $scope.boardId = $routeParams.board;
    ticketsFactory.load($scope.boardId).then(function(data){
        $scope.tickets = data.data;
    });
}
ticketCtrl.$inject = ['$scope', '$routeParams', 'ticketsFactory'];