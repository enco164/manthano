var activityModule = angular.module('activityModule', []);

// Load full information about activity into activityFull
activityModule.controller('activityShow', ['$scope','$http','$routeParams','$location',function($scope, $http, $routeParams, $location){
    var id = $routeParams.idActivity;
    var path = "activity_b_rest.php/activity/"+id;
    $http.get(path).success(function(data){
        $scope.activity = data;
        $scope.parentid = 0;
        for(i in $scope.activity.path){
            $scope.parentid++;
        }
        if($scope.parentid <= 1){
            $scope.parentid = $scope.activity.idActivity;
        }
        else{
            $scope.parentid = $scope.activity.path[$scope.parentid-2].idActivity;
        }

    });
    //TODO Write error function for $http

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

activityModule.controller('activityNew', ['$scope','$http','$routeParams','$location',function($scope, $http, $routeParams, $location){
    var idt = $routeParams.idActivity;
    var path = "activity_b_rest.php/activity/"+idt;
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

activityModule.controller('activityModify',['$scope','$http','$routeParams','$location',function($scope, $http, $routeParams, $location){
    var id = $routeParams.idActivity;
    var path = "activity_b_rest.php/activity/"+id;
    $http.get(path).success(function(data, status, headers){
        $scope.activity = data;
        $scope.etag = headers("Etag");
    });
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


}]);

activityModule.controller('activityFoo',['$scope',function($scope){
	$scope.basic_info = "Ovo je sample foo informacija";
	$scope.info = "Ovo je neka druga informacija";
}]);