<?php

use Illuminate\Support\Facades\Route;

Route::get('/', "VisitorController@index")->name("visitor.index");
Route::post('register', "VisitorController@register")->name("visitor.register");
Route::get('waiting', "VisitorController@waiting")->name("visitor.waiting");
Route::get('chat', "VisitorController@chat")->name("visitor.chat");
Route::post('chat/end', "VisitorController@endChat")->name("visitor.chat.end");

Route::group(['prefix' => "admin"], function () {
    Route::get('login', "AdminController@loginPage")->name("admin.loginPage");
    Route::post('login', "AdminController@login")->name("admin.login");
    Route::get('logout', "AdminController@logout")->name("admin.logout");
    
    Route::get('dashboard', "AdminController@dashboard")->name("admin.dashboard")->middleware('Admin');
    Route::group(['prefix' => "user"], function () {
        Route::get('agent', "AdminController@agent")->name("admin.agent")->middleware('Admin');
        Route::get('visitor', "AdminController@visitor")->name("admin.visitor")->middleware('Admin');
    });

    Route::get('topic', "AdminController@topic")->name("admin.topic")->middleware('Admin');
    Route::get('conversation', "AdminController@conversation")->name("admin.conversation")->middleware('Admin');
    Route::get('export/{roomID}', "ChatController@export")->name("admin.conversation.export")->middleware('Admin');

    Route::group(['prefix' => "topic"], function () {
        Route::post('store', "TopicController@store")->name("admin.topic.store")->middleware('Admin');
        Route::post('update', "TopicController@update")->name("admin.topic.update")->middleware('Admin');
        Route::post('delete', "TopicController@delete")->name("admin.topic.delete")->middleware('Admin');
    });

    Route::group(['prefix' => "agent"], function () {
        Route::post('store', "AgentController@store")->name("admin.agent.store")->middleware('Admin');
        Route::post('update', "AgentController@update")->name("admin.agent.update")->middleware('Admin');
        Route::post('delete', "AgentController@delete")->name("admin.agent.delete")->middleware('Admin');
    });
});

Route::group(['prefix' => "agent"], function () {
    Route::get('login', "AgentController@loginPage")->name("agent.loginPage");
    Route::post('login', "AgentController@login")->name("agent.login");
    Route::get('logout', "AgentController@logout")->name("agent.logout");

    Route::get('dashboard', "AgentController@dashboard")->name("agent.dashboard")->middleware('Agent');
    Route::get('profile', "AgentController@profile")->name("agent.profile")->middleware('Agent');
});