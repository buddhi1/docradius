<?php

Route::get('/', function()
{
	return View::make('hello');
});

Route::Controller('admin/state', 'StateController');

Route::Controller('admin/lga', 'LgaController');

Route::Controller('admin/town', 'TownController');

//route to user controller
Route::controller('admin/user', 'UserController');


//route to specilties controller
Route::controller('admin/specialty', 'SpecialtyController');

//route to advertisement
Route::controller('admin/advert', 'AdvertisementController');

Route::get('member/patient/activate/{code}', array(
	'as' => 'account-activate',
	'uses' => 'PatientController@getActivate'
	));

Route::controller('member/patient', 'PatientController');

//route to doctor controller
Route::controller('member/doctor', 'DoctorController');

//route to doctor controller to admin panel
Route::controller('admin/doctor', 'DoctorController');


//route to schedule controller
Route::controller('member/schedule', 'ScheduleController');

Route::controller('member/job', 'JobController');

Route::controller('channel', 'ChannelController');

//route to inactve controler
Route::controller('member/calendar', 'InactiveController');

//route to member login
Route::controller('member', 'AuthController');

