<?php

namespace App\Http\Controllers;

use Auth;
use Storage;
use Session;
use Carbon\Carbon;
use App\Models\Agent;
use App\Models\Room;
use Illuminate\Http\Request;

class AgentController extends Controller
{
    public static function me() {
        return Auth::guard('agent')->user();
    }
    public static function get($filter = NULL) {
        if ($filter == NULL) {
            return new Agent;
        }
        return Agent::where($filter);
    }
    public function store(Request $request) {
        $photo = $request->file('photo');
        $photoFileName = $photo->getClientOriginalName();

        $saveData = Agent::create([
            'topic_id' => $request->topic_id,
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'photo' => $photoFileName,
            'is_busy' => 0
        ]);

        $photo->storeAs('public/agent_photos', $photoFileName);

        return redirect()->route('admin.agent')->with([
            'message' => "New agent has been added"
        ]);
    }
    public function update(Request $request) {
        $id = $request->id;
        $data = Agent::where('id', $id);
        $agent = $data->first();

        $toUpdate = [
            'topic_id' => $request->topic_id,
            'name' => $request->name,
            'email' => $request->email,
        ];

        if ($request->password != "") {
            $toUpdate['password'] = bcrypt($request->password);
        }

        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $photoFileName = $photo->getClientOriginalName();
            $toUpdate['photo'] = $photoFileName;
            $deleteOldPhoto = Storage::delete('public/agent_photos/'.$agent->photo);
            $storeNewPhoto = $photo->storeAs('public/agent_photos/', $photoFileName);
        }

        $updateData = $data->update($toUpdate);

        return redirect()->route('admin.agent')->with([
            'message' => "Agent ".$agent->name."'s data has been updated"
        ]);
    }
    public function delete(Request $request) {
        $id = $request->id;
        $data = Agent::where('id', $id);
        $agent = $data->first();

        $deleteData = $data->delete();
        $deletePhoto = Storage::delete('public/agent_photos/'.$agent->photo);

        return redirect()->route('admin.agent')->with([
            'message' => "Agent ".$agent->name." has been deleted"
        ]);
    }
    public function loginPage() {
        $message = Session::get('message');
        return view('agent.login');
    }
    public function login(Request $request) {
        $loggingIn = Auth::guard('agent')->attempt([
            'email' => $request->email,
            'password' => $request->password,
        ]);

        if (!$loggingIn) {
            return redirect()->route('agent.loginPage')->withErrors(['Wrong email and password combination']);
        }

        return redirect()->route('agent.dashboard');
    }
    public function logout() {
        $loggingOut = Auth::guard('agent')->logout();
        return redirect()->route('agent.loginPage')->with([
            'message' => "Successfully logged out"
        ]);
    }
    public function dashboard() {
        $myData = self::me();
        return view('agent.dashboard', [
            'myData' => $myData
        ]);
    }
    public function profile() {
        $myData = self::me();

        return view('agent.profile', [
            'myData' => $myData
        ]);
    }
    public function getRoom($id = NULL, Request $request) {
        $agentID = $request->id;
        $dateStart = Carbon::now()->startOfMonth()->format('Y-m-d H:i:s');
        $dateEnd = Carbon::now()->endOfMonth()->format('Y-m-d H:i:s');
        
        $rooms = Room::where([
            ['agent_id', $agentID],
            ['created_at', '>=', $dateStart],
            ['created_at', '<=', $dateEnd]
        ])
        ->with(['visitor','topic'])
        ->orderBy('created_at', 'DESC')
        ->get();

        return response()->json([
            'status' => 200,
            'rooms' => $rooms
        ]);
    }
}
