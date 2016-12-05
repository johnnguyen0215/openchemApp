<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controllers to call when that URI is requested.
|
*/


Route::get('/', function () {
    return view('index');
});


Route::group(array('prefix' => 'api'), function(){

	// Application routes
    Route::get('topics/search/{query}', 'TopicsController@search');
    Route::get('topics/getCount', 'TopicsController@getCount');
    Route::resource('topics', 'TopicsController');
    
    Route::resource('keywords', 'KeywordsController');
    
    Route::resource('chemtexts', 'ChemtextsController');
    
    Route::resource('problems', 'ProblemsController');

    Route::get('users/getCount', 'UsersController@getCount');
    Route::resource('users', 'UsersController');
    
    Route::resource('groups', 'GroupsController');
    Route::get('groupinvite/accept/{id}', 'GroupinvitesController@acceptInvite');
    Route::resource('groupinvite', 'GroupinvitesController');
    
    // Route::post('bbb/getSessionUrl', 'BigbluebuttonController@getSessionUrl');
    // Route::get('bbb/getCount', 'BigbluebuttonController@getCount');
    // Route::resource('bbb', 'BigbluebuttonController');
    
    Route::post('studysession/getSessionUrl', 'GhangoutController@getSessionUrl');
    Route::get('studysession/getCount', 'GhangoutController@getCount');
    Route::resource('studysession', 'GhangoutController');

    Route::resource('message', 'MessageController');

    // Authentication routes
    Route::resource('authenticate', 'AuthenticateController', ['only' => ['index']]);
    Route::post('authenticate', 'AuthenticateController@authenticate');

});

Route::any('{path?}', function()
{
    return view("index");
})->where("path", ".+");

