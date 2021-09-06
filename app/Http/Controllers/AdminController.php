<?php

namespace App\Http\Controllers;

use Auth;
use Session;
use Carbon\Carbon;
use App\Models\Room;
use App\Models\Admin;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public static function me() {
        $myData = Auth::guard('admin')->user();
        if ($myData != "") {
            $name = explode(" ", $myData->name);
            $myData->first_name = $name[0];
            if (array_key_exists(1, $name)) {
                $myData->initial = $myData->first_name[0].$name[1][0];
            } else {
                $myData->initial = $myData->first_name[0];
            }
        }

        return $myData;
    }
    public function loginPage(Request $request) {
        return view('admin.login', ['r' => $request->r]);
    }
    public function login(Request $request) {
        $loggingIn = Auth::guard('admin')->attempt([
            'email' => $request->email,
            'password' => $request->password,
        ]);

        if (!$loggingIn) {
            return redirect()->route('admin.loginPage')->withErrors([
                'Wrong email and password combination'
            ]);;
        }

        $redirectTo = $request->r == "" ? "admin.dashboard" : $request->r;
        return redirect()->route($redirectTo);
    }
    public function logout() {
        $loggingOut = Auth::guard('admin')->logout();
        return redirect()->route("admin.loginPage");
    }
    public function dashboard() {
        $myData = self::me();
        $dateStart = Carbon::now()->startOfMonth()->format('Y-m-d H:i:s');
        $dateEnd = Carbon::now()->endOfMonth()->format('Y-m-d H:i:s');
        
        $rooms = Room::where([
            ['created_at', '>=', $dateStart],
            ['created_at', '<=', $dateEnd],
        ])
        ->with('agent')
        ->get(['id','visitor_rate','agent_id']);
        $rooms = json_decode(json_encode($rooms), FALSE);

        $sorted = $sortedAgents = [];
        foreach ($rooms as $i => $room) {
            $sorted[$i] = $room->visitor_rate;
        }
        array_multisort($sorted, SORT_DESC, $rooms);

        // calculating summary rate
        $totalRate = $toDivide = 0;
        foreach ($rooms as $room) {
            $totalRate += $room->visitor_rate;
            if ($room->visitor_rate != NULL) {
                $toDivide += 1;
            }

            // Calculating top agents performance
            if (!array_key_exists($room->agent_id, $sortedAgents)) {
                $sortedAgents[$room->agent_id]['rate'] = $room->visitor_rate;
                $sortedAgents[$room->agent_id]['agent'] = $room->agent;
            } else {
                $sortedAgents[$room->agent_id]['rate'] += $room->visitor_rate;
            }
        }

        $mean = round($totalRate / $toDivide, 2);

        $visitors = VisitorController::get()
        ->orderBy('created_at', 'DESC')
        ->with('room.topic')
        ->take(5)->get();

        return view('admin.dashboard', [
            'myData' => $myData,
            'rooms' => $rooms,
            'visitors' => $visitors,
            'mean' => $mean,
            'sortedAgents' => $sortedAgents,
        ]);
    }
    public function topic() {
        $myData = self::me();
        $message = Session::get('message');
        $topics = TopicController::get()->get();

        return view('admin.topic', [
            'myData' => $myData,
            'topics' => $topics,
            'message' => $message
        ]);
    }
    public function agent() {
        $myData = self::me();
        $message = Session::get('message');
        $agents = AgentController::get()->with('topic')->get();
        $topics = TopicController::get()->get();

        return view('admin.agent', [
            'myData' => $myData,
            'agents' => $agents,
            'topics' => $topics,
            'message' => $message
        ]);
    }
    public function visitor() {
        $myData = self::me();
        $message = Session::get('message');
        $visitors = VisitorController::get()->with('room')
        ->orderBy('created_at', 'DESC')->get();

        return view('admin.visitor', [
            'myData' => $myData,
            'visitors' => $visitors,
            'message' => $message
        ]);
    }
    public function conversation(Request $request) {
        $myData = self::me();
        $message = Session::get('message');

        $data = Room::with(['topic','agent']);
        if ($request->start_date == "") {
            $startDate = $request->start_date = Carbon::now()->startOfMonth()->format('Y-m-d');
            $endDate = $request->end_date = Carbon::now()->endOfMonth()->format('Y-m-d');
        } else {
            $startDate = $request->start_date;
            $endDate = $request->end_date;
        }
        $data = $data->whereBetween('created_at', [$startDate, $endDate]);
        $rooms = $data->orderBy('created_at', 'DESC')->get();

        return view('admin.conversation', [
            'myData' => $myData,
            'rooms' => $rooms,
            'request' => $request,
            'message' => $message
        ]);
    }
}
