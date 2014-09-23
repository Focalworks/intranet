/**
 * Created by amitav on 22/9/14.
 */
var kanbanize = angular.module('kanbanize', ['ngRoute']);

kanbanize.config(['$routeProvider', function($routeProvider) {
    $routeProvider.when("/kanban/projects", {templateUrl: "kanban-api/templates/project-list", pageTitle: "Your kanbanize projects"});
    $routeProvider.when("/kanban/{}/tickets", {templateUrl: "kanban-api/templates/tickets", pageTitle: "Tickets from this board"});
    $routeProvider.when("/kanban/import", {templateUrl: "admin/template/import", pageTitle: "Import expenses"});
    $routeProvider.otherwise({redirectTo: "/kanban/projects"});
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
        this.data = $http.get("http://localhost/focalworks-intranet/public/kanban/all-projects").then(function(projectData) {
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

kanbanize.controller('projectCtrl', function($scope, projectsFactory) {
    projectsFactory.get().then(function(data) {
        $scope.projects = data.data;
    });
});

kanbanize.controller('ticketCtrl', function($scope, projectsFactory) {

});