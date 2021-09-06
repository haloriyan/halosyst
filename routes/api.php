<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => "visitor"], function () {
    Route::post('agent-lookup', "VisitorController@lookingAgent")->name("api.visitor.lookingAgent");
    Route::post('init-chat', "VisitorController@initChat")->name("api.visitor.initChat");
});

Route::group(['prefix' => "agent"], function () {
    Route::post("room", "AgentController@getRoom")->name("api.agent.room");
});

Route::group(['prefix' => "chat"], function () {
    Route::post("get", "ChatController@get")->name("api.chat.get");
    Route::post("send", "ChatController@send")->name("api.chat.send");
    Route::post("end", "ChatController@end")->name("api.chat.end");
});