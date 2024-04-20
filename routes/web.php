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
        Route::get('judges', 'JudgeController@index')->name('judges.index');

        Route::get('categories', 'CategoryController@index')->name('categories.index');

        Route::get('criterias', 'CriteriaController@index')->name('criterias.index');

        Route::name('contests.')->prefix('contests')->group(function () {
            Route::get('status', 'ContestController@status')->name('status');

            Route::prefix('{contest}')->group(function () {
                Route::post('store-from-score', 'ContestController@storeFromScore')->name('store-from-score');

                Route::get('print', 'ContestController@print')->name('print');

                Route::get('print-top', 'ContestController@printTop')->name('print.top');
            });
        });

        Route::name('contests.categories.')->prefix('contests/{contest}/categories/{category}')->group(function () {
            Route::patch('start', 'CategoryController@start')->name('start');
            Route::patch('finish', 'CategoryController@finish')->name('finish');
            Route::get('print', 'CategoryController@print')->name('print');
            Route::get('print-top', 'CategoryController@printTop')->name('print.top');
            Route::get('live', 'CategoryController@live')->name('live');
            Route::post('store-from-score', 'CategoryController@storeFromScore')->name('store-from-score');
        });

        Route::get('contests/{contest}/judges/{judge}/login', 'JudgeController@login')->name('contests.judges.login');

        Route::resource('contests', 'ContestController');
        Route::resource('contests.judges', 'JudgeController')->except(['index', 'show', 'create']);

        Route::name('contests.judges.move.')->prefix('contests/{contest}/judges/{judge}/move/')->group(function () {
            Route::patch('up', 'JudgeController@moveUp')->name('up');
            Route::patch('down', 'JudgeController@moveDown')->name('down');
        });

        Route::resource('contests.contestants', 'ContestantController')->except(['index']);

        Route::name('contests.contestants.move.')->prefix('contests/{contest}/contestants/{contestant}/move/')->group(function () {
            Route::patch('up', 'ContestantController@moveUp')->name('up');
            Route::patch('down', 'ContestantController@moveDown')->name('down');
        });

        Route::resource('contests.categories', 'CategoryController')->except(['index', 'create', 'edit']);

        Route::name('contests.categories.move.')->prefix('contests/{contest}/categories/{category}/move/')->group(function () {
            Route::patch('up', 'CategoryController@moveUp')->name('up');
            Route::patch('down', 'CategoryController@moveDown')->name('down');
        });

        Route::resource('contests.categories.criterias', 'CriteriaController')->except(['index', 'show', 'create']);

        Route::name('contests.categories.criterias.move.')->prefix('contests/{contest}/categories/{category}/criterias/{criteria}')->group(function () {
            Route::patch('up', 'CriteriaController@moveUp')->name('up');
            Route::patch('down', 'CriteriaController@moveDown')->name('down');
        });

        Route::resource('contests.categories.judges', 'CategoryJudgeController')
            ->parameters(['judges' => 'judge'])
            ->only(['store', 'destroy']);

        Route::resource('contests.categories.contestants', 'CategoryContestantController')
            ->parameters(['contestants' => 'contestant'])
            ->only(['show', 'store', 'destroy']);

        Route::name('contests.bestins.')->prefix('contests/{contest}/best-ins')->group(function () {
            Route::resource('', 'BestinController')
                ->parameters(['' => 'bestin'])
                ->only(['show', 'store', 'destroy']);
        });
    });
});

Route::middleware(['auth:judge', 'throttle:999,1'])->group(function () {
    Route::namespace('Judge')->name('judge.')->prefix('judge')->group(function () {
        Route::name('categories.')->prefix('categories')->group(function () {
            Route::get('status', 'CategoryController@status')->name('status');
            Route::get('list-categories', 'CategoryController@listCategories')->name('list-categories');
        });

        Route::resource('categories', 'CategoryController')->except(['create', 'store', 'destroy']);
        Route::resource('categories.contestants', 'ContestantController')->only(['index', 'update']);
    });
});
