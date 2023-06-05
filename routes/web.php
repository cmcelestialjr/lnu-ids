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
    Route::get('/monitor1', 'HRIMS\DTR\BIOMACHINE\Monitor1Controller@monitor1');
    Route::post('/monitor1/display', 'HRIMS\DTR\BIOMACHINE\Monitor1Controller@display');
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
            Route::post('/psgcBrgys', 'SEARCH\PSGCController@brgys');
            Route::post('/psgcCityMuns', 'SEARCH\PSGCController@cityMuns');
            Route::post('/psgcProvinces', 'SEARCH\PSGCController@provinces');
            Route::post('/positionList', 'SEARCH\PositionController@list');
            Route::post('/designationList', 'SEARCH\DesignationController@list');
            Route::post('/ludongStudent', 'SEARCH\LudongController@student');
        });
        Route::group(['prefix'=>'sims'], function(){
            Route::group(['prefix'=>'pre_enroll'], function(){
                Route::post('/preEnrollCourses', 'SIMS\Preenroll\LoadViewController@preEnrollCourses');
            });
        });
        Route::group(['prefix'=>'fis'], function(){
            Route::group(['prefix'=>'students'], function(){                
                Route::post('/studentsTable', 'FAMS\Students\LoadTableController@studentsTable');
                Route::post('/studentSchoolYearTable', 'FAMS\Students\LoadTableController@studentSchoolYearTable');

                Route::post('/gradeLevel', 'FAMS\Students\LoadViewController@gradeLevel');

                Route::post('/studentViewModal', 'FAMS\Students\ModalController@studentViewModal');
            });
            Route::group(['prefix'=>'subjects'], function(){
                Route::post('/subjectsTable', 'FAMS\Subjects\LoadTableController@subjectsTable');
                Route::post('/studentsListTable', 'FAMS\Subjects\LoadTableController@studentsListTable');
                
                Route::post('/studentsListModal', 'FAMS\Subjects\ModalController@studentsListModal');
                Route::post('/studentGradeModal', 'FAMS\Subjects\ModalController@studentGradeModal');

                Route::post('/studentGradeSubmit', 'FAMS\Subjects\UpdateController@studentGradeSubmit');
                
            });
            Route::group(['prefix'=>'schedule'], function(){
                Route::post('/scheduleTable', 'FAMS\Schedule\LoadViewController@scheduleTable');
                
            });
            Route::group(['prefix'=>'advisement'], function(){
                Route::post('/studentInfo', 'FAMS\Advisement\LoadViewController@studentInfo');
                Route::post('/curriculumSelect', 'FAMS\Advisement\LoadViewController@curriculumSelect');
                Route::post('/studentAdvisement', 'FAMS\Advisement\LoadViewController@studentAdvisement');
                Route::post('/sectionSelect', 'FAMS\Advisement\LoadViewController@sectionSelect');

                Route::post('/advisementSubmit', 'FAMS\Advisement\UpdateController@advisementSubmit');
                
            });
        });
        Route::group(['prefix'=>'rims'], function(){
            Route::group(['prefix'=>'student'], function(){
                Route::post('/studentTable', 'RIMS\Student\LoadTableController@studentTable');
                Route::post('/studentSchoolYearTable', 'RIMS\Student\LoadTableController@studentSchoolYearTable');
                Route::post('/studentCoursesTable', 'RIMS\Student\LoadTableController@studentCoursesTable');  

                Route::post('/searchStudent', 'RIMS\Student\LoadViewController@searchStudent');
                Route::post('/searchStudents', 'RIMS\Student\LoadViewController@searchStudents');
                Route::post('/studentTORDiv', 'RIMS\Student\LoadViewController@studentTORDiv');
                Route::post('/studentCurriculumDiv', 'RIMS\Student\LoadViewController@studentCurriculumDiv');

                Route::post('/studentViewModal', 'RIMS\Student\ModalController@studentViewModal');
                Route::post('/studentTORModal', 'RIMS\Student\ModalController@studentTORModal');
                Route::post('/studentCurriculumModal', 'RIMS\Student\ModalController@studentCurriculumModal');
                Route::post('/studentCoursesModal', 'RIMS\Student\ModalController@studentCoursesModal');
                
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
                Route::post('/minMaxModal', 'RIMS\Sections\ModalController@minMaxModal');
                
                Route::post('/sectionNewSubmit', 'RIMS\Sections\NewController@sectionNewSubmit');

                Route::post('/courseSchedRmInstructorUpdate', 'RIMS\Sections\UpdateController@courseSchedRmInstructorUpdate');
                Route::post('/scheduleTimeUpdate', 'RIMS\Sections\UpdateController@scheduleTimeUpdate');
                Route::post('/typeUpdate', 'RIMS\Sections\UpdateController@typeUpdate');
                Route::post('/minMaxSubmit', 'RIMS\Sections\UpdateController@minMaxSubmit');                
            });
            Route::group(['prefix'=>'enrollment'], function(){
                Route::post('/enrollmentTable', 'RIMS\Enrollment\LoadTableController@enrollmentTable');
                Route::post('/courseAnotherTable', 'RIMS\Enrollment\LoadTableController@courseAnotherTable');
                Route::post('/enrollmentViewTable', 'RIMS\Enrollment\LoadTableController@enrollmentViewTable');
                Route::post('/coursesViewTable', 'RIMS\Enrollment\LoadTableController@coursesViewTable');
                
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
                Route::post('/enrollmentViewModal', 'RIMS\Enrollment\ModalController@enrollmentViewModal');
                Route::post('/coursesViewModal', 'RIMS\Enrollment\ModalController@coursesViewModal');
                
                Route::post('/courseAnotherSubmit', 'RIMS\Enrollment\UpdateController@courseAnotherSubmit');
                Route::post('/courseAddSubmit', 'RIMS\Enrollment\UpdateController@courseAddSubmit');
                Route::post('/enrollSubmit', 'RIMS\Enrollment\UpdateController@enrollSubmit');
                Route::post('/enrollAdvisedSubmit', 'RIMS\Enrollment\UpdateController@enrollAdvisedSubmit');
                
            });
            
            Route::group(['prefix'=>'schedule'], function(){
                Route::post('/viewTable', 'RIMS\Schedule\LoadTableController@viewTable');
                Route::post('/searchTable', 'RIMS\Schedule\LoadTableController@searchTable');
                Route::post('/schedWoTable', 'RIMS\Schedule\LoadTableController@schedWoTable');
                
                Route::post('/searchDiv', 'RIMS\Schedule\LoadViewController@searchDiv');

                Route::post('/searchCourseSched', 'RIMS\Schedule\ModalController@searchCourseSched');                
                
                Route::post('/selectRoom', 'RIMS\Schedule\_SelectRoomController@selectRoom');
                Route::post('/selectInstructor', 'RIMS\Schedule\_SelectInstructorController@selectInstructor');
                Route::post('/selectDays', 'RIMS\Schedule\_SelectDays@selectDays');
                Route::post('/selectTime', 'RIMS\Schedule\_SelectTime@selectTime');
            });
            Route::group(['prefix'=>'ludong'], function(){
                Route::post('/studentTable', 'RIMS\Ludong\StudentController@table');
            });
        });
        
        Route::group(['prefix'=>'hrims'], function(){
            Route::group(['prefix'=>'employee'], function(){
                Route::post('/employeeTable', 'HRIMS\Employee\EmployeeController@employeeTable');
                Route::post('/employeeStat', 'HRIMS\Employee\EmployeeController@employeeStat');
                Route::post('/employeeView', 'HRIMS\Employee\EmployeeController@employeeView');
                Route::post('/employeeNewSubmit', 'HRIMS\Employee\EmployeeController@employeeNewSubmit');
                Route::post('/employeeInformation', 'HRIMS\Employee\EmployeeController@employeeInformation');
                Route::post('/uploadImage', 'HRIMS\Employee\EmployeeController@uploadImage');
                Route::post('/workTable', 'HRIMS\Employee\WorkController@workTable');
                
                Route::post('/personalInfo', 'HRIMS\Employee\Information\PersonalInfoController@personalInfo');
                Route::post('/schedule', 'HRIMS\Employee\Information\ScheduleController@schedule');
                Route::post('/employeeStatus', 'HRIMS\Employee\StatusController@status');
                Route::post('/employeeStatusSubmit', 'HRIMS\Employee\StatusController@submit');
                
                
                Route::group(['prefix'=>'information'], function(){
                    Route::post('/infoSubmit', 'HRIMS\Employee\Information\PersonalInfoController@infoSubmit');
                    Route::post('/addressSubmit', 'HRIMS\Employee\Information\PersonalInfoController@addressSubmit');
                    Route::post('/idNoSubmit', 'HRIMS\Employee\Information\PersonalInfoController@idNoSubmit');   
                    
                    Route::group(['prefix'=>'schedule'], function(){
                        Route::post('/schedNewModal', 'HRIMS\Employee\Information\ScheduleController@schedNewModal');
                        Route::post('/schedNewDaysList', 'HRIMS\Employee\Information\ScheduleController@schedNewDaysList');
                        Route::post('/schedNewSubmit', 'HRIMS\Employee\Information\ScheduleController@schedNewSubmit');
                        
                        Route::post('/schedEditModal', 'HRIMS\Employee\Information\ScheduleController@schedEditModal');
                        Route::post('/schedEditDaysList', 'HRIMS\Employee\Information\ScheduleController@schedEditDaysList');
                        Route::post('/schedEditSubmit', 'HRIMS\Employee\Information\ScheduleController@schedEditSubmit');
                        Route::post('/schedDeleteModal', 'HRIMS\Employee\Information\ScheduleController@schedDeleteModal');
                        Route::post('/schedDeleteSubmit', 'HRIMS\Employee\Information\ScheduleController@schedDeleteSubmit');
                    });
                });
                Route::group(['prefix'=>'work'], function(){
                    Route::post('/newModal', 'HRIMS\Employee\WorkController@newModal');
                    Route::post('/editModal', 'HRIMS\Employee\WorkController@editModal');
                    Route::post('/positionShortenGet', 'HRIMS\Employee\WorkController@positionShortenGet');
                    Route::post('/newSubmit', 'HRIMS\Employee\WorkController@newSubmit');
                    Route::post('/editSubmit', 'HRIMS\Employee\WorkController@editSubmit');
                });
            });
            Route::group(['prefix'=>'position'], function(){
                Route::post('/positionTable', 'HRIMS\Position\PositionController@positionTable');
                Route::post('/positionNew', 'HRIMS\Position\PositionController@new');
                Route::post('/positionNewSubmit', 'HRIMS\Position\PositionController@newSubmit');
                Route::post('/positionEdit', 'HRIMS\Position\PositionController@edit');
                Route::post('/positionEditSubmit', 'HRIMS\Position\PositionController@editSubmit');
                Route::post('/positionView', 'HRIMS\Position\PositionController@view');
                Route::post('/positionViewTable', 'HRIMS\Position\PositionController@viewTable');
            });
            Route::group(['prefix'=>'dtr'], function(){
                Route::post('/employeeTable', 'HRIMS\DTR\AllController@table');
                Route::post('/holidayTable', 'HRIMS\DTR\HolidayController@table');
                Route::post('/holidayNewModal', 'HRIMS\DTR\HolidayController@newModal');
                Route::post('/holidayNewSubmit', 'HRIMS\DTR\HolidayController@newSubmit');
                
                Route::post('/dtrView', 'HRIMS\DTR\AllController@dtrView');
                
                Route::get('/pdf/{year}/{month}/{id_no}/{range}', 'HRIMS\DTR\PDFController@PDF');
                Route::post('/individual', 'HRIMS\DTR\IndividualController@individual');
                Route::post('/dtrInputModal', 'HRIMS\DTR\IndividualController@dtrInputModal');
                Route::post('/dtrInputTable', 'HRIMS\DTR\IndividualController@dtrInputTable');
                Route::post('/dtrInputSubmit', 'HRIMS\DTR\IndividualController@dtrInputSubmit');
                Route::post('/dtrInputDurationModal', 'HRIMS\DTR\IndividualController@dtrInputDurationModal');
                Route::post('/dtrInputDurationSubmit', 'HRIMS\DTR\IndividualController@dtrInputDurationSubmit');
            });

            Route::group(['prefix'=>'deduction'], function(){
                Route::group(['prefix'=>'list'], function(){
                    Route::post('/table', 'HRIMS\Deduction\ListController@table');
                    Route::post('/newModal', 'HRIMS\Deduction\ListController@newModal');
                    Route::post('/newSubmit', 'HRIMS\Deduction\ListController@newSubmit');
                });
                Route::group(['prefix'=>'group'], function(){
                    Route::post('/table', 'HRIMS\Deduction\GroupController@table');
                    Route::post('/newModal', 'HRIMS\Deduction\GroupController@newModal');
                    Route::post('/newSubmit', 'HRIMS\Deduction\GroupController@newSubmit');
                    Route::post('/viewModal', 'HRIMS\Deduction\GroupController@viewModal');
                    Route::post('/viewModalTable', 'HRIMS\Deduction\GroupController@viewModalTable');
                    Route::post('/updateModal', 'HRIMS\Deduction\GroupController@updateModal');
                    Route::post('/updateSubmit', 'HRIMS\Deduction\GroupController@updateSubmit');
                });
            });
        });

        Route::group(['prefix'=>'fms'], function(){
            Route::group(['prefix'=>'accounting'], function(){
                Route::group(['prefix'=>'fund'], function(){
                    Route::group(['prefix'=>'cluster'], function(){
                        Route::post('/table', 'FMS\Accounting\Fund\ClusterController@table');
                        Route::post('/newModal', 'FMS\Accounting\Fund\ClusterController@newModal');
                        Route::post('/newSubmit', 'FMS\Accounting\Fund\ClusterController@newSubmit');
                        Route::post('/updateModal', 'FMS\Accounting\Fund\ClusterController@updateModal');
                        Route::post('/updateSubmit', 'FMS\Accounting\Fund\ClusterController@updateSubmit');
                        Route::post('/viewModal', 'FMS\Accounting\Fund\ClusterController@viewModal');
                        Route::post('/viewTable', 'FMS\Accounting\Fund\ClusterController@viewTable');
                    });
                    Route::group(['prefix'=>'source'], function(){
                        Route::post('/table', 'FMS\Accounting\Fund\SourceController@table');
                        Route::post('/newModal', 'FMS\Accounting\Fund\SourceController@newModal');
                        Route::post('/newSubmit', 'FMS\Accounting\Fund\SourceController@newSubmit');
                        Route::post('/updateModal', 'FMS\Accounting\Fund\SourceController@updateModal');
                        Route::post('/updateSubmit', 'FMS\Accounting\Fund\SourceController@updateSubmit');
                    });
                    Route::group(['prefix'=>'financing'], function(){
                        Route::post('/table', 'FMS\Accounting\Fund\FinancingController@table');
                        Route::post('/newModal', 'FMS\Accounting\Fund\FinancingController@newModal');
                        Route::post('/newSubmit', 'FMS\Accounting\Fund\FinancingController@newSubmit');
                        Route::post('/updateModal', 'FMS\Accounting\Fund\FinancingController@updateModal');
                        Route::post('/updateSubmit', 'FMS\Accounting\Fund\FinancingController@updateSubmit');
                    });
                });
            });
        });
    });
});

