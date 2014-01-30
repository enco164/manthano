/**
 * Created by Gera on
 */
var materialModule = angular.module('materialModule', []);

materialModule.controller('materialShow', ['$scope','$http', '$routeParams', '$location', function($scope, $http, $routeParams, $location){
    $scope.id = $routeParams.idMaterial;
    var path = '/materials/material_data/'+$scope.id ;
    $http.get(path).success(function(data){
        $scope.material = data;
       // $scope.deletematerialButton = "Delete material";
    }).error(function(data, status, header, config){
            window.alert("Requested material does not exist");
            history.back();
        });

    $scope.deletematerial = function(idMaterial){
        $http({
            method: 'DELETE',
            url: '/materials/material_data/'+$routeParams.idMaterial,
            data: {"idMaterial" : idMaterial}
        }).success(function(){
                window.alert("material is Deleted!");
                history.back();
            }).error(function(data, status, header, config){
                window.alert("Deleting material is not succesful!");
            });
    };
}]);


materialModule.controller('materialModify',['$scope', '$http', '$routeParams', '$location', function($scope, $http, $routeParams, $location){
    $scope.id = $routeParams.idMaterial;
    var path = '/materials/material_data/'+$scope.id ;
    $http.get(path).success(function(data, status, headers){
        $scope.material = data;
        console.log(data);
        $scope.etag = headers("Etag");
       // $scope.deletematerialButton = "Modify Material";
       $scope.editmaterialButton="Save Changes";
    }).error(function(data, status, header, config){
            window.alert("Requested material does not exist");
            //history.back();
        });


    $scope.saveChanges = function(){
        $http({
            method: 'PUT',
            url: '/materials/material_data/'+$routeParams.idMaterial,
            data: {"id":$routeParams.idMaterial, "Name":$scope.material.Name, "URI":$scope.material.URI, "Type":$scope.material.Type ,"Date":$scope.material.Date , "Etag":$scope.etag,"OwnerID":globalUID}
        }).success(function(){
                window.alert("material is sucessfully modified!");
                //history.back();
            }).error(function(data, status, header, config){
                window.alert("Modifying material is not sucessful!");
            });
    };


}]);








materialModule.controller('materialNew',['$scope','$http','$routeParams','$location', function($scope, $http, $routeParams, $location){
    $scope.nameOfActivity = $routeParams.nameActivity;
    $scope.idMaterial = $routeParams.idMaterial;
    $http.get('/materials/material_data/'+ $scope.idMaterial).success(function(data){
        if(!data.check && data.exist){
            history.back();
        }
    });
/*    $scope.getmaterials = function(){
        $http.get('materials/material_data/'+$routeParams.idMaterial).success(function(data){
            $scope.materialsShort = data;
            $scope.loading = "";
        }).error(function(data, status, header, confihg){
                alert("smth wrong in materialNew" + status)
            });
    };*/
    $scope.choise = true;
    $scope.noviEnko = function (){
        $scope.choise = true;
    };

    $scope.postojeciEnko = function (){
        $scope.choise = false;
    };
    $scope.getmaterials();
//    $scope.materialName = "";
//    $scope.materialDescription = "";
//    $scope.materialVenue = "";
//    $scope.materialTime = "";
//    $scope.materialDate = "";
    $scope.addNew = function(materialName, materialURI, materialType, materialDate){
        $http({
            method: 'POST',
            url: '/materials/material_data/'+$scope.idMaterial,
            data: {"Name":materialName, "URI":materialURI, "Type":materialType, "Date":materialDate}
        }).success(function(data){
                window.alert("material succesfully added!");
                $location.path("/activity/"+$scope.idMaterial);
            }).error(function(data, status, header, config){
                window.alert("material adding error!");
            });
    };



}]);