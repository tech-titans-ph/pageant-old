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
Route::get('/sample', function () {
    return view('sample');
});

Route::redirect('/', '/home');

Route::get('/home', 'HomeController@index')->name('home');

Auth::routes(['register' => false]);

Route::middleware('auth')->group(function () {
    Route::namespace('Admin')->name('admin.')->prefix('admin')->group(function () {
        Route::get('judges', 'JudgeController@index')
            ->name('judges.index');

        Route::get('categories', 'CategoryController@index')
            ->name('categories.index');

        Route::get('criterias', 'CriteriaController@index')
            ->name('criterias.index');

        Route::name('contests.')->prefix('contests')->group(function () {
            Route::get('status', 'ContestController@status')
                ->name('status');

            Route::prefix('{contest}')->group(function () {
                Route::post('store-from-score', 'ContestController@storeFromScore')
                    ->name('store-from-score');

                Route::get('print', 'ContestController@print')
                    ->name('print');
            });
        });

        Route::name('contests.categories.')->prefix('contests/{contest}/categories/{category}')->group(function () {
            Route::patch('start', 'CategoryController@start')->name('start');
            Route::patch('finish', 'CategoryController@finish')->name('finish');
            Route::get('print', 'CategoryController@print')->name('print');
            Route::get('live', 'CategoryController@live')->name('live');
            Route::post('store-from-score', 'CategoryController@storeFromScore')->name('store-from-score');
        });

        Route::get('contests/{contest}/judges/{judge}/login', 'JudgeController@login')
            ->name('contests.judges.login');

        Route::resource('contests', 'ContestController');
        Route::resource('contests.judges', 'JudgeController')->except(['index', 'show', 'create']);
        Route::resource('contests.contestants', 'ContestantController')->except(['index']);
        Route::resource('contests.categories', 'CategoryController')->except(['index', 'create', 'edit']);
        Route::resource('contests.categories.criterias', 'CriteriaController')->except(['index', 'show', 'create']);
        Route::resource('contests.categories.judges', 'CategoryJudgeController')
            ->parameters(['judges' => 'judge'])
            ->only(['store', 'destroy']);
        Route::resource('contests.categories.category-contestants', 'CategoryContestantController')
            ->parameters(['category-contestants' => 'categoryContestant'])
            ->only(['show', 'store', 'destroy']);
    });
});

Route::middleware('auth:judge')->group(function () {
    Route::namespace('Judge')->name('judge.')->prefix('judge')->group(function () {
        Route::name('categories.')->prefix('categories')->group(function () {
            Route::get('status', 'CategoryController@status')->name('status');
            Route::get('list-categories', 'CategoryController@listCategories')->name('list-categories');
        });

        Route::resource('categories', 'CategoryController')->except(['create', 'store', 'destroy']);
        Route::resource('categories.contestants', 'ContestantController')->only(['index', 'update']);
    });
});
