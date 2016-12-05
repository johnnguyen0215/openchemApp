/* service for calling Admin related api endpoints to laravel backend */
app.factory('Admin', function($http) {

	var adminFactory = {};

	/* get current topic
		@topicId: topic id for getting a topic
	*/
	adminFactory.get = function(topicId) {
		return $http.get('/api/topics/' + topicId);
	};

	/* get all topics */
	adminFactory.allTopics = function() {
		return $http.get('/api/topics');
	};

	/* create a topic 
		@param topicData: data for uploading a topic to the db
		*/
	adminFactory.createTopic = function(topicData) {
		return $http.post('/api/topics', topicData);
	};

	/* input a list of keywords to the db
		@param keywordData: list of keywords to add to the db
	*/
	adminFactory.createKeywords = function(keywordData){
		return $http.post('/api/keywords', keywordData);
	};

	/* input a list of chemistry text objects to the db
		@param chemtextData: list of chem text objects
	*/
	adminFactory.createChemtexts = function(chemtextData){
		return $http.post('/api/chemtexts', chemtextData);
	};

	/* input a list of problem text objects to the db
		@param problemData: list of problem objects
	*/
	adminFactory.createProblems = function(problemData){
		return $http.post('api/problems', problemData);
	};

	/* update user information
		@param topicId: id of topic to update
		@param topicData: data to update
	*/
	adminFactory.update = function(topicId, topicData) {
		return $http.put('/api/topics/' + topicId, topicData);
	};

	/* delete a topic from the db
		@param topicId: id of topic to delete
	*/
	adminFactory.deleteTopic = function(topicId) {
		return $http.delete('/api/topics/' + topicId);
	};
	/* get file upload directory from backend */
	adminFactory.getUploadDirectory = function(){
		return $http.get('/api/admin/uploadDirectory');
	};

	/* get all users registered from the db */
	adminFactory.allUsers = function(){
		return $http.get('/api/users');
	};

	/* delete a user from the db */
	adminFactory.deleteUser = function(userId){
		return $http.delete('/api/users/' + userId);
	};

	/* get total number of topics in the db */
	adminFactory.getTopicCount = function(){
		return $http.get('/api/topics/getCount');
	};


	return adminFactory;
});