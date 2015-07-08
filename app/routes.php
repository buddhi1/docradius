<?php

Route::Resource('drad/admin/state', 'StateControllerRes');

//Route::Controller('admin/state', 'StateController');

//Route::Controller('admin/lga', 'LgaController');
Route::Resource('drad/admin/lga', 'LgaControllerRes');

Route::Resource('drad/admin/town', 'TownControllerRes');

// Route::Controller('admin/town', 'TownController');

//route to admin user controller -> list,show,add,update,delete
Route::get('drad/admin/user/editaccount', 'UserController@editAccount');
Route::get('drad/admin/user/updateaccountsettings', 'UserController@updateaccountsettings');
Route::get('drad/admin/user/editaccountsettings', 'UserController@editaccountsettings');

Route::Resource('drad/admin/user', 'UserControllerRes'); 

//Route::put('admin/user/{id}', 'UserController@update');


//route to specilties controller
//Route::controller('admin/specialty', 'SpecialtyController');

Route::Resource('drad/admin/specialty', 'SpecialtyControllerRes');

//route to advertisement
Route::controller('drad/admin/advert', 'AdvertisementController');

//routes to insurance controller
//Route::controller('admin/insurance', 'InsuranceController');

Route::Resource('drad/admin/insurance', 'InsuranceControllerRes');

//routes to insurance plans controller
//Route::controller('admin/insurancePlan', 'PlanController');

Route::Resource('drad/admin/insurancePlan', 'PlanControllerRes');

//routes to hospital controller
//Route::controller('admin/hospital', 'HospitalController');

Route::Resource('drad/admin/hospital', 'HospitalControllerRes');

Route::get('drad/member/patient/activate/{code}', array(
	'as' => 'account-activate',
	'uses' => 'PatientController@getActivate'
	));

Route::controller('drad/member/patient', 'PatientController');

//route to patient controller by admin
Route::controller('drad/admin/patient', 'PatientController'); 

//route to doctor controller
//Route::controller('drad/member/doctor', 'DoctorController');

Route::get('drad/member/doctor/search', 'DoctorController@searchDoctorById');


//route to doctor controller to admin panel
//Route::controller('admin/doctor', 'DoctorController');-------------------------- create routes for extra methods

Route::Resource('drad/doctor', 'DoctorControllerRes');

//route to schedule controller
// Route::controller('member/schedule', 'ScheduleController');

Route::get('drad/member/schedule/doctor/{id}', 'ScheduleController@scheduleSearchByDoctorId');

Route::Resource('drad/member/schedule', 'ScheduleControllerRes');

//Route::controller('member/job', 'JobController');

Route::Resource('drad/member/job', 'JobControllerRes');

// action after a time slot is selected
Route::get('drad/channel/schedule/create/{id}', 'ChannelController@create');

// get the schedule of a doctor
Route::get('drad/channel/schedule/{id}', 'ChannelController@schedule');

Route::controller('drad/channel', 'ChannelController');

//route to inactve controler
Route::controller('drad/member/calendar', 'InactiveController');


Route::get('drad/member/index', function() {
	
	return View::make('member.layouts.main');
});

//route to admin controller for edit account
Route::get('drad/admin/editaccountsettings', 'UserController@editaccountsettings');


//route to member home
Route::controller('drad/member', 'AuthController');

//route to admin home
Route::controller('drad/admin', 'AuthController');



Route::controller('drad/auth', 'AuthController');

//member and adminpanel routes
Route::get('drad/member', function(){
	return App::make('AuthController')->getIndex('member');
});

Route::get('drad/admin', function(){
	return App::make('AuthController')->getIndex('admin');
});

Route::get('/', function(){
	return Response::json(array( 'data' => array( 'route' => '/kkk' ) ));
});


Route::get('/dev/csrf', function(){
	return csrf_token();
});





// ------------------------ frontend routes ---------------------------- //
Route::group([ 'prefix' => 'admin' ], function(){
	Route::any('{all?}',function(){
		return View::make('frontend.admin')->with('USER_TYPE', 'admin');
	});
});

Route::group([ 'prefix' => 'hospital' ], function(){
	Route::any('{all?}',function(){
		return View::make('frontend.login')->with('USER_TYPE', 'hospital');
	});
});

Route::group([ 'prefix' => 'patient' ], function(){
	Route::any('{all?}',function(){
		return View::make('frontend.login')->with('USER_TYPE', 'patient');
	});
});



// --------------------------------------------------------------------- //