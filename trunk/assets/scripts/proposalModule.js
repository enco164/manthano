/* angular module for activities */
var proposalModule = angular.module('proposalModule', []);

// Load full information about activity into activityFull
proposalModule.controller('proposalInfo', ['$scope','$http','$routeParams','$location','$route',function($scope, $http, $routeParams, $location, $route){
    var id = $routeParams.idProposal;
    var path = "/REST_proposal/proposal/"+id;
    $http.get(path).success(function(data){
        $scope.proposal = data;

        // setting button text
        if($scope.proposal.is_support)
            $scope.enrollButtonName = "Odjavi se";
        else
            $scope.enrollButtonName = "Podrži predlog";

        // setting button text
        if($scope.proposal.is_owner)
            $scope.addMeButtonName = "Neću da budem vlasnik";
        else
            $scope.addMeButtonName = "Hoću da budem vlasnik";
    });

    // Actions when button for add support to proposal clicked
    $scope.addsupport = function(){
        if($scope.proposal.is_support)
        {
        // only delete support from proposal
            $http({
                method: 'DELETE',
                url: "/REST_proposal/proposalsupport/" + id,
                data: '{ "Proposal"  : ' + id + '}'
            }).success(function(){
                    window.alert("Uspešno ste se odjavili sa predloga!");
                    $route.reload();
                }).error(function(data, status, header, config){
                    window.alert ("Neuspesna odjava sa predloga!");
                });
        }
        else
        {
            var data = '{'
                +'"Proposal"  : ' + $scope.proposal.idProposal
                +'}';
        // add support to proposal
            $http({
                method: 'POST',
                url: "/REST_proposal/proposalsupport/" + id,
                data: '{ "Proposal"  : ' + id + '}'
            }).success(function(data){
                    window.alert("Uspešno ste podržali predlog!");
                    $route.reload();
                }).error(function(data, status, headers, config){
                    window.alert("Neuspešna pokušaj podrške predloga! ");
                });
        }
    };

    $scope.addmeowner = function(){
        if($scope.proposal.is_owner)
        {
            // only delete support from proposal
            $http({
                method: 'DELETE',
                url: "/REST_proposal/proposalowner/" + id,
                data: '{ "Proposal"  : ' + id + '}'
            }).success(function(){
                    window.alert("Uspešno ste se odjavili za vlasnika!");
                    $route.reload();
                }).error(function(data, status, header, config){
                    window.alert ("Neuspesna odjava za vlasnika!");
                });
        }
        else
        {
            var data = '{'
                +'"Proposal"  : ' + $scope.proposal.idProposal
                +'}';
            // add support to proposal
            $http({
                method: 'POST',
                url: "/REST_proposal/proposalowner/" + id,
                data: '{ "Proposal"  : ' + id + '}'
            }).success(function(data){
                    window.alert("Uspešno ste se prijavili za vlasnika!");
                    $route.reload();
                }).error(function(data, status, headers, config){
                    window.alert("Neuspešna pokušaj prijave za vlasnika! ");
                });
        }
    };
}]);

proposalModule.controller('proposalNew', ['$scope','$http','$routeParams','$location',function($scope, $http, $routeParams, $location){
    var path = "/REST_proposal/proposal/0";
    $http.get(path).success(function(data, status, headers){
        $scope.proposal = data;
        $scope.etag = headers("Etag");
    });

    /* button "modify Activity" action */
    $scope.newProposal = function(){
        $http({
            method: 'POST',
            url: path,
            data: {"Name":$scope.proposal.name, "Description":$scope.proposal.description, "Etag":$scope.etag}
        }).success(function(data){
                window.alert("Predlog je uspešno dodat! ");
                $location.path("/proposals");
            }).error(function(data, status, headers, config){
                window.alert("Predlog nije uspešno dodat! ");
            });
    };
}]);

proposalModule.controller('proposalModify',['$scope','$http','$routeParams','$location',function($scope, $http, $routeParams, $location){
    var id = $routeParams.idProposal;
    var path = "/REST_proposal/proposal/"+id;
    $http.get(path).success(function(data, status, headers){
        $scope.proposal = data;
        $scope.etag = headers("Etag");
        });

    /* button "modify Activity" action */
    $scope.modifyProposal = function(){
        $http({
            method: 'PUT',
            url: path,
            data: {"User": $scope.proposal.user_proposed, "Name":$scope.proposal.name, "Description":$scope.proposal.description, "Etag":$scope.etag}
        }).success(function(data){
                window.alert("Predlog je uspešno izmenjen! ");
                $location.path("/proposal/"+id);
            }).error(function(data, status, headers, config){
                window.alert("Predlog nije uspešno izmenjen! ");
            });
    };
}]);

proposalModule.controller('proposalList',['$scope','$http','$routeParams','$location',function($scope, $http, $routeParams, $location){
    var path = "/REST_proposal/proposallist";
    $http.get(path).success(function(data){
        $scope.proposallist=data;
    });
}]);

proposalModule.controller('proposalFoo',['$scope',function($scope){
    $scope.basic_info = "Ovo je sample foo informacija";
    $scope.info = "Ovo je neka druga informacija";
}]);