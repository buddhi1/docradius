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
			->with('states',['' => 'Select a State'] + State::lists('name', 'id'));
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
		$town_id = Input::get('town_id');
		$tp = Input::get('tp');

		$validator_patient = Validator::make(array('name' => $name, 'town_id' => $town_id), Patient::$rules);
		if($validator_patient->passes()) {

			$validator_user = Validator::make(array('email' => $email), User::$rules_patient);

			if($validator_user->passes()) {
				Session::put('name', $name);
				Session::put('email', $email);
				Session::put('town_id', $town_id);
				Session::put('tp', $tp);

				return Redirect::To('member/patient/makeaccount');
			}
			return Redirect::To('member/patient/create')
				->withErrors($validator_user)
				->withInput();
		}
		return Redirect::To('member/patient/create')
			->withErrors($validator_patient)
			->withInput();
	}

	public function getMakeaccount() {

		return View::make('member.patient.account');
	}

	public function postMakeaccount() {

		$name = Session::get('name');
		$email = Session::get('email');
		$town_id = Session::get('town_id');
		$tp = Session::get('tp');
		$password = Hash::make(Input::get('password'));
		$type = 2;

		$user = new User;
		$user->email = $email;
		$user->password = $password;
		$user->type = $type;

		if($user) {
			// first, inserting the record to the user table

			$validator = Validator::make(array('name' => $name, 'town_id' => $town_id), Patient::$rules);
			if($validator->passes()) {

				$user->save();

				$user_id = DB::table('users')->where('email',$email)->pluck('id');

				$patient = new Patient;
				$patient->name = $name;
				$patient->tp = $tp;
				$patient->town_id = $town_id;
				$patient->user_id = $user_id;

				// then entering the record to the patient table

				$patient->save();
				Session::flush();
				return Redirect::To('member/patient/create');
			}
			Session::flush();
			return Redirect::To('member/patient/create')
				->withErrors($validator)
				->withInput();
		}
	}
}