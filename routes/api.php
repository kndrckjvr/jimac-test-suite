<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// User
Route::post('/user/login', 'UserController@loginUser');

//Test Case
Route::post('/testcase/getlatestid', 'TestCaseController@getLatestId');
Route::post('/testcase/getdata', 'TestCaseController@getData');
Route::post('/testcase/create', 'TestCaseController@createTestCase');

// Module
Route::post('/module/getlatestid', 'ModuleController@getLatestId');