<?php

  /*
  |--------------------------------------------------------------------------
  | Routes File
  |--------------------------------------------------------------------------
  |
  | Here is where you will register all of the routes in an application.
  | It's a breeze. Simply tell Laravel the URIs it should respond to
  | and give it the controller to call when that URI is requested.
  |
  */


  Route::get('/', [
    'as' => 'index',
    'uses' => 'CommitController@showBuildList',
  ]);
  Route::get('/view/{id}', [
    'as' => 'viewCommitInfo',
    'uses' => 'CommitController@viewLog',
  ]);
  Route::get('/retry/{id}', [
    'as' => 'retry',
    'uses' => 'CommitController@retry',
  ]);
  Route::match(['get', 'post'], '/add-commit', 'CommitController@addCommit');


  /*
  |--------------------------------------------------------------------------
  | Application Routes
  |--------------------------------------------------------------------------
  |
  | This route group applies the "web" middleware group to every route
  | it contains. The "web" middleware group is defined in your HTTP
  | kernel and includes session state, CSRF protection, and more.
  |
  */

  Route::group(['middleware' => ['web']], function () {
    //
  });
