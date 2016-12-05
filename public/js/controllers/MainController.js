/* controller handling front content page
	@param Admin: service for Admin api endpoints;
	@param Auth: service for Auth api endpoints;
	@param $rootScope: object handling root scope of application
	@param $sce: object used to verify urls within iframe
	@param $state: object handling state of application, routing
	@param $stateParams: object containing url parameters
	@param $auth: object handling authentication
	@param $location: object handling location/route of application

*/
app.controller('mainController', function(Admin, Auth, $rootScope, $sce, Search, $state, $stateParams, $auth, $location) {
    var vm = this;
    
	$rootScope.$on('$stateChangeStart', function() {
		vm.loggedIn = Auth.isLoggedIn();
		if (vm.loggedIn){
			var userId = $auth.getPayload().sub;
			Auth.getUser(userId)
			.then(function(result){
				vm.user = result;
			});
		}
		else{
			vm.user = null;
		}
	});

	if ($stateParams.id){
		Admin.get($stateParams.id)
			.success(function(data){
				vm.setTopic(data);
			})
			.error(function(data){
				$state.go('home');
			})
	}

	/* loads user */
	vm.loadUser = function(){
		vm.loggedIn = Auth.isLoggedIn();
		if (vm.loggedIn){
			var userId = $auth.getPayload().sub;
			Auth.getUser(userId)
			.then(function(result){
				vm.user = result;
			});
		}
	}

	vm.loadUser();

    vm.searchQuery = "";

    vm.embedUrl = $sce.trustAsResourceUrl("https://www.youtube.com/embed/6JGQH-olv3M");

    vm.currentTopicName = "Welcome to UCI OpenChem";

    vm.selectedTab = 1;

    vm.scribblarView = "scribblarHidden";

    /* search database with searchQuery variable and populates topicData */
    vm.search = function(){

        vm.processing = true;

    	if (vm.searchQuery === ""){
    		vm.processing = false;
    		vm.searchMessage = "No results were found";
    	}
    	else{


			vm.topicData = '';

			var searchQuery = vm.searchQuery;
			var queryarray = searchQuery.split(' ');
			var articles = ["from", "to", "of", "with", "the", "between"]; // remove these because these words aren't relevant to search
			var searchTerms = [];

			for (var i = 0; i < queryarray.length; i++) {
				if (articles.indexOf(queryarray[i].toLowerCase()) != -1) {
					continue;
				}
				searchTerms.push(queryarray[i].toLowerCase());
			}

			var finalSearchQuery = JSON.stringify(searchTerms);

	        Search.getTopics(finalSearchQuery)
	            .success(function(data){
	                vm.processing = false;
	                vm.topicData = data;
	                if (vm.topicData == ''){
	                    vm.searchMessage = "No results were found";
	                }
	                else{
	                    vm.searchMessage = '';
	                }
	            });
        }


        vm.searchQuery = "";
    }


	/* parse embedded video url
		@param url: url of the video 
	*/
	vm.parseEmbed = function(url){
		var startIndex = url.indexOf('?start=');
		var idIndex = url.indexOf('&v');
		if (startIndex != -1){
			vm.startTime = url.substring(startIndex, idIndex);
		}
	}
	
	/* loads topic into content page, unused as of now
		@param topicId: id of topic to load
	*/
	vm.loadTopic = function(topicId){
		$state.go('topic', {id : topicId});
	}

	/* sets the topic for content page
		@param topic: topic object to load
	*/
	vm.setTopic = function(topic){
		vm.topic = topic;
		vm.currentTopicName = vm.topic.topic_name;
		vm.currentChemTextName = vm.currentChemTextUrl = vm.currentProblemName = vm.currentProblemUrl = vm.currentSolutionName = vm.currentSolutionUrl = '';
		vm.parseEmbed(vm.topic.video_url);
		if (vm.startTime){
			vm.embedUrl = $sce.trustAsResourceUrl('https://www.youtube.com/embed/'+topic.video_id+vm.startTime);
		}
		else{
			vm.embedUrl = $sce.trustAsResourceUrl('https://www.youtube.com/embed/'+topic.video_id);
		}
		if (vm.topic.chemtexts.length > 0){
			vm.currentChemTextName = vm.topic.chemtexts[0].chemtext_name;
			vm.currentChemTextUrl = $sce.trustAsResourceUrl(vm.topic.chemtexts[0].url);
		}
		if (vm.topic.problems.length > 0){
			vm.currentProblemName = vm.topic.problems[0].problem_name;
			vm.currentProblemUrl = $sce.trustAsResourceUrl(vm.topic.problems[0].url);
		}
		if (vm.topic.solutions.length > 0){
			vm.currentSolutionName = vm.topic.solutions[0].solution_name;
			vm.currentSolutionUrl = $sce.trustAsResourceUrl(vm.topic.solutions[0].url);
		}
	}

	/* Change the chemistry text content
		@param chemText: object to load into chemtext iframe
	*/
	vm.changeChemText = function(chemText){
		vm.currentChemTextName = chemText.chemtext_name;
		vm.currentChemTextUrl = $sce.trustAsResourceUrl(chemText.url); 
	}

	/* change the problem content
		@param problem: object to load into problem iframe
	*/
	vm.changeProblem = function(problem){
		vm.currentProblemName = problem.problem_name;
		vm.currentProblemUrl = $sce.trustAsResourceUrl(problem.url);
	}
	
	/* change the solution content
		@param solution: object to load into solution iframe
	*/
	vm.changeSolution = function(solution){
		vm.currentSolutionName = solution.solution_name;
		vm.currentSolutionUrl = $sce.trustAsResourceUrl(solution.url);
	}

	/* log the user out */
	vm.logout = function(){
		$auth.logout().then(function(data){
			$state.reload();
		});
	}

	/* change view of certain tabs 
		@param tab: string of selected tab
	*/
	vm.changeTabs = function(tab){
		vm.selectedTab = tab;

		// if ((vm.scribblarView == "scribblarNone" || vm.scribblarView == "scribblarHidden") && vm.selectedTab == 5){
		if (vm.selectedTab == 5) {
			vm.scribblarView = "scribblarShow";
		}
		else{
			vm.scribblarView = "scribblarHidden";
		}
	}
	/* leaves scribblar session, sets insession flag to 1
		of current user.
	 */

	vm.leaveSession = function(){
		vm.userInfo = {
			name: vm.user.name,
			email: vm.user.email,
			username: vm.user.username,
			admin: vm.user.admin,
			leader: vm.user.leader,
			inSession: '0'
		}

		Auth.setInSession(vm.userInfo, vm.user.id)
			.success(function(data){
				$state.go($state.current, {}, {reload: true});
			});

	}
});