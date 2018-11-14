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

Route::get('/', function () {
    return view('welcome');
});

Route::post('issues', 'IssuesController@store')->name('issues.store');
Route::get('issues/{issue}', 'IssuesController@show')->name('issues.show');
Route::get('issues/{issue}/resolve', 'IssuesController@resolve')->name('issues.resolve');

Route::post('issues/{issue}/comments', 'CommentsController@store')->name('comments.store');

Route::post('webhook', 'WebhookController@handle');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
