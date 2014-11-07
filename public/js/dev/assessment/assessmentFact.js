/**
 * Created by amitav on 11/6/14.
 */
var assessmentGetMultiple = base_url + 'assessment-api/test';
assessment.factory('assessmentFactory', ['$http', function($http) {
    var assessments = {};
    assessments.getMultiple = function() {
        return $http.get(assessmentGetMultiple).then(function(data) {
            return data;
        })
    };
    return assessments;
}]);