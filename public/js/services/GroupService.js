/* service for calling Group related api endpoints on the laravel backend */
app.factory('Group', function($http) {
	var groupFactory = {}
 	
 	/* add a group 
		@param groupData: group information needed to upload
 	*/
 	groupFactory.addGroup = function(groupData){
 		return $http.post('/api/groups', groupData);
 	};

 	/* update a group
		@param groupData: updated group data
		@param groupId: id of group to be updated
 	*/
 	groupFactory.updateGroup = function(groupData, groupId){
 		return $http.put('/api/groups/'+groupId, groupData);
 	};

 	/* delete a group
		@param groupId: id of group to be deleted
 	*/
 	groupFactory.deleteGroup = function(groupId){
 		return $http.delete('/api/groups/'+groupId);
 	};

 	/* get a group from the db
		@param groupId: id of group to get
 	*/
 	groupFactory.getGroup = function(groupId){
 		return $http.get('/api/groups/'+groupId);
 	};

 	/* get a list of groups 
		@param groupsArray: list of groups to get from the db
 	*/
 	groupFactory.getGroups = function(groupsArray){
 		return $http.post('/api/groups/getGroups', groupsArray);
 	};
 	
 	/* get all groups */
 	groupFactory.allGroups = function(){
 		return $http.get('/api/groups');
 	};

 	/* add a group member by name by sending them an invite 
		@param memberName: name of the member to be invited
		@param invite: invitation information to send
 	*/
 	groupFactory.addGroupMember = function(memberName, invite){
 		return $http.put('/api/addGroupMember/'+memberName, invite);
 	};

 	/* invite a member to group
		@param invite: invite to send to all members
 	*/
 	groupFactory.inviteMember = function(invite){
 		return $http.post('/api/groupinvite', invite);
 	};

 	/* recall a group invite, remove from all inboxes
		@param inviteId: id of invite to remove
 	*/
 	groupFactory.recallGroupInvite = function(inviteId){
 		return $http.delete('/api/groupinvite/' + inviteId);
 	};

 	/* accept an invite to join group
		@param inviteId: id of invite to accept
 	*/
 	groupFactory.acceptInvite = function(inviteId){
 		return $http.get('/api/groupinvite/accept/' + inviteId);
 	};

 	/* invite entire group to a session
		@param message: message to be sent to all group members
 	*/
	groupFactory.inviteGroup = function(message){
		console.log(message);
		return $http.post('/api/message', message);
	};

	/* get total number of open sessions */
	groupFactory.getSessionCount = function(){
		console.log("getting session count");
		return $http.get('/api/bbb/getCount');
	};


	return groupFactory;
});