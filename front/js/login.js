/**
 * Created by damingl on 6/1/14.
 */

var loginApp = angular.module('loginApp', []);

loginApp.controller('LoginCtrl', ['$scope', '$http',function($scope, $http, $location, $window) {
    $scope.isLogin = true;

    $scope.hasError=false;
    $scope.errorMsg = "";

    $scope.switchToRegister = function () {
        $scope.isLogin = false;
    };
    $scope.switchToLogin = function () {
        $scope.isLogin = true;
    };

    $scope.showRules = false;

    $scope.nUser = newUser;

    $scope.fullName = "";
    $scope.email = "";
    $scope.password = "";
    $scope.token = "";

    $scope.switchToRules = function () {
        $scope.showRules = true;
    };

    $scope.hideRules = function () {
        $scope.showRules = false;
    };

    $scope.signUpNewUser = function () {
        //var url = "http://peirongli.dreamhosters.com/worldcup/dev/back/signUpNewUser.php";
        var url = urlPrefix + "wc2014/back/signUpNewUser.php";
        var params = {
            'fullName'  : $scope.fullName,
            'email'     : $scope.email,
            'password'  : $scope.password,
            'token'     : $scope.token
        };
        var authParams = {
            'zs': $scope.email+'zs'+$scope.token
        };
        var combinedParams = angular.extend((params || {}), authParams);


        $http({
            method: 'POST',
            url: url,
            data: combinedParams
        }).then(
            function(response) {
                window.location.href = urlPrefix + "wc2014/front/main.php";
            },
            function(error) {
                $scope.hasError=true;
                $scope.errorMsg = error['data'];
            });
    };

    $scope.userInit = function(passedInToken) {
        $scope.token = passedInToken;
    };


    $scope.loginUser = function () {
        //var url = "http://peirongli.dreamhosters.com/worldcup/dev/back/signUpNewUser.php";
        var url = urlPrefix + "wc2014/back/loginUser.php";
        var params = {
            'email'     : $scope.email,
            'password'  : $scope.password,
            'token'     : $scope.token
        };

        var authParams = {
            'zs': $scope.email+'zs'+$scope.token
        };

        var combinedParams = angular.extend((params || {}), authParams);

        $http({
            method: 'POST',
            url: url,
            data: combinedParams
        }).then(
            function(response) {
                window.location.href = urlPrefix + "wc2014/front/main.php";
            },
            function(error) {
                $scope.hasError=true;
                $scope.errorMsg = error['data'];
                //window.location.href = urlPrefix + "wc2014/front/login_signup.php";
            }
        );
    };
}]);
