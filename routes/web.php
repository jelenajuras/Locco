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
  'show' 		=> 'admin.afterHours.show', 
  'edit' 		=> 'admin.afterHours.edit', 
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
});

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
Route::get('admin/confirmationAfter', ['as' => 'admin.confirmationAfter', 'uses' => 'Admin\AfterHoursController@storeConf']);

Route::get('admin/showKalendar', ['as' => 'admin.showKalendar', 'uses' => 'Admin\MeetingController@showKalendar']);

// Gantt kalendar
Route::get('admin/', function () {
    return view('gantt');
})->name('admin.gantt');
