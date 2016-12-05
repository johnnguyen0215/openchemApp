<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Log;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Keyword;
use App\Topic;

/*
* Controller to fetch, create, and modify Keyword objects
*/
class KeywordsController extends Controller
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
     * Store a newly created Keyword in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {	
		$keywords = $request->input('keywords');
		$topicId = $request->input('topicId');
		
		if (!empty($keywords)){
			
			foreach ($keywords as $word){
				$keyword = new Keyword;
				$keyword->word = $word;
				$keyword->save();
				$keyword->topics()->attach($topicId);
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
     * Remove the specified Keyword from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $keyword = Keyword::find($id);
        $keyword->delete();
    }
}
