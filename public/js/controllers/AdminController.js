/* 	Controller for the admin page 
	@param Admin: service for handling Admin api endpoints
	@param Group: service for handling Group api endpionts
	@param Auth: service for handling Auth api endpoints
	@param $auth: satellizer dependency for handling authentication
*/
app.controller('adminController', function(Admin, Group, Auth, $auth){
	var vm = this;

	var userId = $auth.getPayload().sub;
	
	vm.userCount = vm.topicCount = vm.sessionCount = 0;

	/* Gets number of users in the app */ 
	Auth.getUserCount()
		.success(function(data){
			vm.userCount = data;
		})

	/* Gets number of topics in the app */
	Admin.getTopicCount()
		.success(function(data){
			vm.topicCount = data;
		})

	/* load user */
	Auth.getUser(userId).then(function(result){
		vm.user = result;
	})
	
})
/* 	Controller for uploading topics 
	@param Auth: service for handling authentication endpionts
	@param Admin: service for handling administrator endpoints
	@param $stateParams: object for getting route parameters
	@param $state: object for handling application's state, routing
*/
.controller('topicController', function(Auth, Admin, $stateParams, $state) {
	var vm = this;
	
	/* Get the directory for uploading files */
	Admin.getUploadDirectory()
		.success(function(data){
			vm.files = data;
		});
	
	/* If type param is edit then load pre-existing data */
	if ($stateParams.id){
		vm.type = 'Edit';
		Admin.get($stateParams.id)
			.success(function(data){
				vm.topicData = data;
			})
	}
	else{
		vm.type = 'Create';
	}
	

	vm.chemtextFileName = vm.problemFileName = "File Dropdown";


	vm.videoUrlModel = ''; // Stores the url 

	vm.keyword = ''; // Stores the keyword being input

	vm.chemtextObj = {'chemtext_name': '', 'url':'', 'chemtext_type':''};

	vm.problemObj = {'problem_name': '', 'url':'', 'problem_type':''};

	vm.message = ''; // stores the message to be displayed upon submission

	vm.chemtextMsg = '';

	vm.problemsMsg = '';

	vm.alertmsg; // Used to change styling of the message

	vm.topicData = {
		'topic_name': '',
		'video_id': '',
		'video_url': '',
		'video_description': '',
		'keywords': [],
		'chemtexts': [],
		'problems': []
	};

	vm.editTopicData = {
		'keywords': [],
		'chemtexts': [],
		'problems':[]
	}

	
	vm.fieldColors = {'nameColor': 'blackHighlight', 'uploadVideoColor': 'blackHighlight', 'keywordsColor': 'blackHighlight',
	'videoDescColor': 'blackHighlight'};

	/* 	used to change the the chem text file 
		@param filename: string specifying which file to load
	*/
	vm.changeChemTextFile = function(fileName){
		vm.chemtextObj.url = "./uploads/"+ fileName;
		vm.chemtextFileName = fileName;
	}

	/* used to change the problem file 
		@param fileName: string specifying which file to load
	*/
	vm.changeProblemFile = function(fileName){
		vm.problemObj.url = "./uploads/"+ fileName;
		vm.problemFileName = fileName;
	}

	/* 	used to parse the id from the video url
		@param url: string of the video url
	*/
	vm.parseId = function(url){
		vm.topicData.video_id = url.split('v=')[1];
		var ampersandPosition = vm.topicData.video_id.indexOf('&');
		if(ampersandPosition != -1) {
		  vm.topicData.video_id = vm.topicData.video_id.substring(0, ampersandPosition);
		}
	}
	
	/* inputs the video url from input tag */
	vm.inputVideoUrl = function(){
		vm.topicData.video_url = vm.videoUrlModel;
		vm.parseId(vm.videoUrlModel);
		vm.videoUrlModel = '';
	}

	/* 	checks if array of objects has specific value
		@param array: array of objects to check
		@param attr: specific attribute to check
		@param value: value to check object attribute against
	*/
	vm.findWithAttr = function(array, attr, value) {
	    for(var i = 0; i < array.length; i += 1) {
	        if(array[i][attr] === value) {
	            return i;
	        }
    	}
    	return -1;
	}

	/* inputs keyword into list of topic's tags */
	vm.inputKeyword = function(){
		if (vm.type === 'Edit'){
			var containsIndex = vm.findWithAttr(vm.topicData.keywords, 'word', vm.keyword);
			if (!($.inArray(vm.keyword, vm.editTopicData.keywords) > -1) && containsIndex === -1){
				vm.editTopicData.keywords.push(vm.keyword);
        	}
		}
		else{
	        if (!($.inArray(vm.keyword, vm.topicData.keywords) > -1)){
	            vm.topicData.keywords.push(vm.keyword);
	        }
	    }
	    vm.keyword = '';
	}

	/* deletes keywords from list of topic's tags */
	vm.deleteKeyword = function(keyword){
		if (vm.type === 'Edit'){
			var index = vm.findWithAttr(vm.topicData.keywords, 'id', keyword.id);
			if (index > -1){
				vm.topicData.keywords.splice(index,1);	
			}
		}
		else{
	        var index = vm.topicData.keywords.indexOf(keyword);
	        if (index > -1){
	            vm.topicData.keywords.splice(index, 1);
        	}
        }
	}

	/* deletes topic keyword when in edit mode */
	vm.deleteEditTopicKeyword = function(keyword){
        var index = vm.editTopicData.keywords.indexOf(keyword);
        if (index > -1){
            vm.editTopicData.keywords.splice(index, 1);
    	}
	}

	/* checks of pdf file */
	vm.isPdf = function(url){
		if (url.indexOf('.pdf') > -1){
			return true;
		}
		return false;
	}

	/* inputs chem text object */
	vm.inputChemTextObj = function(){
		if (!vm.chemtextObj.chemtext_name || !vm.chemtextObj.url){
			vm.chemtextMsg = "Please input a value in both fields.";
		}
		else{
			if (vm.isPdf(vm.chemtextObj.url)){
				vm.chemtextObj.chemtext_type = "pdf";
			}
			else {
				vm.chemtextObj.chemtext_type = "link";
			}
			if (vm.type === 'Edit'){
				vm.editTopicData.chemtexts.push(vm.chemtextObj);
			}
			else{
				vm.topicData.chemtexts.push(vm.chemtextObj);
			}
			vm.chemtextObj = {};
			vm.chemtextMsg = '';
		}
	}

	/* deletes chem text object 
		@param chemtextObject: object to delete
	*/
	vm.deleteChemTextObj = function(chemtextObject){
		var index = vm.topicData.chemtexts.indexOf(chemtextObject);
		if (index > -1){
			vm.topicData.chemtexts.splice(index,1);
		}
		
	}

	/* delete chem text object when in edit mode
		@param chemtextObject: object to delette
	*/
	vm.deleteEditTopicChemTextObj = function(chemtextObject){
		var index = vm.editTopicData.chemtexts.indexOf(chemtextObject);
		if (index > -1){
			vm.editTopicData.chemtexts.splice(index,1);
		}
	}

	/* input problem object */
	vm.inputProbObj = function(){
		if (!vm.problemObj.problem_name || !vm.problemObj.url){
			vm.problemsMsg = "Please input a value in both fields.";
		}
		else{
			if (vm.isPdf(vm.problemObj.url)){
				vm.problemObj.type = "pdf";
			}
			else {
				vm.problemObj.type = "link";
			}
			if (vm.type === 'Edit'){
				vm.editTopicData.problems.push(vm.problemObj);
			}
			else{
				vm.topicData.problems.push(vm.problemObj);
			}
			vm.problemObj = {};
			vm.problemsMsg = '';
		}
	}

	/* delete problem object 
		@param problem: problem object to delete
	*/
	vm.deleteProbObj = function(problem){
		var index = vm.topicData.problems.indexOf(problem);
		if (index > -1){
			vm.topicData.problems.splice(index,1);
		}
	}

	/* delete problem object in edit mode
		@param problem: problem object to delete
	*/
	vm.deleteEditTopicProbObj = function(problem){
		var index = vm.editTopicData.problems.indexOf(problem);
		if (index > -1){
			vm.editTopicData.problems.splice(index,1);
		}
	}

	/* checks if all required fields are complete and sets
		style of notification

	*/
	vm.checkCompletion = function(){
		var numOfIncomplete = 0;
		if (vm.topicData.topic_name == ''){
			vm.fieldColors['nameColor'] = 'redHighlight'; numOfIncomplete++;	
		}
		else{
			vm.fieldColors['nameColor'] = 'blackHighlight';
		}
		if (vm.topicData.video_id == ''){
			vm.fieldColors['uploadVideoColor'] = 'redHighlight'; numOfIncomplete++;
		}
		else{
			vm.fieldColors['uploadVideoColor'] = 'blackHighlight';
		}

		if (vm.topicData.video_description == ''){
			vm.fieldColors['videoDescColor'] = 'redHighlight'; numOfIncomplete++;
		}
		else{
			vm.fieldColors['videoDescColor'] = 'blackHighlight';
		}

		return numOfIncomplete;
	}
	
	/* uploads topic to the database */
	vm.uploadTopic = function(){
		vm.processing = true;
		vm.message = '';
		var complete = vm.checkCompletion();
		if (complete != 0){
			vm.alertmsg = "alert alert-danger";
			vm.message = "Please input a value in the highlighted fields";
		}
		else{
			Admin.createTopic(vm.topicData)
				.success(function(data){
					var keywordObj = {keywords : vm.topicData.keywords, topicId: data};
					var chemtextObj = {chemtexts: vm.topicData.chemtexts, topicId: data};
					var problemObj = {problems: vm.topicData.problems, topicId: data};
					
					Admin.createKeywords(keywordObj)
						.success(function(data){
						});
					Admin.createChemtexts(chemtextObj)
						.success(function(data){
						});
					Admin.createProblems(problemObj)
						.success(function(data){
						});
					
					vm.topicData = {
						'topic_name': '',
						'video_id': '',
						'video_url': '',
						'video_description': '',
						'keywords': [],
						'chemtexts': [],
						'problems': []
					};
					vm.message = data.message;
				});
			vm.alertmsg = "alert alert-info";
			vm.keyword = '';
			vm.videoUrlModel = '';
		}
	}
	
	/* update the existing topic in the database */
	vm.updateTopic = function(){
		vm.processing = true;
		vm.message = '';
		var complete = vm.checkCompletion();
		if (complete != 0){
			vm.alertmsg = "alert alert-danger";
			vm.message = "Please specify a value in the highlighted fields";
		}
		else{
			Admin.update($stateParams.id, vm.topicData)
				.success(function(data){

					var keywordObj = {keywords : vm.editTopicData.keywords, topicId: data};
					var chemtextObj = {chemtexts: vm.editTopicData.chemtexts, topicId: data};
					var problemObj = {problems: vm.editTopicData.problems, topicId: data};
					
					Admin.createKeywords(keywordObj);
					Admin.createChemtexts(chemtextObj);
					Admin.createProblems(problemObj);
					vm.processing = false;
					vm.topicData = {};
					$state.go('edit_topics');
					vm.message = data.message;
				});
			vm.alertmsg = "alert alert-info";
			vm.keyword = '';
			vm.videoUrlModel = '';
		}
	}

})
/* controller handling logic in the edit topic page 
	@param Admin: service for handling Admin api endpionts
*/
.controller('editTopicsController', function(Admin){
	var vm = this;
	vm.type = 'Edit';

	vm.processing = true;
	/* loads all topics into the ui topic list */
	Admin.allTopics()
		.success(function(data){
			vm.topics = data;
			vm.processing = false;
		})
	/* deletes topic from list of topics */
	vm.deleteTopic = function(topicId){
		vm.processing = true;
		Admin.deleteTopic(topicId)
			.success(function(data){
				vm.message = data.message;

				Admin.allTopics()
					.success(function(data) {
						vm.topics = data;
						vm.processing = false;
					});

			});

	}
})
/* controller handling logic in the edit users page
	@param Admin: service for handling Admin api endpionts
*/
.controller('editUsersController', function(Admin){
	var vm = this;

	vm.processing = true;
	/* loads all users into the ui users list */
	Admin.allUsers()
		.success(function(data){
			vm.users = data;
			vm.processing = false;
		})
	/* deletes user from list of users */
	vm.deleteUser = function(userId){
		vm.processing = true;
		Admin.deleteUser(userId)
			.success(function(data){
				vm.message = data.message;

				Admin.allUsers()
					.success(function(data) {
						vm.users = data;
						vm.processing = false;
					});

			});

	}
});
