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

	public function getSearchbyspeciality() {
		// Search the database for the practitioner and the location

		$town_arr = array();

		$doc = Input::get('practitioner');
		if($doc) {
			$special = Specialty::find($doc);
			if($special) {
				$special = $special->name;
			}
		} else {
			$special = null;
		}
		
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
						->where('doctors.specialties', 'LIKE', '%'.$special.'%')
						->where('active', 1)
						->whereIn('schedules.town_id',$town_arr)
						->groupBy('schedules.town_id', 'schedules.doctor_id')
						->get();

		if($doctors) {

			return View::make('channel.doctor')
				->with('doctors', $doctors);
		}
		return Redirect::to('channel')
			->with('message', 'Could not find any doctors');
	}

	public function getSearchbydoctor() {
		// Search the database for the doctor

		$doc = Input::get('doc');	//doctor name
		$location = Input::get('location'); // town name

		$town_arr = array();
		$schedules = array();

		if($doc) {

			$towns = DB::table('towns')
						->select('id')
						->where('name', 'LIKE', '%'.$location.'%')
						->get();

			foreach ($towns as $town) {

				$town_arr[] = $town->id;
			}
			
			$doctors = DB::table('doctors')
							->join('schedules', 'schedules.doctor_id', '=', 'doctors.id')
							->where('doctors.name', 'LIKE', '%'.$doc.'%')
							->where('active', 1)
							->whereIn('schedules.town_id',$town_arr)
							->groupBy('schedules.town_id', 'schedules.doctor_id')
							->get();

			//getting the schedules of the doctors
			foreach ($doctors as $key => $value) {
				
				$schedules[] = $this->schedule($value->doctor_id);
			}

			if($doctors) {

				return $schedules;
			}

			return 'No doctors found';
		} else {

			return null;
		}
	}

	//clinic search by  area
	public function getSearchclinicbyname(){

		
		$text = Input::get('clinic_name');	//searching clinic name
		
		$town = Input::get('town_name');	//searching town name

		if($text){		

			$inactives = DB::table('schedules')
						->leftJoin('doctors', 'schedules.doctor_id', '=', 'doctors.id')
						->leftJoin('towns', 'schedules.town_id', '=', 'towns.id')
						->leftJoin('inactives', 'inactives.schedule_id', '=', 'schedules.id')
						->where('hospital', 'LIKE', '%'.$text.'%')
						->where('towns.name', 'LIKE', '%'.$town.'%')
						->select('start_time', 'end_time', 'schedules.doctor_id', 'town_id', 'day', 'doctors.name', 'towns.name', 'date', 'hospital')
						->get();

			if(sizeOf($inactives) > 0){
				return $inactives;
			}
			return 'No match';
		}
	}

	public function schedule($id) {
		// show the schedule of a specific doctor
		$week_arr = array('0', '1', '2', '3', '4', '5', '6');

		$days = array();

		foreach ($week_arr as $week) {

			$days[] = DB::table('schedules')
						->leftJoin('inactives', 'inactives.schedule_id', '=', 'schedules.id')
						->select('schedules.id','schedules.start_time', 'schedules.end_time', 'date')
						->where('schedules.doctor_id', $id)
						->where('schedules.day', $week)
						->get();

		}
		// return $days;
		
		return View::make('channel.schedule')
			->with('days', $days);
	}

	public function create($id) {
		// put the schedule details into session variables

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
		// put patient's channeling details in session varibales

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
		//display the make account view

		return View::make('channel.account');
	}

	public function postMakeaccount() {
		// save user details

		$id = Session::get('schedule_id');
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
				$patient_id = DB::table('patients')
								->join('users', 'users.id', '=', 'patients.user_id')
								->where('users.email', $email)
								->pluck('patients.id');
				$channelling_date = '2015-04-29';	//find the channelling date
				$book_count = DB::table('channels')
								->having('chanelling_date', '=', $channelling_date)
								->having('schedule_id', '=', $id)
								->groupBy('chanelling_date','schedule_id')
								->count('id');

				$max_patients = Session::get('schedule_no_of_patients');
				$time_diff = strtotime(Session::get('schedule_end_time'))-strtotime(Session::get('schedule_start_time'));
				$time_per_patient = $time_diff/$max_patients;
				$booked_time = $time_per_patient*$book_count;
				$time_addition = strtotime(Session::get('schedule_start_time'))+$booked_time;
				$time = gmdate("H:i:s", $time_addition);

				$channel = new Channel;
				$channel->state = 0;
				$channel->time = $time;
				$channel->chanelling_date = $channelling_date;	//find the channelling date
				$channel->town_id = $town_id;
				$channel->hospital = Session::get('schedule_hospital');
				$channel->patient_tp = $tp;
				$channel->doctor_id = Session::get('schedule_doctor_id');
				$channel->patient_id = $patient_id;
				$channel->schedule_id = $id;

				$channel->save();

				Mail::send('emails.auth.activate', array('name'=>$name, 'link'=>URL::route('account-activate',$code)), function($message) use ($user) {

					$message->to($user->email, 'Pulasthi')->subject('Activate Your Account');
				});

				Session::flush();
				return Redirect::To('channel')
					->with('Your booking is completed');
			}

			return Redirect::To('channel/schedule/create/'.$id)
				->withErrors($validator)
				->withInput();
		}
	}

	public function getInactivedays() {
		// return the inactive days to the schedule

		$schedule_id = Input::get('schedule_id');
		$inactives = Inactive::where('schedule_id', '=', $schedule_id)->first();

		return $inactives->date;
	}

	public function getNumberexceed() {
		// check whether there is a slot available for a channeling

		$schedule_id = Input::get('schedule_id');
		$channelling_date = Input::get('channelling_date');

		$chk_active = Inactive::where('date', '=', $channelling_date)->first();
		if(!$chk_active) {

			//how many are alreay in the channels table in that schedule time on that date.
			$book_count = DB::table('channels')
							->having('chanelling_date', '=', $channelling_date)
							->having('schedule_id', '=', $schedule_id)
							->groupBy('chanelling_date','schedule_id')
							->count('id');

			$max_patients = Schedule::find($schedule_id)->no_of_patients;

			if($book_count + 1 < $max_patients) {

				return 'success';
			}

			return 'Max limit exceeded';
		}
		return 'This is an inactive date';
	}
}