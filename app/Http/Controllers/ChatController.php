<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\Room;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

use App\Exports\Conversation;

class ChatController extends Controller
{
    public function get(Request $request) {
        $roomID = $request->roomID;

        $datas = Chat::where('room_id', $roomID)
        ->with('room:id,ended_at')
        ->orderBy('created_at', 'DESC')->get();
        return response()->json($datas);
    }
    public function send(Request $request) {
        if ($request->hasFile('attachment')) {
            $attachment = $request->attachment;
            $filename = $attachment->getClientOriginalName();
            return response()->json($attachment);
        }else {
            return response()->json(['data' => $request->file('attachment')]);
        }
        $toSave = [
            'room_id' => $request->roomID,
            'sender' => $request->sender,
            'body' => $request->body,
        ];

        if ($request->hasFile('attachment')) {
            $attachment = $request->file('attachment');
            $filename = $attachment->getClientOriginalName();
            $toSave['attachment'] = $filename;
            $attachment->storeAs('attachment/'.$request->room_id, $filename);
        }

        $saveData = Chat::create($toSave);

        return response()->json(['status' => 200]);
    }
    public function end(Request $request) {
        $roomID = $request->room_id;
        $now = date('Y-m-d H:i:s');
        
        $data = Room::where('id', $roomID);
        $room = $data->first();
        $updateData = $data->update([
            'ended_at' => $now
        ]);

        $makeAgentNotBusy = AgentController::get([['id', $room->agent_id]])->update(['is_busy' => 0]);

        return response()->json(['status' => 200]);
    }
    public function export($roomID) {
        $room = Room::where('id', $roomID)->with(['chats','agent','visitor','topic'])->first();
        $filename = "Conversation_".$room->agent->name."_-_".$room->visitor->name."_".$room->topic->name.".xlsx";
        return Excel::download(new Conversation($room), $filename);
    }
}
