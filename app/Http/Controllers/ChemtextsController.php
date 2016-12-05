<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Log;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Chemtext;

/*
* Controller to fetch, create, and modify Chemtext
*/
class ChemtextsController extends Controller
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
     * Store a newly created Chemtext in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        // retrieve user input
        $chemtexts = $request->input('chemtexts');
		$topicId = $request->input('topicId');

        // if request includes  chemtexts information, store the information in database
        // attach the Chemtext to the given Topic
		if (!empty($chemtexts)){
			foreach ($chemtexts as $text){
				$chemtext = new Chemtext;
				$chemtext->chemtext_name = $text['chemtext_name'];
				$chemtext->url = $text['url'];
				$chemtext->save();
				$chemtext->topics()->attach($topicId);
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
