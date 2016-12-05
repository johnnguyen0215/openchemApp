app.controller('userController', function($http) {
    var vm = this;
    
    vm.users;
    vm.error;

    vm.getUsers = function() {

        // This request will hit the index method in the AuthenticateController
        // on the Laravel side and will return the list of users
        $http.get('api/authenticate').success(function(users) {
            vm.users = users;
        }).error(function(error) {
            vm.error = error;
        });
    }

    /* signs users up with given credentials */

    vm.signup = function() {
        var credentials = {
            name: vm.name,
            email: vm.email,
            username: vm.username,
            password: vm.password,
            inSession: '0'
        }

        $http.post('api/users', credentials)
        .success(function(data){
            alert("signup was successful");
            $state.go('login', {});
        })
        .error(function(error){
            alert("signup was unsuccessful");
        })

    }

});
