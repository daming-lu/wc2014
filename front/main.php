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


        <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js">
        </script>

        <script type="text/javascript" src="js/constants.js"></script>

        <!-- Import AngularJS library -->
        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/angularjs/1.1.5/angular.min.js">
        </script>


        <link href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap-glyphicons.css" rel="stylesheet">


        <link href="css/bootstrap.min.css" rel="stylesheet">
		<!--[if lt IE 9]>
			<script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->
		<link href="css/styles.css" rel="stylesheet">

        <link href="css/main.css" rel="stylesheet" type="text/css" />

        <script type="text/javascript" src="js/main.js"></script>

	</head>
    <!-- class="main_bg_cover frames_fade_in" -->
	<body class="main_bg_cover frames_fade_in" ng-app="mainApp" class="">

<div    ng-controller="mainCtrl" class="page-container">
  
	<!-- top navbar -->
    <div >
        <h2 class="for_test" > Welcome <?=$user_name?> !</h2>
        <br />
    </div>

    <div class="container-fluid"
        ng-init="getUserName('<?php echo $user_name; ?>','<?php echo $user_id; ?>')" >
    </div>

    <div class="container-fluid">
      <div class="row row-offcanvas row-offcanvas-left">
        <!--sidebar-->
        <div class="col-md-1" id="sidebar" role="navigation">
        &nbsp;
        </div><!--/sidebar-->
  	
        <!--/main-->
        <div class="col-md-10" data-spy="scroll" data-target="#sidebar-nav">
          <div class="row">
             <!-- Left Column -->
           	 <div class="col-md-7">
                <div class="panel panel-default">
                  <div class="panel-heading"><a href="#" class="pull-right"></a> <h4>Current Ranking</h4></div>
                    <div class="panel-body">

                        <table class="table table-bordered">
                            <th>
                                <td class="table_header" rowspan="2"><b >User Name</b></td>
                                <td class="table_header"><b>Past Match</b></td>
                                <td class="table_header"><b>Next Match</b></td>
                                <td class="table_header" rowspan="2"><b>Score</b></td>
                            </th>
                            <tr >
                                <td>&nbsp;</td>
                                <td class="table_team" ng-if="conciseRanking.PastMatch!=''">{{conciseRanking.PastMatch.left_team}} - {{conciseRanking.PastMatch.right_team}}</td>
                                <td class="table_team" ng-if="conciseRanking.PastMatch==''">&nbsp;</td>
                                <td class="table_team">{{conciseRanking.NextMatch.left_team}} - {{conciseRanking.NextMatch.right_team}}</td>
                            </tr>
                            <tbody ng-repeat="(user_id, info) in conciseRanking.user_guesses">
                            <tr>
                                <td>{{$index + 1}}</td>
                                <td>
                                    {{info.user_name}}
                                </td>
                                <td ng-if="info.past_match!=null">
                                    {{info.past_match}}
                                </td>
                                <td ng-if="info.past_match==null">
                                    missed
                                </td>
                                <td ng-if="info.next_match!=null">
                                    {{info.next_match}}
                                </td>
                                <td ng-if="info.next_match==null">
                                    &nbsp;
                                </td>
                                <td>
                                    {{info.user_score}}
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div><!--/panel-body-->
                </div><!--/panel-->
            </div><!--/col-->
            <!-- Right Column -->
            <div class="col-md-5">

                 <div class="panel panel-default">
                   <div class="panel-heading"> <h4>Upcoming Matches for Next 2 Days</h4>
                       <br />
                       Please fill in all your guesses and click submit
                   </div>
                    <div class="panel-body">
                        <form class="form-horizontal" role="form">
                        <table class="table table-bordered">
                            <tbody ng-model="guess" ng-repeat="(i, info) in upcomingMatches">
                            <tr>
                                <td>
                                    {{info.match_id}}
                                </td>
                                <td>
                                    {{info.left_team}} vs. {{info.right_team}}
                                </td>
                                <td>
                                    <input class="guess-score" ng-model="guess[info.match_id]" id="{{info.match_id}}"  placeholder="0:0" required="" type="text" maxlength="3"/>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <button class="my-submit-button" ng-click="submitGuess(guess)">Submit</button>
                            {{submitStatus}}
                        </form>

                    </div><!--/panel-body-->
                 </div><!--/panel-->
                <div class="well">
                     <form class="form-horizontal" role="form">
                      <h4>Comments</h4>
                         <i>Still under development :'(</i>
                       <div class="form-group" style="padding:14px;">
                        <textarea class="form-control" placeholder="Write your comments here"></textarea>
                      </div>
                      <button class="btn btn-success" type="button">Post</button>

                    </form>
                </div><!--/well-->
              </div><!--/col-->
          </div><!--/row-->
      </div><!--/.row-->
        <!--sidebar-->
        <div class="col-md-1" id="sidebar" role="navigation">
        &nbsp;
        </div><!--/sidebar-->
    </div>
  </div><!--/.container-->
</div><!--/.page-container-->
  

        
	<!-- script references -->
		<script src="js/scripts.js"></script>
	</body>
</html>
