/* controller for the groups page
	@param Group: service for the Group api endpoints
	@param Account: service for the Account api endpoints
	@param Auth: service for the Auth api endpionts
	@param $auth: object for authenticating user
	@param $window: object for manipulating user window
	@param $state: object for manipulating application state, routing
*/
app.controller('groupController', function(Group, Account, Auth, $auth, $window, $state){

	// template group object to be filled out in the dom

	var vm = this;

    vm.preferences = ["General Chemistry", "Organic Chemistry", "Inorganic Chemistry",
    "Biochemistry", "Physical Chemistry", "Analytical Chemistry"];

    vm.groupPreferences = [];

    /* loads the user info upon state change */
	vm.loadUser = function(){
		var userId = $auth.getPayload().sub;
		Auth.getUser(userId).then(function(result){
			vm.user = result;
			console.log(vm.user);
			if (vm.user.sessions){
				for (var i = 0; i < vm.user.sessions.length; i++){
					vm.user.sessions[i].meeting_name = vm.user.sessions[i].meeting_name.replace(/\+/g, " ");
				}
			}

			vm.currentGroup = vm.user.groups[0];
			vm.studyGroup = vm.user.groups[0];


			vm.renderHangoutsButton();


		});
	}

	/* add group preference to hard coded list of preferences 
		@param preference: String specifying preference
	*/
    vm.addGroupPreference = function(preference){
        if (!(vm.groupPreferences.indexOf(preference) !== -1)){
            vm.groupPreferences.push(preference);
        }
    }

    /* delete group preference from hard coded list of preferences
		@param preference: String specifying preference
    */
    vm.deleteGroupPreference = function(preference){
        var index = vm.groupPreferences.indexOf(preference);
        if (index > -1){
            vm.groupPreferences.splice(index, 1);
        }
    }

	vm.messageObj = {
		message_type: "",
		sender_id: "",
		group_id: "",
		message: "https://scribblar.com/3uwtr7u"
	}

	vm.groupObj = {
		group_name: "",
		leader_id: ""
	}

	vm.session = 
	{
		user_id: '',
		username: '',
		meetingName: '',
		moderatorPw: '',
		attendeePw: ''
	}

	if (Auth.isLoggedIn()){
		vm.loadUser();
	}

	/* create a scribblar session and send invites to all people
		in current group
	 */
	vm.createSession = function(){
		vm.session.username = vm.user.username;
		vm.session.user_id = vm.user.id;
		Group.createSession(vm.session)
			.success(function(data){
				vm.sessionUrl = data;
				vm.session = 
				{
					user_id: '',
					username: '',
					meetingName: '',
					moderatorPw: '',
					attendeePw: ''
				}
				vm.loadUser();
			})
	}

	/* close scribblar session */
	vm.closeSession = function(sessionId){
		Group.closeSession(sessionId)
			.success(function(data){
				vm.loadUser();
			})
	}


	/* create a group and send hard coded notification to all users*/
	vm.addGroup = function(){
		vm.message = '';
		vm.groupObj.leader_id = vm.user.id;
		var notificationMsgContent = "A group with your topic preference has been created. The group name is " + 
			vm.groupObj.group_name + ". The Peer leader can be reached at " + vm.user.email + ".";
		var notificationMessage = {
			sender_id: vm.user.id,
			subject: "Group Preference Notification",
			message_type: "groupNotification",
			message: notificationMsgContent
		}

		Group.addGroup(vm.groupObj)
			.success(function(data){
				vm.groupObj = {
					group_name: "",
					leader_id: ""
				};
				vm.addGroupAlert = "alert alert-info";
				vm.addGroupMsg = data;
				vm.loadUser();
			})
			.error(function(data){
				vm.addGroupAlert = "alert alert-danger";
				vm.addGroupMsg = "This group name already exists.";
			})


		Account.sendMessage(notificationMessage)
			.success(function(data){
				vm.groupPreferences = [];
				console.log("Message Successfully sent");
			})
		
	}

	/* invites a member to the current group */
	vm.inviteMember = function(){
		if (!vm.currentGroup){
			vm.groupInviteMsg = "A group must be selected before you can invite members.";
			vm.groupInviteAlert = "alert alert-danger";
		}
		else{
			var invite = {to:vm.memberEmail, from:vm.user.email, group_name: vm.currentGroup.group_name, group_id: vm.currentGroup.id};
			if (invite.to === vm.user.email){
				vm.groupInviteMsg = "You cannot send an invite to yourself";
				vm.groupInviteAlert = "alert alert-danger";
			}
			else{
				Group.inviteMember(invite)
					.success(function(data){
						vm.groupInviteAlert = "alert alert-info";
						vm.memberEmail = "";
						vm.groupInviteMsg = data;
						vm.loadUser();
					})
					.error(function(data){
						vm.groupInviteAlert = "alert alert-danger";
						vm.groupInviteMsg = "This user does not exist.";
					})
			}
		}

	}

	/* recall a group invite sent to specific users
		@param inviteId: id of the invite
	*/
	vm.recallGroupInvite = function(inviteId){
		Group.recallGroupInvite(inviteId)
			.success(function(data){
				vm.loadUser();
			})
			.error(function(data){
			})

	}

	
	/* update the group */
	vm.updateGroup = function(groupData){
		Group.updateGroup(groupData, groupData.id)
			.success(function(data){
				vm.groupUpdateMsg = data;
				vm.groupUpdateAlert = "alert alert-info";
				vm.loadUser();
			})
			.error(function(data){
				vm.groupUpdateMsg = "Unable to edit group.";
				vm.groupUpdateAlert = "alert alert-danger";
			})
	}

	/* remove a specific member from the group
		@param groupData: groupData object to be altered
		@param memberId: id of the member to be removed
	*/
	vm.removeMember = function(groupData, memberId){
		if (memberId == vm.user.id){
			vm.groupEditMsg = "You cannot remove yourself from the group.";
			vm.groupEditAlert = "alert alert-danger";
		}
		else{
			var index = 0;
			for (; index < groupData.users.length; index++){
				if (groupData.users[index].id === memberId){
					break;
				}
			}
			if (index > -1){
				groupData.users.splice(index, 1);
			}
		}
	}
	
	/* deletes a group */
	vm.deleteGroup = function(groupId){
		Group.deleteGroup(groupId)
			.success(function(data){
				vm.groupEditMsg = data;
				vm.groupEditAlert = "alert alert-info";
				vm.loadUser();
			})
			.error(function(data){
				vm.groupEditMsg = "Could not delete group.";
				vm.groupEditAlert = "alert alert-danger";
			})
	}


	/* accept an invite from list of group invites
		then reloads state
	*/
	vm.acceptInvite = function(invite){
		Group.acceptInvite(invite.invite_id)
			.success(function(data){
				vm.loadUser();
			})
	}

	/* decline an invite from list of group invites */
	vm.declineInvite = function(invite){
		//Group.deleteGroup(vm.currentGroup.id);
	}

	/* join a scribblar session and redirect to session page*/
	vm.joinSession = function(sessionId){
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

	/* create a session message and sends invite to all users of group */
	vm.createRoom = function(){
		vm.messageObj.message_type = 'sessionInvite';
		vm.messageObj.sender_id = vm.user.id;
		vm.messageObj.group_id = vm.studyGroup.id;

		vm.userInfo = {
			name: vm.user.name,
			email: vm.user.email,
			username: vm.user.username,
			admin: vm.user.admin,
			leader: vm.user.leader,
			inSession: '1'
		}

		Auth.setInSession(vm.userInfo, vm.user.id)
			.success(function(data){
				Group.inviteGroup(vm.messageObj)
					.success(function(data){
						$state.go('home');
						vm.groupSessionMsg = data;
						vm.groupSessionAlert = "alert alert-info";
						vm.messageObj = {
							message_type: '',
							sender_id: '',
							group_id: '',
							message: ''
						}
					});
			});
		}

	
});
