
/* creating our module, mainly for adding $routeProvider which can be added only with config method *
manthanoApp is root module!!
 */
var manthanoApp = angular.module('manthanoApp', ['ngRoute', 'activityModule']);
/* Route setting for templates in our main module */
manthanoApp.config(['$routeProvider', function ($routerProvider){
    $routerProvider.
        // Settings for activity
        when('/activity/:idActivity', {
            templateUrl: 'activityInfo.html',
            controller: 'activityShow'
        }).
		when('/foo/',{
			templateUrl: 'foo.html',
			controller: 'activityFoo'
		}).
        when('/activity/modify/:idActivity',{
            templateUrl: 'activityModify.html',
            controller: 'activityModify'
        }).
        when('/activity/new/:idActivity', {
            templateUrl: 'activityNew.html',
            controller: 'activityNew'
        }).
        // Where to redirect if nothing above is applied
        otherwise({
            redirectTo: '/activity/3'
        });
}]);
