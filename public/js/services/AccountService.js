/* service for calling Account related api endpoints to laravel backend */ 
app.factory('Account', function($http) {

	// create a new object
	var accountFactory = {};

	/* deletes a message 
		@param messageId: id of message to delete
	*/
	accountFactory.deleteMessage = function(messageId){
		return $http.delete('/api/message/' + messageId);	
	};

	/* sends a message
		@param message: message object to send
	*/
	accountFactory.sendMessage = function(message){
		console.log(message);
		return $http.post('/api/message', message);
	};

	return accountFactory;

});