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

//Route::group(['middleware' => ['verify.app.token']], function(){
    Route::group(['prefix'=>'api'], function(){
        Route::get('login', 'API\ApiAuthController@login');
    });
//});


Route::group(['middleware' => ['HTTPS']], function(){
    Route::group(['middleware' => ['CheckUser']], function(){
        Route::get('/', 'IndexController@view')->name('indexpage');
        //Route::middleware(['throttle:5,1'])->group(function () {
            Route::post('/login', 'LoginController@check');
        //});
    });

    Route::get('/monitor1', 'HRIMS\DTR\BIOMACHINE\Monitor1Controller@monitor1');
    Route::post('/monitor1/display', 'HRIMS\DTR\BIOMACHINE\Monitor1Controller@display');

    Route::get('/student/certification/{stud_id}/{certification}/{program_level}/{school_year}/{period}/{date}/{pdf_code}', 'RIMS\Student\CertificationController@pdf');
    Route::get('/student/enrollmentform/{stud_id}/{school_year}/{school_year_period}/{enrollment_form_no}/{pdf_code}', 'RIMS\Student\EnrollmentFormController@pdf');

    Route::group(['middleware' => ['auth']], function(){
    //Route::group(['middleware' => ['auth','Login','PreventBackHistory']], function(){
        Route::middleware(['throttle:10,1'])->group(function () {
            Route::group(['prefix'=>'import'], function(){
                Route::post('/import', 'ImportController@import');

            });
        });
        // Route::middleware(['throttle:60,1'])->group(function () {
            Route::get('/ludongExport','EXPORTS\LudongController@export');
            Route::get('/poesExport','EXPORTS\PoesController@export');

            Route::get('/logout', 'LoginController@logout');
            Route::get('/system', 'IndexController@systempage')->name('systempage');
            Route::get('/systems', 'IndexController@systems');

            Route::get('/ids/{system_selected}/{nav_selected}/{type}', 'IndexController@ids');
            Route::get('/ids/{system_selected}/{nav_selected}/{type}/{search}', 'IndexController@ids');

            Route::get('/students/tor/{id_no}/{level}/{dateTime}', 'RIMS\Student\TORController@tor');

            Route::get('/enrollment_form/{id_no}/{school_year}/{school_period}', 'RIMS\Student\EnrollmentFormController@form');

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

            Route::group(['prefix'=>'pagination'], function(){
                Route::get('/paginate', 'PaginationController@index');
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
                Route::post('/employeePayroll', 'SEARCH\PayrollController@employee');
                Route::post('/employeeDesignation', 'SEARCH\EmployeeController@designation');
                Route::post('/studentSearch', 'SEARCH\StudentController@student');
                Route::post('/courseSelect', 'SEARCH\CourseController@course');
                Route::post('/courseSelectID', 'SEARCH\CourseController@courseID');
                Route::post('/employeeSearch', 'SEARCH\EmployeeController@employee');
                Route::post('/unitByDepartment', 'SEARCH\UnitController@byDepartment');
                Route::post('/school', 'SEARCH\SchoolController@school');
                Route::post('/school1', 'SEARCH\SchoolController@school1');
                Route::post('/programSearch2', 'SEARCH\ProgramController@programSearch2');
            });

            Route::group(['prefix'=>'signatory'], function(){
                Route::post('/table', 'SignatoryController@table');
                Route::post('/modal', 'SignatoryController@modal');
                Route::post('/update', 'SignatoryController@update');
            });

            Route::group(['prefix'=>'pdf'], function(){
                Route::get('/view/{pdf_option}', 'PDF\PDFController@view')->name('pdf.view');
                Route::post('/src', 'PDF\PDFController@src');
            });

            Route::group(['prefix'=>'sims'], function(){
                Route::group(['prefix'=>'information'], function(){
                    Route::post('/informationEdit', 'SIMS\Information\InformationController@index');
                    Route::post('/proceedEdit', 'SIMS\Information\InformationController@proceed');
                    Route::post('/informationEdiModal', 'SIMS\Information\InformationController@show');
                    Route::post('/informationEditDiv', 'SIMS\Information\InformationController@showDiv');
                    Route::post('/personalInfoSubmit', 'SIMS\Information\InformationController@update');
                    Route::post('/informationPersonalInfo', 'SIMS\Information\InformationController@edit');

                    Route::post('/coursesSelect', 'SIMS\Information\CoursesController@index');
                    Route::post('/coursesList', 'SIMS\Information\CoursesController@list');

                    Route::post('/curriculumSelect', 'SIMS\Information\CurriculumController@index');
                    Route::post('/curriculumList', 'SIMS\Information\CurriculumController@list');

                    Route::post('/educBgNew', 'SIMS\Information\EducBgController@create');
                    Route::post('/educBgNewSubmit', 'SIMS\Information\EducBgController@store');
                    Route::post('/educBgEdit', 'SIMS\Information\EducBgController@edit');
                    Route::post('/educBgEditSubmit', 'SIMS\Information\EducBgController@update');
                    Route::post('/educBgDelete', 'SIMS\Information\EducBgController@delete');
                    Route::post('/educBgDeleteSubmit', 'SIMS\Information\EducBgController@destroy');
                    Route::post('/informationEducBg', 'SIMS\Information\EducBgController@show');

                    Route::post('/famNew', 'SIMS\Information\FamBgController@create');
                    Route::post('/famNewSubmit', 'SIMS\Information\FamBgController@store');
                    Route::post('/famEdit', 'SIMS\Information\FamBgController@edit');
                    Route::post('/famEditSubmit', 'SIMS\Information\FamBgController@update');
                    Route::post('/famDelete', 'SIMS\Information\FamBgController@delete');
                    Route::post('/famDeleteSubmit', 'SIMS\Information\FamBgController@destroy');
                    Route::post('/informationFamBg', 'SIMS\Information\FamBgController@show');
                });
                Route::group(['prefix'=>'courses'], function(){
                    Route::post('/listTable', 'SIMS\Courses\ListController@index');
                    Route::post('/listCourseView', 'SIMS\Courses\ListController@show');
                    Route::post('/scheduleTable', 'SIMS\Courses\ScheduleController@index');
                });
                Route::group(['prefix'=>'teachers'], function(){
                    Route::post('/teachersTable', 'SIMS\Teachers\TeachersController@index');
                    Route::post('/courseViewModal', 'SIMS\Teachers\TeachersController@show');
                    Route::post('/courseViewTable', 'SIMS\Teachers\TeachersController@showTable');
                });
                Route::group(['prefix'=>'pre_enroll'], function(){
                    Route::post('/preEnrollCourses', 'SIMS\Preenroll\LoadViewController@preEnrollCourses');
                    Route::post('/preenrollSubmit', 'SIMS\Preenroll\PreenrollController@preenrollSubmit');
                });
            });
            Route::group(['prefix'=>'fis'], function(){
                Route::group(['prefix'=>'students'], function(){
                    Route::post('/studentsTable', 'FIS\Students\LoadTableController@studentsTable');
                    Route::post('/studentSchoolYearTable', 'FIS\Students\LoadTableController@studentSchoolYearTable');

                    Route::post('/gradeLevel', 'FIS\Students\LoadViewController@gradeLevel');

                    Route::post('/studentViewModal', 'FIS\Students\ModalController@studentViewModal');
                });
                Route::group(['prefix'=>'courses'], function(){
                    Route::post('/allTable', 'FIS\CoursesController@index');
                    Route::post('/subjectsTable', 'FIS\CoursesController@semTable');
                    Route::post('/studentsListTable', 'FIS\CoursesController@showTable');
                    Route::post('/studentsListModal', 'FIS\CoursesController@show');
                    Route::post('/studentGradeUpdate', 'FIS\CoursesController@update');
                });
                Route::group(['prefix'=>'schedule'], function(){
                    Route::post('/scheduleTable', 'FIS\ScheduleController@index');
                });
                Route::group(['prefix'=>'advisement'], function(){
                    Route::post('/studentInfo', 'FIS\Advisement\LoadViewController@studentInfo');
                    Route::post('/curriculumSelect', 'FIS\Advisement\LoadViewController@curriculumSelect');
                    Route::post('/studentAdvisement', 'FIS\Advisement\LoadViewController@studentAdvisement');
                    Route::post('/sectionSelect', 'FIS\Advisement\LoadViewController@sectionSelect');

                    Route::post('/advisementSubmit', 'FIS\Advisement\UpdateController@advisementSubmit');
                });
                Route::post('/loadSheet', 'FIS\LoadSheetController@index');
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
                    Route::post('/studentShiftModalCurriculum', 'RIMS\Student\LoadViewController@studentShiftModalCurriculum');
                    Route::post('/studentCurriculumList', 'RIMS\Student\LoadViewController@studentCurriculumList');
                    Route::post('/studentCurriculumLoad', 'RIMS\Student\LoadViewController@studentCurriculumLoad');

                    Route::post('/studentViewModal', 'RIMS\Student\ModalController@studentViewModal');
                    Route::post('/studentTORModal', 'RIMS\Student\ModalController@studentTORModal');
                    Route::post('/studentCurriculumModal', 'RIMS\Student\ModalController@studentCurriculumModal');
                    Route::post('/studentCoursesModal', 'RIMS\Student\ModalController@studentCoursesModal');
                    Route::post('/studentCourseAddModal', 'RIMS\Student\ModalController@studentCourseAddModal');
                    Route::post('/studentCourseAddTr', 'RIMS\Student\ModalController@studentCourseAddTr');
                    Route::post('/studentShiftModal', 'RIMS\Student\ModalController@studentShiftModal');
                    Route::post('/studentPrintModal', 'RIMS\Student\ModalController@studentPrintModal');

                    Route::post('/studentEditInfo', 'RIMS\Student\InfoController@index');
                    Route::post('/studentProgramList', 'RIMS\Student\InfoController@programList');
                    Route::post('/studentCurriculumList', 'RIMS\Student\InfoController@curriculumList');
                    Route::post('/studentUpdateInfoSubmit', 'RIMS\Student\InfoController@infoUpdate');
                    Route::post('/studentUpdateContactSubmit', 'RIMS\Student\InfoController@contactUpdate');
                    Route::post('/studentUpdateEducSubmit', 'RIMS\Student\InfoController@educUpdate');
                    Route::post('/studentUpdateFamSubmit', 'RIMS\Student\InfoController@famUpdate');

                    Route::post('/studentSelectProgramModal', 'RIMS\Student\SelectProgramController@index');
                    Route::post('/selectProgramList', 'RIMS\Student\SelectProgramController@showProgram');
                    Route::post('/selectCurriculumList', 'RIMS\Student\SelectProgramController@showCurriculum');

                    Route::post('/studentGradesModal', 'RIMS\Student\GradesController@index');
                    Route::post('/studentGradesList', 'RIMS\Student\GradesController@show');

                    Route::post('/studentCertificationModal', 'RIMS\Student\CertificationController@index');
                    Route::post('/certificationSYperiod', 'RIMS\Student\CertificationController@showSYperiod');
                    Route::post('/certificationSubmit', 'RIMS\Student\CertificationController@show');
                    Route::post('/certificationDisplay', 'RIMS\Student\CertificationController@display');


                    Route::post('/studentShiftModalSubmit', 'RIMS\Student\UpdateController@studentShiftModalSubmit');
                    Route::post('/studentCourseAddModalSubmit', 'RIMS\Student\UpdateController@studentCourseAddModalSubmit');
                    Route::post('/studentCourseCreditSubmit', 'RIMS\Student\UpdateController@studentCourseCreditSubmit');
                    Route::post('/studentCreditRemove', 'RIMS\Student\UpdateController@studentCreditRemove');
                    Route::post('/studentPrintSubmit', 'RIMS\Student\UpdateController@studentPrintSubmit');
                    Route::post('/specializationNameSubmit', 'RIMS\Student\UpdateController@specializationNameSubmit');
                    Route::post('/useThisCurriculum', 'RIMS\Student\UpdateController@useThisCurriculum');
                });
                Route::group(['prefix'=>'departments'], function(){
                    Route::post('/viewTable', 'RIMS\Departments\DepartmentController@index');
                    Route::post('/editModal', 'RIMS\Departments\DepartmentController@edit');
                    Route::post('/newModal', 'RIMS\Departments\DepartmentController@create');
                    Route::post('/newModalSubmit', 'RIMS\Departments\DepartmentController@store');
                    Route::post('/editModalSubmit', 'RIMS\Departments\DepartmentController@update');

                    Route::post('/programsModal', 'RIMS\Departments\ProgramController@index');
                    Route::post('/programsList', 'RIMS\Departments\ProgramController@show');
                    Route::post('/programAddList', 'RIMS\Departments\ProgramController@create');
                    Route::post('/programAddModal', 'RIMS\Departments\ProgramController@edit');
                    Route::post('/programsAddSubmit', 'RIMS\Departments\ProgramController@update');

                });
                Route::group(['prefix'=>'programs'], function(){
                    Route::post('/viewTable', 'RIMS\Programs\ProgramController@index');
                    Route::post('/viewModal', 'RIMS\Programs\ProgramController@show');
                    Route::post('/programNewModal', 'RIMS\Programs\ProgramController@create');
                    Route::post('/programsNewSubmit', 'RIMS\Programs\ProgramController@store');
                    Route::post('/programStatusModal', 'RIMS\Programs\ProgramController@status');
                    Route::post('/programStatusSubmit', 'RIMS\Programs\ProgramController@statusUpdate');
                    Route::post('/programEdit', 'RIMS\Programs\ProgramController@edit');
                    Route::post('/programUpdate', 'RIMS\Programs\ProgramController@update');
                    Route::post('/departments', 'RIMS\Programs\ProgramController@departments');

                    Route::post('/branch', 'RIMS\Programs\BranchController@index');
                    Route::post('/branchTable', 'RIMS\Programs\BranchController@show');
                    Route::post('/branchUpdate', 'RIMS\Programs\BranchController@update');

                    Route::post('/curriculumTable', 'RIMS\Programs\LoadViewController@curriculumTable');
                    Route::post('/curriculumTablePre', 'RIMS\Programs\LoadViewController@curriculumTablePre');
                    Route::post('/courseTablePre', 'RIMS\Programs\LoadViewController@courseTablePre');
                    Route::post('/curriculumInfo', 'RIMS\Programs\LoadViewController@curriculumInfo');
                    Route::post('/courseInfo', 'RIMS\Programs\LoadViewController@courseInfo');

                    Route::post('/newCourse', 'RIMS\Programs\ModalController@newCourse');
                    Route::post('/courseUpdate', 'RIMS\Programs\ModalController@courseUpdate');
                    Route::post('/curriculumNewModal', 'RIMS\Programs\ModalController@curriculumNewModal');

                    Route::post('/newCourseSubmit', 'RIMS\Programs\NewController@newCourseSubmit');
                    Route::post('/curriculumNewSubmit', 'RIMS\Programs\NewController@curriculumNewSubmit');

                    Route::post('/courseStatus', 'RIMS\Programs\UpdateController@courseStatus');
                    Route::post('/courseUpdateSubmit', 'RIMS\Programs\UpdateController@courseUpdateSubmit');
                    Route::post('/curriculumStatus', 'RIMS\Programs\UpdateController@curriculumStatus');
                    Route::post('/curriculumInputUpdate', 'RIMS\Programs\UpdateController@curriculumInputUpdate');
                });
                Route::group(['prefix'=>'curriculums'], function(){
                    Route::post('/viewTable', 'RIMS\Curriculums\CurriculumsController@index');
                    Route::post('/viewModal/{id}', 'RIMS\Curriculums\CurriculumsController@viewModal');
                    Route::post('/editModal/{id}', 'RIMS\Curriculums\CurriculumsController@editModal');
                    Route::post('/updateSubmit/{id}', 'RIMS\Curriculums\CurriculumsController@update');
                    Route::post('/programList/{id}', 'RIMS\Curriculums\CurriculumsController@programList');
                    Route::post('/newModal', 'RIMS\Curriculums\CurriculumsController@create');
                    Route::post('/storeSubmit', 'RIMS\Curriculums\CurriculumsController@store');
                    Route::post('/departments', 'RIMS\Curriculums\CurriculumsController@departments');
                });
                Route::group(['prefix'=>'courses'], function(){
                    Route::post('/coursesTable/{id}', 'RIMS\Courses\CoursesController@index');

                });
                Route::group(['prefix'=>'buildings'], function(){
                    Route::post('/buildingsTable', 'RIMS\Buildings\BuildingsController@index');
                    Route::post('/buildingsNewModal', 'RIMS\Buildings\BuildingsController@create');
                    Route::post('/buildingsNewSubmit', 'RIMS\Buildings\BuildingsController@store');
                    Route::get('/buildingsViewModal/{id}', 'RIMS\Buildings\BuildingsController@show');
                    Route::get('/buildingsRoomsTable/{id}', 'RIMS\Buildings\BuildingsController@showTable');
                    Route::get('/buildingsEditModal/{id}', 'RIMS\Buildings\BuildingsController@edit');
                    Route::get('/buildingsEditSubmit/{id}', 'RIMS\Buildings\BuildingsController@update');
                });
                Route::group(['prefix'=>'rooms'], function(){
                    Route::post('/roomsTable', 'RIMS\Rooms\RoomsController@index');
                    Route::post('/roomsNewModal', 'RIMS\Rooms\RoomsController@create');
                    Route::post('/roomsNewSubmit', 'RIMS\Rooms\RoomsController@store');
                    Route::get('/roomsEditModal/{id}', 'RIMS\Rooms\RoomsController@edit');
                    Route::get('/roomsEditSubmit/{id}', 'RIMS\Rooms\RoomsController@update');
                });
                Route::group(['prefix'=>'schoolYear'], function(){
                    Route::post('/viewTable', 'RIMS\SchoolYear\SchoolYearController@index');
                    Route::post('/editView', 'RIMS\SchoolYear\SchoolYearController@edit');
                    Route::post('/schoolYearEditSubmit', 'RIMS\SchoolYear\SchoolYearController@update');
                    Route::post('/new', 'RIMS\SchoolYear\SchoolYearController@store');

                    Route::post('/programsViewModal', 'RIMS\SchoolYear\ProgramController@index');
                    Route::post('/programsViewTable', 'RIMS\SchoolYear\ProgramController@show');
                    Route::post('/offerPrograms', 'RIMS\SchoolYear\ProgramController@store');
                    Route::post('/programs', 'RIMS\SchoolYear\ProgramController@edit');
                    Route::post('/moveProgram', 'RIMS\SchoolYear\ProgramController@update');

                    Route::post('/coursesViewModal', 'RIMS\SchoolYear\CourseController@index');
                    Route::post('/curriculumViewList', 'RIMS\SchoolYear\CourseController@show');
                    Route::post('/courseViewStatusModal', 'RIMS\SchoolYear\CourseController@edit');
                    Route::post('/courseViewStatusSubmit', 'RIMS\SchoolYear\CourseController@update');

                    Route::post('/coursesOpenModal', 'RIMS\SchoolYear\CourseOpenController@index');
                    Route::post('/coursesListTable', 'RIMS\SchoolYear\CourseOpenController@show');
                    Route::post('/courseOpenSubmit', 'RIMS\SchoolYear\CourseOpenController@store');

                    Route::post('/selectStatusUpdate', 'RIMS\SchoolYear\StatusUpdateController@update');

                });
                Route::group(['prefix'=>'sections'], function(){
                    Route::post('/viewTable', 'RIMS\Sections\LoadTableController@viewTable');
                    Route::post('/sectionViewTable', 'RIMS\Sections\LoadTableController@sectionViewTable');
                    Route::post('/courseViewTable', 'RIMS\Sections\LoadTableController@courseViewTable');

                    Route::post('/programsSelect', 'RIMS\Sections\LoadViewController@programsSelect');
                    Route::post('/gradeLevelSelect', 'RIMS\Sections\LoadViewController@gradeLevelSelect');
                    Route::post('/courseSchedRmSchedule', 'RIMS\Sections\LoadViewController@courseSchedRmSchedule');
                    Route::post('/courseSchedRmTable', 'RIMS\Sections\LoadViewController@courseSchedRmTable');

                    Route::post('/sectionNewModal', 'RIMS\Sections\ModalController@sectionNewModal');
                    Route::post('/sectionViewModal', 'RIMS\Sections\ModalController@sectionViewModal');
                    Route::post('/courseViewModal', 'RIMS\Sections\ModalController@courseViewModal');
                    Route::post('/minMaxModal', 'RIMS\Sections\ModalController@minMaxModal');

                    Route::post('/sectionNewSubmit', 'RIMS\Sections\NewController@sectionNewSubmit');

                    Route::post('/courseSchedRmInstructorUpdate', 'RIMS\Sections\UpdateController@courseSchedRmInstructorUpdate');
                    Route::post('/scheduleTimeUpdate', 'RIMS\Sections\UpdateController@scheduleTimeUpdate');
                    Route::post('/typeUpdate', 'RIMS\Sections\UpdateController@typeUpdate');
                    Route::post('/minMaxSubmit', 'RIMS\Sections\UpdateController@minMaxSubmit');
                    Route::post('/minMaxStudent', 'RIMS\Sections\UpdateController@minMaxStudent');

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
                    Route::post('/dateList', 'RIMS\Enrollment\LoadViewController@dateList');

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

                    Route::post('/scheduleCourseModal', 'RIMS\Schedule\ScheduleController@index');
                    Route::post('/scheduleCourseTime', 'RIMS\Schedule\ScheduleController@time');
                    Route::post('/scheduleCourseDetails', 'RIMS\Schedule\ScheduleController@details');
                    Route::post('/scheduleCourseTable', 'RIMS\Schedule\ScheduleController@show');
                    Route::post('/scheduleCourseTableRe', 'RIMS\Schedule\ScheduleController@reShow');
                    Route::post('/scheduleCourseUpdate', 'RIMS\Schedule\ScheduleController@update');
                    Route::post('/scheduleRemove', 'RIMS\Schedule\ScheduleController@destroy');
                    Route::post('/scheduleRemoveDay', 'RIMS\Schedule\ScheduleController@destroyDay');

                    Route::post('/selectDay', 'RIMS\Schedule\_SelectDetailsList@selectDay');
                    Route::post('/selectTime', 'RIMS\Schedule\_SelectDetailsList@selectTime');
                    Route::post('/selectRoom', 'RIMS\Schedule\_SelectRoomController@selectRoom');
                    Route::post('/selectInstructor', 'RIMS\Schedule\_SelectInstructorController@selectInstructor');

                    Route::post('/roomView', 'RIMS\Schedule\RoomController@index');
                    Route::post('/roomTable', 'RIMS\Schedule\RoomController@show');

                    Route::post('/instructorView', 'RIMS\Schedule\InstructorController@index');
                    Route::post('/instructorTable', 'RIMS\Schedule\InstructorController@show');

                });

                Route::group(['prefix'=>'addDrop'], function(){
                    Route::post('/dropDiv', 'RIMS\AddDrop\AddDropController@dropDiv');
                    Route::post('/dropSubmit', 'RIMS\AddDrop\AddDropController@dropSubmit');
                    Route::post('/addSubmit', 'RIMS\AddDrop\AddDropController@addSubmit');
                });

                Route::group(['prefix'=>'nstp'], function(){
                    Route::post('/nstpTable', 'RIMS\NstpController@index');
                    Route::post('/newModal', 'RIMS\NstpController@create');
                    Route::post('/newSubmit', 'RIMS\NstpController@store');
                    Route::post('/viewModal', 'RIMS\NstpController@show');
                    Route::post('/getCount', 'RIMS\NstpController@getCount');
                    Route::post('/studentList', 'RIMS\NstpController@show');
                    Route::post('/studentListTable', 'RIMS\NstpController@showTable');
                    Route::post('/editCount', 'RIMS\NstpController@edit');
                    Route::post('/editCountSubmit', 'RIMS\NstpController@update');
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

                    Route::post('/familyInfo', 'HRIMS\Employee\Information\FamilyInfoController@index');
                    Route::post('/familyTable', 'HRIMS\Employee\Information\FamilyInfoController@show');
                    Route::post('/familyNew', 'HRIMS\Employee\Information\FamilyInfoController@create');
                    Route::post('/familyNewSubmit', 'HRIMS\Employee\Information\FamilyInfoController@store');
                    Route::post('/familyMoreInfo', 'HRIMS\Employee\Information\FamilyInfoController@showMore');
                    Route::post('/familyEdit', 'HRIMS\Employee\Information\FamilyInfoController@edit');
                    Route::post('/familyEditSubmit', 'HRIMS\Employee\Information\FamilyInfoController@update');
                    Route::post('/familyDelete', 'HRIMS\Employee\Information\FamilyInfoController@delete');
                    Route::post('/familyDeleteSubmit', 'HRIMS\Employee\Information\FamilyInfoController@destroy');

                    Route::post('/educInfo', 'HRIMS\Employee\Information\EducInfoController@index');
                    Route::post('/educTable', 'HRIMS\Employee\Information\EducInfoController@show');
                    Route::post('/educNew', 'HRIMS\Employee\Information\EducInfoController@create');
                    Route::post('/educNewSubmit', 'HRIMS\Employee\Information\EducInfoController@store');
                    Route::post('/educEdit', 'HRIMS\Employee\Information\EducInfoController@edit');
                    Route::post('/educEditSubmit', 'HRIMS\Employee\Information\EducInfoController@update');
                    Route::post('/educDelete', 'HRIMS\Employee\Information\EducInfoController@delete');
                    Route::post('/educDeleteSubmit', 'HRIMS\Employee\Information\EducInfoController@destroy');

                    Route::post('/eligInfo', 'HRIMS\Employee\Information\EligInfoController@index');
                    Route::post('/eligTable', 'HRIMS\Employee\Information\EligInfoController@show');
                    Route::post('/eligDoc', 'HRIMS\Employee\Information\EligInfoController@showDoc');
                    Route::post('/eligNew', 'HRIMS\Employee\Information\EligInfoController@create');
                    Route::post('/eligNewSubmit', 'HRIMS\Employee\Information\EligInfoController@store');
                    Route::post('/eligEdit', 'HRIMS\Employee\Information\EligInfoController@edit');
                    Route::post('/eligEditSubmit', 'HRIMS\Employee\Information\EligInfoController@update');
                    Route::post('/eligDelete', 'HRIMS\Employee\Information\EligInfoController@delete');
                    Route::post('/eligDeleteSubmit', 'HRIMS\Employee\Information\EligInfoController@destroy');

                    Route::post('/expInfo', 'HRIMS\Employee\Information\ExpInfoController@index');
                    Route::post('/expTable', 'HRIMS\Employee\Information\ExpInfoController@show');
                    Route::post('/expDoc', 'HRIMS\Employee\Information\ExpInfoController@showDoc');

                    Route::post('/volunInfo', 'HRIMS\Employee\Information\VolunInfoController@index');
                    Route::post('/volunTable', 'HRIMS\Employee\Information\VolunInfoController@show');
                    Route::post('/volunDoc', 'HRIMS\Employee\Information\VolunInfoController@showDoc');
                    Route::post('/volunNew', 'HRIMS\Employee\Information\VolunInfoController@create');
                    Route::post('/volunNewSubmit', 'HRIMS\Employee\Information\VolunInfoController@store');
                    Route::post('/volunEdit', 'HRIMS\Employee\Information\VolunInfoController@edit');
                    Route::post('/volunEditSubmit', 'HRIMS\Employee\Information\VolunInfoController@update');
                    Route::post('/volunDelete', 'HRIMS\Employee\Information\VolunInfoController@delete');
                    Route::post('/volunDeleteSubmit', 'HRIMS\Employee\Information\VolunInfoController@destroy');

                    Route::post('/learnInfo', 'HRIMS\Employee\Information\LearnInfoController@index');
                    Route::post('/learnTable', 'HRIMS\Employee\Information\LearnInfoController@show');
                    Route::post('/learnDoc', 'HRIMS\Employee\Information\LearnInfoController@showDoc');
                    Route::post('/learnNew', 'HRIMS\Employee\Information\LearnInfoController@create');
                    Route::post('/learnNewSubmit', 'HRIMS\Employee\Information\LearnInfoController@store');
                    Route::post('/learnEdit', 'HRIMS\Employee\Information\LearnInfoController@edit');
                    Route::post('/learnEditSubmit', 'HRIMS\Employee\Information\LearnInfoController@update');
                    Route::post('/learnDelete', 'HRIMS\Employee\Information\LearnInfoController@delete');
                    Route::post('/learnDeleteSubmit', 'HRIMS\Employee\Information\LearnInfoController@destroy');

                    Route::post('/otherInfo', 'HRIMS\Employee\Information\OtherInfoController@index');
                    Route::post('/otherSkillTable', 'HRIMS\Employee\Information\OtherInfoController@showSkill');
                    Route::post('/otherRecognitionTable', 'HRIMS\Employee\Information\OtherInfoController@showRecognition');
                    Route::post('/otherOrganizationTable', 'HRIMS\Employee\Information\OtherInfoController@showOrganization');
                    Route::post('/otherNew', 'HRIMS\Employee\Information\OtherInfoController@create');
                    Route::post('/otherNewSubmit', 'HRIMS\Employee\Information\OtherInfoController@store');
                    Route::post('/otherEdit', 'HRIMS\Employee\Information\OtherInfoController@edit');
                    Route::post('/otherEditSubmit', 'HRIMS\Employee\Information\OtherInfoController@update');
                    Route::post('/otherDelete', 'HRIMS\Employee\Information\OtherInfoController@delete');
                    Route::post('/otherDeleteSubmit', 'HRIMS\Employee\Information\OtherInfoController@destroy');

                    Route::post('/docInfo', 'HRIMS\Employee\Information\DocInfoController@index');

                    Route::post('/paginate', 'HRIMS\Employee\EmployeePaginateController@paginate');
                    Route::post('/counts', 'HRIMS\Employee\EmployeePaginateController@counts');

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
                    Route::group(['prefix'=>'doc'], function(){
                        Route::post('/pdsInfo', 'HRIMS\Employee\Information\Doc\PdsDocController@index');

                    });
                    Route::group(['prefix'=>'deduction'], function(){
                        Route::post('/deductionModal', 'HRIMS\Employee\Deduction\DeductionController@deductionModal');
                        Route::post('/deductionTable', 'HRIMS\Employee\Deduction\DeductionController@deductionTable');
                        Route::post('/deductionUpdate', 'HRIMS\Employee\Deduction\DeductionController@update');
                        Route::post('/docsModal', 'HRIMS\Employee\Deduction\DocsController@modal');
                        Route::post('/docsTable', 'HRIMS\Employee\Deduction\DocsController@table');
                        Route::post('/docsSubmit', 'HRIMS\Employee\Deduction\DocsController@submit');
                        Route::post('/docsViewModal', 'HRIMS\Employee\Deduction\DocsController@viewModal');
                    });
                    Route::group(['prefix'=>'allowance'], function(){
                        Route::post('/table', 'HRIMS\Employee\Allowance\AllowanceController@table');
                    });
                    Route::group(['prefix'=>'work'], function(){
                        Route::post('/newModal', 'HRIMS\Employee\WorkController@newModal');
                        Route::post('/editModal', 'HRIMS\Employee\WorkController@editModal');
                        Route::post('/positionShortenGet', 'HRIMS\Employee\WorkController@positionShortenGet');
                        Route::post('/newSubmit', 'HRIMS\Employee\WorkController@newSubmit');
                        Route::post('/editSubmit', 'HRIMS\Employee\WorkController@editSubmit');
                    });
                });
                Route::group(['prefix'=>'payroll'], function(){
                    Route::group(['prefix'=>'generate'], function(){
                        Route::post('/table', 'HRIMS\Payroll\Generate\GenerateController@table');
                        Route::post('/list', 'HRIMS\Payroll\Generate\GenerateController@list');
                        Route::post('/generate', 'HRIMS\Payroll\Generate\GenerateController@generate');
                    });
                    Route::group(['prefix'=>'view'], function(){
                        Route::post('/table', 'HRIMS\Payroll\PayrollListController@index');
                        Route::post('/delete', 'HRIMS\Payroll\PayrollListController@delete');
                        Route::post('/deleteSubmit', 'HRIMS\Payroll\PayrollListController@destroy');
                        Route::get('/{payroll_id}/{encoded}', 'HRIMS\Payroll\PayrollViewController@index');
                        Route::post('/payroll_table', 'HRIMS\Payroll\PayrollViewController@show');
                        Route::post('/deductionModal', 'HRIMS\Payroll\PayrollDeductionController@index');
                        Route::post('/deductionModalTable', 'HRIMS\Payroll\PayrollDeductionController@show');
                        Route::post('/deductionModalInput', 'HRIMS\Payroll\PayrollDeductionController@update');
                        Route::post('/allowanceModalTable', 'HRIMS\Payroll\PayrollAllowanceController@index');
                        Route::post('/allowanceModalCheck', 'HRIMS\Payroll\PayrollAllowanceController@update');
                        Route::post('/lwopModalInput', 'HRIMS\Payroll\PayrollLWOPController@index');
                        Route::post('/monthInput', 'HRIMS\Payroll\PayrollMonthController@index');
                        Route::post('/salaryChange', 'HRIMS\Payroll\PayrollSalaryController@index');
                        Route::post('/addEmployeeSubmit', 'HRIMS\Payroll\PayrollViewController@store');
                        Route::post('/removeEmployeeModal', 'HRIMS\Payroll\PayrollViewController@destroyView');
                        Route::post('/removeEmployeeModalSubmit', 'HRIMS\Payroll\PayrollViewController@destroy');
                        Route::post('/generatePayroll', 'HRIMS\Payroll\PayrollViewController@update');
                        Route::get('/src', 'HRIMS\Payroll\PayrollPrintController@src');

                    });
                    Route::group(['prefix'=>'payrollType'], function(){
                        Route::post('/table', 'HRIMS\Payroll\TypeController@index');
                        Route::post('/newModal', 'HRIMS\Payroll\TypeController@create');
                        Route::post('/newSubmit', 'HRIMS\Payroll\TypeController@store');
                        Route::post('/updateModal', 'HRIMS\Payroll\TypeController@edit');
                        Route::post('/updateSubmit', 'HRIMS\Payroll\TypeController@update');

                        Route::post('/newGuideline', 'HRIMS\Payroll\TypeGuidelineController@create');
                        Route::post('/newGuidelineSubmit', 'HRIMS\Payroll\TypeGuidelineController@store');
                        Route::post('/tableGuideline/{id}', 'HRIMS\Payroll\TypeGuidelineController@show');
                        Route::post('/editGuideline/{id}', 'HRIMS\Payroll\TypeGuidelineController@edit');
                        Route::post('/editGuidelineSubmit/{id}', 'HRIMS\Payroll\TypeGuidelineController@update');
                        Route::post('/deleteGuideline/{id}', 'HRIMS\Payroll\TypeGuidelineController@delete');
                        Route::post('/deleteGuidelineSubmit/{id}', 'HRIMS\Payroll\TypeGuidelineController@destroy');
                    });
                    Route::group(['prefix'=>'deduction'], function(){
                        Route::group(['prefix'=>'list'], function(){
                            Route::post('/table', 'HRIMS\Payroll\DeductionListController@index');
                            Route::post('/newModal', 'HRIMS\Payroll\DeductionListController@create');
                            Route::post('/newSubmit', 'HRIMS\Payroll\DeductionListController@store');
                            Route::post('/updateModal', 'HRIMS\Payroll\DeductionListController@edit');
                            Route::post('/updateSubmit', 'HRIMS\Payroll\DeductionListController@update');
                        });
                        Route::group(['prefix'=>'group'], function(){
                            Route::post('/table', 'HRIMS\Payroll\DeductionGroupController@index');
                            Route::post('/newModal', 'HRIMS\Payroll\DeductionGroupController@create');
                            Route::post('/newSubmit', 'HRIMS\Payroll\DeductionGroupController@store');
                            Route::post('/viewModal', 'HRIMS\Payroll\DeductionGroupController@show');
                            Route::post('/viewModalTable', 'HRIMS\Payroll\DeductionGroupController@showTable');
                            Route::post('/updateModal', 'HRIMS\Payroll\DeductionGroupController@edit');
                            Route::post('/updateSubmit', 'HRIMS\Payroll\DeductionGroupController@update');
                        });
                    });
                    Route::group(['prefix'=>'allowance'], function(){
                        Route::post('/table', 'HRIMS\Payroll\AllowanceController@index');
                        Route::post('/newModal', 'HRIMS\Payroll\AllowanceController@create');
                        Route::post('/newSubmit', 'HRIMS\Payroll\AllowanceController@store');
                        Route::post('/updateModal', 'HRIMS\Payroll\AllowanceController@edit');
                        Route::post('/updateSubmit', 'HRIMS\Payroll\AllowanceController@update');
                    });
                    Route::group(['prefix'=>'billing'], function(){
                        Route::post('/table', 'HRIMS\Payroll\BillingController@index');
                        Route::post('/show', 'HRIMS\Payroll\BillingController@show');
                        Route::post('/showTable', 'HRIMS\Payroll\BillingController@showTable');
                        Route::post('/assign', 'HRIMS\Payroll\BillingController@assign');
                        Route::post('/assignSubmit', 'HRIMS\Payroll\BillingController@assignSubmit');
                        Route::post('/import', 'HRIMS\Payroll\BillingController@import');
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
                Route::group(['prefix'=>'designation'], function(){
                    Route::post('/table', 'HRIMS\Designation\DesignationController@index');
                    Route::post('/new', 'HRIMS\Designation\DesignationController@create');
                    Route::post('/newSubmit', 'HRIMS\Designation\DesignationController@store');
                });
                Route::group(['prefix'=>'dtr'], function(){
                    Route::post('/employeeTable', 'HRIMS\DTR\AllController@index');
                    Route::post('/receiveDTR', 'HRIMS\DTR\AllController@update');
                    Route::post('/dtrView', 'HRIMS\DTR\AllController@show');

                    Route::post('/holidayTable', 'HRIMS\DTR\HolidayController@table');
                    Route::post('/holidayNewModal', 'HRIMS\DTR\HolidayController@newModal');
                    Route::post('/holidayNewSubmit', 'HRIMS\DTR\HolidayController@newSubmit');

                    Route::get('/pdf/{year}/{month}/{id_no}/{range}/{option}', 'HRIMS\DTR\PDFController@PDF');
                    Route::post('/individual', 'HRIMS\DTR\IndividualController@individual');
                    Route::post('/dtrInputModal', 'HRIMS\DTR\IndividualController@dtrInputModal');
                    Route::post('/dtrInputTable', 'HRIMS\DTR\IndividualController@dtrInputTable');
                    Route::post('/dtrInputSubmit', 'HRIMS\DTR\IndividualController@dtrInputSubmit');
                    Route::post('/dtrInputDurationModal', 'HRIMS\DTR\IndividualController@dtrInputDurationModal');
                    Route::post('/dtrInputDurationSubmit', 'HRIMS\DTR\IndividualController@dtrInputDurationSubmit');
                    Route::post('/schedule', 'HRIMS\DTR\IndividualController@schedule');
                    Route::post('/department', 'HRIMS\DTR\IndividualController@department');
                    Route::post('/departmentSubmit', 'HRIMS\DTR\IndividualController@departmentSubmit');
                });
                Route::group(['prefix'=>'office'], function(){
                    Route::post('/office', 'HRIMS\Office\OfficeController@index');
                    Route::post('/officeTable', 'HRIMS\Office\OfficeController@table');
                    Route::post('/officeNew', 'HRIMS\Office\OfficeController@create');
                    Route::post('/officeNewSubmit', 'HRIMS\Office\OfficeController@store');
                    Route::post('/officeUpdate/{id}', 'HRIMS\Office\OfficeController@edit');
                    Route::post('/officeUpdateSubmit/{id}', 'HRIMS\Office\OfficeController@update');
                });
                Route::group(['prefix'=>'import'], function(){
                    Route::post('/import', 'HRIMS\Import\ImportController@index');
                });
                Route::group(['prefix'=>'my'], function(){
                    Route::post('/payslip', 'HRIMS\MY\PayslipController@index');
                });
                Route::group(['prefix'=>'devices'], function(){
                    Route::post('/devicesTable', 'HRIMS\Devices\DevicesController@table');
                    Route::post('/devicesNewModal', 'HRIMS\Devices\DevicesController@newModal');
                    Route::post('/devicesNewModalSubmit', 'HRIMS\Devices\DevicesController@newModalSubmit');
                    Route::post('/devicesEditModal', 'HRIMS\Devices\DevicesController@editModal');
                    Route::post('/devicesEditModalSubmit', 'HRIMS\Devices\DevicesController@editModalSubmit');
                    Route::post('/devicesUpdateStatus', 'HRIMS\Devices\DevicesController@updateStatus');
                    Route::post('/devicesDateTimeModal', 'HRIMS\Devices\DevicesController@dateTimeModal');
                    Route::post('/dateTimeModalSubmit', 'HRIMS\Devices\DevicesController@dateTimeModalSubmit');

                    Route::post('/logsAcquire', 'HRIMS\Devices\LogsController@acquire');
                    Route::post('/logsClear', 'HRIMS\Devices\LogsController@clear');
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
                        Route::group(['prefix'=>'services'], function(){
                            Route::post('/table', 'FMS\Accounting\Fund\ServicesController@table');
                            Route::post('/newModal', 'FMS\Accounting\Fund\ServicesController@newModal');
                            Route::post('/newSubmit', 'FMS\Accounting\Fund\ServicesController@newSubmit');
                            Route::post('/updateModal', 'FMS\Accounting\Fund\ServicesController@updateModal');
                            Route::post('/updateSubmit', 'FMS\Accounting\Fund\ServicesController@updateSubmit');
                        });
                    });

                    Route::group(['prefix'=>'fees'], function(){
                        Route::group(['prefix'=>'fees'], function(){
                            Route::post('/table', 'FMS\Accounting\Fees\FeesController@table');
                            Route::post('/newModal', 'FMS\Accounting\Fees\FeesController@newModal');
                            Route::post('/newSubmit', 'FMS\Accounting\Fees\FeesController@newSubmit');
                            Route::post('/feesSubmit', 'FMS\Accounting\Fees\FeesController@feesSubmit');
                            Route::post('/feesAllSubmit', 'FMS\Accounting\Fees\FeesController@feesAllSubmit');
                            Route::post('/labFeeModal', 'FMS\Accounting\Fees\FeesController@labFeeModal');
                        });
                        Route::group(['prefix'=>'lab'], function(){
                            Route::post('/tableGroup', 'FMS\Accounting\Fees\LabController@tableGroup');
                            Route::post('/tableCourses', 'FMS\Accounting\Fees\LabController@tableCourses');
                            Route::post('/newGroupModal', 'FMS\Accounting\Fees\LabController@newGroupModal');
                            Route::post('/newGroupModalSubmit', 'FMS\Accounting\Fees\LabController@newGroupModalSubmit');
                            Route::post('/updateGroupModal', 'FMS\Accounting\Fees\LabController@updateGroupModal');
                            Route::post('/updateGroupModalSubmit', 'FMS\Accounting\Fees\LabController@updateGroupModalSubmit');
                            Route::post('/groupCoursesModal', 'FMS\Accounting\Fees\LabController@groupCoursesModal');
                            Route::post('/tableGroupCourses', 'FMS\Accounting\Fees\LabController@tableGroupCourses');
                            Route::post('/groupCourseAdd', 'FMS\Accounting\Fees\LabController@groupCourseAdd');
                            Route::post('/groupCourseRemove', 'FMS\Accounting\Fees\LabController@groupCourseRemove');
                            Route::post('/labCoursesAmount', 'FMS\Accounting\Fees\LabController@labCoursesAmount');
                        });
                        Route::group(['prefix'=>'list'], function(){
                            Route::post('/table', 'FMS\Accounting\Fees\ListController@table');
                            Route::post('/newModal', 'FMS\Accounting\Fees\ListController@newModal');
                            Route::post('/newSubmit', 'FMS\Accounting\Fees\ListController@newSubmit');
                            Route::post('/updateModal', 'FMS\Accounting\Fees\ListController@updateModal');
                            Route::post('/updateSubmit', 'FMS\Accounting\Fees\ListController@updateSubmit');
                        });
                        Route::group(['prefix'=>'discount'], function(){
                            Route::post('/table', 'FMS\Accounting\Fees\DiscountController@table');
                            Route::post('/newModal', 'FMS\Accounting\Fees\DiscountController@newModal');
                            Route::post('/newSubmit', 'FMS\Accounting\Fees\DiscountController@newSubmit');
                            Route::post('/programOption', 'FMS\Accounting\Fees\DiscountController@programOption');
                            Route::post('/programList', 'FMS\Accounting\Fees\DiscountController@programList');
                            Route::post('/studentAdd', 'FMS\Accounting\Fees\DiscountController@studentAdd');
                            Route::post('/updateModal', 'FMS\Accounting\Fees\DiscountController@updateModal');
                            Route::post('/updateSubmit', 'FMS\Accounting\Fees\DiscountController@updateSubmit');
                            Route::post('/statusUpdate', 'FMS\Accounting\Fees\DiscountController@statusUpdate');
                        });
                    });
                });
            });
            Route::group(['prefix'=>'dts'], function(){
                Route::get('/inboxPaginate', 'DTS\InboxController@paginate');
                Route::get('/inboxCount', 'DTS\InboxController@count');

                Route::post('/receive', 'DTS\ReceiveController@index');
                Route::post('/receiveTab', 'DTS\ReceiveController@receiveTab');
                Route::post('/receivedTab', 'DTS\ReceiveController@receivedTab');
                Route::get('/receivePaginate', 'DTS\ReceiveController@paginate');
                Route::get('/receivedPaginate', 'DTS\ReceiveController@paginate1');

                Route::post('/forward', 'DTS\ForwardController@index');
                Route::post('/forwardSubmit', 'DTS\ForwardController@submit');
                Route::post('/forwardTab', 'DTS\ForwardController@forwardTab');
                Route::post('/forwardedTab', 'DTS\ForwardController@forwardedTab');
                Route::get('/forwardPaginate', 'DTS\ForwardController@paginate');
                Route::get('/forwardedPaginate', 'DTS\ForwardController@paginate1');

                Route::post('/search', 'DTS\SearchController@index');
                Route::get('/searchPaginate', 'DTS\SearchController@paginate');

                Route::post('/status', 'DTS\StatusController@index');
                Route::post('/statusSubmit', 'DTS\StatusController@submit');

                Route::post('/newSubmit', 'DTS\NewController@create');
            });
        // });
    });
});

