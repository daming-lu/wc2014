var rankApp = angular.module('rankApp', []);

rankApp.controller('rankCtrl', ['$scope', '$http',function($scope, $http, $location, $window) {
    $scope.isReady = false;

    $scope.allGroupMatches = [];
    $scope.allUserGuesses = [];

    var initialize = function() {
        var url = urlPrefix + "wc2014/back/detailedRanking.php";
        var params = {

        };
        $http({
            method: 'POST',
            url: url,
            data: params
        }).then(
            function(response) {
                $scope.allGroupMatches = response['data']['allGroupMatches'];
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
