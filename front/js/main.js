var mainApp = angular.module('mainApp', []);

mainApp.controller('mainCtrl', ['$scope', '$http',function($scope, $http, $location, $window) {
    console.log("run?");
    $scope.userName = "";
    $scope.userID = "";

    $scope.isReady = false;
    $scope.conciseRanking = [];


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
                console.log("for main page");
                console.log(JSON.stringify(response));
                $scope.conciseRanking = response['data']['conciseRanking'];
                console.log('conciseRanking');
                console.log(JSON.stringify($scope.conciseRanking));
                $scope.isReady = true
            },
            function(error) {
                console.log("Error");
                //window.location.href = "http://damingl-mbp15ret.zoosk.local/wc2014/front/login_signup.php";
                window.location.href = "http://damingl-mbp15ret.zoosk.local/wc2014/front/loginNonPHP.html";
            }
        );
    };

    $scope.getUserName = function(userName,userID) {
        $scope.userName = userName;
        console.log("userName : \n" + $scope.userName);
        $scope.userID = userID;
        console.log("userID : \n" + $scope.userID);
        initialize();
    };

}]);