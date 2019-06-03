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

Route::get('/loginas/{user}', function(\App\User $user){
	auth()->login($user);
	return redirect('/contests');
});

Route::view('/layout', 'sample');

Route::view('/judging', 'judging.index');

Route::view('/', 'welcome');

Auth::routes();

Route::get('sample', function () {
    return view('sample');
});

Route::get('/home', 'HomeController@index')->name('home');

Route::resource('/users', 'UserController');

Route::resource('/categories', 'CategoryController');

Route::resource('/criterias', 'CriteriaController');

Route::resource('/contests', 'ContestController');

Route::resource('/contests/{contest}/contestants', 'ContestantController');

Route::resource('/contests/{contest}/judges', 'JudgeController');

Route::resource('/contests/{contest}/categories', 'ContestCategoryController')->parameters(['categories' => 'contestCategory']);
Route::get('/contests/{contest}/categories/{contestCategory}/scoring', 'ContestCategoryController@scoring');
Route::get('/contests/{contest}/categories/{contestCategory}/done', 'ContestCategoryController@done');

Route::post('/contests/{contest}/categories/{contestCategory}/contestants/{contestant}', 'CategoryContestantController@store');
Route::delete('/contests/{contest}/categories/{contestCategory}/contestants/{categoryContestant}', 'CategoryContestantController@destroy');

Route::post('/contests/{contest}/categories/{contestCategory}/judges/{judge}', 'CategoryJudgeController@store');
Route::delete('/contests/{contest}/categories/{contestCategory}/judges/{categoryJudge}', 'CategoryJudgeController@destroy');

Route::resource('/contests/{contest}/categories/{contestCategory}/criterias', 'CategoryCriteriaController')
    ->parameters([
        'categories' => 'contestCategory',
        'criterias' => 'categoryCriteria',
	]);
	
Route::get('/judge/{categoryJudge}/login', 'JudgeScoreController@login');
Route::get('/judge-score/{categoryContestant}', 'JudgeScoreController@edit');
Route::patch('/judge-score/{score}', 'JudgeScoreController@update');
Route::get('/judge/{categoryJudge', 'JudgeScoreController@show');