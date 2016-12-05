
<!DOCTYPE HTML>
<html ng-app="openchemApp">
<head>

    <base href="/">

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width">

    <title>
        UCI Openchem
    </title>

    <!-- CSS -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <link href='http://fonts.googleapis.com/css?family=Lato&subset=latin,latin-ext' rel='stylesheet' type='text/css'>
    <script src="https://apis.google.com/js/platform.js" async defer></script>
    
    <!--Angular -->
    <script src="//code.jquery.com/jquery-1.11.3.min.js"></script>
    <script src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
    <script src="js/angular/angular.min.js"></script>
    <script src="js/angular/angular-ui-router.min.js"></script>
    <script src="js/angular/angular-animate.min.js"></script>

    <!-- Satellizer -->
    <script src="js/satellizer/satellizer.js"></script>

	<!-- App JS -->
    <script src="js/app.js"></script>
	
	<!-- Controllers -->
    <script src="js/controllers/MainController.js"></script>
    <script src="js/controllers/AdminController.js"></script>
    <script src="js/controllers/AuthController.js"></script>
    <script src="js/controllers/UserController.js"></script>
    <script src="js/controllers/GroupController.js"></script>
    <script src="js/controllers/AccountController.js"></script>
	
	<!-- Services -->
	<script src="js/services/SearchService.js"></script>
    <script src="js/services/AdminService.js"></script>
    <script src="js/services/AuthService.js"></script>
    <script src="js/services/GroupService.js"></script>
    <script src="js/services/AccountService.js"></script>
	
    <!-- Bootstrap JS -->
    <script src="js/bootstrap/bootstrap.js"></script>

</head>


<body ng-controller = "mainController as main">
    <div class="container">
        <div class="row">
            <nav class="navbar navbar-default navbar-static-top" role="navigation">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navigation" aria-expanded="false" aria-controls="navbar">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <div class="navbar-brand" id="logo">
                        <img id="logoImage" src="assets/img/uci_open.png">
                    </div>
                    <!--<span id="headerTitle">OpenChem</span>-->
                </div>
                <div class="collapse navbar-collapse" id="navigation">
                    <ul class="nav navbar-nav navbar-right">
                        <li class="bordered">
                            <a class="navBtn" href="/"><strong>HOME</strong></a>
                        </li>
                        <li class="bordered" ng-if="main.user.admin == 1">
                            <a href="/admin"><strong>ADMIN</strong></a>
                        </li>
                        <li class="bordered" ng-if="main.loggedIn">
                            <a href="/account"><strong>ACCOUNT</strong></a>
                        </li>
                        <li class="bordered" ng-if="main.loggedIn">
                            <a href="/group"><strong>GROUP</strong></a>
                        </li>
                        <li class="bordered" ng-if="!main.loggedIn">
                            <a href="/signup"><strong>CREATE ACCOUNT</strong></a>
                        </li>
                        <li class="bordered" ng-if="!main.loggedIn">
                            <a href="/login"><strong>LOGIN</strong></a>
                        </li>
                        <li class="bordered" ng-if="main.loggedIn">
                            <a href="/" ng-click="main.logout()"><strong>LOGOUT</strong></a>
                        </li>
                        <li>
                            <div id="socialList">
                                <ul>
                                    <li><a target="_blank" class="ghost-button-full-color" href="http://www.uadv.uci.edu/OpenCourseWare"><strong>Donate Now</strong></a></li>
                                    <li><a target="_blank" href="https://twitter.com/UCI_OCW"><img src="assets/Icons/32/Twitter.png"/></a></li>
                                    <li><a target="_blank" href="http://www.facebook.com/pages/UC-Irvine-OpenCourseWare/314697770646?v=info"><img src="assets/Icons/32/Facebook.png"/></a></li>
                                    <li><a target="_blank" href="http://www.youtube.com/user/UCIrvineOCW"><img src="assets/Icons/32/Youtube.png"/></a></li>
                                    <li><a target="_blank" href="http://sites.uci.edu/opencourseware/feed/"><img src="assets/Icons/32/RSS.png"/></a></li>
                                </ul>
                            </div>
                        </li>
                    </ul>
                </div>
                <div id="social">
                	<ul>
                		<li><a target="_blank" class="ghost-button-full-color" href="http://www.uadv.uci.edu/OpenCourseWare"><strong>Donate Now</strong></a></li>
                		<li><a target="_blank" href="https://twitter.com/UCI_OCW"><img src="assets/Icons/32/Twitter.png"/></a></li>
                		<li><a target="_blank" href="http://www.facebook.com/pages/UC-Irvine-OpenCourseWare/314697770646?v=info"><img src="assets/Icons/32/Facebook.png"/></a></li>
                		<li><a target="_blank" href="http://www.youtube.com/user/UCIrvineOCW"><img src="assets/Icons/32/Youtube.png"/></a></li>
                		<li><a target="_blank" href="http://sites.uci.edu/opencourseware/feed/"><img src="assets/Icons/32/RSS.png"/></a></li>
                	</ul>
                </div>
		            <!--
	                <div class="greeting pull-right" ng-if="main.loggedIn">
	                    <strong>Hello, {{ main.user.username }} </strong>
	                </div>-->
            </nav>
        </div>
        <div class="row" id="headerBorder">
        </div>
        <div class="row">
            <div ui-view></div>
        </div>
        <div class="row" id="bottomBorder">
        </div>
    </div>
    <!--
    <div class="site-footer" >
        <p align="center"><a href="http://uci.edu">UC Irvine</a> | <a href="http://unex.uci.edu/?WT.mc_id=OCWWEB">UC Irvine Extension</a> | <a href="http://www.ocwconsortium.org/">Open Courseware Consortium</a>
    </div>
    -->
</body>
</html>
