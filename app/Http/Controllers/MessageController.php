<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Message;
use App\Group;
use App\User;
use App\Bbbsession;
use Log;

/*
* Controller to fetch and modify Message objects
*/
class MessageController extends Controller
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
     * Store a newly created Message in storage.
     *
     * @param  Request  $request
     * @return String
     */
    public function store(Request $request)
    {
    	// Log::info("message received");
        
        // save the user input
        $message_type = $request->input('message_type');
        $sender_id = $request->input('sender_id');
        $message_content = $request->input('message');

        // if the message is to invite users to a study session
        if ($message_type == 'sessionInvite') {

            // fetch the Group relevant to the study session
            $group_id = $request->input('group_id');
            $group = Group::find($group_id);
            $recipients = $group->users->lists('id');

            // create a Message for every user in the group
            foreach ($recipients as $recipient) {
            	$receiver = User::find($recipient);            	

                $message = new Message;
                $message->message_type = $message_type;
                $message->sender_id = $sender_id;
                $message->user_id = $recipient;
                //$message->message_content = app('app\Http\Controllers\BigbluebuttonController')->join($receiver->name, $session->meeting_id, $session->attendeepw);
                $message->message_content = $message_content;
                $message->group_id = $group_id;
                //$message->group_name = $group->group_name;
                //$message->message_content = $this->join($receiver->name, $session->meeting_id, $session->attendeepw);
                $message->save();
             
                $receiver->messages()->save($message);

            }
            
        }
        // if the message is to invite users to a Group
        else if ($message_type == 'groupinvite') {

            // fetch id of Group
            $group_id = $request->input('group_id');

            // create a Message for every User invited
            foreach ($recipients as $recipient) {
                $message = new Message;
                $message->message_type = $message_type;
                $message->sender_id = $sender_id;
                $message->recipient_id = $recipient;
                $message->message_content = $message_content;
                $message->save();
            }

        }
        // if the message is a custom message
        else if ($message_type == 'userMessage') {

        	$message_subject = $request->input('subject');
        	$recipients = $request->input('recipients');

            // no group is associated with the message
            // $group_id = -1;

        	$badEmail = array();

        	foreach ($recipients as $recipient) {
        		$recipient_id = $this->findId($recipient);
                Log::info($recipient_id);
                Log::info(gettype($recipient_id));
        		if ($recipient_id == -1) {
        			// add recipient to bad email array
        			$badEmail[] = $recipient;
        		} else {
	        		// user-written message
		            $message = new Message;
		            $message->message_type = $message_type;
		            $message->sender_id = $sender_id;
		            $message->user_id = $recipient_id;
		            $message->message_subject = $message_subject;
		            $message->message_content = $message_content;
                    // $message->group_id = $group_id;
		            $message->save();
	        	}
        	}

        	// feedback to front end
        	return $badEmail;         
        }
        // if the message is to notify users for a creation of Group with a topic of their interest
        /*
        * NOTE:
        * This branch of if statement includes hardcoded values.
        *
        * @TODO implement actual functionality to make this feature possible
        */
        else if ($message_type == 'groupNotification') {

        	$message_subject = $request->input('subject');

            // fetch all the users to send the message to every user
            // @TODO implement way to fetch only relevant users
        	$recipients = User::all();

            // create message for every user to send the message to
        	foreach ($recipients as $recipient) {
        		if ($recipient->id != $sender_id) {
	        		$message = new Message;
	        		$message->message_type = $message_type;
	        		$message->sender_id = $sender_id;
	        		$message->user_id = $recipient->id;
	        		$message->message_subject = $message_subject;
	        		$message->message_content = $message_content;
	        		$message->save();
        		}
        	}
        	// notification to group leader that users are interested

        	// find the users that are interested in the group
        	$recipient = User::find(9); // hard-coded user id. change depending on usage
        	// the message to be sent to the group leader
        	$notification_message = "The user \"".$recipient->username."\" is interested in the topic of your group. Add them to your group
        	 with the email \"".$recipient->email."\".";

        	$message = new Message;
        	$message->message_type = $message_type;
        	$message->sender_id = $sender_id;
        	$message->user_id = $sender_id;
        	$message->message_subject = $message_subject;
        	$message->message_content = $notification_message;
        	$message->save();
        }
        // @TODO add functionality for group messages

        return "invite successfully sent";
   
    }

    /**
     * Find the user_id associated with a given string which represents an email address
	 *
	 * @param  string $recipient
	 * @return int $id
     */
    private function findId($recipient)
    {
    	$recipient_id = User::where("email", "=", $recipient)->first(['id']);
    	if ($recipient_id == null) {
    		return -1;
    	}

    	return (int)$recipient_id->id;
    }

    /**
     * Display the specified Message.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($user_id)
    {
        $messages = Message::where('recipient_id', '=', $user_id)->get();

        return $messages;
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
     * Remove the specified Message from storage.
     *
     * @param  int  $id
     */
    public function destroy($id)
    {
        $message = Message::find($id);
        $message->delete();
    }


}
