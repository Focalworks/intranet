/**
 * Created by amitav on 11/6/14.
 */
function listingCtrl($scope,assessmentFactory) {
    $scope.title = 'Check';
    console.log($scope.title);
    assessmentFactory.getMultiple().then(function(data) {
        $scope.questions = data.data;
    });
};
listingCtrl.$inject = ['$scope', 'assessmentFactory'];
