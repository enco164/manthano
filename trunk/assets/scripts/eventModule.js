/**
 * Created by Stefan on 1/3/14.
 */
var eventModule = angular.module('eventModule', []);

eventModule.controller('eventShow', ['$scope','$http', '$routeParams', '$location', function($scope, $http, $routeParams, $location){
    $scope.id = $routeParams.idEvent;
    var path = '/REST_event_b/event/'+$scope.id ;
    $http.get(path).success(function(data){
        $scope.event = data;
        $scope.deleteEventButton = "Obriši event";
    }).error(function(data, status, header, config){
           window.alert("Trazeni event ne postoji");
           history.back();
    });

$scope.deleteEvent = function(idEvent){
        $http({
            method: 'DELETE',
            url: '/REST_event_b/event/'+$routeParams.idEvent,
            data: {"idEvent" : idEvent}
        }).success(function(){
                window.alert("Event je uspešno obrisan!");
                history.back();
            }).error(function(data, status, header, config){
                window.alert("Event nije uspešno obrisan!");
            });
    };
}]);

eventModule.controller('eventModify',['$scope', '$http', '$routeParams', '$location', function($scope, $http, $routeParams, $location){
    $scope.id = $routeParams.idEvent;
    var path = '/REST_event_b/event/'+$scope.id ;
    $http.get(path).success(function(data, status, headers){
        $scope.event = data;
        $scope.etag = headers("Etag");
        $scope.deleteEventButton = "Obriši event";
        $scope.editEventButton="Sačuvaj izmene";
    }).error(function(data, status, header, config){
            window.alert("Trazeni event ne postoji");
            history.back();
        });
    $http.get('/REST_event_b/nonholder/'+$scope.id).success(function(data){
        $scope.nonholders = data;
    }).error(function(data, status, header, config){
            window.alert("smth wrong"+status);
        });
/* funckija za dugme sacuvaj promene */
    $scope.saveChanges = function(){
        $http({
            method: 'PUT',
            url: '/REST_event_b/event/'+$routeParams.idEvent,
            data: {"id":$routeParams.idEvent, "Name":$scope.event.Name, "Description":$scope.event.Description, "Date":$scope.event.Date ,"Venue":$scope.event.Venue, "Time":$scope.event.Time, "Etag":$scope.etag}
        }).success(function(){
                window.alert("Event je uspešno izmenjen!");
                history.back();
            }).error(function(data, status, header, config){
                window.alert("Event nije uspešno izmenjen!");
            });
    };
/* dodavanje holdera */
    $scope.addHolder = function(uid){
        $http({
            method: 'POST',
            url: '/REST_event_b/holder/'+$scope.id+'/'+uid
        }).success(function(data){
            }).error(function(data, status, header, config){
                window.alert("Adding holder error!");
            });
        /* refresovanje podataka vezanih za holdere */
        $http.get('/REST_event_b/nonholder/'+$scope.id).success(function (data){
            $scope.nonholders = data;
        });
        $http.get('/REST_event_b/holder/'+$scope.id+'/1').success(function(data){
            $scope.event.holders = data;
        });
    };

}]);

eventModule.controller('eventNew',['$scope','$http','$routeParams','$location', function($scope, $http, $routeParams, $location){
    $scope.nameOfActivity = $routeParams.nameActivity;
    $scope.idActivity = $routeParams.idActivity;
    $http.get('/check_service/activity/'+ $scope.idActivity).success(function(data){
        if(!data.check && data.exist){
            history.back();
        }
    });
    $scope.getEvents = function(){
        $http.get('REST_event_b/eventsshort/'+$routeParams.idActivity).success(function(data){
            $scope.eventsShort = data;
            $scope.event=data;
            $scope.loading = "";
        }).error(function(data, status, header, confihg){
                alert("smth wrong in eventNew" + status)
            });
    };
    $scope.choise = true;
    $scope.noviEnko = function (){
      $scope.choise = true;
        alertify.success("Vidi radi!");
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
            data: {"Name":$scope.event.Name, "Description":$scope.event.Description, "Venue":$scope.event.Venue, "Time":$scope.event.Time, "Date":$scope.event.Date}
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