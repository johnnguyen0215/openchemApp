<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Topic;
use App\Keyword;
use App\Chemtexts;
use Log;
use DB;

/*
* Controller to fetch, modify, and create Topic objects
*/
class TopicsController extends Controller
{


    /**
     * Display a listing of all Topics.
     *
     * @return Response
     */
    public function index()
    {
        return Topic::all();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created Topic in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        // save all of the user specified information as Topic in database
        $topic = new Topic($request->all());
        $topic->save();
		$topicId = $topic->id;
		return $topicId;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        // grab all the resources associated with the Topic
        $topic = Topic::find($id);
        $topic->keywords = $topic->keywords()->get(array('id', 'word'));
        $topic->chemtexts = $topic->chemtexts()->get(array('id', 'chemtext_name', 'url'));
        $topic->problems = $topic->problems()->get(array('id', 'problem_name','url'));
        $topic->solutions = $topic->solutions()->get(array('id', 'solution_name', 'url'));
        $topic->simulations = $topic->simulations()->get(array('id', 'simulation_name', 'url'));

        return $topic;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified Topic in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $topic = Topic::find($id);

        $topic->topic_name = $request->input('topic_name');
        $topic->video_id = $request->input('video_id');
        $topic->video_url = $request->input('video_url');
        $topic->video_description = $request->input('video_description');

        $keywords = $request->input('keywords');
        $chemtexts = $request->input('chemtexts');
        $problems = $request->input('problems');
        $simulations = $request->input('simulations');

        $synccedKeywordIds = array_column($keywords, 'id');
        $topic->keywords()->sync($synccedKeywordIds);

        $synccedChemtextIds = array_column($chemtexts, 'id');
        $topic->chemtexts()->sync($synccedChemtextIds);

        $synccedProblemIds = array_column($problems, 'id');
        $topic->problems()->sync($synccedProblemIds);

        $synccedSimulationIds = array_column($simulations, 'id');
        $topic->simulations()->sync($synccedSimulationIds);
        
        $topic->save();

        $topicId = $topic->id;

        return $topicId;
    }

    /**
     * Remove the specified Topic from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $topic = Topic::find($id);
        $topic->delete();
    }

    /*
    * Find all Topic objects relevant to the given query.
    * Every topic and keyword stored in the database containing the search terms in the query will be fetched.
    * The fetched Topics will be "joined" (in the MySQL sense) and rid of repeats.
    * If a search term is to yield no relevant Topics, this function will return an empty array.
    *
    * @param JSON
    * @return array of Topics
    *
    * @TODO optimize efficiency and quality of algorithm of finding relevant Topics
    */
    public function search($query)
    {
    	// decode the query
        $searchTerms = json_decode($query);

        // Log::info($searchTerms);

     //    $query = $this->makeJoinQueryStatement($searchTerms);

     //    Log::info($query);

    	// $result = DB::select(DB::raw($query));

    	// Log::info($result);

    	// foreach ($result as $topic) {
    	// 	$topic->chemtexts;
    	// 	$topic->problems;
    	// }

    	// return $result;

        // OLD WAY *******

    	// $topics = Topic::where('topic_name', 'LIKE', '%'.$query.'%')->get();

    	// foreach ($topics as $topic) {
    	// 	$topic->chemtexts;
    	// 	$topic->problems;
    	// }

    	// return $topics;

    	//return array();

         //*******

        $results = array();
        //$accesspoints = array();

        // used later for merging results together; the first iteration has no other results to merge with
        $firstTime = true;

        // find relevant Topics for each search term in the query
        // append Topic into the final result array
        foreach ($searchTerms as $query) {

            // Log::info($query);

            // fetch all Keyword containing the search term
        	$keywords = Keyword::where('word', 'LIKE', '%'.$query.'%')->get();  	
            // fetch all Topic containing the search term
        	$topics = Topic::where('topic_name', 'LIKE', '%'.$query.'%')->get();

            // if found nothing, quit the search immediately 
        	if (empty($keywords->toArray()) && empty($topics->toArray())) {
        		$results = array();
        		break;
        	}

        	// $accesspoints[$query] = array();

        	//if (!empty($topics->toArray())) {

            // for some reason, this is necessary to fetch the resources; Laravel doesn't automatically fetch
        	foreach ($topics as $topic) {
        		$topic->chemtexts;
        		$topic->problems;
                $topic->solutions;
                $topic->simulations;
        	}
        	//}

        	// Log::info("this is keywords: ".$keywords);

            // if relevant keywords were found, merge Topics with the Keyword to the list of results
   			if (!empty($keywords->toArray())) {
   				$keywords[0]->topics;

				// Log::info($keywords[0]['topics']);

				foreach ($keywords[0]['topics'] as $topic) {
					$topic->chemtexts;
					$topic->problems;
                    $topic->solutions;
                    $topic->simulations;
				}

				if ($firstTime) {
					$results = $keywords[0]['topics']->merge($topics);
				} else {
                    // only merge unique Topics with Keyword to result
					$results->intersect($keywords[0]['topics']->merge($topics));
				}
   			} else {
                // if no relevant keywords were found, merge only the topics
   				if ($firstTime) {
   					$results = $topics;
   				} else {
                    // only merge unique Topics with result
   					$results->intersect($topics);
   				}
   			}

            // change flag after the first iteration
   			$firstTime = false;

    //     	//Log::info($results);

   	// 		//$pointspot = 0;
   	// 		foreach ($results[$query] as $topic) {
   	// 			array_push($accesspoints[$query], $topic['id']);
   	// 			// $accesspoints[$query][$pointspot] = $topic['id'];
   	// 			// $pointspot = $pointspot + 1;
   	// 		}
   			
    	}

    	return $results;

    }

    /*
    * Return the total number of Topics 
    */
    public function getCount(){
        return Topic::count();
    }


}
