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
    $scope.choise = true;
    $scope.noviEnko = function (){
      $scope.choise = true;
    };

    $scope.postojeciEnko = function (){
        $scope.choise = false;
    };
    $scope.getEvents();
//    $scope.eventName = "";
//    $scope.eventDescription = "";
//    $scope.eventVenue = "";
//    $scope.eventTime = "";
//    $scope.eventDate = "";
    $scope.addNew = function(eventName, eventDescription, eventVenue, eventTime, eventDate){
        $http({
            method: 'POST',
            url: '/REST_event_b/event/'+$scope.idActivity,
            data: {"Name":eventName, "Description":eventDescription, "Venue":eventVenue, "Time":eventTime, "Date":eventDate}
        }).success(function(data){
                window.alert("event succesfully added!");
                $location.path("/activity/"+$scope.idActivity);
            }).error(function(data, status, header, config){
                window.alert("Event adding error!");
            });
    };
    // adding existing event to current activity
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
    // removing existing event from current activity
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