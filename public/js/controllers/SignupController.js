
app.controller('userController', function(User) {

        var vm = this;

        // set a processing variable to show loading things
        vm.processing = true;

        // grab all the users at page load
        User.all()
            .success(function(data) {

                // when all the users come back, remove the processing variable
                vm.processing = false;

                // bind the users that come back to vm.users
                vm.users = data;
            });

        // function to delete a user
        vm.deleteUser = function(id) {
            vm.processing = true;

            User.delete(id)
                .success(function(data) {

                    // get all users to update the table
                    // you can also set up your api
                    // to return the list of users with the delete call
                    User.all()
                        .success(function(data) {
                            vm.processing = false;
                            vm.users = data;
                        });
                });
        };

    })

// controller applied to user creation page
app.controller('userCreateController', function(User) {

    var vm = this;

    

    vm.validateEmail = function(email) {
        var re = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
        return re.test(email);
    }

    // variable to hide/show elements of the view
    // differentiates between create or edit pages
    vm.type = 'create';
    // function to create a user
    vm.saveUser = function() {
        if (!vm.validateEmail(vm.userData.email)){
            vm.alertmsg = "alert alert-danger";
            vm.message = "Please input a valid email address";
        }
        else if (vm.userData.password.length < 6){
            vm.alertmsg = "alert alert-danger"
            vm.message = "Please input a password with 6 or more characters";
        }
        else{
            vm.processing = true;
            vm.message = '';
            // use the create function in the userService
            User.create(vm.userData)
                .success(function(data) {
                    vm.processing = false;
                    vm.userData = {};
                    if (data.error){
                        vm.alertmsg = "alert alert-danger"
                    }
                    else{
                        vm.alertmsg = "alert alert-info"
                    }
                    vm.message = data.message;
                });
        }
    };

})

// controller applied to user edit page
app.controller('userEditController', function($routeParams, User) {

    var vm = this;

    // variable to hide/show elements of the view
    // differentiates between create or edit pages
    vm.type = 'edit';

    // get the user data for the user you want to edit
    // $routeParams is the way we grab data from the URL
    User.get($routeParams.user_id)
        .success(function(data) {
            vm.userData = data;
        });

    // function to save the user
    vm.saveUser = function() {
        vm.processing = true;
        vm.message = '';

        // call the userService function to update
        User.update($routeParams.user_id, vm.userData)
            .success(function(data) {
                vm.processing = false;

                // clear the form
                vm.userData = {};

                // bind the message from our API to vm.message
                vm.message = data.message;
            });
    };

});