<?php

Route::Controller('admin/state', 'StateController');

Route::Controller('admin/lga', 'LgaController');

Route::Controller('admin/town', 'TownController');

//route to admin user controller -> list,show,add,update,delete
Route::controller('admin/user', 'UserController');


//route to specilties controller
Route::controller('admin/specialty', 'SpecialtyController');

//route to advertisement
Route::controller('admin/advert', 'AdvertisementController');

//routes to insurance controller
Route::controller('admin/insurance', 'InsuranceController');

//routes to insurance plans controller
Route::controller('admin/insurancePlan', 'PlanController');

//routes to hospital controller
Route::controller('admin/hospital', 'HospitalController');

Route::get('member/patient/activate/{code}', array(
	'as' => 'account-activate',
	'uses' => 'PatientController@getActivate'
	));

Route::controller('member/patient', 'PatientController');

//route to patient controller by admin
Route::controller('admin/patient', 'PatientController'); 

//route to doctor controller
Route::controller('member/doctor', 'DoctorController');

//route to doctor controller to admin panel
Route::controller('admin/doctor', 'DoctorController');


//route to schedule controller
Route::controller('member/schedule', 'ScheduleController');

Route::controller('member/job', 'JobController');

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