var activityModule = angular.module('activityModule', []);

// Load full information about activity into activityFull
activityModule.controller('activityShow', ['$scope','$http','$routeParams',function($scope, $http, $routeParams){
    var id = $routeParams.idActivity;
    var path = "REST.php/activity/"+id;
    $http.get(path).success(function(data){
        $scope.activity = data;
    });
    //TODO Write error function for $http
}]);
