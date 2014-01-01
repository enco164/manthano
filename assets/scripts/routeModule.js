
/* creating our module, mainly for adding $routeProvider which can be added only with config method *
manthanoApp is root module!!
 */
var manthanoApp = angular.module('manthanoApp', ['ngRoute', 'activityModule']);
//alert(manthanoApp);
/* Route setting for templates in our main module */
manthanoApp.config(['$routeProvider', function ($routerProvider){
    $routerProvider.
        // Settings for activity
        when('/activity/:idActivity', {
            templateUrl: '/OUR_CORE_CLASSES/partial.php',
            controller: 'activityShow'
        }).
        // Where to redirect if nothing above is applied
        otherwise({
            redirectTo: '/activity/1'
        });
}]);
