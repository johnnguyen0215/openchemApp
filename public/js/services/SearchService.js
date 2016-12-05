/* service for calling Service related api endpoints on the laravel backend */
app.factory('Search', function($http) {

	var searchFactory = {};

	/* get a list of topics with the given query
		@param query: String search query
	*/
	searchFactory.getTopics = function(query) {
		console.log(query);
		return $http.get('/api/topics/search/'+ query);
	};

	return searchFactory;

});