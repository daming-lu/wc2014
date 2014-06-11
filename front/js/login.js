/**
 * Created by damingl on 6/1/14.
 */

var loginApp = angular.module('loginApp', []);

loginApp.controller('LoginCtrl', ['$scope', '$http',function($scope, $http, $location, $window) {
    $scope.isLogin = true;

    $scope.switchToRegister = function () {
        $scope.isLogin = false;
    };
    $scope.switchToLogin = function () {
        $scope.isLogin = true;
    };

    newUser.email = "haha@gmail.com";
    $scope.nUser = newUser;

    $scope.fullName = "";
    $scope.email = "";
    $scope.password = "";
    $scope.token = "";

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
            'zs': 'zs'
        };
        /*
        $http.post(url, params).success(function(data) {
            console.log("returned data == \n");
            data = data['data'];
            console.log(JSON.stringify(data));
            //$scope.users = data;
        });
        */
        var combinedParams = angular.extend((params || {}), authParams);

        var xsrf = $.param(combinedParams);

        $http({
            method: 'POST',
            url: url,
            data: combinedParams
            //data: xsrf
            //headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        });
    };

    $scope.userInit = function(passedInToken) {
        $scope.token = passedInToken;
        console.log("token : \n" + $scope.token);
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
            'zs': 'zs'
        };
        /*
        $http.post(url, params).success(function(data) {
            console.log("returned data == \n");
            data = data['data'];
            console.log(JSON.stringify(data));
            //$scope.users = data;
        });
        */
        var combinedParams = angular.extend((params || {}), authParams);

        var xsrf = $.param(combinedParams);

        $http({
            method: 'POST',
            url: url,
            data: combinedParams
            //data: xsrf
            //headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).then(
            function(response) {
                console.log("success");
                console.log(JSON.stringify(response));
                //$location.path('http://www.bing.com');
                //$window.location.href = 'http://www.google.com';
                window.location.href = "http://damingl-mbp15ret.zoosk.local/wc2014/front/main.php";

               //$location.url('http://www.google.com');

                console.log("after bing");

            },
            function(error) {
                console.log("Error");
                //window.location.href = "http://damingl-mbp15ret.zoosk.local/wc2014/front/login_signup.php";
                window.location.href = "http://damingl-mbp15ret.zoosk.local/wc2014/front/loginNonPHP.html";
            });

            /*)
            success(function(data, status, headers, config) {
          // this callback will be called asynchronously
          // when the response is available
            console.log("login success");
            console.log(JSON.stringify(response));
        }).error(function(data, status, headers, config) {
          // called asynchronously if an error occurs
          // or server returns response with an error status.
                console.log("login failed");
        });
        */
    };

}]);


loginApp.config(['$routeProvider', function($routeProvider) {
    $routeProvider
        .when('/home', {templateUrl: 'http://damingl-mbp15ret.zoosk.local/wc2014/front/main.php',   controller: HomeCtrl})
        .when('/haha',{
            template:"haha!"
        })
        .otherwise({redirectTo: '/home'});
}]);

function HomeCtrl($scope, Page) {
    Page.setTitle("Welcome");
}