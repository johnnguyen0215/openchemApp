/* 	
    Angular entry point for the application, define all relevant libraries and routes
	here. The app is currently using the stateprovider library for routing and satellizer.js
	for authentication.
*/
var app = angular.module('openchemApp', ['ui.router', 'ngAnimate', 'satellizer'])
    .config(function($stateProvider, $urlRouterProvider, $locationProvider, $authProvider) {

    	/* API endpoint for logging in. Used by satellizer's auth provider */
        $authProvider.loginUrl = '/api/authenticate';

        /* API endpoing for signign up. Used by satellizer's auth provider */
        $authProvider.signupUrl = '/api/users';

        $urlRouterProvider.otherwise('/');

        /* Routes defined here */
        $stateProvider
            .state('home', {
                url: '/',
                templateUrl: '/views/home.html',
                controller: 'mainController as main'
            })
			.state('admin', {
				url: '/admin',
				templateUrl: '/views/admin.html',
				controller: 'adminController as admin'
			})
            .state('topic', {
                url: '/topic?:id',
                templateUrl: '/views/home.html',
                controller: 'mainController as main'
            })
            .state('create_topic', {
                url: '/admin/create_topic',
                templateUrl: '/views/topics.html',
                controller: 'topicController as admin'
            })
            .state('edit_topics', {
                url: '/admin/edit_topics',
                templateUrl: '/views/edit_topics.html',
                controller: 'editTopicsController as admin'
            })
            .state('edit_topic', {
            	url: '/admin/edit_topic?:id',
            	templateUrl: '/views/topics.html',
            	controller: 'topicController as admin'
            })
            .state('signup', {
                url: '/signup',
                templateUrl: '/views/signup.html',
                controller: 'authController as signup'
            })
            .state('login', {
                url: '/login',
                templateUrl: '/views/login.html',
                controller: 'authController as login'
            })
            .state('edit_users', {
                url: '/admin/edit_users',
                templateUrl: '/views/edit_users.html',
                controller: 'editUsersController as admin'
            })
            .state('group', {
                url: '/group',
                templateUrl: '/views/group.html',
                controller: 'groupController as group'
            })
            .state('account', {
                url: '/account',
                templateUrl: '/views/account.html',
                controller: 'accountController as account'
            })
            .state('create_message', {
                url: '/create_message',
                templateUrl: '/views/create_message.html',
                controller: 'accountController as account'
            })            

        $locationProvider.html5Mode(true);
    })
	/* Define which routes require login and which do not here */
	.run(['$rootScope', '$location', '$auth', 'Auth', function ($rootScope, $location, $auth, Auth) {
	    $rootScope.$on('$stateChangeStart', function (event, toState, fromState) {
	    	if (toState.name === 'home'){
	    		return;
	    	}
	    	if (!Auth.isLoggedIn() && toState.name !== 'login' && toState.name !== 'signup' && toState.name !== 'group' && toState.name !== 'account' && toState.name !== 'topic'){
                $location.path('/home');
	    	}
            else if (Auth.isLoggedIn()){
                Auth.getUser($auth.getPayload().sub).then(function(result){
                    var isAdmin = result.admin;
                    if (isAdmin != 1 && toState.name !== 'login' && toState.name !== 'signup' && toState.name !== 'group' && toState.name !== 'account' && toState.name !== 'topic'){
                        $location.path('/home');
                    }
                });
            }
	    });
	}]);