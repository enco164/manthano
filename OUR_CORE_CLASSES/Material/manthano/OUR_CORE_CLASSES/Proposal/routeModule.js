
/* creating our module, mainly for adding $routeProvider which can be added only with config method *
manthanoApp is root module!!
 */
var manthanoApp = angular.module('manthanoApp', ['ngRoute', 'proposalModule']);
/* Route setting for templates in our main module */
manthanoApp.config(['$routeProvider', function ($routerProvider){
    $routerProvider.
        // Settings for proposal
        when('/proposal/:idProposal', {
            templateUrl: 'proposalInfo.html',
            controller: 'proposalInfo'
        }).
		when('/foo/',{
			templateUrl: 'foo.html',
			controller: 'proposalFoo'
		}).
        when('/proposal/modify/:idProposal',{
            templateUrl: 'proposalModify.html',
            controller: 'proposalModify'
        }).
        when('/proposal/new/:idProposal', {
            templateUrl: 'proposalNew.html',
            controller: 'proposalNew'
        }).
        // Where to redirect if nothing above is applied
        otherwise({
            /*default page, should be /1 but it can be changed, as long as nubmer is
            * id of root activity
            * */
            redirectTo: '/proposal/17'
        });
}]);
