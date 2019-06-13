<?php

/*
  |--------------------------------------------------------------------------
  | Web Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register web routes for your application. These
  | routes are loaded by the RouteServiceProvider within a group which
  | contains the "web" middleware group. Now create something great!
  |
 */

Auth::routes();


Route::get('/test', 'HomeController@test');

Route::prefix('admin')->group(function () {
    Route::get('answer/member', 'Admin\AnswerController@member');
    Route::resources([
        'question' => 'Admin\QuestionController',
        'answer' => 'Admin\AnswerController'
    ]);
    
});
