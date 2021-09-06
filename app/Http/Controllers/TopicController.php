<?php

namespace App\Http\Controllers;

use App\Models\Topic;
use Illuminate\Http\Request;

class TopicController extends Controller
{
    public static function get($filter = NULL) {
        if ($filter == NULL) {
            return new Topic;
        }
        return Topic::where($filter);
    }
    public function store(Request $request) {
        $saveData = Topic::create([
            'name' => $request->name,
            'used_in_chat' => 0,
        ]);

        return redirect()->route('admin.topic')->with([
            'message' => "New topic has been added"
        ]);
    }
    public function delete(Request $request) {
        $id = $request->id;

        $data = Topic::where('id', $id);
        $topic = $data->first();
        $deleteData = $data->delete();
        
        return redirect()->route('admin.topic')->with([
            'message' => "Topic ".$topic->name." has been deleted"
        ]);
    }
}
