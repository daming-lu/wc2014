var mainApp = angular.module('mainApp', []);

mainApp.controller('mainCtrl', ['$scope', '$http',function($scope, $http, $location, $window) {
    console.log("run?");
    $scope.getUserName = function(userName,userID) {
        $scope.userName = userName;
        console.log("userName : \n" + $scope.userName);
        $scope.userID = userID;
        console.log("userID : \n" + $scope.userID);
    };



}]);