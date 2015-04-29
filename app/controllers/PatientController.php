<?php

class PatientController extends BaseController {

	public function __construct() {

		$this->beforeFilter('csrf', array('on' => 'post'));
	}

	public function getIndex() {
	// display all the Patients

		$patients = DB::table('patients')
						->join('users', 'users.id', '=', 'patients.user_id')
						->select('patients.id as id', 'name', 'email', 'tp', 'sex', 'town_id')
						->get();

		return View::make('member.patient.index')
			->with('patients', $patients);
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
		$sex = Input::get('sex');
		$tp = Input::get('tp');

		$validator_patient = Validator::make(array('name' => $name, 'town_id' => $town_id, 'sex' => $sex, 'tp' => $tp), Patient::$rules);
		if($validator_patient->passes()) {

			$validator_user = Validator::make(array('email' => $email), User::$rules_patient);

			if($validator_user->passes()) {

				Session::put('name', $name);
				Session::put('email', $email);
				Session::put('town_id', $town_id);
				Session::put('sex', $sex);
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
		$sex = Session::get('sex');
		$tp = Session::get('tp');
		$password = Hash::make(Input::get('password'));
		$type = 3;
		$code = str_random(60);

		$user = new User;
		$user->email = $email;
		$user->password = $password;
		$user->type = $type;
		$user->code = $code;
		$user->active = 0;


		if($user) {
			// first, inserting the record to the user table

			$validator = Validator::make(array('name' => $name, 'town_id' => $town_id, 'sex' => $sex, 'tp' => $tp), Patient::$rules);
			if($validator->passes()) {

				$user->save();

				$user_id = DB::table('users')->where('email',$email)->pluck('id');

				$patient = new Patient;
				$patient->name = $name;
				$patient->tp = $tp;
				$patient->town_id = $town_id;
				$patient->sex = $sex;
				$patient->user_id = $user_id;

				// then entering the record to the patient table

				$patient->save();
				Mail::send('emails.auth.activate', array('name'=>$name, 'link'=>URL::route('account-activate',$code)), function($message) use ($user) {

					$message->to($user->email, 'Pulasthi')->subject('Activate Your Account');
				});


				Session::flush();
				return Redirect::To('member/patient/create');
			}
			Session::flush();
			return Redirect::To('member/patient/create')
				->withErrors($validator)
				->withInput();
		}
	}

	public function getActivate($code) {

		$user = User::where('code','=',$code)->where('active','=',0);

		if($user->count()) {

			$user = $user->first();

			//update the active user
			$user->active = 1;
			$user->code = ' ';

			if($user->save()) {

				return Redirect::To('member/patient/create')
					->with('message', 'Successfully Activated the Account');
			}
		}

		return Redirect::To('member/patient/create')
			->with('message', 'Could not Activate the Account. Please Try Again Later');
	}

	public function postEditprofile() {

		$id = Input::get('p_id');
		$patient = Patient::find($id);
		$town_id = $patient->town_id;
		$towns = Town::find($town_id);
		$lga_id = Town::find($town_id)->lga_id;
		$lgas = Lga::find($lga_id);
		$towns_selected = Town::where('lga_id', '=', $lga_id)->lists('name', 'id');
		$state_id = Lga::find($lga_id)->state_id;
		$lgas_selected = Lga::where('state_id', '=', $state_id)->lists('name', 'id');

		if($patient) {

			return View::make('member.patient.editprofile')
				->with('patient',$patient)
				->with('town_id', $town_id)
				->with('lga_id', $lga_id)
				->with('state_id', $state_id)
				->with('states',['' => 'Select a State'] + State::lists('name', 'id'))
				->with('towns',$towns_selected)
				->with('lgas',$lgas_selected);
		}

		return Redirect::To('member/patient')
			->with('message', 'Error Occured');
	}

	public function postUpdateprofile() {

		$id = Input::get('id');
		$name = Input::get('name');
		$town_id = Input::get('town_id');
		$sex = Input::get('sex');
		$tp = Input::get('tp');

		$patient = Patient::find($id);

		if($patient) {

			$patient->name = $name;
			$patient->tp = $tp;
			$patient->town_id = $town_id;
			$patient->sex = $sex;

			$patient->save();

			return Redirect::To('member/patient')
				->with('message', 'Profile Has Been Updated');
		}

		return Redirect::To('member/patient')
			->with('message', 'Error Occured');
	}

	public function postDestroy() {

		$id = Input::get('id');

		$patient = Patient::find($id);
		$user = User::find($patient->user_id);

		if($patient) {

			$patient->delete();

			if($user) {

				$user->delete();

				return Redirect::To('member/patient')
					->with('message', 'Patient has been Deleted');
			}
		}
	}
}