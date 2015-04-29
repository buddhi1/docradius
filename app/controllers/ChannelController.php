<?php

class ChannelController extends BaseController {

	public function __construct() {

		$this->beforeFilter('csrf', array('on' => 'post'));
	}

	public function getIndex() {
		// display the main page where the user will enter practitioner and the location to get started.

		return View::make('channel.search')
			->with('specialty', Specialty::all()->lists('name', 'id'));
	}

	public function getSearch() {
		// Search the database for the practitioner and the location

		$town_arr = array();

		$doc = Input::get('practitioner');
		$special = Specialty::find($doc)->pluck('name');
		$location = Input::get('location');
		$towns = DB::table('towns')
						->select('id')
						->where('name', 'LIKE', '%'.$location.'%')
						->get();

		foreach ($towns as $town) {
			$town_arr[] = $town->id;
		}

		$doctors = DB::table('doctors')
			->join('schedules', 'schedules.doctor_id', '=', 'doctors.id')
			->where('specialties', 'LIKE', '%'.$special.'%')
			->whereIn('schedules.town_id',$town_arr)
			->get();

		if($doctors) {

			return View::make('channel.doctor')
				->with('doctors', $doctors);
		}
		return Redirect::to('channel')
			->with('message', 'Could not find any doctors');
		
	}

	public function schedule($id) {
		// show the of a specific doctor
		$week_arr = array('0', '1', '2', '3', '4', '5', '6');

		$days = array();

		foreach ($week_arr as $week) {

			$days[] = DB::table('schedules')
						->select('id','start_time', 'end_time')
						->where('doctor_id', $id)
						->where('day', $week)
						->get();
		}
		
		return View::make('channel.schedule')
			->with('days', $days);
	}

	public function create($id) {

		$schedule = Schedule::find($id);

		Session::put('schedule_id', $schedule->id);
		Session::put('schedule_start_time', $schedule->start_time);
		Session::put('schedule_end_time', $schedule->end_time);
		Session::put('schedule_no_of_patients', $schedule->no_of_patients);
		Session::put('schedule_hospital', $schedule->hospital);
		Session::put('schedule_day', $schedule->day);
		Session::put('schedule_doctor_id', $schedule->doctor_id);
		Session::put('schedule_town_id', $schedule->town_id);

		return View::make('channel.patient')
			->with('states',['' => 'Select a State'] + State::lists('name', 'id'))
			->with('schedule_msg', 'You have booked from '.$schedule->start_time.' to '.$schedule->end_time);
	}

	public function postCreate() {

		$name = Input::get('name');
		$email = Input::get('email');
		$town_id = Input::get('town_id');
		$sex = Input::get('sex');
		$tp = Input::get('tp');

		$validator_patient = Validator::make(array('name' => $name, 'town_id' => $town_id, 'sex' => $sex, 'tp' => $tp), Patient::$rules);
		$id = Session::get('schedule_id');

		if($validator_patient->passes()) {

			$validator_user = Validator::make(array('email' => $email), User::$rules_patient);

			if($validator_user->passes()) {

				Session::put('name', $name);
				Session::put('email', $email);
				Session::put('town_id', $town_id);
				Session::put('sex', $sex);
				Session::put('tp', $tp);

				return Redirect::To('channel/makeaccount');
			}

			return Redirect::To('channel/schedule/create/'.$id)
				->withErrors($validator_user)
				->withInput();
		}
		return Redirect::To('channel/schedule/create/'.$id)
			->withErrors($validator_patient)
			->withInput();
	}

	public function getMakeaccount() {

		return View::make('channel.account');
	}

	public function postMakeaccount() {

		$id = Session::get('schedule_id');
		$name = Session::get('name');
		$email = Session::get('email');
		$town_id = Session::get('town_id');
		$sex = Session::get('sex');
		$tp = Session::get('tp');
		$password = Hash::make(Input::get('password'));
		$type = 2;
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
				return Redirect::To('channel');
			}
			
			return Redirect::To('channel/schedule/create/'.$id)
				->withErrors($validator)
				->withInput();
		}
	}
}