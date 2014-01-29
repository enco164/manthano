/* angular module for activities */
var activityModule = angular.module('activityModule', []);

// Load full information about activity into activityFull
activityModule.controller('activityShow', ['$scope','$http','$routeParams','$location','$route',function($scope, $http, $routeParams, $location, $route){
    var id = $routeParams.idActivity;
    var path = "/REST_activity_b/activity/"+id;

    $http.get(path).success(function(data){
        // loading data in $scope
        $scope.activity = data;

        // getting parent id of activity
        // first we find length of array (length function wont work so we go brute)
        $scope.parentid = 0;
        for(i in $scope.activity.path){
            $scope.parentid++;
        }
        // Depend on length I find parent id.
        if($scope.parentid <= 1){
            $scope.parentid = $scope.activity.idActivity;
        }
        else{
            $scope.parentid = $scope.activity.path[$scope.parentid-2].idActivity;
        }
        // Setting variable for showing list or message
        if(typeof $scope.activity.participants == 'string' || $scope.activity.participants instanceof String)
            // There is no participants , show message
            $scope.showString = 1;
        else
            // There is some participants, show list
            $scope.showString = 0;
        // setting button text
        if($scope.activity.is_participant)
            $scope.enrollButtonName = "Odjavi se!";
        else
            $scope.enrollButtonName = "Prijavi se!";

        if(Object.keys($scope.activity.events).length === 0){
            $scope.hasEvents = 0;
        }
        else{
            $scope.hasEvents = 1;
        }
        $scope.is_holder = $scope.activity.is_holder;
    }).error(function(status, data, header, config){
            window.alert("Trazena aktivnost ne postoji!");
            history.back();
        });

    //TODO Write error function for $http
    // Actions when button for participating in Activity is clicked
    $scope.enroll = function(){
        if($scope.activity.is_participant)
        // I am participant in current activity so I need to stop being participant :)
        // Should be some kind of prompt.
            $http({
                method: 'DELETE',
                url: "/REST_activity_b/participant/"+id
            }).success(function(){
                    window.alert("Uspesno ste odjavljeni sa kursa!");
                    $route.reload();
                }).error(function(data, status, header, config){
                    window.alert ("Neuspesna odjava!");
                });
        else
        // I am not participant so I need to become one.
            $http({
                method: 'PUT',
                url: "/REST_activity_b/participant/"+id
            }).success(function(data){
                    window.alert("Uspesno ste se prijavili za kurs ");
                    $route.reload();
                }).error(function(data, status, headers, config){
                    window.alert("Neuspesna prijava! ");
                });
    };
    // dummy button actions for showing extra options
    $scope.changePrivilege = function(){ $scope.is_holder = !$scope.is_holder; };

    $scope.deleteActivity = function(){
        if(confirm("Da li ste sigurni da zelite da obrisete kurs"+$scope.activity.name+" i sve njegove podkurseve?")){
            $http({
                method: 'DELETE',
                url: path
            }).success(function(){
                    $scope.message = "Kurs obrisan!";
                    $location.path("/activity/"+$scope.parentid);
                }).error(function(data, status, header, config){
                    $scope.message = "Kurs nije obrisan!";
                });
        }
    };

}]);
// controller for creating new activity
activityModule.controller('activityNew', ['$scope','$http','$routeParams','$location',function($scope, $http, $routeParams, $location){

    var idt = $routeParams.idActivity;
    var path = "/REST_activity_b/activity/"+idt;
    $http.get('/check_service/activity/'+idt).success(function(data){
        if(!data.check && data.exist){
            history.back();
        }
    });
    /* button "add Activity" action  */
    $scope.addActivity = function(){
        $http({
            method: 'POST',
            url: path,
            data: {"Name":$scope.activityName, "Description":$scope.activityDescription, "Cover":$scope.activityCover, "id":idt, "Date":$scope.activityDate}
        }).success(function(data){
                $scope.message = "Activity added successfully!";
                $location.path("/activity/"+idt);
            }).error(function(data, status, header, config){
                $scope.message = "Activity add error!"
            });
    };

}]);
// controller for editing activity.
activityModule.controller('activityModify',['$scope','$http','$routeParams','$location',function($scope, $http, $routeParams, $location){
    var id = $routeParams.idActivity;
    var path = "/REST_activity_b/activity/"+id;

    //check if user have permissions to access this page
    $http.get('/check_service/activity/'+id).success(function(data){
        if(!data.check && data.exist){
            history.back();
        }
    });


    $http.get(path).success(function(data, status, headers){
        $scope.activity = data;
        $scope.etag = headers("Etag");
    });
    // getting data for select
    $http.get('/REST_activity_b/activities/').success(function(data, status, headers){
        $scope.activities = data;
    }).error(function(){
            window.alert("greska pri ucitavanju svih aktivitija");
    });

    $http.get('/REST_activity_b/holder/'+id+'/1').success(function(data){
       $scope.holders = data;
    });

    $http.get('/REST_activity_b/nonholder/'+id).success(function (data){
        $scope.users = data;
    });

    // move activity button
    $scope.moveActivity = function(){
        $http({
            method: 'PUT',
            url: '/REST_activity_b/place/'+id+'/'+ $scope.choosenActivity.idActivity
        }).success(function(data){
                $scope.message = "Activity moved successfully!";
                $location.path("/activity/"+id);
            }).error(function(data, status, header, config){
                $scope.message = "Activity move error!"
            });
    };

    /* button "modify Activity" action */
    $scope.modifyActivity = function(){
        $http({
            method: 'PUT',
            url: path,
            data: {"Name":$scope.activity.name, "Description":$scope.activity.description, "Cover":$scope.activity.cover, "id":$scope.activity.id, "Date":$scope.activity.date, "Etag":$scope.etag}
        }).success(function(data){
                window.alert("Activity successfully updated! ");
                $location.path("/activity/"+id);
            }).error(function(data, status, headers, config){
                window.alert("Activity unsuccessfully updated! ");
            });
    };

    $scope.removeEvent = function(idEvent){
        $http({
            method: 'DELETE',
            url: '/REST_activity_b/event/'+id,
            data: {"idEvent" : idEvent , "idActivity": id}
        }).success(function(){
                $scope.message = "Ok!";
            }).error(function(data, status, header, config){
                window.alert("smth wrong in scope.remove");
            });

        $http.get(path).success(function(data, status, headers){
            $scope.activity = data;
        });
    };

    $scope.addHolder = function(uid){
        $http({
            method: 'POST',
            url: '/REST_activity_b/holder/'+id+'/'+uid
        }).success(function(data){
            }).error(function(data, status, header, config){
                window.alert("Adding holder error!");
        });
        $http.get('/REST_activity_b/nonholder/'+id).success(function (data){
            $scope.users = data;
        });
        $http.get('/REST_activity_b/holder/'+id+'/1').success(function(data){
            $scope.holders = data;
        });
    };

    $scope.removeHolder = function(uid){
        $http({
            method: 'DELETE',
            url: '/REST_activity_b/holder/'+id+'/'+uid
        }).success(function(data){
            }).error(function(data, status, header, config){
                window.alert("Removing holder error!");
            });
        $http.get('/REST_activity_b/holder/'+id+'/1').success(function(data){
            $scope.holders = data;
        });
        $http.get('/REST_activity_b/nonholder/'+id).success(function (data){
            $scope.users = data;
        });
    };

}]);

activityModule.controller('activityFoo',['$scope','socket',function($scope, socket){
    $scope.basic_info = "Ovo je sample foo informacija";
    $scope.info = "Ovo je neka druga informacija";
}]);