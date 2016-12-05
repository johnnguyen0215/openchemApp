/* service for calling Auth related api endpoints to the laravel backend */
app.factory('Auth', function($rootScope, $http, $auth, $state) {
	// create a new object

	var authFactory = {};

    authFactory.user = null;

    /* check if user is logged in */
	authFactory.isLoggedIn = function(){
		return $auth.isAuthenticated();
	};

    /* logs user in with credentials
        @param credentials: user information required to log user in
     */
    authFactory.login = function(credentials) {
        // Use Satellizer's $auth service to login
        var promise = $auth.login(credentials);
        return promise;
    };


    /* sign the user up with given credentials
        @param credentials: user information required to register user
    */
    authFactory.signup = function(credentials) {
    	var promise = $auth.signup(credentials);
       	return promise;
    };

    /* get a user from the database and return a promise
        @param userId: id of the user to get
    */
    authFactory.getUser = function(userId){
        var promise = $http.get('api/users/'+userId)
        .then(function(result){
            authFactory.user = result.data;
            return result.data;
        });
        return promise;
    };

    /* get total number of users in the db */
    authFactory.getUserCount = function(){
        return $http.get('api/users/getCount');
    }

    /* check if user is an admin */
    authFactory.isAdmin = function(){
        if ($auth.isAuthenticated()){
            var userId = $auth.getPayload().sub;
            authFactory.getUser(userId)
            .then(function(result){
                return result.admin;
            });
        }
    }

    /* set the in session flag for a specific user*/
    authFactory.setInSession = function(user, userId){
        return $http.put('api/users/'+userId, user);
    }


	return authFactory;

});