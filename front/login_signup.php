<?php

/*** begin our session ***/
session_start();

/*** set a form token ***/
$cur_time = time();
$form_token = md5( uniqid($cur_time, true) );

/*** set the session form token ***/
$_SESSION['form_token'] = $form_token;
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta name="generator" content="HTML Tidy for Linux/x86 (vers 25 March 2009), see www.w3.org" />

        <title>Daming's 2014 World Cup Casino</title><!-- Import jQuery -->

        <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js">
        </script>

        <!-- Import AngularJS library -->
        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/angularjs/1.1.5/angular.min.js">
        </script>

        <!-- Import Booststrap -->
        <link href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <script src="//netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js" type="text/javascript">
        </script>

        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

        <!-- Import application specific code -->
        <script type="text/javascript" src="js/constants.js"></script>

        <script type="text/javascript" src="js/newuser.js"></script>
        <script type="text/javascript" src="js/login.js"></script>

        <link href="css/login.css" rel="stylesheet" type="text/css" />
        <link href="css/foundation.css" rel="stylesheet" type="text/css" />
    </head>

    <body class="wc_bg_cover">
        <div ng-app="loginApp" class="container">
            <div ng-controller="LoginCtrl" class="row">

                <div class="container-fluid"
                    ng-init="userInit('<?php echo $form_token; ?>')" >
                </div>
                <div ng-show="showRules" class="col-md-10 col-md-offset-1">
                    <div class="panel panel-default pad_top">
                        <div class="panel-heading">
                            <strong>Game Rules : </strong>
                        </div>
                        <div class="panel-body">
                            <p>
                            <b>Welcome to Daming's World Cup 2014 Guess Club!</b> <br /><br />
                            The rules are simple : you just need to try to correctly guess the results of the matches. To make things more fun, we give different weights to matches in different stages:
                            <li>
                                For each <b>Group</b> match, you earn <b>3</b> points if you guess it correctly; (48 matches in total)
                            </li>
                            <br />
                            <li>
                                For each <b>Round of 16</b> match, you earn <b>4</b> points if you guess it correctly; (8 matches in total)
                            </li>
                            <br />
                            <li>
                                For each <b>Quarter-final</b> match, you earn <b>5</b> points if you guess it correctly; (4 matches in total)
                            </li>
                            <br />
                            <li>
                                For each <b>Semi-final</b> match, you earn <b>6</b> points if you guess it correctly; (2 matches in total)
                            </li>
                            <br />
                            <li>
                                For the <b>3rd-place</b> match, you earn <b>7</b> points if you guess it correctly; (only 1 match)
                            </li>
                            <br />
                            <li>
                                For the <b>Final</b> match, you earn <b>8</b> points if you guess it correctly; (only 1 match)
                            </li>
                            <br /><br />
				<p>
				The judging process normally happens at midnight so if you guess it right, <br />don't panic as you don't get the score immediately :) <br />
				</p>
                            The <b>Top 3</b> players with the highest scores will be awarded:
                            <br /><br /><br />
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Amazon Gift Card ($50, $30, $20)</b> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(<i>Courtesy of Daming</i>)
                            <br /><br /><br />
                            You can also choose to get paid in RMB by 支付宝 :)

                            <a class="btn btn-lg btn-info go-back-button" href="#" ng-click="hideRules()">
                                <i class="icon-info-sign"></i> Go Back
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Login Page -->
                <div ng-show="isLogin && !showRules" class="col-md-6 col-md-offset-3">
                    <div class="panel panel-default pad_top">
                        <div class="panel-heading">
                            <strong>Login</strong>
                        </div>
                        <h4 ng-if="hasError" class="error">{{errorMsg}}</h4>
                        <div class="panel-body">
                            <form class="form-horizontal" role="form">
                                <div class="form-group">
                                    <label for="inputEmail3" class="col-sm-3 control-label">Email</label>

                                    <div class="col-sm-9">
                                        <input class="form-control" id="inputEmail3" ng-model="email" placeholder="Email" required="" type="email" />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="inputPassword3" class="col-sm-3 control-label">Password</label>

                                    <div class="col-sm-9">
                                        <input class="form-control" id="inputPassword3" ng-model="password" placeholder="Password" required="" type="password" />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="inputPassword3" class="col-sm-9 control-label"><i>Not familiar with the rules?  </i></label>
                                    <a class="btn btn-small btn-info" href="#" ng-click="switchToRules()">
                                      <i class="icon-info-sign"></i> Info</a>
                                </div>

                                <div class="form-group">
                                    <div class="col-sm-offset-3 col-sm-9">
                                        <div class="checkbox">
                                            <label><input type="checkbox" /> Remember me</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group last">
                                    <div class="col-sm-offset-5 col-sm-9">
                                        <button type="submit" class="btn btn-success btn-md" ng-click="loginUser()">Sign in</button> <button type="reset" class="btn btn-default btn-md">Reset</button>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <div class="panel-footer">
                            Not Registered?
                            <a href="#" ng-click="switchToRegister()">Register here</a>
                        </div>

                    </div>
                </div>

                <!-- Register Page -->
                <div ng-show="!isLogin && !showRules" class="col-md-6 col-md-offset-3">
                    <div class="panel panel-default pad_top">
                        <div class="panel-heading">
                            <strong>Register</strong>
                        </div>
                        <h4 ng-if="hasError">{{errorMsg}}</h4>
                        <div class="panel-body">
                            <form class="form-horizontal" role="form">
                                <div class="form-group">
                                    <label for="inputFullName" class="col-sm-3 control-label">Full Name</label>

                                    <div class="col-sm-9">
                                        <input class="form-control" ng-model="fullName" id="inputFullName" placeholder="Daming Lu" required="" type="text" maxlength="30"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputEmail3" class="col-sm-3 control-label">Email</label>

                                    <div class="col-sm-9">
                                        <input class="form-control" ng-model="email" id="inputEmail3" placeholder="Email" required="" type="email" />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="inputPassword3" class="col-sm-3 control-label">Password</label>

                                    <div class="col-sm-9">
                                        <input class="form-control" ng-model="password" id="inputPassword3" placeholder="Password" required="" type="password" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputPassword3" class="col-sm-9 control-label"><i>Not familiar with the rules?  </i></label>
                                    <a class="btn btn-small btn-info" href="#" ng-click="switchToRules()">
                                      <i class="icon-info-sign"></i> Info</a>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-offset-3 col-sm-9">
                                        <div class="checkbox">
                                            <label><input type="checkbox" /> Remember me</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group last">
                                    <div class="col-sm-offset-3 col-sm-9">
                                        <button type="submit" class="btn btn-success btn-md" ng-click="signUpNewUser()">Sign up</button> <button type="reset" class="btn btn-default btn-md">Reset</button>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <div class="panel-footer">
                            Already Registered?
                            <a href="#" ng-click="switchToLogin()">Login here</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
