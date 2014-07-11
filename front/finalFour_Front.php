<?php
    session_start();
    $user_name = isset($_SESSION['user_name'])?$_SESSION['user_name']:'Annoymous';
    $user_id = isset($_SESSION['user_id'])?$_SESSION['user_id']:'no ID';
    
    if (!isset($_SESSION['user_name']) || !isset($_SESSION['user_id']) || !isset($_SESSION['login'])) {
        session_destroy();
        header ("Location: login_signup.php");
    }
    $user_name = $_SESSION['user_name'];
    $user_id =  $_SESSION['user_id'];
    
    if ($_SESSION['login'] != sha1($user_name.$user_id)) {
        session_destroy();
        header ("Location: login_signup.php");
    }
    
    if (!isset($_SESSION['CREATED'])) {
        $_SESSION['CREATED'] = time();
    } else if (time() - $_SESSION['CREATED'] > 1800) {
        // session started more than 30 minutes ago
        session_regenerate_id(true);    // change session ID for the current session an invalidate old session ID
        $_SESSION['CREATED'] = time();  // update creation time
    }
    
    ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8">
        <meta charset="utf-8">
        <title>Daming's World Cup Guess Club</title>
        <meta name="generator" content="Bootply" />
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
        <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
        <script type="text/javascript" src="js/constants.js"></script>
        <!-- Import AngularJS library -->
        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/angularjs/1.1.5/angular.min.js"></script>
        <link href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap-glyphicons.css" rel="stylesheet">
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <!--[if lt IE 9]>
        <script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
        <link href="css/styles.css" rel="stylesheet">
        <link href="css/rank.css" rel="stylesheet" type="text/css" />
        <link href="css/main.css" rel="stylesheet" type="text/css" />

        <script type="text/javascript" src="js/finalFour.js"></script>
    </head>
    <!-- class="main_bg_cover frames_fade_in" -->
    <body class="main_bg_cover frames_fade_in" ng-app="ffApp" class="">
        <div ng-controller="finalFourCtrl" class="page-container">
            <!-- top navbar -->
            <div >
                <h2 class="for_test" > Welcome <?=$user_name?> !</h2>

                <div class="centered">
                    <button class="btn btn-success" ng-click="goBack()" type="button">Go Back</button>
                </div>
                <br />

                <div class="for_test" ng-if="!isReady">
                    <h2>Page is loading...</h2>
                </div>
            </div>
            <div class="container-fluid"
                ng-init="getUserName('<?php echo $user_name; ?>','<?php echo $user_id; ?>')" >
            </div>

            <div ng-if="isReady" class="container-fluid">
                <div class="row row-offcanvas row-offcanvas-left">
                    <!--/main-->
                    <div class="col-md-12 x_scroll panel panel-default" data-spy="scroll" data-target="#sidebar-nav">
                        <table class="table-hover table table-bordered table_center ">
                            <tr style="color:blue">
                                <td>User Name</td>
                                <td>1st</td>
                                <td>2nd</td>
                                <td>3rd</td>
                                <td>4th</td>
                            </tr>
                            <tbody>
                            <tr ng-repeat="(i,info) in allUserGuesses">
                                <td>{{info.user_name}}</td>
                                <td>{{info.finalFour.1}}</td>
                                <td>{{info.finalFour.2}}</td>
                                <td>{{info.finalFour.3}}</td>
                                <td>{{info.finalFour.4}}</td>
                             </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>