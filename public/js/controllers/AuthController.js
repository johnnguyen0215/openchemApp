/* Controller used for handling authentication and sign up */
app.controller('authController', function(Auth, $http, $auth, $state) {
    var vm = this;

    vm.preferences = ["General Chemistry", "Organic Chemistry", "Inorganic Chemistry",
    "Biochemistry", "Physical Chemistry", "Analytical Chemistry"];

    vm.userPreferences = [];

    /* authenticates user based on credentials */
    vm.login = function() {
        var credentials = {
            email: vm.email,
            password: vm.password
        }

        Auth.login(credentials)
        .then(function(data){
            // If login is successful, redirect to the users state
            $state.go('home', {});
        }, function(reason){
            vm.error = reason.data.error;
        });
    };

    /* registers user into the database with given credentials */
    vm.signup = function() {
        var credentials = {
            name: vm.name,
            email: vm.email,
            username: vm.username,
            password: vm.password,
            inSession: '0'
        }

        
        Auth.signup(credentials)
            .then(function(data){
                $state.go('login', {});
            }, function(reason){
                vm.error = reason.data.error;
            });
        
    };

    /* [hardcoded] adds a user preference only on the frontend, no updates to backend
        to be implement later
        @param preference: String stating a topic preference for the user
    */
    vm.addUserPreference = function(preference){
        if (!(vm.userPreferences.indexOf(preference) !== -1)){
            vm.userPreferences.push(preference);
        }
    }
    /* [hardcoded] deletes a user preference only on the frontend, no updates to backend
        to be implemented later
        @param preference: String stating a topic preference for the user
    */
    vm.deleteUserPreference = function(preference){
        var index = vm.userPreferences.indexOf(preference);
        if (index > -1){
            vm.userPreferences.splice(index, 1);
        }
    }

});
