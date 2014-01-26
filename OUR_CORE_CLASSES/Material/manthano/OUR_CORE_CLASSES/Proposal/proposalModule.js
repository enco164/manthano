/* angular module for activities */
var proposalModule = angular.module('proposalModule', []);

// Load full information about activity into activityFull
proposalModule.controller('proposalInfo', ['$scope','$http','$routeParams','$location',function($scope, $http, $routeParams, $location){
    var id = $routeParams.idProposal;
    var path = "http://localhost/REST_proposal/proposal/"+id;
    $http.get(path).success(function(data){
        $scope.proposal = data;
    });
}]);

proposalModule.controller('proposalNew', ['$scope','$http','$routeParams','$location',function($scope, $http, $routeParams, $location){

}]);

proposalModule.controller('proposalModify',['$scope','$http','$routeParams','$location',function($scope, $http, $routeParams, $location){

}]);

proposalModule.controller('proposalFoo',['$scope',function($scope){
	$scope.basic_info = "Ovo je sample foo informacija";
	$scope.info = "Ovo je neka druga informacija";
}]);