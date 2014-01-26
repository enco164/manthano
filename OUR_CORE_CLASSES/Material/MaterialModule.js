/* angular module for Materials */
var materialModule = angular.module('materialModule', []);

// Load full information about material 
materialModule.controller('materialShow', ['$scope','$http','$routeParams','$location',function($scope, $http, $routeParams, $location){
    var id = $routeParams.idMaterial;
    var path = "material_b_rest.php/material/"+id;
    $http.get(path).success(function(data){
        $scope.material = data;
        $scope.parentid = 0;

        for(i in $scope.material.path){
            $scope.parentid++;
        }
        if($scope.parentid <= 1){
            $scope.parentid = $scope.material.idMaterial;
        }
        else{
            $scope.parentid = $scope.material.path[$scope.parentid-2].idMaterial;
        }

    });
    /* TODO Add is_holder in rest response for display purposes */
    /* if is_holder = 1 than you can modify and delete and add Materials
    * otherwise you can't*/
    $scope.is_holder = 1;
    //TODO Write error function for $http

    $scope.deleteMaterial = function(){
        if(confirm("are you sure you want to delete material?"+$scope.material.name)){
            $http({
                method: 'DELETE',
                url: path
            }).success(function(){
                    $scope.message = "Material Deleted!";
                    $location.path("/material/"+$scope.parentid);
                }).error(function(data, status, header, config){
                    $scope.message = "Material is not Deleted!";
                });
        }
    };

}]);

materialModule.controller('materialNew', ['$scope','$http','$routeParams','$location',function($scope, $http, $routeParams, $location){
    var idt = $routeParams.idActivity;
    var path = "material_b_rest.php/material/"+idt;
    /* button "add material" action  */
    $scope.addMaterial = function(){
        $http({
            method: 'POST',
            url: path,
            data: {"Name":$scope.materialName, "URI":$scope.materialURI, "Type":$scope.materialType, "id":idt, "Date":$scope.materialDate}
        }).success(function(data){
                $scope.message = "material added successfully!";
                $location.path("/material/"+idt);
            }).error(function(data, status, header, config){
                $scope.message = "material insert Error!"
            });
    };

}]);

materialModule.controller('materialModify',['$scope','$http','$routeParams','$location',function($scope, $http, $routeParams, $location){
    var id = $routeParams.idMaterial;
    var path = "material_b_rest.php/material/"+id;
    $http.get(path).success(function(data, status, headers){
        $scope.material = data;
        $scope.etag = headers("Etag");
    });
    /* button "modify material" action */
    $scope.modifyActivity = function(){
      $http({
          method: 'PUT',
          url: path,
          data: {"Name":$scope.material.name, "URI":$scope.material.URI, "Type":$scope.material.Type, "id":$scope.material.id, "Date":$scope.material.date, "Etag":$scope.etag}
      }).success(function(data){
            window.alert("material successfully updated! ");
            $location.path("/material/"+id);
      }).error(function(data, status, headers, config){
            window.alert("material update unsuccessful! ");
      });
    };


}]);

materialModule.controller('materialFoo',['$scope',function($scope){
	$scope.basic_info = "Ovo je sample foo informacija";
	$scope.info = "Ovo je neka druga informacija";
}]);