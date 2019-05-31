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
Route::patch('/judge-score/{categoryContestant', 'JudgeScoreController@update');
Route::get('/judge/{categoryJudge', 'JudgeScoreController@show');

Route::get('/temp', function () {
    /* $data = App\ContestCategory::first()->load([
        'contestants' => function ($query) {
            $query->where('status', 'scoring');
        },
        'judges' => function ($query) {
            $query->where('user_id', App\User::find(9)->id);
        }
    ]);

    $response['data'] = $data;

    if ($data->judges->count() && $data->contestants->count()) {
        $response['message'] = 'Judge has Contestant to Score.';
    } else {
        $response['message'] = 'Judge has no Contestant to Score.';
    }

    return $response; */
    
    $contestCategory = App\ContestCategory::find(2);

    $contest = App\Contest::find($contestCategory->contest_id);

    $contest->contestants;

    $data['contest'] = $contest;

    $data['addedContestant'] = [];
    foreach ($contest->contestants as $contestant) {
        $contestCategory->contestants()->create([
            'contestant_id' => $contestant->id,
        ]);
    }

    $data['contestCategory'] = $contestCategory;

    return $data;
});

\Event::listen('Illuminate\Database\Events\QueryExecuted', function ($query) {
    Log::info(json_encode($query->sql));
    Log::info(json_encode($query->bindings));
    Log::info(json_encode($query->time));
});
