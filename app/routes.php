<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

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

Route::controller('member/patient', 'PatientController');

//route to doctor controller
Route::controller('member/doctor', 'DoctorController');