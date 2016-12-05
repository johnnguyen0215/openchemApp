/*
	Controller containing logic for the account page
	@param Auth: service for handling Authentication api endpoints
	@param Account: service for handling Account api endpionts
	@param $auth: object for handling user authentication
	@param $window: window object for handling redirects
	@param $state: object for routing to different states
*/

app.controller('accountController', function(Auth, Account, $auth, $window, $state){

	var vm = this;

	vm.recipientEmail = "";

	vm.message = {
		message_type: '',
		sender_id: '',
		recipients: [],
		subject: '',
		message: ''
	}

	/* reloads the user whenever a change is made also called when the page is first
	loaded*/

	vm.loadUser = function(){
		var userId = $auth.getPayload().sub;
		Auth.getUser(userId).then(function(result){
			vm.user = result;
			
			for (var i =0 ; i < vm.user.messages.length ; i++){
				if (vm.user.messages[i].message_type === 'sessionInvite'){
					var sessionId = vm.user.messages[i].message_content;
					vm.user.messages[i]['sessionId'] = sessionId;
					var htmlString = 'You have been invited to a Scribblar study session with the group "' + vm.user.messages[i].group_name + '". Please click this button to join the session. <button ng-click="account.setInSession()" class="btn btn-sm btn-primary">Join Session</button>';
					vm.user.messages[i].message_content = htmlString;
					
				}
			}
			vm.currentGroup = vm.user.groups[0];
		});
	}

	/* 	if the user is logged in reload their info */
	if (Auth.isLoggedIn()){
		vm.loadUser();
	}
	
	/* 	Adds a recipient to list of recipients for a message */
	vm.addRecipient = function(){
		if (!($.inArray(vm.recipientEmail, vm.message.recipients) > -1)){
			vm.message.recipients.push(vm.recipientEmail);
    	}
    	vm.recipientEmail = "";
	}

	/* 	Removes a recipient from list of recipients for a message 
		@param recipientEmail: string of recipients email
	*/
	vm.deleteRecipient = function(recipientEmail){
        var index = vm.message.recipients.indexOf(recipientEmail);
        if (index > -1){
            vm.message.recipients.splice(index, 1);
        }
	}

	/* Sends a message to the list of recipients using the Account service */
	vm.sendMessage = function(){
		vm.message.message_type = "userMessage";
		vm.message.sender_id = vm.user.id;
		Account.sendMessage(vm.message)
			.success(function(data){
				vm.message = {
					message_type: '',
					sender_id: '',
					recipients: [],
					subject: '',
					message: ''
				}
				vm.successMsg = "Message has been sent";
			});
		vm.alertmsg = "alert alert-info";
	}
	
	/* 	Gets the scribblar session url and redirect the user 
		@param sessionId: int of session's id
	*/
	vm.getSessionurl = function(sessionId){
		var sessionInfo = {
			'username' : vm.user.username,
			'session_id': sessionId
		};
		
		Account.getSessionUrl(sessionInfo)
			.success(function(data){
				var url = data;
				$window.open(url, '_blank');
			});
	}

	/* Delete a message from the user's inbox 
		@param messageId: int of message's id
	*/
	vm.deleteMessage = function(messageId){
		Account.deleteMessage(messageId)
			.success(function(data){
				vm.loadUser();
			});
	}

	/* Sets the in session flag for the currently logged in user */
	vm.setInSession = function(){
		vm.userInfo = {
			name: vm.user.name,
			email: vm.user.email,
			admin: vm.user.admin,
			leader: vm.user.leader,
			inSession: 1
		}

		Auth.setInSession(vm.userInfo, vm.user.id)
			.success(function(data){
				$state.go('home');
			})
		

	}


})
.directive('compile', ['$compile', function ($compile) {
  return function(scope, element, attrs) {
    scope.$watch(
      function(scope) {
        return scope.$eval(attrs.compile);
      },
      function(value) {
        element.html(value);
        $compile(element.contents())(scope);
      }
   )};
  }])