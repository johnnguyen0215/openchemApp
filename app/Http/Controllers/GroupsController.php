<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Group;
use App\User;
use Log;

/*
* Controller to fetch and modify Group
*/
class GroupsController extends Controller
{
    /**
     * Fetch all Groups.
     *
     * @return Response
     */
    public function index()
    {
        return Group::all();
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
     * Store a newly created Group in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        // create a Group object given user request and store in database
        // attach group to the user to set as the leader
        $group = new Group;
        $group->group_name = $request->input('group_name');
        $saved = $group->save();
        $group->users()->attach($request->input('leader_id'));
        return "Successfully created group.";
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
     * Update the specified Group in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return String
     */
    public function update(Request $request, $id)
    {
        $group = Group::find($id);
        
        $group->group_name = $request->input('group_name');

        $users = $request->input('users');

        $synccedUserIds = array_column($users, 'id');
        $group->users()->sync($synccedUserIds);

        return "Successfully updated group.";
    }

    /**
     * Remove the specified Group from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $group = Group::find($id);
        $group->delete();
    }
}
