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

eventModule.controller('eventNew',['$scope','$http','$routeParams','$location', function($scope, $http, $routeParams, $location){
    $scope.choises = [{Name: 'Novi', Value: true},{Name: 'Postojeci', Value: false}];
    $scope.nameOfActivity = $routeParams.nameActivity;
    $scope.idActivity = $routeParams.idActivity;
    $scope.getEvents = function(){
        $http.get('REST_event_b/eventsshort/'+$routeParams.idActivity).success(function(data){
            $scope.eventsShort = data;
            $scope.loading = "";
        }).error(function(data, status, header, confihg){
                windows.alert("smth wrong in eventNew" + status)
            });
    };
    $scope.getEvents();

    $scope.add = function( idEvent, idActivity){
        $http({
            method: 'POST',
            url: '/REST_activity_b/event/'+$routeParams.idActivity,
            data: {"idEvent" : idEvent , "idActivity": idActivity}
        }).success(function(data){
                $scope.loading = "loading...";
                $scope.getEvents();
            }).error(function(data, status, header, config){
                window.alert("smth wrong in scope.add");
            });
    };
    $scope.removeEvent = function( idEvent, idActivity){
            $http({
                method: 'DELETE',
                url: '/REST_activity_b/event/'+$routeParams.idActivity,
                data: {"idEvent" : idEvent , "idActivity": idActivity}
            }).success(function(){
                    $scope.loading = "loading...";
                    $scope.getEvents();
                }).error(function(data, status, header, config){
                    window.alert("smth wrong in scope.remove");
                });
    };
}]);