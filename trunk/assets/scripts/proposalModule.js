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

        $scope.addOwnerButtonName = "Predloži vlasnika";
        $scope.addSupportButtonName = "Predloži podršku";


    });

    $http.get('/REST_proposal/nonusers/'+id).success(function (data){
        $scope.users = data;
    });

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

    function getObjects(obj, key, val)
    {
        var objects = [];
        for (var i in obj) {
            if (!obj.hasOwnProperty(i)) continue;
            if (typeof obj[i] == 'object') {
                objects = objects.concat(getObjects(obj[i], key, val));
            } else if (i == key && obj[key] == val) {
                objects.push(obj);
            }
        }
        return objects;
    }
    //add support
    $scope.addOwnerUser = function(uid){
            // add support to proposal
            $http({
                method: 'POST',
                url: "/REST_proposal/proposalowner/" + id,
                data: {"UserProposed":uid, "Proposal":id }
            }).success(function(data){
                    window.alert("Uspešno ste prijavili korisnika za vlasnika!");
                    $route.reload();
                }).error(function(data, status, headers, config){
                    window.alert("Neuspešna pokušaj prijave korisnika za vlasnika! ");
                });
    };

    $scope.addSupportUser = function(uid)
    {
        // add support to proposal
        $http({
            method: 'POST',
            url: "/REST_proposal/proposalsupport/" + id,
            data: {"User":uid, "Proposal":id }
        }).success(function(data){
                window.alert("Uspešno ste prijavili korisnika za podršku!");
                $route.reload();
            }).error(function(data, status, headers, config){
                window.alert("Neuspešan pokušaj prijave korisnika za podršku! ");
            });
    };

    $scope.deleteSupportUser = function(uid)
    {
        // delete support from proposal
        $http({
            method: 'DELETE',
            url: "/REST_proposal/proposalsupport/" + id,
            data: {"User":uid, "Proposal":id }
        }).success(function(data){
                window.alert("Uspešno ste odjavili korisnika za podršku!");
                $route.reload();
            }).error(function(data, status, headers, config){
                window.alert("Neuspešan pokušaj odjave korisnika za podršku! ");
            });
    };

    //add support
    $scope.deleteOwnerUser = function(uid){
        // add support to proposal
        $http({
            method: 'DELETE',
            url: "/REST_proposal/proposalowner/" + id,
            data: {"UserProposed":uid, "Proposal":id }
        }).success(function(data){
                window.alert("Uspešno ste odjavili korisnika za vlasnika!");
                $route.reload();
            }).error(function(data, status, headers, config){
                window.alert("Neuspešna pokušaj odjave korisnika za vlasnika! ");
            });
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
        $scope.name_surname= $scope.proposal.user_proposed.Name + " " + $scope.proposal.user_proposed.Surname;
        });

    /* button "modify Activity" action */
    $scope.modifyProposal = function(){
        $http({
            method: 'PUT',
            url: path,
            data: {"User": $scope.proposal.user_proposed.user_id, "Name":$scope.proposal.name, "Description":$scope.proposal.description, "Etag":$scope.etag}
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
    $http.get(path).success(function(data, status, headers){
        $scope.proposallist=data;
        $scope.counttext = null;
    });
}]);

proposalModule.controller('proposalMove',['$scope','$http','$routeParams','$location', 'mail',function($scope, $http, $routeParams, $location, mail){
    var id = $routeParams.idProposal;
    var path = "/REST_proposal/proposal/" + id;
    $http.get(path).success(function(data, status, headers)
    {
        $scope.proposal=data;
        $scope.etag = headers("Etag");
        $scope.name_surname= $scope.proposal.user_proposed.Name + " " + $scope.proposal.user_proposed.Surname;

    });

    // getting data for select
    $http.get('/REST_activity_b/activities/').success(function(data, status, headers){
        $scope.activities = data;
        $scope.choosenActivity=$scope.activities[0];
    }).error(function(){
            window.alert("greska pri ucitavanju svih aktivitija");
    });
    $scope.ids = new Array();

    $scope.moveProposal = function(idAct, chosenD){
        $http({
            method: 'POST',
            url: "/REST_activity_b/activity/" + $scope.choosenActivity.idActivity,
            data: {"id":idAct, "Name":$scope.proposal.name, "Description":$scope.proposal.description, "Date":chosenD, "Cover":"/assets/img/math.jpg", "Etag":$scope.etag}
        }).success(function(data){
                $scope.proposal.support.forEach(function(entry) {
                    $scope.ids.push(entry.id_user);
                });
                var emailBody = "Obaveštavamo vas da je predlog " + $scope.proposal.name + " realizovan!";
                mail.sendMail($scope.proposal.name,  emailBody, $scope.ids);
                //console.log($scope.ids);
                //alert($scope.ids);
                $location.path("/proposal/"+id);
            }).error(function(data, status, headers, config){
                window.alert("Predlog nije uspešno prebačen u activity! ");
            });
    };
}]);

proposalModule.controller('proposalFoo',['$scope',function($scope){
    $scope.basic_info = "Ovo je sample foo informacija";
    $scope.info = "Ovo je neka druga informacija";
}]);