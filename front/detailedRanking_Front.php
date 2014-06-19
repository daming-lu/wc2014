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

        <script type="text/javascript" src="js/detailedranking.js"></script>
    </head>
    <!-- class="main_bg_cover frames_fade_in" -->
    <body class="main_bg_cover frames_fade_in" ng-app="rankApp" class="">
        <div ng-controller="rankCtrl" class="page-container">
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
                            <th >
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td style="min-width:260px" ng-repeat="(key,val) in allGroupMatches" class="table_header">{{val.left_team}} - {{val.right_team}}</td>
                            </th>
                            <tbody>
                            <tr >
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td style="min-width:260px" ng-repeat="(key,val) in allGroupMatches" class="table_header">{{val.match_time}} PDT</td>
                            </tr>
                            <tr >
                                <td style="min-width:90px">Rank</td>
                                <td style="min-width:90px">User Name</td>
                                <td style="min-width:90px">Score</td>
                                <td style="min-width:260px;color:blue" ng-repeat="(key,val) in allGroupMatches" class="table_header">{{val.result}}</td>
                            </tr>
                            <tr ng-repeat="val in allUserGuesses track by $index">
                                <td>{{$index + 1}}</td>
                                <td>{{val.user_name}}</td>
                                <td>{{val.user_score}}</td>
                                <td ng-repeat="(k,v) in val.match_guesses track by $index | orderBy:identity">
                                    {{v}}
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>