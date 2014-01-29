
/* creating our module, mainly for adding $routeProvider which can be added only with config method *
 manthanoApp is root module!!
 */
var manthanoApp = angular.module('manthanoApp', ['ngRoute', 'activityModule','userModule','eventModule','materialModule','proposalModule']);
/* Route setting for templates in our main module */
manthanoApp.config(['$routeProvider', function ($routerProvider){
    $routerProvider.
        // Settings for activity
        when('/activity/:idActivity', {
            templateUrl: '/assets/html_fragments/activityInfo.html',
            controller: 'activityShow'
        }).
        when('/activity/modify/:idActivity',{
            templateUrl: '/assets/html_fragments/activityModify.html',
            controller: 'activityModify'
        }).
        when('/activity/new/:idActivity', {
            templateUrl: '/assets/html_fragments/activityNew.html',
            controller: 'activityNew'
        }).
        when('/event/:idEvent', {
            templateUrl: '/assets/html_fragments/eventInfo.html',
            controller: 'eventShow'
        }).
        when('/event/modify/:idEvent', {
           templateUrl: '/assets/html_fragments/eventModify.html',
           controller: 'eventModify'
        }).
        when('/event/new/:nameActivity/:idActivity',{
            templateUrl: '/assets/html_fragments/eventNew.html',
            controller: 'eventNew'
        }).
        when('/user/:id', {
           templateUrl: '/assets/html_fragments/userInfo.html',
           controller: 'userInfo'
        }).
        when('/user/settings/:id', {
            templateUrl: '/assets/html_fragments/userSettings.html',
            controller: 'userSettings'
        }).
        when('/proposal/:idProposal', {
            templateUrl: '/assets/html_fragments/proposalInfo.html',
            controller: 'proposalInfo'
        }).
        when('/proposal/modify/:idProposal',{
            templateUrl: '/assets/html_fragments/proposalModify.html',
            controller: 'proposalModify'
        }).
        when('/proposal/new/:idProposal', {
            templateUrl: '/assets/html_fragments/proposalNew.html',
            controller: 'proposalNew'
        }).
        when('/proposal/movetoactivity/:idProposal', {
            templateUrl: '/assets/html_fragments/proposalMove.html',
            controller: 'proposalMove'
        }).
        when('/proposals', {
            templateUrl: '/assets/html_fragments/proposalList.html',
            controller: 'proposalList'
        }).
		when('/materials/material_data/:idMaterial', {
            templateUrl: '/assets/html_fragments/materialInfo.html',
            controller: 'materialShow'
        }).
        when('/materials/modify/:idMaterial',{
            templateUrl: '/assets/html_fragments/materialModify.html',
            controller: 'materialModify'
        }).
        when('/materials/new/:idMaterial', {
            templateUrl: '/assets/html_fragments/materialNew.html',
            controller: 'materialNew'
        }).
        // Where to redirect if nothing above is applied
        otherwise({
            /*default page, should be /1 but it can be changed, as long as nubmer is
             * id of root activity
             * */
            redirectTo: '/activity/1'
        });
}]);

manthanoApp.factory('socket', function ($rootScope) {
    var socket = io.connect('http://localhost:8000');
    return {
        on: function (eventName, callback) {
            socket.on(eventName, function () {
                var args = arguments;
                $rootScope.$apply(function () {
                    callback.apply(socket, args);
                });
            });
        },
        emit: function (eventName, data, callback) {
            socket.emit(eventName, data, function () {
                var args = arguments;
                $rootScope.$apply(function () {
                    if (callback) {
                        callback.apply(socket, args);
                    }
                });
            })
        }
    };
});

manthanoApp.factory('mail', function ($rootScope, $http) {
    return{
        sendMail: function(title, body, lista){
            $http({
                method: 'POST',
                url: '/async/send_emails_to_users',
                data: {"subject":title, "body":body, "list":lista}
            }).success(function(data){
                window.alert(" Done! :) ");
                    console.log(data);
            });
        }
    }
});

manthanoApp.controller('test123',['$scope','socket','mail',function($scope, socket, mail){
    var number = 0;;
    $scope.blah = function() {mail.sendMail("title", "neko telo",[1,2,3,4]);};
    socket.on('notification', function(data){
        $scope.testList = data.users;
        $scope.number = $scope.testList.length;
        if(number < $scope.number && globalPrljavo == 1){
            for(var i =0; i < $scope.number - number; i++){
                alertify.log($scope.testList[i].body);
            }
            number = $scope.number;
        }
        if(globalPrljavo == 0)
            number = $scope.number;
        globalPrljavo = 1;

    });
}]);

