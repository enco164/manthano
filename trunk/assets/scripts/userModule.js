/**
 * Created by Stefan on 1/3/14.
 */
var userModule = angular.module('userModule', []);

userModule.controller('userInfo',['$scope','$http','$routeParams',function($scope, $http, $routeParams){
    $scope.id = $routeParams.id;
    $scope.globalUID = globalUID;
}]);