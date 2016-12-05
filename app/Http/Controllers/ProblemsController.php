<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Log;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Problem;

/*
* Controller to fetch, create, and modify Problem objects
*/
class ProblemsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created Problem in storage.
     *
     * @param  Request  $request
     */
    public function store(Request $request)
    {
        $problems = $request->input('problems');
		$topicId = $request->input('topicId');

        // if requests includes Problem information, store in database
        // attach created Problem object to the specified Topic
		if (!empty($problems)){
			foreach ($problems as $prob){
				$problem = new Problem;
				$problem->problem_name = $prob['problem_name'];
				$problem->url = $prob['url'];
				$problem->save();
				$problem->topics()->attach($topicId);
			}
		}
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
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
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
