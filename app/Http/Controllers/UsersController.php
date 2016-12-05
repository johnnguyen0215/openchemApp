<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Hash;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\User;
use App\Groupinvite;
use App\Group;
use Log;
use DB;

/*
* Controller to fetch, create, and modify User objects
*/
class UsersController extends Controller
{
    /**
     * Display a listing of all Users.
     *
     * @return Response
     */
    public function index()
    {
        return User::all();
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
     * Store a newly created User in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        $user = new User;
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->username = $request->input('username');
        $user->password = Hash::make($request->input('password'));
        $user->inSession = $request->input('inSession');
        // automatically make new users a leader; hardcoded for demo
        $user->leader = "1"; // delete this line after demo
        $user->save();
        return $user;
    }

    /**
     * Display the specified User.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $user = User::find($id);
        $user->groups = $user->groups()->get();
        
        foreach($user->groups as $group){
            $group->users = $group->users()->get();
        }

        // legacy code; may not need anymore but kept uncommented in case system needs it
        $user->sessions = $user->bbbsessions;

        // grab all the messages associated with the user
        $user->messages = $user->messages()->get();

        // grab the group name for any messages that are study session invitations to display on the front end
        foreach ($user->messages as $message){
            $message->sender_name = User::find($message->sender_id)->name;
            if ($message->message_type == 'sessionInvite'){
                $message->group_name = Group::find($message->group_id)->group_name;
            }
        }
        
        // run query to fetch the names of the group of a group invite
        $user->invites = DB::table('groupinvites')
            ->join('users', 'users.id', '=', 'groupinvites.sender_id')
            ->join('groups', 'groups.id', '=', 'groupinvites.group_id')
            ->where('groupinvites.recipient_id', $id)
            //->get();
            ->select('users.name', 'groups.group_name', 'groups.id as group_id', 'groupinvites.id as invite_id')
            // ->get(array(
            //     'name' => 'users.name', 
            //     'group_name' => 'groups.group_name', 
            //     'group_id' => 'groups.id',
            //     'invite_id' => 'groupinvites.id'));
            ->get();

        //grab the users who were the recipients of the invites this current user sent
        $user->invitedMembers = DB::table('groupinvites')
            ->join('users', 'users.id', '=', 'groupinvites.recipient_id')
            ->join('groups', 'groups.id', '=', 'groupinvites.group_id')
            ->where('groupinvites.sender_id', $id)
            ->select('users.name', 'groups.group_name', 'groups.id as group_id', 'groupinvites.id as invite_id')
            ->get();
            // ->get(array(
            //     'name' => 'users.name',
            //     'group_name' => 'groups.group_name',
            //     'id' => 'groups.id',
            //     'invite_id' => 'groupinvites.id'));

        return $user;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {

    }

    /**
     * Update the specified User in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {

        $user = User::find($id);
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->username = $request->input('username');
        $user->admin = $request->input('admin');
        $user->leader = $request->input('leader');
        $user->inSession = $request->input('inSession');
        $user->save();
        return $user;
    }

    /**
     * Remove the specified User from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $user = User::find($id);
        $user->delete();
    }

    /*
    * Return total number of Users
    *
    * @return int
    */
    public function getCount(){
        return User::count();
    }

}
