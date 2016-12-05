<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Bbbsession;
use App\User;
use Log;

/*
* Controller to interact with BigBlueButton
* 
* This class is depracated - no longer using BigBlueButton
*/
class BigbluebuttonController extends Controller
{

    private $securitysalt = "346d628c91bc9e6047a8820682b3ed8c"; // never show this to the users

    private $apiurl = "http://52.88.195.94/bigbluebutton/api/"; // to make api calls to BBB

    /**
     * Create and return an URL for a new BBB session
     * 
     * Currently called "store" because that's what Laravel routes to
     *
     * @return String
     */
    public function index()
    {
        
    }


    public function store(Request $request)
    {
        $name = $this->formatusername($request->input('username'));
        $meetingName = $this->formatmeetingname($request->input('meetingName'));
        $meetingID = uniqid('', true);
        $moderatorpw = $request->input('moderatorPw');
        $attendeepw = $request->input('attendeePw');

        $user_id = $request->input('user_id');

        // Log::info("request: ".$request);

        // Log::info("moderatorpw: ".$moderatorpw);
        // Log::info("attendeepw: ".$attendeepw);

        // create checksum for request and obtain XML
        $querystring = "name=".$meetingName."&meetingID=".$meetingID."&attendeePW=".$attendeepw."&moderatorPW=".$moderatorpw;

        $checksum = $this->createchecksum("create", $querystring);

        $createurl = $this->apiurl."create?".$querystring."&checksum=".$checksum;

        // Log::info($createurl);

        // return $createurl;

        $xmlresponse = simplexml_load_string(file_get_contents($createurl));

        if ($xmlresponse->returncode == strtoupper("success")) {

            $session = new Bbbsession(); // save the session information upon success of creating the room
            $session->meeting_name = $meetingName;
            $session->meeting_id = $meetingID;
            $session->modpw = $moderatorpw;
            $session->attendeepw = $attendeepw;
            $session->save();

            $user = User::find($user_id);
            $user->bbbsessions()->attach($session->id);

            $querystring = "meetingID=".$meetingID."&password=".$moderatorpw."&fullName=".$name;
            $checksum = $this->createchecksum("join", $querystring);
            $joinurl = $this->apiurl."join?".$querystring."&checksum=".$checksum;

            // Log::info($joinurl);

            return $joinurl; // need to return to a view
        } 

        return "failed"; // need to return to a view
    }

    public function getSessionUrl(Request $request) {
        // when the parameter was $request
        $username = $this->formatusername($request->input('username'));
        $sessionId = $request->input('session_id');

        $session = Bbbsession::find($sessionId);

        $meetingID = $session->meeting_id;
        $password = $session->attendeepw;

       // $username = $this->formatusername($username);

        // check that the meeting is running
        if ($this->isMeetingRunning($meetingID)) {
            Log::info("Meeting is Running");
            $querystring = "fullName=".$username."&meetingID=".$meetingID."&password=".$password;

            $checksum = $this->createchecksum("join", $querystring);

            $createurl = $this->apiurl."join?".$querystring."&checksum=".$checksum;

            Log::info($createurl);

            //Log::info(file_get_contents($createurl));

            $returnedxml = file_get_contents($createurl);

            // Log::info(gettype($test_string));


            $isvalidurl = strpos($returnedxml, "<returncode>FAILED</returncode>");

            if ($isvalidurl === false) {
                Log::info("returning the valid url");
                return $createurl;
            }


            // try {
            //     $xmlresponse = simplexml_load_string(file_get_contents($createurl));
            // } catch (Exception $e) {
            //     // on success, xml is not returned by url
            //     return $createurl;
            // }
            
            // if exception is not caught, xml was generated, which indicates an error in joining room


            // Log::info($xmlresponse->returncode);


            // if ($xmlresponse->returncode != strtoupper("failed")) {
            //     return $createurl;
            // }
        }

        return "failed";
    }

    public function isInMeeting(Request $request) {
        $username = $request ->input('username');
        $meetingID = $request->input('meetingID');
        $password = $request->input('password');

        //check that someone with given username is in the meeting
        $querystring = "meetingID=".$username."$password=".$password;

        $checksum = $this->createchecksum("getMeetingInfo", $querystring);

        $createurl = $this->apiurl."getMeetingInfo?".$querystring."&checksum=".$checksum;

        $xmlresponse = simplexml_load_string(file_get_contents($createurl));

        if ($xmlresponse->returncode != strtoupper("failed")) {
            foreach ($xmlresponse->attendees as $attendee) {
                if ($attendee->fullName == $username) {
                    return 1; // someone with the given username is in the meeting
                }
            }

            return 0; // someone with the given username was not in the meeting
        }

        return -1; //somehow the api call failed
    }

    public function isMeetingRunning($meetingID) {
        $querystring = "meetingID=".$meetingID;

        $checksum = $this->createchecksum("isMeetingRunning", $querystring);

        $createurl = $this->apiurl."isMeetingRunning?".$querystring."&checksum=".$checksum;

        $xmlresponse = simplexml_load_string(file_get_contents($createurl));

        Log::info($createurl);

        if ($xmlresponse->returncode == strtoupper("success")) {
            return true;
        }

        return false;
    }

    /*
     * =====    IMPORTANT    =====
     * 
     * endMeeting() will not necessary end the meeting as soon as it is called. The API call tells the backend server of BBB to send out
     * a "logout" signal and kicks them out. Depending on network conditions, it may take several seconds for the process of ending the
     * meeting to complete. That is why it's important to send confirmation to the front end in delete() that the deletion of the 
     * session information has been successful or not, because deletion may fail on some circumstance with just one call.
     */
    private function endMeeting($id) {
        $session = Bbbsession::find($id);

        $meetingID = $session->meeting_id;
        $moderatorpw = $session->modpw;

        $querystring = "meetingID=".$meetingID."&password=".$moderatorpw;

        $checksum = $this->createchecksum("end", $querystring);

        $createurl = $this->apiurl."end?".$querystring."&checksum=".$checksum;

        $xmlresponse = simplexml_load_string(file_get_contents($createurl));

        if ($xmlresponse->returncode == strtoupper("success")) {
            return true; // meeting was successfully ended
        }

        return false; // meeting could not be ended due to checksum error
    }

    public function destroy($id) {
        $session = Bbbsession::find($id);
        $session->delete();
        
        /*if ($this->isMeetingRunning($session->meetingID)) {
            $session->delete(); // only delete the session information from db if the meeting isn't running anymore

            if ($this->endMeeting($id)) {
                return true; // tell the front end that the deletion was sucessful
            }

        }

        return false; //tell the front end that the deletion was not successful*/

    }

    public function getCount(){
        return Bbbsession::count();
    }

    private function createchecksum($action, $querystring) {
        return sha1($action.$querystring.$this->securitysalt);
    }

    private function formatmeetingname($meetingName) {
        return str_replace(" ", "+", $meetingName);
    }

    private function formatusername($username) {
        return str_replace(" ", "", $username);
    }
}
