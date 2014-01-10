/**
 * Created by Stefan on 1/3/14.
 */
var eventModule = angular.module('eventModule', []);

eventModule.controller('eventShow', ['$scope','$http', '$routeParams', '$location', function($scope, $http, $routeParams, $location){
    var id = $routeParams.idEvent;
    var path = '/REST_event_b/event/'+id;
    $http.get(path).success(function(data){
        $scope.event = data;
    }).error(function(data, status, header, config){
           window.alert("smth wrong");
        });
}]);

eventModule.controller('eventModify',['$scope', '$http', '$routeParams', '$location', function($scope, $http, $routeParams, $location){
    var id = $routeParams.idEvent;
    $http.get('/REST_activity_b/activities/').success(function(data){
        $scope.listOfActivities = data;
    }).error(function(data, status, header, config){
            window.alert("smth wrong"+status);
        });

}]);