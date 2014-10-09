var add_question = angular.module('add_question',[]);
add_question.controller('add_questionCtrl',function($scope) {

  $scope.options = [
    {text:'',correct:false,id:0},
    {text:'',correct:false,id:1},
    {text:'',correct:false,id:2},
    {text:'',correct:false,id:3}
  ];

  $scope.add_filed = function() {
    var id=$scope.options.length;
    $scope.options.push({text:'',correct:false,id:id});
    console.log($scope.options);
  }
});