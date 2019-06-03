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
Route::get('/','WelcomeController@index');

Route::view('/layout', 'sample');

Route::view('/judging', 'judging.index');

Auth::routes();

Route::get('sample', function(){
    return view('sample');
});

Route::get('/home', 'HomeController@index')->name('home');

Route::resource('/users', 'UserController');

Route::resource('/contests', 'ContestController');
Route::post('/contests/{contest}/active', 'ContestController@active');

Route::resource('/criterias', 'CriteriaController');

Route::resource('/contestants', 'ContestantController');

Route::resource('/judges', 'JudgeController');

Route::resource('/contest-categories', 'ContestCategoryController');

Route::get('/no-active-contest', function(){
    return view('/validations.no-active-contest');
});