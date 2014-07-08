var mainApp = angular.module('mainApp', []);

mainApp.controller('mainCtrl', ['$scope', '$http',function($scope, $http, $location, $window) {
    $scope.userName = "";
    $scope.userID = "";

    $scope.isReady = false;
    $scope.conciseRanking = [];

    $scope.upcomingMatches = [];

    $scope.final_four = {};

    // initialize all the data for main page !!!
    var initialize = function() {
        var url = urlPrefix + "wc2014/back/getMainPage.php";
        var params = {
            'userName'  : $scope.userName,
            'userID'    : $scope.userID
        };
        $http({
            method: 'POST',
            url: url,
            data: params
        }).then(
            function(response) {
                $scope.conciseRanking               = response['data']['conciseRanking'];
                $scope.upcomingMatches              = response['data']['upcomingMatches'];
                $scope.final_four                   = response['data']['ff_guesses'];
                $scope.isReady = true
            },
            function(error) {
                //window.location.href = "http://damingl-mbp15ret.zoosk.local/wc2014/front/login_signup.php";
                window.location.href = urlPrefix + "wc2014/front/login_signup.php";
            }
        );
    };

    $scope.getUserName = function(userName,userID) {
        $scope.userName = userName;
        $scope.userID = userID;
        initialize();
    };

    $scope.submitStatus = "";

    $scope.guess = {};
    $scope.submitGuess = function(inputGuess) {
          var today = new Date();
        var url = urlPrefix + "wc2014/back/submitGuess.php";
        var params = {
            'userName'  : $scope.userName,
            'userID'    : $scope.userID,
            'guesses'   : inputGuess,
            'timestamp' : today
        };

        $http({
            method: 'POST',
            url: url,
            data: params
        }).then(
            function(response) {
                $scope.submitStatus = response['data'];
            },
            function(error) {
                $scope.submitStatus = "Your submission failed. Please check your format";
              }
        );
    };

    $scope.submitFinalFourGuess = function(inputGuess) {
        /*
        var today = new Date();
        var url = urlPrefix + "wc2014/back/submitFinalFourGuess.php";
        var params = {
            'userName'  : $scope.userName,
            'userID'    : $scope.userID,
            'guesses'   : inputGuess,
            'timestamp' : today
        };

        $http({
            method: 'POST',
            url: url,
            data: params
        }).then(
            function(response) {
                $scope.ffSubmitStatus = response['data']['msg'];

            },
            function(error) {
                $scope.ffSubmitStatus = "Your final-four submission failed. Please check your format";
              }
        );
        */
    };

    $scope.goToRanking = function() {
        window.location.href = urlPrefix + "wc2014/front/detailedRanking_Front.php";

    };

    $scope.rankFour = [
        {'value': 1,  'label': '1st'},
        {'value': 2,  'label': '2nd'},
        {'value': 3,  'label': '3rd'},
        {'value': 4,  'label': '4th'}
    ];
}]);