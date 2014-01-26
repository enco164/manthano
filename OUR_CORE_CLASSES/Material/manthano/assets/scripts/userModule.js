/**
 * Created by Stefan on 1/3/14.
 */
var userModule = angular.module('userModule', []);

userModule.controller('userInfo',['$scope','$http','$routeParams',function($scope, $http, $routeParams){
    $scope.id = $routeParams.id;
    $scope.globalUID = globalUID;
    var id = $routeParams.id;
    var path = "/REST_user/personal_data/"+id;



    $http.get(path).success(function(data){
        // loading data in $scope
        $scope.userdata = data;
        console.log($scope.userdata);
        if(typeof $scope.userdata.username == 'string' || $scope.userdata.username instanceof String)
        // There is no participants , show message
            $scope.showString = 1;
        else
        // There is some participants, show list
            $scope.showString = 0;
        // setting button text
        if(globalUID == id || globalAccType==99){
            $scope.canEdit = 1;
        }
        else{
            $scope.canEdit = 0;
        }
    });
}]);

userModule.controller('userSettings',['$scope','$http','$routeParams',function($scope, $http, $routeParams){
    $scope.id = $routeParams.id;
    $scope.globalUID = globalUID;
    var id = $routeParams.id;
    var path = "/REST_user/personal_data/"+id;



    $http.get(path).success(function(data){
        // loading data in $scope
        $scope.userdata = data;
        //console.log($scope.userdata);
        if(typeof $scope.userdata.username == 'string' || $scope.userdata.username instanceof String)
        // There is no participants , show message
            $scope.showString = 1;
        else
        // There is some participants, show list
            $scope.showString = 0;
        // setting button text
        if(globalUID == id || globalAccType==99){
            $scope.canEdit = 1;
        }
        else{
            $scope.canEdit = 0;
        }
    });

    //if canEdit = 1 than you can modify personal data

    //TODO Write error function for $http
    // Actions when button for participating in Activity is clicked
    $scope.editSettings = function(){
        //console.log(JSON.stringify($('#user_settings').serializeArray()));
        if($scope.canEdit)
            $http({
                method: 'PUT',
                url: "/REST_user/personal_data/"+id,
                data:$('#user_settings').serializeObject(),
                dataType:'json'
            }).success(function(data){
                    console.log(data);
                    window.alert("Uspesno ste izmenili podatke");
                    $route.reload();
                }).error(function(data, status, headers, config){
                    console.log(data);
                    window.alert("Neuspele izmene!");
                });
    };
    // dummy button actions for showing extra options

}]);
