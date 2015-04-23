<?php

class PatientController extends BaseController {

	public function __construct() {

		$this->beforeFilter('csrf', array('on' => 'post'));
	}

	public function getIndex() {
	// display all the Patients

		// return View::make('admin.patient.view')
		// 	->with('patients', Patient::all());
	}

	public function getCreate() {
	// Create a new Patient

		return View::make('member.patient.add')
			->with('states', State::lists('name', 'id'));
	}

	public function getDropdowns() {
	// populate LGA dropdown
		
		$id = Input::get('state_id');
		$lgas = Lga::where('state_id','=',$id)->get();
	
		return Response::json($lgas);
	}

	public function getTowndrop() {
	// populate Town dropdown
		
		$id = Input::get('lga_id');
		$towns = Town::where('lga_id','=',$id)->get();
	
		return Response::json($towns);
	}

	public function postCreate() {

		$name = Input::get('name');
		$email = Input::get('email');
		$town_id = Input::get('town');
		$tp = Input::get('tp');

		Session::put('name', $name);
		Session::put('email', $email);
		Session::put('town_id', $town_id);
		Session::put('tp', $tp);
	}
}