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
        
        Route::group(['prefix'=>'search'], function(){
            Route::post('/courseCode', 'SEARCH\CourseCodeController@courseCode');
            Route::post('/courseDesc', 'SEARCH\CourseDescController@courseDesc');
            Route::post('/sectionCode', 'SEARCH\SectionCodeController@sectionCode');
            Route::post('/instructor', 'SEARCH\InstructorController@instructor');
            Route::post('/room', 'SEARCH\RoomController@room');
        });

        Route::group(['prefix'=>'rims'], function(){
            Route::group(['prefix'=>'student'], function(){
                Route::post('/searchStudent', 'RIMS\Student\LoadViewController@searchStudent');
            });

            Route::group(['prefix'=>'departments'], function(){
                Route::post('/viewTable', 'RIMS\Departments\LoadTableController@viewTable');
                Route::post('/programsList', 'RIMS\Departments\LoadTableController@programsList');
                Route::post('/programAddList', 'RIMS\Departments\LoadTableController@programAddList');

                Route::post('/editModal', 'RIMS\Departments\ModalController@editModal');
                Route::post('/newModal', 'RIMS\Departments\ModalController@newModal');
                Route::post('/programsModal', 'RIMS\Departments\ModalController@programsModal');
                Route::post('/programAddModal', 'RIMS\Departments\ModalController@programAddModal');

                Route::post('/newModalSubmit', 'RIMS\Departments\NewController@newModalSubmit');

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

                Route::post('/newCourseSubmit', 'RIMS\Programs\NewController@newCourseSubmit');
                Route::post('/curriculumNewSubmit', 'RIMS\Programs\NewController@curriculumNewSubmit');
                Route::post('/programsNewSubmit', 'RIMS\Programs\NewController@programsNewSubmit');
                Route::post('/programCodeNewSubmit', 'RIMS\Programs\NewController@programCodeNewSubmit');

                Route::post('/courseStatus', 'RIMS\Programs\UpdateController@courseStatus');                
                Route::post('/courseUpdateSubmit', 'RIMS\Programs\UpdateController@courseUpdateSubmit');
                Route::post('/curriculumStatus', 'RIMS\Programs\UpdateController@curriculumStatus');
                Route::post('/programStatusSubmit', 'RIMS\Programs\UpdateController@programStatusSubmit');
                Route::post('/programCodeEditSubmit', 'RIMS\Programs\UpdateController@programCodeEditSubmit');
                Route::post('/programCodeStatus', 'RIMS\Programs\UpdateController@programCodeStatus');
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
                
                Route::post('/new', 'RIMS\SchoolYear\NewController@new');
                Route::post('/offerPrograms', 'RIMS\SchoolYear\NewController@offerPrograms');
                
                Route::post('/moveProgram', 'RIMS\SchoolYear\UpdateController@moveProgram');                
                Route::post('/courseStatus', 'RIMS\SchoolYear\UpdateController@courseStatus');
                Route::post('/courseViewStatusSubmit', 'RIMS\SchoolYear\UpdateController@courseViewStatusSubmit');
            });
            Route::group(['prefix'=>'sections'], function(){
                Route::post('/scheduleRemoveDay', 'RIMS\Sections\DeleteController@scheduleRemoveDay');
                Route::post('/scheduleRemove', 'RIMS\Sections\DeleteController@scheduleRemove');

                Route::post('/viewTable', 'RIMS\Sections\LoadTableController@viewTable');
                Route::post('/sectionViewTable', 'RIMS\Sections\LoadTableController@sectionViewTable');
                Route::post('/courseViewTable', 'RIMS\Sections\LoadTableController@courseViewTable');                
                
                Route::post('/programsSelect', 'RIMS\Sections\LoadViewController@programsSelect');
                Route::post('/gradeLevelSelect', 'RIMS\Sections\LoadViewController@gradeLevelSelect');
                Route::post('/courseSchedRmDetails', 'RIMS\Sections\LoadViewController@courseSchedRmDetails');
                Route::post('/courseSchedRmSchedule', 'RIMS\Sections\LoadViewController@courseSchedRmSchedule');
                Route::post('/courseSchedRmInstructor', 'RIMS\Sections\LoadViewController@courseSchedRmInstructor');   
                Route::post('/courseSchedRmTable', 'RIMS\Sections\LoadViewController@courseSchedRmTable');             
                
                Route::post('/sectionNewModal', 'RIMS\Sections\ModalController@sectionNewModal');
                Route::post('/sectionViewModal', 'RIMS\Sections\ModalController@sectionViewModal');
                Route::post('/courseViewModal', 'RIMS\Sections\ModalController@courseViewModal');
                Route::post('/courseSchedRmModal', 'RIMS\Sections\ModalController@courseSchedRmModal');
                
                Route::post('/sectionNewSubmit', 'RIMS\Sections\NewController@sectionNewSubmit');

                Route::post('/courseSchedRmInstructorUpdate', 'RIMS\Sections\UpdateController@courseSchedRmInstructorUpdate');
                Route::post('/scheduleTimeUpdate', 'RIMS\Sections\UpdateController@scheduleTimeUpdate');
            });
            Route::group(['prefix'=>'enrollment'], function(){
                Route::post('/enrollmentTable', 'RIMS\Enrollment\LoadTableController@enrollmentTable');
                Route::post('/courseAnotherTable', 'RIMS\Enrollment\LoadTableController@courseAnotherTable');
                
                Route::post('/studentInformationDiv', 'RIMS\Enrollment\LoadViewController@studentInformationDiv');
                Route::post('/programCodeDiv', 'RIMS\Enrollment\LoadViewController@programCodeDiv');
                Route::post('/programCurriculumDiv', 'RIMS\Enrollment\LoadViewController@programCurriculumDiv');
                Route::post('/programSectionDiv', 'RIMS\Enrollment\LoadViewController@programSectionDiv');
                Route::post('/programCoursesDiv', 'RIMS\Enrollment\LoadViewController@programCoursesDiv');
                Route::post('/programAddSelect', 'RIMS\Enrollment\LoadViewController@programAddSelect');
                Route::post('/programAddCourseDiv', 'RIMS\Enrollment\LoadViewController@programAddCourseDiv');

                Route::post('/enrollModal', 'RIMS\Enrollment\ModalController@enrollModal');
                Route::post('/courseAnotherModal', 'RIMS\Enrollment\ModalController@courseAnotherModal');
                Route::post('/courseAddModal', 'RIMS\Enrollment\ModalController@courseAddModal');

                Route::post('/courseAnotherSubmit', 'RIMS\Enrollment\UpdateController@courseAnotherSubmit');
                
            });
            
            Route::group(['prefix'=>'schedule'], function(){
                Route::post('/viewTable', 'RIMS\Schedule\LoadTableController@viewTable');
                Route::post('/searchTable', 'RIMS\Schedule\LoadTableController@searchTable');
                Route::post('/schedWoTable', 'RIMS\Schedule\LoadTableController@schedWoTable');
                
                Route::post('/searchDiv', 'RIMS\Schedule\LoadViewController@searchDiv');

                Route::post('/searchCourseSched', 'RIMS\Schedule\ModalController@searchCourseSched');                
                
            });
        });
        
    });
});

