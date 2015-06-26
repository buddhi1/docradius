<?php

Route::Resource('admin/state', 'StateControllerRes');

//Route::Controller('admin/state', 'StateController');

//Route::Controller('admin/lga', 'LgaController');
Route::Resource('admin/lga', 'LgaControllerRes');

Route::Resource('admin/town', 'TownControllerRes');

// Route::Controller('admin/town', 'TownController');

//route to admin user controller -> list,show,add,update,delete
Route::get('admin/user/editaccount', 'UserController@editAccount');
Route::get('admin/user/updateaccountsettings', 'UserController@updateaccountsettings');
Route::get('admin/user/editaccountsettings', 'UserController@editaccountsettings');

Route::Resource('admin/user', 'UserControllerRes'); 

//Route::put('admin/user/{id}', 'UserController@update');


//route to specilties controller
//Route::controller('admin/specialty', 'SpecialtyController');

Route::Resource('admin/specialty', 'SpecialtyControllerRes');

//route to advertisement
Route::controller('admin/advert', 'AdvertisementController');

//routes to insurance controller
//Route::controller('admin/insurance', 'InsuranceController');

Route::Resource('admin/insurance', 'InsuranceControllerRes');

//routes to insurance plans controller
//Route::controller('admin/insurancePlan', 'PlanController');

Route::Resource('admin/insurancePlan', 'PlanControllerRes');

//routes to hospital controller
//Route::controller('admin/hospital', 'HospitalController');

Route::Resource('admin/hospital', 'HospitalControllerRes');

Route::get('member/patient/activate/{code}', array(
	'as' => 'account-activate',
	'uses' => 'PatientController@getActivate'
	));

Route::controller('member/patient', 'PatientController');

//route to patient controller by admin
Route::controller('admin/patient', 'PatientController'); 

//route to doctor controller
Route::get('member/doctor/search', 'DoctorController@searchDoctorById');

//route to doctor controller to admin panel
//Route::controller('admin/doctor', 'DoctorController');-------------------------- create routes for extra methods

Route::Resource('admin/doctor', 'DoctorControllerRes');

//route to schedule controller
// Route::controller('member/schedule', 'ScheduleController');

Route::get('member/schedule/doctor/{id}', 'ScheduleController@scheduleSearchByDoctorId');

Route::Resource('member/schedule', 'ScheduleControllerRes');

//Route::controller('member/job', 'JobController');

Route::Resource('member/job', 'JobControllerRes');

// action after a time slot is selected
Route::get('channel/schedule/create/{id}', 'ChannelController@create');

// get the schedule of a doctor
Route::get('channel/schedule/{id}', 'ChannelController@schedule');

Route::controller('channel', 'ChannelController');

//route to inactve controler
Route::controller('member/calendar', 'InactiveController');


Route::get('member/index', function() {
	
	return View::make('member.layouts.main');
});

//route to admin controller for edit account
Route::get('admin/editaccountsettings', 'UserController@editaccountsettings');


//route to member home
Route::controller('member', 'AuthController');

//route to admin home
Route::controller('admin', 'AuthController');



Route::controller('/login', 'AuthController');

//member and adminpanel routes
Route::get('member', function(){
	return App::make('AuthController')->getIndex('member');
});

Route::get('admin', function(){
	return App::make('AuthController')->getIndex('admin');
});

Route::get('/', function(){
	return Response::json(array( 'data' => array( 'route' => '/kkk' ) ));
});


Route::get('/dev/csrf', function(){
	return csrf_token();
});
