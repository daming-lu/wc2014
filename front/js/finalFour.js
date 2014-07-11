var ffApp = angular.module('ffApp', []);

ffApp.controller('finalFourCtrl', ['$scope', '$http',function($scope, $http, $location, $window) {
    $scope.isReady = false;

    $scope.allGroupMatches = [];
    $scope.allUserGuesses = [];

    var initialize = function() {
        var url = urlPrefix + "wc2014/back/finalFour.php";
        var params = {

        };
        $http({
            method: 'POST',
            url: url,
            data: params
        }).then(
            function(response) {
                $scope.allUserGuesses = response['data']['allUserGuesses'];
                $scope.isReady = true
            },
            function(error) {
                window.location.href = urlPrefix + "wc2014/front/login_signup.php";
            }
        );
    };
    initialize();

    $scope.goBack = function() {
        window.location.href = urlPrefix + "wc2014/front/main.php";

    }
}]);
/**
 * Created by damingl on 7/11/14.
 */
