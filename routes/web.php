<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/
Route::get('/', function () {
    return view('Welcome');
});

// Index page
Route::get('/', ['as' => 'index', 'uses' => 'IndexController@index']);
// Home page
Route::get('home', ['as' => 'home', 'uses' => 'User\HomeController@index']);

// Authorization
Route::get('login', ['as' => 'auth.login.form', 'uses' => 'Auth\SessionController@getLogin']);
Route::post('login', ['as' => 'auth.login.attempt', 'uses' => 'Auth\SessionController@postLogin']);
Route::get('logout', ['as' => 'auth.logout', 'uses' => 'Auth\SessionController@getLogout']);

// Registration
Route::get('register', ['as' => 'auth.register.form', 'uses' => 'Auth\RegistrationController@getRegister']);
Route::post('register', ['as' => 'auth.register.attempt', 'uses' => 'Auth\RegistrationController@postRegister']);

// Activation
Route::get('activate/{code}', ['as' => 'auth.activation.attempt', 'uses' => 'Auth\RegistrationController@getActivate']);
Route::get('resend', ['as' => 'auth.activation.request', 'uses' => 'Auth\RegistrationController@getResend']);
Route::post('resend', ['as' => 'auth.activation.resend', 'uses' => 'Auth\RegistrationController@postResend']);

// Password Reset
Route::get('password/reset/{code}', ['as' => 'auth.password.reset.form', 'uses' => 'Auth\PasswordController@getReset']);
Route::post('password/reset/{code}', ['as' => 'auth.password.reset.attempt', 'uses' => 'Auth\PasswordController@postReset']);
Route::get('password/reset', ['as' => 'auth.password.request.form', 'uses' => 'Auth\PasswordController@getRequest']);
Route::post('password/reset', ['as' => 'auth.password.request.attempt', 'uses' => 'Auth\PasswordController@postRequest']);


/*############# ADMIN ##############*/
Route::group(['prefix' => 'admin'], function () {
	// Dashboard
	Route::get('/', ['as' => 'admin.dashboard', 'uses' => 'Admin\DashboardController@index']);
	// Users
	Route::resource('users', 'Admin\UserController');
	// Roles
	Route::resource('roles', 'Admin\RoleController');
	//
	Route::resource('posts', 'Admin\PostController', ['names' => [
		'index' 		=> 'admin.posts.index', 
		'create' 		=> 'admin.posts.create', 
		'store' 		=> 'admin.posts.store', 
		'show' 		=> 'admin.posts.show', 
		'edit' 		=> 'admin.posts.edit', 
		'update'		=> 'admin.posts.update', 
		'destroy'		=> 'admin.posts.destroy'
	]]);
	Route::resource('employees', 'Admin\EmployeeController', ['names' => [
		'index' 		=> 'admin.employees.index', 
		'create' 		=> 'admin.employees.create', 
		'store' 		=> 'admin.employees.store', 
		'show' 		=> 'admin.employees.show', 
		'edit' 		=> 'admin.employees.edit', 
		'update'		=> 'admin.employees.update', 
		'destroy'		=> 'admin.employees.destroy'
	]]);
	Route::resource('equipments', 'Admin\EquipmentController', ['names' => [
		'index' 		=> 'admin.equipments.index', 
		'create' 		=> 'admin.equipments.create', 
		'store' 		=> 'admin.equipments.store', 
		'show' 		=> 'admin.equipments.show', 
		'edit' 		=> 'admin.equipments.edit', 
		'update'		=> 'admin.equipments.update', 
		'destroy'		=> 'admin.equipments.destroy'
	]]);
	Route::resource('employee_terminations', 'Admin\EmployeeTerminationController', ['names' => [
		'index' 		=> 'admin.employee_terminations.index', 
		'create' 		=> 'admin.employee_terminations.create', 
		'store' 		=> 'admin.employee_terminations.store', 
		'show' 		=> 'admin.employee_terminations.show', 
		'edit' 		=> 'admin.employee_terminations.edit', 
		'update'		=> 'admin.employee_terminations.update', 
		'destroy'		=> 'admin.employee_terminations.destroy'
	]]);
	Route::resource('terminations', 'Admin\TerminationController', ['names' => [
		'index' 		=> 'admin.terminations.index', 
		'create' 		=> 'admin.terminations.create', 
		'store' 		=> 'admin.terminations.store', 
		'show' 		=> 'admin.terminations.show', 
		'edit' 		=> 'admin.terminations.edit', 
		'update'		=> 'admin.terminations.update', 
		'destroy'		=> 'admin.terminations.destroy'
	]]);
	Route::resource('kids', 'Admin\KidController', ['names' => [
		'index' 		=> 'admin.kids.index', 
		'create' 		=> 'admin.kids.create', 
		'store' 		=> 'admin.kids.store', 
		'show' 		=> 'admin.kids.show', 
		'edit' 		=> 'admin.kids.edit', 
		'update'		=> 'admin.kids.update', 
		'destroy'		=> 'admin.kids.destroy'
	]]);
	Route::resource('works', 'Admin\WorkController', ['names' => [
		'index' 		=> 'admin.works.index', 
		'create' 		=> 'admin.works.create', 
		'store' 		=> 'admin.works.store', 
		'show' 		=> 'admin.works.show', 
		'edit' 		=> 'admin.works.edit', 
		'update'		=> 'admin.works.update', 
		'destroy'		=> 'admin.works.destroy'
	]]);
	Route::resource('registrations', 'Admin\RegistrationController', ['names' => [
		'index' 		=> 'admin.registrations.index', 
		'create' 		=> 'admin.registrations.create', 
		'store' 		=> 'admin.registrations.store', 
		'show' 		=> 'admin.registrations.show', 
		'edit' 		=> 'admin.registrations.edit', 
		'update'		=> 'admin.registrations.update', 
		'destroy'		=> 'admin.registrations.destroy'
	]]);
	Route::resource('employee_equipments', 'Admin\EmployeeEquipmentController', ['names' => [
		'index' 		=> 'admin.employee_equipments.index', 
		'create' 		=> 'admin.employee_equipments.create', 
		'store' 		=> 'admin.employee_equipments.store', 
		'show' 		=> 'admin.employee_equipments.show', 
		'edit' 		=> 'admin.employee_equipments.edit', 
		'update'		=> 'admin.employee_equipments.update', 
		'destroy'		=> 'admin.employee_equipments.destroy'
	]]);
	Route::resource('workingTags', 'Admin\Working_TagController', ['names' => [
		'index' 		=> 'admin.workingTags.index', 
		'create' 		=> 'admin.workingTags.create', 
		'store' 		=> 'admin.workingTags.store', 
		'show' 		=> 'admin.workingTags.show', 
		'edit' 		=> 'admin.workingTags.edit', 
		'update'		=> 'admin.workingTags.update', 
		'destroy'		=> 'admin.workingTags.destroy'
	]]);
	Route::resource('workingHours', 'Admin\Working_hourController', ['names' => [
		'index' 		=> 'admin.workingHours.index', 
		'create' 		=> 'admin.workingHours.create', 
		'store' 		=> 'admin.workingHours.store', 
		'show' 		=> 'admin.workingHours.show', 
		'edit' 		=> 'admin.workingHours.edit', 
		'update'		=> 'admin.workingHours.update', 
		'destroy'		=> 'admin.workingHours.destroy'
	]]);
	Route::resource('afterHours', 'Admin\AfterHoursController', ['names' => [
		'index' 		=> 'admin.afterHours.index', 
		'create' 		=> 'admin.afterHours.create', 
		'store' 		=> 'admin.afterHours.store', 
		'show' 			=> 'admin.afterHours.show', 
		'edit' 			=> 'admin.afterHours.edit', 
		'update'		=> 'admin.afterHours.update', 
		'destroy'		=> 'admin.afterHours.destroy'
	]]);
	Route::resource('vacation_requests', 'Admin\VacationRequestController', ['names' => [
		'index' 		=> 'admin.vacation_requests.index', 
		'create' 		=> 'admin.vacation_requests.create', 
		'store' 		=> 'admin.vacation_requests.store', 
		'show' 		=> 'admin.vacation_requests.show', 
		'edit' 		=> 'admin.vacation_requests.edit', 
		'update'		=> 'admin.vacation_requests.update', 
		'destroy'		=> 'admin.vacation_requests.destroy'
	]]);
	Route::resource('notices', 'Admin\NoticeController', ['names' => [
		'index' 		=> 'admin.notices.index', 
		'create' 		=> 'admin.notices.create', 
		'store' 		=> 'admin.notices.store', 
		'show' 		=> 'admin.notices.show', 
		'edit' 		=> 'admin.notices.edit', 
		'update'		=> 'admin.notices.update', 
		'destroy'		=> 'admin.notices.destroy'
	]]);
	Route::resource('documents', 'DocumentController', ['names' => [
		'index' 		=> 'admin.documents.index', 
		'create' 		=> 'admin.documents.create', 
		'store' 		=> 'admin.documents.store', 
		'show' 		=> 'admin.documents.show', 
		'edit' 		=> 'admin.documents.edit', 
		'update'		=> 'admin.documents.update', 
		'destroy'		=> 'admin.documents.destroy'
	]]);
	Route::resource('customers', 'Admin\CustomerController', ['names' => [
		'index' 		=> 'admin.customers.index', 
		'create' 		=> 'admin.customers.create', 
		'store' 		=> 'admin.customers.store', 
		'show' 		=> 'admin.customers.show', 
		'edit' 		=> 'admin.customers.edit', 
		'update'		=> 'admin.customers.update', 
		'destroy'		=> 'admin.customers.destroy'
	]]);
	Route::resource('projects', 'Admin\ProjectController', ['names' => [
		'index' 		=> 'admin.projects.index', 
		'create' 		=> 'admin.projects.create', 
		'store' 		=> 'admin.projects.store', 
		'show' 		=> 'admin.projects.show', 
		'edit' 		=> 'admin.projects.edit', 
		'update'		=> 'admin.projects.update', 
		'destroy'		=> 'admin.projects.destroy'
	]]);
	Route::resource('cars', 'Admin\CarController', ['names' => [
		'index' 		=> 'admin.cars.index', 
		'create' 		=> 'admin.cars.create', 
		'store' 		=> 'admin.cars.store', 
		'show' 		=> 'admin.cars.show', 
		'edit' 		=> 'admin.cars.edit', 
		'update'		=> 'admin.cars.update', 
		'destroy'		=> 'admin.cars.destroy'
	]]);
	Route::resource('shedulers', 'Admin\ShedulerController', ['names' => [
		'index' 		=> 'admin.shedulers.index', 
		'create' 		=> 'admin.shedulers.create', 
		'store' 		=> 'admin.shedulers.store', 
		'show' 		=> 'admin.shedulers.show', 
		'edit' 		=> 'admin.shedulers.edit', 
		'update'		=> 'admin.shedulers.update', 
		'destroy'		=> 'admin.shedulers.destroy'
	]]);
	Route::resource('job_interviews', 'Admin\JobInterviewController', ['names' => [
		'index' 		=> 'admin.job_interviews.index', 
		'create' 		=> 'admin.job_interviews.create', 
		'store' 		=> 'admin.job_interviews.store', 
		'show' 		=> 'admin.job_interviews.show', 
		'edit' 		=> 'admin.job_interviews.edit', 
		'update'		=> 'admin.job_interviews.update', 
		'destroy'		=> 'admin.job_interviews.destroy'
	]]);
	Route::resource('meetings', 'Admin\MeetingController', ['names' => [
		'index' 		=> 'admin.meetings.index', 
		'create' 		=> 'admin.meetings.create', 
		'store' 		=> 'admin.meetings.store', 
		'show' 		=> 'admin.meetings.show', 
		'edit' 		=> 'admin.meetings.edit', 
		'update'		=> 'admin.meetings.update', 
		'destroy'		=> 'admin.meetings.destroy'
	]]);
	Route::resource('meeting_rooms', 'Admin\MeetingRoomController', ['names' => [
		'index' 		=> 'admin.meeting_rooms.index', 
		'create' 		=> 'admin.meeting_rooms.create', 
		'store' 		=> 'admin.meeting_rooms.store', 
		'show' 		=> 'admin.meeting_rooms.show', 
		'edit' 		=> 'admin.meeting_rooms.edit', 
		'update'		=> 'admin.meeting_rooms.update', 
		'destroy'		=> 'admin.meeting_rooms.destroy'
	]]);
	Route::resource('effective_hours', 'Admin\EffectiveHourController', ['names' => [
		'index' 		=> 'admin.effective_hours.index', 
		'create' 		=> 'admin.effective_hours.create', 
		'store' 		=> 'admin.effective_hours.store', 
		'show' 		=> 'admin.effective_hours.show', 
		'edit' 		=> 'admin.effective_hours.edit', 
		'update'		=> 'admin.effective_hours.update', 
		'destroy'		=> 'admin.effective_hours.destroy'
	]]);
	Route::resource('departments', 'Admin\DepartmentController', ['names' => [
		'index' 		=> 'admin.departments.index', 
		'create' 		=> 'admin.departments.create', 
		'store' 		=> 'admin.departments.store', 
		'show' 		=> 'admin.departments.show', 
		'edit' 		=> 'admin.departments.edit', 
		'update'		=> 'admin.departments.update', 
		'destroy'		=> 'admin.departments.destroy'
	]]);
	Route::resource('employee_departments', 'Admin\EmployeeDepartmentController', ['names' => [
		'index' 		=> 'admin.employee_departments.index', 
		'create' 		=> 'admin.employee_departments.create', 
		'store' 		=> 'admin.employee_departments.store', 
		'show' 		=> 'admin.employee_departments.show', 
		'edit' 		=> 'admin.employee_departments.edit', 
		'update'		=> 'admin.employee_departments.update', 
		'destroy'		=> 'admin.employee_departments.destroy'
	]]);
	Route::resource('evaluating_groups', 'Admin\EvaluatingGroupController', ['names' => [
		'index' 		=> 'admin.evaluating_groups.index', 
		'create' 		=> 'admin.evaluating_groups.create', 
		'store' 		=> 'admin.evaluating_groups.store', 
		'show' 		=> 'admin.evaluating_groups.show', 
		'edit' 		=> 'admin.evaluating_groups.edit', 
		'update'		=> 'admin.evaluating_groups.update', 
		'destroy'		=> 'admin.evaluating_groups.destroy'
	]]);
	Route::resource('evaluating_questions', 'Admin\EvaluatingQuestionController', ['names' => [
		'index' 		=> 'admin.evaluating_questions.index', 
		'create' 		=> 'admin.evaluating_questions.create', 
		'store' 		=> 'admin.evaluating_questions.store', 
		'show' 		=> 'admin.evaluating_questions.show', 
		'edit' 		=> 'admin.evaluating_questions.edit', 
		'update'		=> 'admin.evaluating_questions.update', 
		'destroy'		=> 'admin.evaluating_questions.destroy'
	]]);
	Route::resource('evaluating_ratings', 'Admin\EvaluatingRatingController', ['names' => [
		'index' 		=> 'admin.evaluating_ratings.index', 
		'create' 		=> 'admin.evaluating_ratings.create', 
		'store' 		=> 'admin.evaluating_ratings.store', 
		'show' 		=> 'admin.evaluating_ratings.show', 
		'edit' 		=> 'admin.evaluating_ratings.edit', 
		'update'		=> 'admin.evaluating_ratings.update', 
		'destroy'		=> 'admin.evaluating_ratings.destroy'
	]]);
	Route::resource('evaluating_employees', 'Admin\EvaluatingEmployeeController', ['names' => [
		'index' 		=> 'admin.evaluating_employees.index', 
		'create' 		=> 'admin.evaluating_employees.create', 
		'store' 		=> 'admin.evaluating_employees.store', 
		'show' 		=> 'admin.evaluating_employees.show', 
		'edit' 		=> 'admin.evaluating_employees.edit', 
		'update'		=> 'admin.evaluating_employees.update', 
		'destroy'		=> 'admin.evaluating_employees.destroy'
	]]);
	Route::resource('evaluation_targets', 'Admin\EvaluationTargetController', ['names' => [
		'index' 		=> 'admin.evaluation_targets.index', 
		'create' 		=> 'admin.evaluation_targets.create', 
		'store' 		=> 'admin.evaluation_targets.store', 
		'show' 		=> 'admin.evaluation_targets.show', 
		'edit' 		=> 'admin.evaluation_targets.edit', 
		'update'		=> 'admin.evaluation_targets.update', 
		'destroy'		=> 'admin.evaluation_targets.destroy'
	]]);
	Route::resource('evaluations', 'Admin\EvaluationController', ['names' => [
		'index' 		=> 'admin.evaluations.index', 
		'create' 		=> 'admin.evaluations.create', 
		'store' 		=> 'admin.evaluations.store', 
		'show' 		=> 'admin.evaluations.show', 
		'edit' 		=> 'admin.evaluations.edit', 
		'update'		=> 'admin.evaluations.update', 
		'destroy'		=> 'admin.evaluations.destroy'
	]]);
	Route::resource('questionnaires', 'Admin\QuestionnaireController', ['names' => [
		'index' 		=> 'admin.questionnaires.index', 
		'create' 		=> 'admin.questionnaires.create', 
		'store' 		=> 'admin.questionnaires.store', 
		'show' 		=> 'admin.questionnaires.show', 
		'edit' 		=> 'admin.questionnaires.edit',  
		'update'		=> 'admin.questionnaires.update', 
		'destroy'		=> 'admin.questionnaires.destroy'
	]]);
	Route::resource('educations', 'Admin\EducationController', ['names' => [
		'index' 		=> 'admin.educations.index', 
		'create' 		=> 'admin.educations.create', 
		'store' 		=> 'admin.educations.store', 
		'show' 		=> 'admin.educations.show', 
		'edit' 		=> 'admin.educations.edit',  
		'update'		=> 'admin.educations.update', 
		'destroy'		=> 'admin.educations.destroy'
	]]);
	Route::resource('education_themes', 'Admin\EducationThemeController', ['names' => [
		'index' 		=> 'admin.education_themes.index', 
		'create' 		=> 'admin.education_themes.create', 
		'store' 		=> 'admin.education_themes.store', 
		'show' 		=> 'admin.education_themes.show', 
		'edit' 		=> 'admin.education_themes.edit',  
		'update'		=> 'admin.education_themes.update', 
		'destroy'		=> 'admin.education_themes.destroy'
	]]);
	Route::resource('education_articles', 'Admin\EducationArticleController', ['names' => [
		'index' 		=> 'admin.education_articles.index', 
		'create' 		=> 'admin.education_articles.create', 
		'store' 		=> 'admin.education_articles.store', 
		'show' 		=> 'admin.education_articles.show', 
		'edit' 		=> 'admin.education_articles.edit',  
		'update'		=> 'admin.education_articles.update', 
		'destroy'		=> 'admin.education_articles.destroy'
	]]);
	Route::resource('presentations', 'Admin\PresentationController', ['names' => [
		'index' 		=> 'admin.presentations.index', 
		'create' 		=> 'admin.presentations.create', 
		'store' 		=> 'admin.presentations.store', 
		'show' 		=> 'admin.presentations.show', 
		'edit' 		=> 'admin.presentations.edit',  
		'update'		=> 'admin.presentations.update', 
		'destroy'		=> 'admin.presentations.destroy'
	]]);
	Route::resource('education_comments', 'Admin\EducationCommentController', ['names' => [
		'index' 		=> 'admin.education_comments.index', 
		'create' 		=> 'admin.education_comments.create', 
		'store' 		=> 'admin.education_comments.store', 
		'show' 		=> 'admin.education_comments.show', 
		'edit' 		=> 'admin.education_comments.edit',  
		'update'		=> 'admin.education_comments.update', 
		'destroy'		=> 'admin.education_comments.destroy'
	]]);
	Route::resource('companies', 'Admin\CompanyController', ['names' => [
		'index' 		=> 'admin.companies.index', 
		'create' 		=> 'admin.companies.create', 
		'store' 		=> 'admin.companies.store', 
		'show' 		=> 'admin.companies.show', 
		'edit' 		=> 'admin.companies.edit',  
		'update'		=> 'admin.companies.update', 
		'destroy'		=> 'admin.companies.destroy'
	]]);
	Route::resource('benefits', 'Admin\BenefitController', ['names' => [
		'index' 		=> 'admin.benefits.index', 
		'create' 		=> 'admin.benefits.create', 
		'store' 		=> 'admin.benefits.store', 
		'show' 		=> 'admin.benefits.show', 
		'edit' 		=> 'admin.benefits.edit',  
		'update'		=> 'admin.benefits.update', 
		'destroy'		=> 'admin.benefits.destroy'
	]]);
	Route::resource('tables', 'Admin\TableController', ['names' => [
		'index' 		=> 'admin.tables.index', 
		'create' 		=> 'admin.tables.create', 
		'store' 		=> 'admin.tables.store', 
		'show' 		=> 'admin.tables.show', 
		'edit' 		=> 'admin.tables.edit',  
		'update'		=> 'admin.tables.update', 
		'destroy'		=> 'admin.tables.destroy'
	]]);
	Route::resource('ad_categories', 'Admin\AdCategoryController', ['names' => [
		'index' 		=> 'admin.ad_categories.index', 
		'create' 		=> 'admin.ad_categories.create', 
		'store' 		=> 'admin.ad_categories.store', 
		'show' 		=> 'admin.ad_categories.show', 
		'edit' 		=> 'admin.ad_categories.edit',  
		'update'		=> 'admin.ad_categories.update', 
		'destroy'		=> 'admin.ad_categories.destroy'
	]]);
	Route::resource('ads', 'Admin\AdController', ['names' => [
		'index' 		=> 'admin.ads.index', 
		'create' 		=> 'admin.ads.create', 
		'store' 		=> 'admin.ads.store', 
		'show' 		=> 'admin.ads.show', 
		'edit' 		=> 'admin.ads.edit',  
		'update'		=> 'admin.ads.update', 
		'destroy'		=> 'admin.ads.destroy'
	]]);
	Route::resource('trainings', 'Admin\TrainingController', ['names' => [
		'index' 		=> 'admin.trainings.index', 
		'create' 		=> 'admin.trainings.create', 
		'store' 		=> 'admin.trainings.store', 
		'show' 		=> 'admin.trainings.show', 
		'edit' 		=> 'admin.trainings.edit',  
		'update'		=> 'admin.trainings.update', 
		'destroy'		=> 'admin.trainings.destroy'
	]]);
	Route::resource('employee_trainings', 'Admin\EmployeeTrainingController', ['names' => [
		'index' 		=> 'admin.employee_trainings.index', 
		'create' 		=> 'admin.employee_trainings.create', 
		'store' 		=> 'admin.employee_trainings.store', 
		'show' 		=> 'admin.employee_trainings.show', 
		'edit' 		=> 'admin.employee_trainings.edit',  
		'update'		=> 'admin.employee_trainings.update', 
		'destroy'		=> 'admin.employee_trainings.destroy'
	]]);
	Route::resource('job_records', 'Admin\JobRecordController', ['names' => [
		'index' 		=> 'admin.job_records.index', 
		'create' 		=> 'admin.job_records.create', 
		'store' 		=> 'admin.job_records.store', 
		'show' 			=> 'admin.job_records.show', 
		'edit' 			=> 'admin.job_records.edit',  
		'update'		=> 'admin.job_records.update', 
		'destroy'		=> 'admin.job_records.destroy'
	]]);
	Route::resource('events', 'Admin\EventController', ['names' => [
		'index' 		=> 'admin.events.index', 
		'create' 		=> 'admin.events.create', 
		'store' 		=> 'admin.events.store', 
		'show' 			=> 'admin.events.show', 
		'edit' 			=> 'admin.events.edit',  
		'update'		=> 'admin.events.update', 
		'destroy'		=> 'admin.events.destroy'
	]]);
	Route::resource('catalog_categories', 'Admin\CatalogCategoryController', ['names' => [
		'index' 		=> 'admin.catalog_categories.index', 
		'create' 		=> 'admin.catalog_categories.create', 
		'store' 		=> 'admin.catalog_categories.store', 
		'show' 			=> 'admin.catalog_categories.show', 
		'edit' 			=> 'admin.catalog_categories.edit',  
		'update'		=> 'admin.catalog_categories.update', 
		'destroy'		=> 'admin.catalog_categories.destroy'
	]]);
	Route::resource('catalog_manufacturers', 'Admin\CatalogManufacturerController', ['names' => [
		'index' 		=> 'admin.catalog_manufacturers.index', 
		'create' 		=> 'admin.catalog_manufacturers.create', 
		'store' 		=> 'admin.catalog_manufacturers.store', 
		'show' 			=> 'admin.catalog_manufacturers.show', 
		'edit' 			=> 'admin.catalog_manufacturers.edit',  
		'update'		=> 'admin.catalog_manufacturers.update', 
		'destroy'		=> 'admin.catalog_manufacturers.destroy'
	]]);
	Route::resource('instructions', 'Admin\InstructionController', ['names' => [
		'index' 		=> 'admin.instructions.index', 
		'create' 		=> 'admin.instructions.create', 
		'store' 		=> 'admin.instructions.store', 
		'show' 			=> 'admin.instructions.show', 
		'edit' 			=> 'admin.instructions.edit',  
		'update'		=> 'admin.instructions.update', 
		'destroy'		=> 'admin.instructions.destroy'
	]]);
	Route::resource('comment_instructions', 'Admin\CommentInstructionController', ['names' => [
		'index' 		=> 'admin.comment_instructions.index', 
		'create' 		=> 'admin.comment_instructions.create', 
		'store' 		=> 'admin.comment_instructions.store', 
		'show' 			=> 'admin.comment_instructions.show', 
		'edit' 			=> 'admin.comment_instructions.edit',  
		'update'		=> 'admin.comment_instructions.update', 
		'destroy'		=> 'admin.comment_instructions.destroy'
	]]);
	Route::resource('visitors', 'Admin\VisitorController', ['names' => [
		'index' 		=> 'admin.visitors.index', 
		'create' 		=> 'admin.visitors.create', 
		'store' 		=> 'admin.visitors.store', 
		'show' 			=> 'admin.visitors.show', 
		'edit' 			=> 'admin.visitors.edit',  
		'update'		=> 'admin.visitors.update', 
		'destroy'		=> 'admin.visitors.destroy'
	]]);
	Route::resource('tasks', 'Admin\TaskController', ['names' => [
		'index' 		=> 'admin.tasks.index', 
		'create' 		=> 'admin.tasks.create', 
		'store' 		=> 'admin.tasks.store', 
		'show' 			=> 'admin.tasks.show', 
		'edit' 			=> 'admin.tasks.edit',  
		'update'		=> 'admin.tasks.update', 
		'destroy'		=> 'admin.tasks.destroy'
	]]);
	Route::resource('employee_tasks', 'Admin\EmployeeTaskController', ['names' => [
		'index' 		=> 'admin.employee_tasks.index', 
		'create' 		=> 'admin.employee_tasks.create', 
		'store' 		=> 'admin.employee_tasks.store', 
		'show' 			=> 'admin.employee_tasks.show', 
		'edit' 			=> 'admin.employee_tasks.edit',  
		'update'		=> 'admin.employee_tasks.update', 
		'destroy'		=> 'admin.employee_tasks.destroy'
	]]);
	Route::resource('temporary_employees', 'Admin\TemporaryEmployeeController', ['names' => [
		'index' 		=> 'admin.temporary_employees.index', 
		'create' 		=> 'admin.temporary_employees.create', 
		'store' 		=> 'admin.temporary_employees.store', 
		'show' 			=> 'admin.temporary_employees.show', 
		'edit' 			=> 'admin.temporary_employees.edit',  
		'update'		=> 'admin.temporary_employees.update', 
		'destroy'		=> 'admin.temporary_employees.destroy'
	]]);
	Route::resource('temporary_employee_requests', 'Admin\TemporaryEmployeeRequestController', ['names' => [
		'index' 		=> 'admin.temporary_employee_requests.index', 
		'create' 		=> 'admin.temporary_employee_requests.create', 
		'store' 		=> 'admin.temporary_employee_requests.store', 
		'show' 			=> 'admin.temporary_employee_requests.show', 
		'edit' 			=> 'admin.temporary_employee_requests.edit',  
		'update'		=> 'admin.temporary_employee_requests.update', 
		'destroy'		=> 'admin.temporary_employee_requests.destroy'
	]]);
});

Route::get('visitors/{id}', ['as' => 'visitors', 'uses' => 'Admin\VisitorController@visitors_show']);
Route::get('en/visitors/{id}', ['as' => 'en.visitors', 'uses' => 'Admin\VisitorController@visitors_show_en']);
Route::get('de/visitors/{id}', ['as' => 'de.visitors', 'uses' => 'Admin\VisitorController@visitors_show_de']);

// Post page
Route::post('/comment/store', ['as' => 'comment.store', 'uses' => 'IndexController@storeComment']);
Route::get('/{slug}', ['as' => 'post.show', 'uses' => 'IndexController@show']);

//pdf_add_annotation
Route::get('/generate_pdf/{employee_id}','Admin\RegistrationController@generate_pdf');

//pdf_Prijava
Route::get('/generate_pdf/{employee_id}','Admin\EmployeeController@generate_pdf');
//pdf_Liječnički
Route::get('/lijecnicki_pdf/{employee_id}','Admin\EmployeeController@lijecnicki_pdf');
Route::get('/lijecnicki/{employee_id}','Admin\EmployeeController@lijecnicki');
//pdf_Zaduženje
Route::get('/zaduzenje_pdf/{employee_id}','Admin\EmployeeEquipmentController@zaduzenje_pdf');
//pdf_Prijava
Route::get('/prijava_pdf/{employee_id}','Admin\EmployeeController@prijava_pdf');

Route::get('/{id}','DocumentController@generate_pdf');

Route::get('admin/confirmation', ['as' => 'admin.confirmation', 'uses' => 'Admin\VacationRequestController@storeConf']);
Route::get('admin/confirmationTemp', ['as' => 'admin.confirmationTemp', 'uses' => 'Admin\TemporaryEmployeeRequestController@storeConf']);
Route::post('admin/confDirector', ['as' => 'admin.confDirector', 'uses' => 'Admin\VacationRequestController@confDirector']);
Route::get('admin/confirmation_show', ['as' => 'admin.confirmation_show', 'uses' => 'Admin\VacationRequestController@confirmation_show']);
Route::get('admin/VacationRequest', ['as' => 'admin.VacationRequest', 'uses' => 'Admin\VacationRequestController@VacationRequest']);
Route::get('admin/AllVacationRequest', ['as' => 'admin.AllVacationRequest', 'uses' => 'Admin\VacationRequestController@AllVacationRequest']);

Route::get('admin/confirmationAfter', ['as' => 'admin.confirmationAfter', 'uses' => 'Admin\AfterHoursController@storeConf']);
Route::post('admin/confDirectorAfter', ['as' => 'admin.confDirectorAfter', 'uses' => 'Admin\AfterHoursController@confDirectorAfter']);
Route::get('admin/confirmationAfter_show', ['as' => 'admin.confirmationAfter_show', 'uses' => 'Admin\AfterHoursController@confirmationAfter_show']);
Route::post('paidHours', ['as' => 'paidHours', 'uses' => 'Admin\AfterHoursController@paidHours']);

Route::get('admin/showKalendar', ['as' => 'admin.showKalendar', 'uses' => 'Admin\MeetingController@showKalendar']);

Route::get('admin/noticeBoard', ['as' => 'admin.noticeBoard', 'uses' => 'NoticeBoardController@index']);
Route::get('admin/announcement', ['as' => 'admin.announcement', 'uses' => 'NoticeBoardController@announcement']);

Route::get('admin/show_instructions', ['as' => 'admin.show_instructions', 'uses' => 'Admin\InstructionController@show_instructions']);

Route::get('admin/prezentacije', ['as' => 'admin.prezentacije', 'uses' => 'Admin\Presentations@prezentacije']);

/* Oglasnik*/
Route::get('admin/oglasnik', ['as' => 'admin.oglasnik', 'uses' => 'Admin\AdController@oglasnik']);

Route::get('admin/shedulePost', ['as' => 'admin.shedulePost', 'uses' => 'Admin\PostController@shedulePost']);

Route::get('admin/shedule', ['as' => 'admin.shedule', 'uses' => 'Admin\ShedulerController@shedule']);
Route::post('confirmTask', ['as' => 'confirmTask', 'uses' => 'Admin\EmployeeTaskController@confirmTask']);

Route::get('admin/contacts', ['as' => 'contacts', 'uses' => 'Admin\RegistrationController@contacts']);
Route::get('admin/deleteDoc', ['as' => 'deleteDoc', 'uses' => 'DocumentController@deleteDoc']);

Route::get('/clear-cache', function() {
    Artisan::call('cache:clear');
    return "Cache is cleared";
});
