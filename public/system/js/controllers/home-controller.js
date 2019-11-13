system.controller("HomeController", HomeController);

/**
 *
 * @param {*} $scope
 * @param {*} $http
 * @param {*} $rootScope
 */
function HomeController($scope, $http, $rootScope){

    $scope.packageService = {};
    $scope.aboutExpiretime = 0;

    this.__proto__ = new BaseController($scope, $http, $rootScope);

    this.initialize = function() {
        $scope.checkPackageServiceExpire();
    }

    /**
     * Send http request to service for check shop's service package is available
     * Then store this calculated data to sessionStore
     *
     */
    $scope.checkPackageServiceExpire = function() {
        var endpoint = `/package-service`;
        var getFromSession = sessionStorage.getItem('service_package');
        if ( !getFromSession ) {
            $http.get(`https://shopbay.vn${endpoint}`)
                .then( response => {
                    if ( response.data.status == 'successful' ) {
                        var result = response.data.data;
                        $scope.packageService = result;
                        sessionStorage.setItem('service_package', JSON.stringify($scope.packageService));
                    }
                }); 
        } 
        else { 
            $scope.packageService = JSON.parse(getFromSession);
        }
    }

    this.initialize();
}
