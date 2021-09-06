<?php

namespace App\Http\Controllers;

use Auth;
use Str;
use App\Models\Room;
use App\Models\Visitor;
use Illuminate\Http\Request;

class VisitorController extends Controller
{
    public static function get($filter = NULL) {
        if ($filter == NULL) {
            return new Visitor;
        }
        return Visitor::where($filter);
    }
    public function index() {
        $topics = TopicController::get()->get();
        return view('index', [
            'topics' => $topics
        ]);
    }
    public function register(Request $request) {
        $token = Str::random(64);
        
        $register = Visitor::create([
            'name' => $request->name,
            'contact' => $request->contact,
            'token' => $token,
        ]);

        $createRoom = Room::create([
            'topic_id' => $request->topicID,
            'visitor_id' => $register->id
        ]);

        return response()->json([
            'token' => $token
        ]);
    }
    public function waiting() {
        return view('waiting');
    }
    public function lookingAgent(Request $request) {
        $status = 404;
        $token = $request->token;
        $visitor = Visitor::where('token', $token)->with('room')->first();

        $agent = AgentController::get([
            ['is_busy', 0],
            ['topic_id', $visitor->room->topic_id]
        ])
        ->orderBy('updated_at', 'DESC')
        ->first();

        if ($agent != "") {
            $status = 200;

            $assignToRoom = Room::where('id', $visitor->room->id)->update([
                'agent_id' => $agent->id
            ]);
            $makeAgentBusy = AgentController::get([['id', $agent->id]])->update([
                'is_busy' => 1
            ]);
        }

        return response()->json([
            'status' => $status
        ]);
    }
    public function chat(Request $request) {
        return view('chat');
    }
    public function initChat(Request $request) {
        $token = $request->token;
        $visitor = Visitor::where('token', $token)
        ->with(['room.agent','room.topic'])->first();

        return response()->json(['status' => 200, 'data' => $visitor]);
    }
    public function endChat(Request $request) {
        $roomID = $request->room_id;
        $rate = $request->star == 0 || !$request->star ? NULL : $request->star;
        $now = date('Y-m-d H:i:s');
        
        $data = Room::where('id', $roomID);
        $room = $data->first();
        $updateData = $data->update([
            'visitor_rate' => $rate,
            'ended_at' => $now
        ]);

        $makeAgentNotBusy = AgentController::get([['id', $room->agent_id]])->update(['is_busy' => 0]);
    }
}
