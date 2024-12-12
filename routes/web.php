<?php

use App\Models\CriteriaModel;
use Illuminate\Support\Facades\Route;

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
Auth::routes();

Route::get('/test' , function(){
    return view('test');
});
Route::get('/', function () {
    return auth()->check() ? redirect()->route('home') : redirect('/login');
});


Route::get('privacy_and_policy',function(){
    return view('project.admin.privacy_and_policy');
});

Route::group(['middleware' => 'auth'], function () {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::get('/news/details/{id}', [App\Http\Controllers\HomeController::class, 'details_news'])->name('news.details');
    Route::get('/language/{locale}', function($locale) {
        if(in_array($locale , ['en', 'ar'])) {
            session()->put('locale' , $locale);
        }
        return redirect()->back();
    })->name('language');

    Route::group(['prefix'=>'project'],function(){
        Route::group(['prefix'=>'allUsersWithoutAdmin'],function(){
            Route::group(['prefix'=>'calendar'],function(){
                Route::post('/display_events',[App\Http\Controllers\project\allUsersWithoutAdmin\CalendarController::class,'display_events'])->name('allUsersWithoutAdmin.calendar.display_events');
                Route::post('/show_event_information',[App\Http\Controllers\project\allUsersWithoutAdmin\CalendarController::class,'show_event_information'])->name('allUsersWithoutAdmin.calendar.show_event_information');
            });
        });
    Route::group(['prefix'=>'admin'],function(){
        Route::group(['prefix'=>'calendar'],function(){
            Route::group(['prefix'=>'ajax'],function(){
                Route::post('/ajax_to_get_courses',[App\Http\Controllers\project\admin\CalendarController::class,'ajax_to_get_courses'])->name('admin.calendar.ajax.ajax_to_get_courses');
                Route::post('/ajax_to_get_majors',[App\Http\Controllers\project\admin\CalendarController::class,'ajax_to_get_majors'])->name('admin.calendar.ajax.ajax_to_get_majors');
                Route::post('/ajax_to_get_companies',[App\Http\Controllers\project\admin\CalendarController::class,'ajax_to_get_companies'])->name('admin.calendar.ajax.ajax_to_get_companies');
                Route::post('/show_event_information',[App\Http\Controllers\project\admin\CalendarController::class,'show_event_information'])->name('admin.calendar.ajax.show_event_information');
                Route::post('/delete_event',[App\Http\Controllers\project\admin\CalendarController::class,'delete_event'])->name('admin.calendar.ajax.delete_event');
                Route::post('/edit_event',[App\Http\Controllers\project\admin\CalendarController::class,'edit_event'])->name('admin.calendar.ajax.edit_event');
            });
            Route::post('/create_event',[App\Http\Controllers\project\admin\CalendarController::class,'create_event'])->name('admin.calendar.create_event');
            Route::post('/display_events',[App\Http\Controllers\project\admin\CalendarController::class,'display_events'])->name('admin.calendar.display_events');
        });
        Route::group(['prefix'=>'registration'],function(){
            Route::get('/index',[App\Http\Controllers\project\admin\RegistrationController::class,'index'])->name('admin.registration.index');
            Route::get('/CourseStudents/{id}',[App\Http\Controllers\project\admin\RegistrationController::class,'CourseStudents'])->name('admin.registration.CourseStudents');
            Route::get('/SemesterStudents',[App\Http\Controllers\project\admin\RegistrationController::class,'SemesterStudents'])->name('admin.registration.semesterStudents');
            Route::post('/FilterSemesterStudents',[App\Http\Controllers\project\admin\RegistrationController::class,'FilterSemesterStudents'])->name('admin.registration.filterSemesterStudents');
            Route::post('/add_training_supervisor',[App\Http\Controllers\project\admin\RegistrationController::class,'add_training_supervisor'])->name('admin.registration.add_training_supervisor');
        });
        Route::group(['prefix' => 'field_visits'],function (){
            Route::get('/index' , [\App\Http\Controllers\project\admin\FieldVisitsController::class, 'index'])->name('admin.field_visits.index');
            Route::post('/list_field_visits' , [\App\Http\Controllers\project\admin\FieldVisitsController::class, 'list_field_visits'])->name('admin.field_visits.list_field_visits');
        });
        Route::group(['prefix'=>'evaluations'],function(){
            Route::get('/index',[App\Http\Controllers\project\admin\EvaluationsController::class,'index'])->name('admin.evaluations.index');
            Route::get('/add',[App\Http\Controllers\project\admin\EvaluationsController::class,'add'])->name('admin.evaluations.add');
            Route::post('/create',[App\Http\Controllers\project\admin\EvaluationsController::class,'create'])->name('admin.evaluations.create');
            Route::get('/edit/{id}',[App\Http\Controllers\project\admin\EvaluationsController::class,'edit'])->name('admin.evaluations.edit');
            Route::post('/update',[App\Http\Controllers\project\admin\EvaluationsController::class,'update'])->name('admin.evaluations.update');
            Route::post('/list_criteria_ajax',[App\Http\Controllers\project\admin\EvaluationsController::class,'list_criteria_ajax'])->name('admin.evaluations.list_criteria_ajax');
            Route::post('/list_evaluation_criteria_ajax',[App\Http\Controllers\project\admin\EvaluationsController::class,'list_evaluation_criteria_ajax'])->name('admin.evaluations.list_evaluation_criteria_ajax');
            Route::post('/add_evaluation_criteria_ajax',[App\Http\Controllers\project\admin\EvaluationsController::class,'add_evaluation_criteria_ajax'])->name('admin.evaluations.add_evaluation_criteria_ajax');
            Route::post('/delete_evaluation_criteria_ajax',[App\Http\Controllers\project\admin\EvaluationsController::class,'delete_evaluation_criteria_ajax'])->name('admin.evaluations.delete_evaluation_criteria_ajax');
            Route::get('/evaluation_criteria/{id}',[App\Http\Controllers\project\admin\EvaluationsController::class,'evaluation_criteria'])->name('admin.evaluations.evaluation_criteria');
            Route::get('/details/{evaluation_id}',[App\Http\Controllers\project\admin\EvaluationsController::class,'details'])->name('admin.evaluations.details');
            Route::post('/list_evaluation_details_list}',[App\Http\Controllers\project\admin\EvaluationsController::class,'list_evaluation_details_list'])->name('admin.evaluations.list_evaluation_details_list');
            Route::post('/edit_total_score',[App\Http\Controllers\project\admin\EvaluationsController::class,'edit_total_score'])->name('admin.evaluations.edit_total_score');
        });
        Route::group(['prefix'=>'criteria'],function(){
            Route::get('/index',[App\Http\Controllers\project\admin\CriteriaController::class,'index'])->name('admin.criteria.index');
            Route::get('/add',[App\Http\Controllers\project\admin\CriteriaController::class,'add'])->name('admin.criteria.add');
            Route::post('/create',[App\Http\Controllers\project\admin\CriteriaController::class,'create'])->name('admin.criteria.create');
            Route::get('/edit/{id}',[App\Http\Controllers\project\admin\CriteriaController::class,'edit'])->name('admin.criteria.edit');
            Route::post('/update',[App\Http\Controllers\project\admin\CriteriaController::class,'update'])->name('admin.criteria.update');
        });
        Route::group(['prefix'=>'cities'],function(){
            Route::get('/index',[App\Http\Controllers\project\admin\CitiesController::class,'index'])->name('admin.cities.index');
            Route::post('/create',[App\Http\Controllers\project\admin\CitiesController::class,'create'])->name('admin.cities.create');
            Route::post('/update',[App\Http\Controllers\project\admin\CitiesController::class,'update'])->name('admin.cities.update');
        });
        Route::group(['prefix'=>'attendance'],function(){
            Route::get('/index',[App\Http\Controllers\project\admin\AttendanceController::class,'index'])->name('admin.attendance.index');
            Route::post('/fillter',[App\Http\Controllers\project\admin\AttendanceController::class,'fillter'])->name('admin.attendance.fillter');
            Route::post('/details',[App\Http\Controllers\project\admin\AttendanceController::class,'details'])->name('admin.attendance.details');
        });
        Route::group(['prefix'=>'survey'],function(){
            Route::get('/index',[App\Http\Controllers\project\admin\surveyController::class,'index'])->name('admin.survey.index');
            Route::post('/surveySearch',[App\Http\Controllers\project\admin\surveyController::class,'surveySearch'])->name('admin.survey.surveySearch');
            Route::get('/addSurvey',[App\Http\Controllers\project\admin\surveyController::class,'addSurvey'])->name('admin.survey.addSurvey');
            Route::post('/createSurvey',[App\Http\Controllers\project\admin\surveyController::class,'createSurvey'])->name('admin.survey.createSurvey');
            Route::get('/surveyView/{id}',[App\Http\Controllers\project\admin\surveyController::class,'surveyView'])->name('admin.survey.surveyView');
            Route::post('/submitSurvey',[App\Http\Controllers\project\admin\surveyController::class,'submitSurvey'])->name('admin.survey.submitSurvey');
            Route::post('/deleteSurvey',[App\Http\Controllers\project\admin\surveyController::class,'deleteSurvey'])->name('admin.survey.deleteSurvey');
            Route::get('/editSurvey/{id}',[App\Http\Controllers\project\admin\surveyController::class,'editSurvey'])->name('admin.survey.editSurvey');
            Route::get('/surveySubmit/{id}',[App\Http\Controllers\project\admin\surveyController::class,'surveySubmit'])->name('admin.survey.surveySubmit');
            Route::post('/update',[App\Http\Controllers\project\admin\surveyController::class,'update'])->name('admin.survey.update');
            Route::get('/surveyResults/{id}',[App\Http\Controllers\project\admin\surveyController::class,'surveyResults'])->name('admin.survey.surveyResults');

        });
        Route::group(['prefix'=>'announcements'],function(){
            Route::get('/index',[App\Http\Controllers\project\admin\announcementController::class,'index'])->name('admin.announcements.index');
            Route::post('/announcementSearch',[App\Http\Controllers\project\admin\announcementController::class,'announcementSearch'])->name('admin.announcements.announcementSearch');
            Route::get('/addAnnouncement',[App\Http\Controllers\project\admin\announcementController::class,'addAnnouncement'])->name('admin.announcements.addAnnouncement');
            Route::post('/create',[App\Http\Controllers\project\admin\announcementController::class,'create'])->name('admin.announcements.create');
            Route::get('/edit/{id}',[App\Http\Controllers\project\admin\announcementController::class,'edit'])->name('admin.announcements.edit');
            Route::post('/update',[App\Http\Controllers\project\admin\announcementController::class,'update'])->name('admin.announcements.update');
            Route::post('/updateStutas',[App\Http\Controllers\project\admin\announcementController::class,'updateStutas'])->name('admin.announcements.updateStutas');
        });
        Route::group(['prefix'=>'courses'],function(){
            Route::get('/index',[App\Http\Controllers\project\admin\CoursesController::class,'index'])->name('admin.courses.index');
            Route::post('/create',[App\Http\Controllers\project\admin\CoursesController::class,'create'])->name('admin.courses.create');
            Route::post('/update',[App\Http\Controllers\project\admin\CoursesController::class,'update'])->name('admin.courses.update');
            Route::post('/couseSearch',[App\Http\Controllers\project\admin\CoursesController::class,'courseSearch'])->name('admin.courses.courseSearch');
            Route::post('/checkrCourseCode',[App\Http\Controllers\project\admin\CoursesController::class,'checkCourseCode'])->name('admin.courses.checkCourseCode');
            Route::get('/loadCourses',[App\Http\Controllers\project\admin\CoursesController::class,'getCourses'])->name('admin.courses.loadMoreCourses');
        });
        Route::group(['prefix'=>'reports'],function(){
            Route::get('/index',[App\Http\Controllers\project\admin\ReportController::class,'index'])->name('admin.reports.index');
            Route::post('/report_history_ajax',[App\Http\Controllers\project\admin\ReportController::class,'report_history_ajax'])->name('admin.reports.report_history_ajax');
        });
        Route::group(['prefix'=>'semesterCourses'],function(){
            Route::get('/index',[App\Http\Controllers\project\admin\SemesterCoursesController::class,'index'])->name('admin.semesterCourses.index');
            Route::post('/create',[App\Http\Controllers\project\admin\SemesterCoursesController::class,'create'])->name('admin.semesterCourses.create');
            Route::post('/delete',[App\Http\Controllers\project\admin\SemesterCoursesController::class,'delete'])->name('admin.semesterCourses.delete');
            Route::post('/search',[App\Http\Controllers\project\admin\SemesterCoursesController::class,'search'])->name('admin.semesterCourses.search');
            Route::post('/courseSearch',[App\Http\Controllers\project\admin\SemesterCoursesController::class,'courseNameSearch'])->name('admin.semesterCourses.courseNameSearch');
        });
        Route::group(['prefix'=>'users'],function(){
            Route::get('/index/{id}' , [App\Http\Controllers\UserController::class, 'index_id'])->name('admin.users.index_id');
            Route::get('/index' , [App\Http\Controllers\UserController::class, 'index'])->name('admin.users.index');
            Route::post('/create' , [App\Http\Controllers\UserController::class, 'create'])->name('admin.users.create');
            Route::get('/edit/{id}' , [App\Http\Controllers\UserController::class, 'edit'])->name('admin.users.edit');
            Route::post('/update' , [App\Http\Controllers\UserController::class, 'update'])->name('admin.users.update');
            Route::post('/search' , [App\Http\Controllers\UserController::class, 'search'])->name('admin.users.search');
            Route::get('/details/{id}' , [App\Http\Controllers\UserController::class, 'details'])->name('admin.users.details');
            Route::get('/courses/student/{id}' , [App\Http\Controllers\UserController::class, 'courses_student'])->name('admin.users.courses.student');
            Route::post('/courses/student/add' , [App\Http\Controllers\UserController::class, 'courses_student_add'])->name('admin.users.courses.student.add');
            Route::post('/courses/student/create_or_update_grade' , [App\Http\Controllers\UserController::class, 'create_or_update_grade'])->name('admin.users.courses.student.create_or_update_grade');
            Route::get('/places/training/{id}' , [App\Http\Controllers\UserController::class, 'places_training'])->name('admin.users.places.training');
            Route::post('/courses/student/delete' , [App\Http\Controllers\UserController::class, 'courses_student_delete'])->name('admin.users.courses.student.delete');
            Route::post('/places/training/branches' , [App\Http\Controllers\UserController::class, 'places_training_branches'])->name('admin.users.places.training.branches');
            Route::post('/places/training/departments' , [App\Http\Controllers\UserController::class, 'places_training_departments'])->name('admin.users.places.training.departments');
            Route::post('/places/training/add' , [App\Http\Controllers\UserController::class, 'places_training_add'])->name('admin.users.places.training.add');
            Route::post('/places/training/delete' , [App\Http\Controllers\UserController::class, 'places_training_delete'])->name('admin.users.places.training.delete');
            Route::post('/places/training/edit' , [App\Http\Controllers\UserController::class, 'places_training_edit'])->name('admin.users.places.training.edit');
            Route::post('/places/training/edit/branch' , [App\Http\Controllers\UserController::class, 'places_training_edit_branch'])->name('admin.users.places.training.edit.branch');
            Route::post('/places/training/update' , [App\Http\Controllers\UserController::class, 'places_training_update'])->name('admin.users.places.training.update');
            Route::post('/training/place/update/file_agreement' , [App\Http\Controllers\UserController::class, 'training_place_update_file_agreement'])->name('admin.users.training.place.update.file_agreement');
            Route::get('/training/place/delete/file_agreement/{sc_id}' , [App\Http\Controllers\UserController::class, 'training_place_delete_file_agreement'])->name('admin.users.training.place.delete.file_agreement');
            Route::get('/students/attendance/{id}' , [App\Http\Controllers\UserController::class, 'students_attendance'])->name('admin.users.students.attendance');
            Route::get('/student/payments/{id}' , [App\Http\Controllers\UserController::class, 'student_payments'])->name('admin.users.student.payments');
            Route::post('/supervisor/major/add' , [App\Http\Controllers\UserController::class , 'supervisor_major_add'])->name('admin.users.supervisor.major.add'); // To add major to academic supervisor
            Route::post('/supervisor/major/delete' , [App\Http\Controllers\UserController::class, 'supervisor_major_delete'])->name('admin.users.supervisor.major.delete'); // To delete major to academic supervisor
            Route::post('/supervisor/students/search' , [App\Http\Controllers\UserController::class, 'supervisor_students_search'])->name('admin.users.supervisor.students.search'); // To make search by username and name to supervisor's students
            Route::post('/supervisor/students/search/major' , [App\Http\Controllers\UserController::class , 'supervisor_students_search_major'])->name('admin.users.supervisor.students.search.major'); // To make filter for major in academic supervisor
            Route::post('/report/student/display' , [App\Http\Controllers\UserController::class , 'report_student_display'])->name('admin.users.report.student.display'); // To show report of student in modal
            Route::post('/report/student/edit' , [App\Http\Controllers\UserController::class , 'report_student_edit'])->name('admin.users.report.student.edit'); // To submit notes of supervisor to student report
            Route::post('/check_email_not_duplicate' , [App\Http\Controllers\UserController::class , 'check_email_not_duplicate'])->name('users.add.check_email_not_duplicate');
            Route::post('/students_waiting_to_approve_cv' , [App\Http\Controllers\UserController::class , 'students_waiting_to_approve_cv'])->name('users.students_waiting_to_approve_cv');
            Route::get('/student/files/{id}' , [App\Http\Controllers\UserController::class , 'students_files'])->name('admin.users.students.students_files');
            Route::post('/student/files/create' , [App\Http\Controllers\UserController::class , 'create_students_files'])->name('admin.users.students.create_students_files');
            Route::post('/change_status_from_cv' , [App\Http\Controllers\UserController::class , 'change_status_from_cv'])->name('users.change_status_from_cv');
            Route::post('/change_user_role' , [App\Http\Controllers\UserController::class , 'change_user_role'])->name('users.change_user_role');
            Route::group(['prefix'=>'company_manager'],function(){
                Route::post('/searchStudentByName' , [App\Http\Controllers\UserController::class , 'searchStudentByName'])->name('users.company_manager.searchStudentByName');
            });
            Route::group(['prefix'=>'supervisor_assistatns'],function(){
                Route::post('/create' , [App\Http\Controllers\project\admin\supervisorAssistatnsController::class, 'create'])->name('admin.assistant.create');
                Route::post('/showSelectSupervisor' , [App\Http\Controllers\project\admin\supervisorAssistatnsController::class, 'show_select_for_supervisor'])->name('admin.assistant.showSelectSupervisor');
                Route::post('/deleteSupervisor' , [App\Http\Controllers\project\admin\supervisorAssistatnsController::class, 'deleteSupervisor'])->name('admin.assistant.deleteSupervisor');
            });
        });

        Route::group(['prefix'=>'students'],function(){
            Route::group(['prefix'=>'final_reports'],function(){
                Route::get('/index',[App\Http\Controllers\project\students\FinalReportController::class,'index'])->name('students.final_reports.index');
                Route::post('/create',[App\Http\Controllers\project\students\FinalReportController::class,'create'])->name('students.final_reports.create');
                Route::get('/delete/{id}',[App\Http\Controllers\project\students\FinalReportController::class,'delete'])->name('students.final_reports.delete');
            });
        });

        Route::group(['prefix'=>'majors'],function(){
            Route::get('/index',[App\Http\Controllers\project\admin\MajorsController::class,'index'])->name('admin.majors.index');
            Route::post('/create',[App\Http\Controllers\project\admin\MajorsController::class,'create'])->name('admin.majors.create');
            Route::post('/update',[App\Http\Controllers\project\admin\MajorsController::class,'update'])->name('admin.majors.update');
            Route::post('/search',[App\Http\Controllers\project\admin\MajorsController::class,'search'])->name('admin.majors.search');
            Route::post('/addSuperVisor',[App\Http\Controllers\project\admin\MajorsController::class,'addSuperVisor'])->name('admin.majors.addSuperVisor');
            Route::post('/updateSuperVisor',[App\Http\Controllers\project\admin\MajorsController::class,'updateSuperVisor'])->name('admin.majors.updateSuperVisor');
            Route::post('/checkMajorCode',[App\Http\Controllers\project\admin\MajorsController::class,'checkMajorCode'])->name('admin.majors.checkMajorCode');

         });


            Route::group(['prefix'=>'companies_categories'],function(){
                Route::get('/index',[App\Http\Controllers\project\admin\CompaniesCategoriesController::class,'index'])->name('admin.companies_categories.index');
                Route::post('/create',[App\Http\Controllers\project\admin\CompaniesCategoriesController::class,'create'])->name('admin.companies_categories.create');
                Route::post('/update',[App\Http\Controllers\project\admin\CompaniesCategoriesController::class,'update'])->name('admin.companies_categories.update');
                Route::post('/companies_categories_search',[App\Http\Controllers\project\admin\CompaniesCategoriesController::class,'companies_categories_search'])->name('admin.companies_categories.companies_categories_search');
            });

        Route::group(['prefix'=>'companies'],function(){
            Route::get('/index',[App\Http\Controllers\project\admin\CompaniesController::class,'index'])->name('admin.companies.index');
            Route::post('/companySearch',[App\Http\Controllers\project\admin\CompaniesController::class,'companySearch'])->name('admin.companies.companySearch');
            Route::post('/company',[App\Http\Controllers\project\admin\CompaniesController::class,'company'])->name('admin.companies.company');
            Route::post('/create',[App\Http\Controllers\project\admin\CompaniesController::class,'create'])->name('admin.companies.create');
            Route::get('/edit/{id}',[App\Http\Controllers\project\admin\CompaniesController::class,'edit'])->name('admin.companies.edit');
            Route::post('/updateCompany',[App\Http\Controllers\project\admin\CompaniesController::class,'updateCompany'])->name('admin.companies.updateCompany');
            Route::post('/createBranches',[App\Http\Controllers\project\admin\CompaniesController::class,'createBranches'])->name('admin.companies.createBranches');
            Route::post('/createBranchesEdit',[App\Http\Controllers\project\admin\CompaniesController::class,'createBranchesEdit'])->name('admin.companies.createBranchesEdit');
            Route::post('/checkCompany',[App\Http\Controllers\project\admin\CompaniesController::class,'checkCompany'])->name('admin.companies.checkCompany');
            Route::post('/companySummary',[App\Http\Controllers\project\admin\CompaniesController::class,'companySummary'])->name('admin.companies.companySummary');
            Route::get('/showCompanyInfo',[App\Http\Controllers\project\admin\CompaniesController::class,'showCompanyInfo'])->name('admin.companies.showCompanyInfo');
            Route::post('/update',[App\Http\Controllers\project\admin\CompaniesController::class,'update'])->name('admin.companies.update');
            Route::post('/updateDepartments',[App\Http\Controllers\project\admin\CompaniesController::class,'updateDepartments'])->name('admin.companies.updateDepartments');
            Route::post('/addDepartment',[App\Http\Controllers\project\admin\CompaniesController::class,'addDepartment'])->name('admin.companies.addDepartment');
            Route::post('/createDepartments',[App\Http\Controllers\project\admin\CompaniesController::class,'createDepartments'])->name('admin.companies.createDepartments');
            Route::post('/updateBranches',[App\Http\Controllers\project\admin\CompaniesController::class,'updateBranches'])->name('admin.companies.updateBranches');
            Route::post('/checkEmailEdit',[App\Http\Controllers\project\admin\CompaniesController::class,'checkEmailEdit'])->name('admin.companies.check_email_not_duplicate_edit');
            Route::post('/search_student_ajax',[App\Http\Controllers\project\admin\CompaniesController::class,'search_student_ajax'])->name('admin.companies.search_student_ajax');
            Route::post('/student_nomination_table_ajax',[App\Http\Controllers\project\admin\CompaniesController::class,'student_nomination_table_ajax'])->name('admin.companies.student_nomination_table_ajax');
            Route::post('/add_nomination_table_ajax',[App\Http\Controllers\project\admin\CompaniesController::class,'add_nomination_table_ajax'])->name('admin.companies.add_nomination_table_ajax');
            Route::post('/delete_nomination_table_ajax',[App\Http\Controllers\project\admin\CompaniesController::class,'delete_nomination_table_ajax'])->name('admin.companies.delete_nomination_table_ajax');
            Route::post('/update_capacity_ajax',[App\Http\Controllers\project\admin\CompaniesController::class,'update_capacity_ajax'])->name('admin.companies.update_capacity_ajax');
            Route::post('/update_company_status',[App\Http\Controllers\project\admin\CompaniesController::class,'update_company_status'])->name('admin.companies.update_company_status');
        });

        });
        Route::group(['prefix'=>'settings'],function(){
            Route::get('/', function () {
                return view('project.admin.settings.index');
            })->name('admin.settings');
            Route::get('/coloring',[App\Http\Controllers\project\settings\SettingsController::class,'index'])->name('admin.color.index');
            Route::post('/primary_background_color',[App\Http\Controllers\project\settings\SettingsController::class,'primary_background_color'])->name('admin.color.primary_background_color');
            Route::post('/primary_font_color',[App\Http\Controllers\project\settings\SettingsController::class,'primary_font_color'])->name('admin.color.primary_font_color');

            Route::get('/integration',[App\Http\Controllers\project\settings\SettingsController::class,'integration'])->name('integration');
            Route::post('/uploadFileExcel',[App\Http\Controllers\project\settings\SettingsController::class,'uploadFileExcel'])->name('integration.uploadFileExcel');
            Route::post('/validateStepOne',[App\Http\Controllers\project\settings\SettingsController::class,'validateStepOne'])->name('integration.validateStepOne');
            Route::post('/submitForm',[App\Http\Controllers\project\settings\SettingsController::class,'submitForm'])->name('integration.submitForm');
            Route::post('/import_integration_student_excel',[App\Http\Controllers\project\settings\SettingsController::class,'import_integration_student_excel'])->name('integration.import_integration_student_excel');

            Route::group(['prefix'=>'integration_company'],function(){
                Route::get('/index',[App\Http\Controllers\project\settings\IntegrationCompaniesController::class,'index'])->name('admin.settings.integration_company.index');
                Route::post('/uploadFileExcel',[App\Http\Controllers\project\settings\IntegrationCompaniesController::class,'uploadFileExcel'])->name('admin.settings.integration_company.uploadFileExcel');
                Route::post('/submitForm',[App\Http\Controllers\project\settings\IntegrationCompaniesController::class,'submitForm'])->name('admin.settings.integration_company.submitForm');
            });
            Route::group(['prefix'=>'integration_students'],function(){
                Route::get('/index',[App\Http\Controllers\project\settings\IntegrationStudentsController::class,'index'])->name('admin.settings.integration_students.index');
                Route::post('/uploadFileExcel',[App\Http\Controllers\project\settings\IntegrationStudentsController::class,'uploadFileExcel'])->name('admin.settings.integration_students.uploadFileExcel');
                Route::post('/submitForm',[App\Http\Controllers\project\settings\IntegrationStudentsController::class,'submitForm'])->name('admin.settings.integration_students.submitForm');
            });

            Route::get('/systemSettings',[App\Http\Controllers\project\settings\SettingsController::class,'systemSettings'])->name('admin.settings.systemSettings');
            Route::post('/systemSettingsUpdate',[App\Http\Controllers\project\settings\SettingsController::class,'systemSettingsUpdate'])->name('admin.settings.systemSettingsUpdate');

            Route::get('/deleteData',[App\Http\Controllers\project\settings\SettingsController::class,'deleteData'])->name('admin.settings.deleteData');
            Route::post('/confirmDelete',[App\Http\Controllers\project\settings\SettingsController::class,'confirmDelete'])->name('admin.settings.confirmDelete');
        });
    });

    Route::group(['prefix' => 'companies'], function () {
    });

    Route::group(['prefix' => 'company_trainer'], function () {
    });
    Route::group(['prefix' => 'communications_manager_with_companies'], function () {
        Route::group(['prefix' => 'students'], function () {
            Route::get('/index' , [App\Http\Controllers\project\communications_manager_with_companies\students\StudentsController::class, 'index'])->name('communications_manager_with_companies.students.index');
            Route::post('/search' , [App\Http\Controllers\project\communications_manager_with_companies\students\StudentsController::class, 'search'])->name('communications_manager_with_companies.students.search');
        });
        Route::group(['prefix' => 'companies'], function () {
            Route::get('/index' , [App\Http\Controllers\project\communications_manager_with_companies\companies\CompaniesController::class, 'index'])->name('communications_manager_with_companies.companies.index');
            Route::get('/students/{id}' , [App\Http\Controllers\project\communications_manager_with_companies\companies\CompaniesController::class , 'students'])->name('communications_manager_with_companies.companies.students'); // To display students
            Route::post('communications_manager_with_companies_table_ajax' , [App\Http\Controllers\project\communications_manager_with_companies\companies\CompaniesController::class , 'communications_manager_with_companies_table_ajax'])->name('communications_manager_with_companies.companies.communications_manager_with_companies_table_ajax'); // To display students
        });
        Route::group(['prefix' => 'follow_up_record'], function () {
            Route::get('/index' , [App\Http\Controllers\project\communications_manager_with_companies\follow_up_record\FollowUpRecordController::class, 'index'])->name('communications_manager_with_companies.follow_up_record.index');
            Route::post('/company_table_ajax' , [App\Http\Controllers\project\communications_manager_with_companies\follow_up_record\FollowUpRecordController::class, 'company_table_ajax'])->name('communications_manager_with_companies.follow_up_record.company_table_ajax');
            Route::post('/update_company_information' , [App\Http\Controllers\project\communications_manager_with_companies\follow_up_record\FollowUpRecordController::class, 'update_company_information'])->name('communications_manager_with_companies.follow_up_record.update_company_information');
            Route::post('/list_contact_company' , [App\Http\Controllers\project\communications_manager_with_companies\follow_up_record\FollowUpRecordController::class, 'list_contact_company'])->name('communications_manager_with_companies.follow_up_record.list_contact_company');
            Route::post('/create_contact_company' , [App\Http\Controllers\project\communications_manager_with_companies\follow_up_record\FollowUpRecordController::class, 'create_contact_company'])->name('communications_manager_with_companies.follow_up_record.create_contact_company');
            Route::post('/delete_contact_company' , [App\Http\Controllers\project\communications_manager_with_companies\follow_up_record\FollowUpRecordController::class, 'delete_contact_company'])->name('communications_manager_with_companies.follow_up_record.delete_contact_company');
            Route::post('/check_email_found' , [App\Http\Controllers\project\communications_manager_with_companies\follow_up_record\FollowUpRecordController::class, 'check_email_found'])->name('communications_manager_with_companies.follow_up_record.check_email_found');
            Route::post('/list_branches' , [App\Http\Controllers\project\communications_manager_with_companies\follow_up_record\FollowUpRecordController::class, 'list_branches'])->name('communications_manager_with_companies.follow_up_record.list_branches');
            Route::post('/create_branches_ajax' , [App\Http\Controllers\project\communications_manager_with_companies\follow_up_record\FollowUpRecordController::class, 'create_branches_ajax'])->name('communications_manager_with_companies.follow_up_record.create_branches_ajax');
            Route::post('/list_student_company_ajax' , [App\Http\Controllers\project\communications_manager_with_companies\follow_up_record\FollowUpRecordController::class, 'list_student_company_ajax'])->name('communications_manager_with_companies.follow_up_record.list_student_company_ajax');
            Route::post('/payment_table_ajax' , [App\Http\Controllers\project\communications_manager_with_companies\follow_up_record\FollowUpRecordController::class, 'payment_table_ajax'])->name('communications_manager_with_companies.follow_up_record.payment_table_ajax');
        });
    });

    Route::group(['prefix' => 'monitor_evaluation'], function () {
        Route::get('/index' , [App\Http\Controllers\project\monitor_evaluation\MonitorEvaluationController::class, 'index'])->name('monitor_evaluation.index');
        Route::get('/user_details' , [App\Http\Controllers\project\monitor_evaluation\MonitorEvaluationController::class, 'user_details'])->name('monitor_evaluation.user_details');
        Route::post('/update_password' , [App\Http\Controllers\project\monitor_evaluation\MonitorEvaluationController::class, 'update_password'])->name('monitor_evaluation.update_password');
        Route::get('/semesterReport' , [App\Http\Controllers\project\monitor_evaluation\MonitorEvaluationController::class, 'semesterReport'])->name('monitor_evaluation.semesterReport');
        Route::get('/companiesReport' , [App\Http\Controllers\project\monitor_evaluation\MonitorEvaluationController::class, 'companiesReport'])->name('monitor_evaluation.companiesReport');
        Route::post('/semesterReportAjax' , [App\Http\Controllers\project\monitor_evaluation\MonitorEvaluationController::class, 'semesterReportAjax'])->name('monitor_evaluation.semesterReportAjax');
        Route::post('/companiesReportSearch' , [App\Http\Controllers\project\monitor_evaluation\MonitorEvaluationController::class, 'companiesReportSearch'])->name('monitor_evaluation.companiesReportSearch');
        // Route::get('/semesterReportPDF/{data}' , [App\Http\Controllers\project\monitor_evaluation\MonitorEvaluationController::class, 'semesterReportPDF'])->name('monitor_evaluation.semesterReportPDF');
        Route::post('/semesterReportPDF' , [App\Http\Controllers\project\monitor_evaluation\MonitorEvaluationController::class, 'semesterReportPDF'])->name('monitor_evaluation.semesterReportPDF');
        Route::post('/companiesReportPDF' , [App\Http\Controllers\project\monitor_evaluation\MonitorEvaluationController::class, 'companiesReportPDF'])->name('monitor_evaluation.companiesReportPDF');
        Route::post('/companiesReportPDFPost' , [App\Http\Controllers\project\monitor_evaluation\MonitorEvaluationController::class, 'companiesReportPDFPost'])->name('monitor_evaluation.companiesReportPDFPost');
        Route::get('/companiesPaymentsReport' , [App\Http\Controllers\project\monitor_evaluation\MonitorEvaluationController::class, 'companiesPaymentsReport'])->name('monitor_evaluation.companiesPaymentsReport');
        Route::post('/companyPaymentDetailes' , [App\Http\Controllers\project\monitor_evaluation\MonitorEvaluationController::class, 'companyPaymentDetailes'])->name('monitor_evaluation.companyPaymentDetailes');
        Route::post('/companiesPaymentsSearch' , [App\Http\Controllers\project\monitor_evaluation\MonitorEvaluationController::class, 'companiesPaymentsSearch'])->name('monitor_evaluation.companiesPaymentsSearch');
        Route::get('/paymentsReport' , [App\Http\Controllers\project\monitor_evaluation\MonitorEvaluationController::class, 'paymentsReport'])->name('monitor_evaluation.paymentsReport');
        Route::post('/paymentsReportSearch' , [App\Http\Controllers\project\monitor_evaluation\MonitorEvaluationController::class, 'paymentsReportSearch'])->name('monitor_evaluation.paymentsReportSearch');
        Route::get('/students_courses_report' , [App\Http\Controllers\project\monitor_evaluation\MonitorEvaluationController::class, 'students_courses_report'])->name('monitor_evaluation.students_courses_report');
        Route::get('/courses_registered_report' , [App\Http\Controllers\project\monitor_evaluation\MonitorEvaluationController::class, 'courses_registered_report'])->name('monitor_evaluation.courses_registered_report');
        Route::get('/training_hours_report' , [App\Http\Controllers\project\monitor_evaluation\MonitorEvaluationController::class, 'training_hours_report'])->name('monitor_evaluation.training_hours_report');
        Route::get('/students_companies_report' , [App\Http\Controllers\project\monitor_evaluation\MonitorEvaluationController::class, 'students_companies_report'])->name('monitor_evaluation.students_companies_report');
        Route::post('/studentsCoursesAjax' , [App\Http\Controllers\project\monitor_evaluation\MonitorEvaluationController::class, 'studentsCoursesAjax'])->name('monitor_evaluation.studentsCoursesAjax');
        Route::post('/registeredCoursesAjax' , [App\Http\Controllers\project\monitor_evaluation\MonitorEvaluationController::class, 'registeredCoursesAjax'])->name('monitor_evaluation.registeredCoursesAjax');
        Route::post('/trainingHoursAjax' , [App\Http\Controllers\project\monitor_evaluation\MonitorEvaluationController::class, 'trainingHoursAjax'])->name('monitor_evaluation.trainingHoursAjax');
        Route::post('/studentsCompaniesAjax' , [App\Http\Controllers\project\monitor_evaluation\MonitorEvaluationController::class, 'studentsCompaniesAjax'])->name('monitor_evaluation.studentsCompaniesAjax');
        Route::post('/studentsCoursesPDF' , [App\Http\Controllers\project\monitor_evaluation\MonitorEvaluationController::class, 'studentsCoursesPDF'])->name('monitor_evaluation.studentsCoursesPDF');
        Route::post('/registeredCoursesPDF' , [App\Http\Controllers\project\monitor_evaluation\MonitorEvaluationController::class, 'registeredCoursesPDF'])->name('monitor_evaluation.registeredCoursesPDF');
        Route::post('/trainingHoursPDF' , [App\Http\Controllers\project\monitor_evaluation\MonitorEvaluationController::class, 'trainingHoursPDF'])->name('monitor_evaluation.trainingHoursPDF');
        Route::post('/studentsCompaniesPDF' , [App\Http\Controllers\project\monitor_evaluation\MonitorEvaluationController::class, 'studentsCompaniesPDF'])->name('monitor_evaluation.studentsCompaniesPDF');
        Route::get('/companyStudents/{id}',[App\Http\Controllers\project\monitor_evaluation\MonitorEvaluationController::class,'companyStudents'])->name('monitor_evaluation.companyStudentsReport');
        Route::post('/companyStudentsReportSearch' , [App\Http\Controllers\project\monitor_evaluation\MonitorEvaluationController::class, 'companyStudentsReportSearch'])->name('monitor_evaluation.companyStudentsReportSearch');
        Route::get('/attendance_and_departure_report_index' , [App\Http\Controllers\project\monitor_evaluation\MonitorEvaluationController::class, 'attendance_and_departure_report_index'])->name('monitor_evaluation.attendance_and_departure_report_index');
        Route::post('/attendance_and_departure_report_table' , [App\Http\Controllers\project\monitor_evaluation\MonitorEvaluationController::class, 'attendance_and_departure_report_table'])->name('monitor_evaluation.attendance_and_departure_report_table');
        Route::get('/export_student_attendance' , [App\Http\Controllers\project\monitor_evaluation\MonitorEvaluationController::class, 'export_student_attendance'])->name('monitor_evaluation.export_student_attendance');
        Route::group(['prefix'=>'files'],function (){
            Route::get('index',[App\Http\Controllers\project\monitor_evaluation\MonitorEvaluationController::class , 'files_index'])->name('monitor_evaluation.files.files_index');
            Route::post('create_me_attachment',[App\Http\Controllers\project\monitor_evaluation\MonitorEvaluationController::class , 'create_me_attachment'])->name('monitor_evaluation.files.create_me_attachment');
            Route::post('create_me_version_attachment',[App\Http\Controllers\project\monitor_evaluation\MonitorEvaluationController::class , 'create_me_version_attachment'])->name('monitor_evaluation.files.create_me_version_attachment');
        });
        Route::group(['prefix'=>'statistic_attendance'],function (){
            Route::get('statistic_attendance_index',[App\Http\Controllers\project\monitor_evaluation\MonitorEvaluationController::class , 'statistic_attendance_index'])->name('monitor_evaluation.statistic_attendance.statistic_attendance_index');
            Route::post('list_statistic_attendance_ajax',[App\Http\Controllers\project\monitor_evaluation\MonitorEvaluationController::class , 'list_statistic_attendance_ajax'])->name('monitor_evaluation.statistic_attendance.list_statistic_attendance_ajax');
        });
    });
    Route::group(['prefix' => 'company_manager'], function () {
        Route::group(['prefix' => 'students'], function () {
            Route::group(['prefix' => 'reports'], function () {
                Route::get('/index/{id}/{student_company_id}' , [App\Http\Controllers\project\company_manager\students\report\ReportController::class, 'index'])->name('company_manager.students.reports.index');
                Route::post('/add' , [App\Http\Controllers\project\company_manager\students\report\ReportController::class, 'addNotes'])->name('company_manager.students.reports.addNotes');
                Route::post('/show' , [App\Http\Controllers\project\company_manager\students\report\ReportController::class, 'showNotes'])->name('company_manager.students.reports.showNotes');
                Route::post('/report' , [App\Http\Controllers\project\company_manager\students\report\ReportController::class, 'showReport'])->name('company_manager.students.reports.showReport');
            });
            Route::group(['prefix' => 'attendance'], function () {
                Route::get('/index/{id}/{student_company_id}' , [App\Http\Controllers\project\company_manager\students\attendance\AttendanceController::class, 'index'])->name('company_manager.students.attendance.index');
                Route::post('/index' , [App\Http\Controllers\project\company_manager\students\attendance\AttendanceController::class, 'index_ajax'])->name('company_manager.students.attendance.index_ajax');

            });
            Route::group(['prefix' => 'payments'], function () {
                Route::get('/index/{id}/{name_student}/{student_company_id}' , [App\Http\Controllers\project\company_manager\students\payments\PaymentsController::class, 'index'])->name('company_manager.students.payments.index');
                Route::post('/create' , [App\Http\Controllers\project\company_manager\students\payments\PaymentsController::class, 'create'])->name('company_manager.students.payments.create');
            });
            Route::get('/index' , [App\Http\Controllers\project\company_manager\students\StudentController::class, 'index'])->name('company_manager.students.index');
        });
        Route::group(['prefix' => 'payments'], function () {
            Route::get('/index' , [App\Http\Controllers\project\company_manager\payments\PaymentsController::class, 'index'])->name('company_manager.payments.index');
            Route::get('/update_status/{id}' , [App\Http\Controllers\project\company_manager\payments\PaymentsController::class, 'update_status'])->name('company_manager.payments.update_status');
        });
        Route::group(['prefix' => 'records'], function () {
            Route::get('/index' , [App\Http\Controllers\project\company_manager\records\RecordsController::class, 'index'])->name('company_manager.records.index');
            Route::post('/search' , [App\Http\Controllers\project\company_Manager\records\RecordsController::class, 'search'])->name('company_manager.records.search');
        });
        Route::group(['prefix'=>'attendance'],function(){
            Route::get('/index',[App\Http\Controllers\project\company_manager\attendance\AttendanceController::class,'index'])->name('company_manager.attendance.index');
            Route::post('/fillter',[App\Http\Controllers\project\company_manager\attendance\AttendanceController::class,'fillter'])->name('company_manager.attendance.fillter');
            Route::post('/details',[App\Http\Controllers\project\company_manager\attendance\AttendanceController::class,'details'])->name('company_manager.attendance.details');
        });
        Route::group(['prefix'=>'student_nomination'],function(){
            Route::get('/index',[App\Http\Controllers\project\company_manager\student_nominations\StudentNominationsController::class,'index'])->name('company_manager.student_nominations.index');
            Route::post('/student_nomination_table',[App\Http\Controllers\project\company_manager\student_nominations\StudentNominationsController::class,'student_nomination_table'])->name('company_manager.student_nominations.student_nomination_table');
        });
    });
    Route::group(['prefix' => 'students'], function () {
        Route::group(['prefix' => 'personal_profile'], function () {
            Route::get('/index' , [App\Http\Controllers\project\students\personal_profile\PersonalProfileController::class, 'index'])->name('students.personal_profile.index');  // To display personal profile for this student
            Route::post('/add_sv_to_student' , [App\Http\Controllers\project\students\personal_profile\PersonalProfileController::class, 'add_sv_to_student'])->name('students.personal_profile.add_sv_to_student');  // To display personal profile for this student
            Route::post('/update_password' , [App\Http\Controllers\project\students\personal_profile\PersonalProfileController::class, 'update_password'])->name('students.personal_profile.update_password');  // To display personal profile for this student
        });
        Route::group(['prefix' => 'company'], function () {
            Route::get('/index' , [App\Http\Controllers\project\students\company\CompanyController::class , 'index'])->name('students.company.index'); // To display list of companies student for student
            Route::group(['prefix' => 'attendance'], function () {
                Route::get('/index/{id}', [App\Http\Controllers\project\students\attendance\AttendanceController::class , 'index_for_specific_company'])->name('students.company.attendance.index_for_specific_student'); // To show the page for specific company to make attendance for student (time in , time out , submit report , show notes of supervisor to student report)
                Route::post('/select' , [App\Http\Controllers\project\students\attendance\AttendanceController::class , 'ajax_company_from_to'])->name('students.attendance.ajax_company_from_to');
            });
        });
        Route::group(['prefix' => 'attendance'], function () {
            Route::group(['prefix' => 'report'], function () {
                Route::get('/edit/{sa_id}' , [App\Http\Controllers\project\students\attendance\report\ReportController::class , 'edit'])->name('students.attendance.report.edit'); // To creat or edit report student
                Route::post('/submit' , [App\Http\Controllers\project\students\attendance\report\ReportController::class , 'submit'])->name('students.attendance.report.submit'); // To submit report student
                Route::post('/upload' , [App\Http\Controllers\project\students\attendance\report\ReportController::class , 'upload'])->name('students.attendance.report.upload'); // To upload report student
            });
            Route::get('/index' , [App\Http\Controllers\project\students\attendance\AttendanceController::class , 'index'])->name('students.attendance.index'); // To show the page for specific company to make attendance for student (time in , time out , submit report , show notes of supervisor to student report)
            Route::post('/create_attendance' , [App\Http\Controllers\project\students\attendance\AttendanceController::class , 'create_attendance'])->name('students.attendance.create_attendance'); // To submit student attendance
            Route::post('/create_departure' , [App\Http\Controllers\project\students\attendance\AttendanceController::class , 'create_departure'])->name('students.attendance.create_departure'); // To submit student departure
        });
        Route::group(['prefix' => 'payments'], function () {
            Route::get('/index' , [App\Http\Controllers\project\students\payments\PaymentsController::class, 'index'])->name('students.payments.index');
            Route::post('/confirm' , [App\Http\Controllers\project\students\payments\PaymentsController::class, 'confirmByAjax'])->name('student.payments.confirmByAjax');
        });
        Route::group(['prefix' => 'evaluation'], function () {
            Route::get('/index' , [App\Http\Controllers\project\students\EvaluationController::class, 'index'])->name('students.evaluation.index');
            Route::get('/details/{evaluation_id}' , [App\Http\Controllers\project\students\EvaluationController::class, 'details'])->name('students.evaluation.details');
            Route::get('/evaluation_submission_page/{registration_id}/{evaluation_id}' , [App\Http\Controllers\project\students\EvaluationController::class, 'evaluation_submission_page'])->name('students.evaluation.evaluation_submission_page');
            Route::post('/evaluation_submission_create' , [App\Http\Controllers\project\students\EvaluationController::class, 'evaluation_submission_create'])->name('students.evaluation.evaluation_submission_create');
            Route::post('/update_status' , [App\Http\Controllers\project\students\EvaluationController::class, 'update_status'])->name('students.evaluation.update_status');
            Route::post('/export_excel' , [App\Http\Controllers\project\students\EvaluationController::class, 'export_excel'])->name('students.evaluation.export_excel');
        });
    });

    Route::group(['prefix' => 'supervisor_assistatns'], function () {
        Route::group(['prefix' => 'majors'], function () {
            Route::get('/index/{id}' , [App\Http\Controllers\project\supervisor_assistants\MajorsController::class , 'index'])->name('supervisor_assistants.majors.index'); // To show majors to supervisor assistant
        });
        Route::group(['prefix' => 'students'], function () {
            Route::get('/index/{ms_major_id?}' , [App\Http\Controllers\project\supervisor_assistants\StudentsController::class , 'index'])->name('supervisor_assistants.students.index');
            Route::post('/search' , [App\Http\Controllers\project\supervisor_assistants\StudentsController::class , 'search'])->name('supervisor_assistants.students.search');
        });
        Route::group(['prefix' => 'companies'], function () {
            Route::get('/index' , [App\Http\Controllers\project\supervisor_assistants\CompaniesController::class , 'index'])->name('supervisor_assistants.companies.index');
            Route::get('/students/{id}' , [App\Http\Controllers\project\supervisor_assistants\CompaniesController::class , 'students'])->name('supervisor_assistants.companies.students'); // To display students
        });
    });

    Route::group(['prefix' => 'supervisors'], function () {
        Route::group(['prefix' => 'majors'], function () {
            Route::get('/index/{id}' , [App\Http\Controllers\project\supervisors\MajorsController::class , 'index'])->name('supervisors.majors.index'); // To show majors to academic supervisor
        });
        Route::group(['prefix' => 'students'], function () {
            Route::get('/index/{id}/{ms_major_id?}' , [App\Http\Controllers\project\supervisors\StudentsController::class , 'index'])->name('supervisors.students.index'); // To display supervisor's students
        });
        Route::group(['prefix' => 'companies'], function () {
            Route::get('/index' , [App\Http\Controllers\project\supervisors\CompaniesController::class , 'index'])->name('supervisors.companies.index'); // To display companies
            Route::get('/students/{id}' , [App\Http\Controllers\project\supervisors\CompaniesController::class , 'students'])->name('supervisors.companies.students'); // To display students
        });
        Route::group(['prefix' => 'assistant'], function () {
            Route::get('/index/{id}' , [App\Http\Controllers\project\supervisors\AssistantController::class , 'index'])->name('supervisors.assistant.index');
            Route::post('/create' , [App\Http\Controllers\project\supervisors\AssistantController::class , 'create'])->name('supervisors.assistant.create');
            Route::post('/delete' , [App\Http\Controllers\project\supervisors\AssistantController::class , 'delete'])->name('supervisors.assistant.delete');
        });
        Route::group(['prefix' => 'training_nominations'], function () {
            Route::get('/index' , [App\Http\Controllers\project\supervisors\TrainingNominationController::class , 'index'])->name('supervisors.training_nominations.index');
        });
        Route::group(['prefix' => 'student_marks'], function () {
            Route::get('/index' , [App\Http\Controllers\project\supervisors\StudentMarksController::class , 'index'])->name('supervisors.supervisors.student_marks');
        });
    });

    Route::group(['prefix' => 'training_supervisor'], function () {
        Route::group(['prefix' => 'conversation'],function (){
            Route::get('/index' , [\App\Http\Controllers\project\training_supervisor\ConversationController::class, 'index'])->name('training_supervisor.conversation.index');
            Route::get('/add' , [\App\Http\Controllers\project\training_supervisor\ConversationController::class, 'add'])->name('training_supervisor.conversation.add');
            Route::post('/create' , [\App\Http\Controllers\project\training_supervisor\ConversationController::class, 'create'])->name('training_supervisor.conversation.create');
            Route::get('/details/{id}' , [\App\Http\Controllers\project\training_supervisor\ConversationController::class, 'details'])->name('training_supervisor.conversation.details');
            Route::post('/create_message' , [\App\Http\Controllers\project\training_supervisor\ConversationController::class, 'create_message'])->name('training_supervisor.conversation.create_message');
            Route::post('/list_conversations' , [\App\Http\Controllers\project\training_supervisor\ConversationController::class, 'list_conversations'])->name('training_supervisor.conversation.list_conversations');
            Route::post('/list_message_ajax' , [\App\Http\Controllers\project\training_supervisor\ConversationController::class, 'list_message_ajax'])->name('training_supervisor.conversation.list_message_ajax');
            Route::post('/add_message_ajax' , [\App\Http\Controllers\project\training_supervisor\ConversationController::class, 'add_message_ajax'])->name('training_supervisor.conversation.add_message_ajax');
        });
        Route::group(['prefix' => 'field_visits'],function (){
            Route::get('/index' , [\App\Http\Controllers\project\training_supervisor\FieldVisitsController::class, 'index'])->name('training_supervisor.field_visits.index');
            Route::get('/add' , [\App\Http\Controllers\project\training_supervisor\FieldVisitsController::class, 'add'])->name('training_supervisor.field_visits.add');
            Route::post('/create' , [\App\Http\Controllers\project\training_supervisor\FieldVisitsController::class, 'create'])->name('training_supervisor.field_visits.create');
            Route::post('/get_student_from_company' , [\App\Http\Controllers\project\training_supervisor\FieldVisitsController::class, 'get_student_from_company'])->name('training_supervisor.field_visits.get_student_from_company');
            Route::get('/details/{id}' , [\App\Http\Controllers\project\training_supervisor\FieldVisitsController::class, 'details'])->name('training_supervisor.field_visits.details');
        });
        Route::group(['prefix' => 'my_students'],function (){
            Route::get('/index' , [\App\Http\Controllers\project\training_supervisor\TrainingSupervisorStudentController::class, 'index'])->name('training_supervisor.my_students.index');
            Route::post('/list_my_student_ajax' , [\App\Http\Controllers\project\training_supervisor\TrainingSupervisorStudentController::class, 'list_my_student_ajax'])->name('training_supervisor.my_students.list_my_student_ajax');
        });
        Route::group(['prefix' => 'final_reports'],function (){
            Route::get('/index' , [\App\Http\Controllers\project\training_supervisor\FinalReportsController::class, 'index'])->name('training_supervisor.final_reports.index');
            Route::post('/final_reports_list_ajax' , [\App\Http\Controllers\project\training_supervisor\FinalReportsController::class, 'final_reports_list_ajax'])->name('training_supervisor.final_reports.final_reports_list_ajax');
        });
    });

    Route::group(['prefix'=>'file_attachment'],function (){
        Route::post('create',[App\Http\Controllers\FileAttachmentController::class , 'create'])->name('file_attachment.create');
        Route::post('file_attachment_list_ajax',[App\Http\Controllers\FileAttachmentController::class , 'file_attachment_list_ajax'])->name('file_attachment.file_attachment_list_ajax');
    });
});

Route::get('user_manual',function (){
    return view('user_manual.index');
})->name('user_manual');

Route::get('generate', function () {
        \Illuminate\Support\Facades\Artisan::call('storage:link');
        echo 'ok';
});

Route::get('migration', function () {
    \Illuminate\Support\Facades\Artisan::call('migrate');
    echo 'migrate';
});
