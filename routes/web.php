<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::get('/error/http', function () {
    return view('layouts.error.http');
});
Route::group(['middleware' => ['HTTPS']], function(){
    Route::group(['middleware' => ['CheckUser']], function(){
        Route::get('/', 'IndexController@view')->name('indexpage');
        Route::post('/login', 'LoginController@check');        
    });
    
    Route::group(['middleware' => ['auth','Login','PreventBackHistory']], function(){
        Route::get('/logout', 'LoginController@logout');
        Route::get('/system', 'IndexController@systempage')->name('systempage');
        Route::get('/systems', 'IndexController@systems');

        Route::get('/ids/{system_selected}/{nav_selected}/{type}', 'IndexController@ids');

        Route::group(['prefix'=>'import'], function(){
            Route::post('/import', 'ImportController@import');
        });
        
        Route::group(['prefix'=>'users'], function(){
            Route::post('/table', 'USERS\TableController@table');

            Route::post('/accessView', 'USERS\AccessController@access');            
            Route::post('/accessListNav', 'USERS\AccessController@listNav');
            Route::post('/accessListNavSub', 'USERS\AccessController@listNavSub');
            Route::post('/accessUpdate', 'USERS\AccessController@update');

            Route::post('/statusView', 'USERS\StatusController@status');
            Route::post('/statusUpdate', 'USERS\StatusController@update');
            
            Route::post('/systems', 'USERS\SystemsController@table');
            Route::post('/systemsTable', 'USERS\SystemsController@table');
            Route::post('/systemsNew', 'USERS\SystemsController@new');
            Route::post('/systemsEdit', 'USERS\SystemsController@edit');            
            Route::post('/systemNewSubmit', 'USERS\SystemsController@newSubmit');
            Route::post('/systemEditSubmit', 'USERS\SystemsController@editSubmit');
            Route::post('/systemsNav', 'USERS\SystemsController@nav');
            Route::post('/systemsNavNew', 'USERS\SystemsController@navNew');
            Route::post('/systemsNavEdit', 'USERS\SystemsController@navEdit');
            Route::post('/systemsNavNewSubmit', 'USERS\SystemsController@navNewSubmit');
            Route::post('/systemsNavEditSubmit', 'USERS\SystemsController@navEditSubmit');
            Route::post('/systemsNavSub', 'USERS\SystemsController@navSub');
            Route::post('/systemsNavSubNew', 'USERS\SystemsController@navSubNew');
            Route::post('/systemsNavSubEdit', 'USERS\SystemsController@navSubEdit');
            Route::post('/systemsNavSubNewSubmit', 'USERS\SystemsController@navSubNewSubmit');
            Route::post('/systemsNavSubEditSubmit', 'USERS\SystemsController@navSubEditSubmit');
            Route::post('/systemsNavView', 'USERS\SystemsController@navView');
            Route::post('/systemsNavSubView', 'USERS\SystemsController@navSubView');            
        });
        
        Route::group(['prefix'=>'rims'], function(){
            Route::group(['prefix'=>'schoolYear'], function(){
                Route::post('/new', 'RIMS\SchoolYearController@new');
                Route::post('/editView', 'RIMS\SchoolYearController@editView');
                Route::post('/viewTable', 'RIMS\SchoolYearController@viewTable');
                Route::post('/programs', 'RIMS\SchoolYearController@programs');
                Route::post('/moveProgram', 'RIMS\SchoolYearController@moveProgram');
                Route::post('/offerPrograms', 'RIMS\SchoolYearController@offerPrograms');
            });
            Route::group(['prefix'=>'programs'], function(){
                Route::post('/viewTable', 'RIMS\ProgramsController@viewTable');
                Route::post('/viewModal', 'RIMS\ProgramsController@viewModal');
                Route::post('/curriculumTable', 'RIMS\ProgramsController@curriculumTable');
                Route::post('/courseStatus', 'RIMS\ProgramsController@courseStatus');
                Route::post('/newCourse', 'RIMS\ProgramsController@newCourse');   
                Route::post('/curriculumTablePre', 'RIMS\ProgramsController@curriculumTablePre');
                Route::post('/newCourseSubmit', 'RIMS\ProgramsController@newCourseSubmit');
            });
        });
        
    });
});

