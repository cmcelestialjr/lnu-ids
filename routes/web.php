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
            Route::group(['prefix'=>'departments'], function(){
                Route::post('/viewTable', 'RIMS\Departments\LoadTableController@viewTable');                
                Route::post('/programsList', 'RIMS\Departments\LoadTableController@programsList');
                Route::post('/programAddList', 'RIMS\Departments\LoadTableController@programAddList');

                Route::post('/editModal', 'RIMS\Departments\ModalController@editModal');
                Route::post('/newModal', 'RIMS\Departments\ModalController@newModal');
                Route::post('/programsModal', 'RIMS\Departments\ModalController@programsModal');
                Route::post('/programAddModal', 'RIMS\Departments\ModalController@programAddModal');

                Route::post('/newModalSubmit', 'RIMS\Departments\UpdateController@newModalSubmit');
                Route::post('/editModalSubmit', 'RIMS\Departments\UpdateController@editModalSubmit');
                Route::post('/programsAddSubmit', 'RIMS\Departments\UpdateController@programsAddSubmit');
            });
            Route::group(['prefix'=>'programs'], function(){
                Route::post('/viewTable', 'RIMS\Programs\LoadTableController@viewTable');
                Route::post('/programCodesList', 'RIMS\Programs\LoadTableController@programCodesList');

                Route::post('/curriculumTable', 'RIMS\Programs\LoadViewController@curriculumTable');
                Route::post('/curriculumTablePre', 'RIMS\Programs\LoadViewController@curriculumTablePre');
                Route::post('/courseTablePre', 'RIMS\Programs\LoadViewController@courseTablePre');

                Route::post('/viewModal', 'RIMS\Programs\ModalController@viewModal');
                Route::post('/newCourse', 'RIMS\Programs\ModalController@newCourse');
                Route::post('/courseUpdate', 'RIMS\Programs\ModalController@courseUpdate');
                Route::post('/curriculumNewModal', 'RIMS\Programs\ModalController@curriculumNewModal');
                Route::post('/programStatusModal', 'RIMS\Programs\ModalController@programStatusModal');
                Route::post('/programNewModal', 'RIMS\Programs\ModalController@programNewModal');
                Route::post('/programCodesModal', 'RIMS\Programs\ModalController@programCodesModal');
                Route::post('/programCodeNewModal', 'RIMS\Programs\ModalController@programCodeNewModal');
                Route::post('/programCodeEditModal', 'RIMS\Programs\ModalController@programCodeEditModal');

                Route::post('/courseStatus', 'RIMS\Programs\UpdateController@courseStatus');
                Route::post('/newCourseSubmit', 'RIMS\Programs\UpdateController@newCourseSubmit');
                Route::post('/courseUpdateSubmit', 'RIMS\Programs\UpdateController@courseUpdateSubmit');
                Route::post('/curriculumNewSubmit', 'RIMS\Programs\UpdateController@curriculumNewSubmit');
                Route::post('/curriculumStatus', 'RIMS\Programs\UpdateController@curriculumStatus');
                Route::post('/programStatusSubmit', 'RIMS\Programs\UpdateController@programStatusSubmit');
                Route::post('/programsNewSubmit', 'RIMS\Programs\UpdateController@programsNewSubmit');
                Route::post('/programCodeNewSubmit', 'RIMS\Programs\UpdateController@programCodeNewSubmit');
                Route::post('/programCodeEditSubmit', 'RIMS\Programs\UpdateController@programCodeEditSubmit');
                Route::post('/programCodeStatus', 'RIMS\Programs\UpdateController@programCodeStatus');
            });
            Route::group(['prefix'=>'sections'], function(){
                Route::post('/viewTable', 'RIMS\Sections\LoadTableController@viewTable');
                
                Route::post('/programsSelect', 'RIMS\Sections\LoadViewController@programsSelect');
                Route::post('/gradeLevelSelect', 'RIMS\Sections\LoadViewController@gradeLevelSelect');
                
                Route::post('/sectionNewModal', 'RIMS\Sections\ModalController@sectionNewModal');
                
                Route::post('/sectionNewSubmit', 'RIMS\Sections\UpdateController@sectionNewSubmit');
                
            });
            Route::group(['prefix'=>'schoolYear'], function(){
                Route::post('/viewTable', 'RIMS\SchoolYear\LoadTableController@viewTable');
                Route::post('/programsViewTable', 'RIMS\SchoolYear\LoadTableController@programsViewTable');

                Route::post('/coursesOpenDiv', 'RIMS\SchoolYear\LoadViewController@coursesOpenDiv');
                Route::post('/curriculumSelect', 'RIMS\SchoolYear\LoadViewController@curriculumSelect');
                Route::post('/curriculumList', 'RIMS\SchoolYear\LoadViewController@curriculumList');
                Route::post('/curriculumViewList', 'RIMS\SchoolYear\LoadViewController@curriculumViewList');
                
                Route::post('/editView', 'RIMS\SchoolYear\ModalController@editView');
                Route::post('/programs', 'RIMS\SchoolYear\ModalController@programs');
                Route::post('/programsViewModal', 'RIMS\SchoolYear\ModalController@programsViewModal');
                Route::post('/coursesOpenModal', 'RIMS\SchoolYear\ModalController@coursesOpenModal');
                Route::post('/coursesViewModal', 'RIMS\SchoolYear\ModalController@coursesViewModal');
                Route::post('/courseViewStatusModal', 'RIMS\SchoolYear\ModalController@courseViewStatusModal');
                
                Route::post('/new', 'RIMS\SchoolYear\UpdateController@new');                
                Route::post('/moveProgram', 'RIMS\SchoolYear\UpdateController@moveProgram');
                Route::post('/offerPrograms', 'RIMS\SchoolYear\UpdateController@offerPrograms');
                Route::post('/courseStatus', 'RIMS\SchoolYear\UpdateController@courseStatus');
                Route::post('/courseViewStatusSubmit', 'RIMS\SchoolYear\UpdateController@courseViewStatusSubmit');
            });
        });
        
    });
});

