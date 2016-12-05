<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Groupinvite;
use App\Group;
use App\User;
use Log;

/*
* Controller to manage GroupInvites between Users
*/
class GroupinvitesController extends Controller
{

    /*
    * Fetch the GroupInvites id, attach the Group to the Recipient, and delete the GroupInvite
    * (accepted GroupInvites are no longer needed)
    *
    * @param $id int
    */
    public function acceptInvite($id)
    {
        //Log::info("made it to acceptInvite with invite_id of: ".$id);
        $invite = Groupinvite::find($id);

        $group = Group::find($invite->group_id);
        $group->users()->attach($invite->recipient_id);

        $this->destroy($id);
    }

    /**
     * Fetch all GroupInvites 
     *
     * @return Response
     */
    public function index()
    {
        return Groupinvite::all();
    }

    /**
     * Create GroupInvite to a Group by taking User id of the sender and the recipient
     *
     * @param  Request  $request
     * @return String
     */
    public function store(Request $request)
    {

        $invite = new Groupinvite;

        // grab User id of the sender
        $invite->sender_id = User::where('email', $request->input('from'))->first()->id;
        // grab User id of the recipient
        $invite->recipient_id = User::where('email', $request->input('to'))->first()->id;

        $invite->group_id = $request->input('group_id');

        $invite->save();

        return "Successfully sent group invite.";

    }


    /**
     * Display the specified GroupInvite.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $invite = Groupinvite::find($id);

        return $invite;
    }

    /**
     * Remove the specified GroupInvite from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {

        $invite = Groupinvite::find($id);

        $invite->delete();
    }

    /*
     * Add user to a group and then remove the invite from storage
    */

}
