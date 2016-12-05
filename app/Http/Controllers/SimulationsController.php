<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Simulation;

/*
* Controller to fetch, create, and modify Simulation objects
*
* This class is still under construction and needs to be tested
*/
class SimulationsController extends Controller
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
     * Store a newly created Simulation in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        $simulations = $request->input('simulations');
        $topicId = $request->input('topicId');

        // if request includes information for Simulation, store it in database
        // attach created Simulation to specified Topic
        if (!empty($simulations)){
            foreach ($simulations as $simu){
                $simulation = new Simulation;
                $simulation->simulation_name = $simu['simulation_name'];
                $simulation->url = $simu['url'];
                $simulation->save();
                $simulation->topics()->attach($topicId);
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
