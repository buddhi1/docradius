<?php

class ScheduleController extends BaseController{

	public function __construct(){
		$this->beforeFilter('csrf', array('on'=>'post'));
		//$this->beforeFilter('receptionist', array('except'=>array('')));
		//$this->beforeFilter('doctor', array('only'=>array('')));
	}

	
	//views schedule create page
	public function getCreate(){

		return View::make('member.schedule.add')
				->with('doctors', Doctor::lists('name', 'id'));
	}

	//create function
	public function postCreate(){
		$validator = Validator::make(Input::all(), Schedule::$rules);
		
		if($validator->passes()){
			$start_time = Input::get('start_time');
			$end_time = Input::get('end_time');
			$day = Input::get('day');
			
			$user_id = Auth::id();
			$hosp = DB::table('hospitals')->where('user_id', '=', $user_id)->first();
			$doctor_id = Input::get('doctor_id');

			$rec1 = DB::table('schedules')
				->where('start_time', '<', $start_time)
				->where('end_time', '>', $start_time)
				->where('doctor_id', '=', $doctor_id)
				->where('day', '=', $day)
				->first();
			$rec2 = DB::table('schedules')
				->where('start_time', '>', $start_time)
				->where('start_time', '<', $end_time)
				->where('doctor_id', '=', $doctor_id)
				->where('day', '=', $day)
				->first();
			$rec3 = DB::table('schedules')
				->where('end_time', '>', $start_time)
				->where('end_time', '<', $end_time)
				->where('doctor_id', '=', $doctor_id)
				->where('day', '=', $day)
				->first();
			$rec4 = DB::table('schedules')
				->where('start_time', '>=', $start_time)
				->where('end_time', '<=', $end_time)
				->where('doctor_id', '=', $doctor_id)
				->where('day', '=', $day)
				->first();
			
			if(!$rec1 && !$rec2 && !$rec3 && !$rec4){
				$schedule = new Schedule;
				$schedule->start_time = $start_time;
				$schedule->end_time = $end_time;
				$schedule->day = $day;
				$schedule->hospital = $hosp->id;
				$schedule->doctor_id = $doctor_id;
				$schedule->no_of_patients = Input::get('no_of_patients');
				$schedule->save();

				return Redirect::to('member/schedule/create')
						->with('message', 'The schedule has been added to timeline successfully');
			}

			return Redirect::to('member/schedule/create')
					->with('message', 'There are overlapping time slots. To enter required schedule please edit or delete the existing schedules');
		}

		return Redirect::to('member/schedule/create')
				->with('message', 'Following erros occurred')
				->withErrors($validator)
				->withInput();
	}

	//view schedule
	public function getIndex(){
		//need to be logged in as a hospital

		$user_id = Auth::id();
		
		$hosp = DB::table('hospitals')->where('user_id', '=', $user_id)->first();
		$schedule = DB::table('schedules')->where('hospital', '=', $hosp->id)->get();

		return View::make('member.schedule.index')
				->with('schedules', $schedule);
	}

	//views edit page
	public function postEdit(){

		$logged_hos = DB::table('hospitals')->where('user_id', '=', Auth::id())->first();		
		$schedule = DB::table('schedules')
						->join('doctors', 'doctors.id', '=', 'schedules.doctor_id')
						->where('schedules.id', '=', Input::get('id'))
						->where('schedules.doctor_id', '=', $logged_hos->id)
						->first();
		
		if($schedule){			
			// $lga = DB::table('towns')->where('id', '=', $schedule->town_id)->pluck('lga_id');
			// $state = DB::table('lgas')->where('id', '=', $lga)->pluck('state_id');
			// $lga_sel = DB::table('lgas')->where('state_id', '=', $state)->lists('name', 'id');
			// $town_sel = DB::table('towns')->where('lga_id', '=', $lga)->lists('name', 'id');

			return View::make('member.schedule.edit')
					->with('schedule', $schedule);
		}

		return Redirect::to('member/schedule/index')
				->with('message', 'Something went wrong. Please try again');
	}

	//update function
	public function postUpdate(){
		$schedule = Schedule::find(Input::get('id'));
		if($schedule){
			$validator = Validator::make(Input::all(), Schedule::$rules2);
			if($validator->passes()){
				$start_time = Input::get('start_time');
				$end_time = Input::get('end_time');
				$day = Input::get('day');
				
				$doctor_id = Input::get('doctor_id');
				// $doctor_id = $logged_doc->id;
				
				//$doctor_id = 1; //remove this after uncommenting above section
				$rec1 = DB::table('schedules')
					->where('start_time', '<', $start_time)
					->where('end_time', '>', $start_time)
					->where('doctor_id', '=', $doctor_id)
					->where('id', '<>', $schedule->id)
					->where('day', '=', $day)
					->first();
				$rec2 = DB::table('schedules')
					->where('start_time', '>', $start_time)
					->where('start_time', '<', $end_time)
					->where('doctor_id', '=', $doctor_id)
					->where('id', '<>', $schedule->id)
					->where('day', '=', $day)
					->first();
				$rec3 = DB::table('schedules')
					->where('end_time', '>', $start_time)
					->where('end_time', '<', $end_time)
					->where('doctor_id', '=', $doctor_id)
					->where('id', '<>', $schedule->id)
					->where('day', '=', $day)
					->first();
				$rec4 = DB::table('schedules')
					->where('start_time', '>=', $start_time)
					->where('end_time', '<=', $end_time)
					->where('doctor_id', '=', $doctor_id)
					->where('id', '<>', $schedule->id)
					->where('day', '=', $day)
					->first();

				if(!$rec1 && !$rec2 && !$rec3 && !$rec4){
					
					$schedule->start_time = $start_time;
					$schedule->end_time = $end_time;
					$schedule->day = $day;
					$schedule->no_of_patients = Input::get('no_of_patients');
					$schedule->save();

					return Redirect::to('member/schedule/index')
							->with('message', 'The schedule has been updated to timeline successfully');
				}
			}

			return Redirect::to('member/schedule/index')
				->with('message', 'Following erros occurred')
				->withErrors($validator)
				->withInput();
		}

		return Redirect::to('member/schedule/index')
				->with('message', 'Something went wrong. Please try again');
	}

	//delete schedule
	public function postDestroy(){
		$doc = DB::table('doctors')->where('user_id', '=', Auth::id())->pluck('id');		
		$schedule = DB::table('schedules')
						->where('id', '=', Input::get('id'))
						->where('doctor_id', '=', $doc)
						->first();
		if($schedule){
			$channels = DB::table('channels')
								->where('schedule_id', '=', $schedule->id)	
								->where('state', 0)		
								->get();
			

			if($channels == null){
				$schedule = Schedule::find($schedule->id);
				DB::table('inactives')->where('schedule_id', '=', $schedule->id)->delete();
				
				$schedule->delete();

				return Redirect::to('member/schedule/index')
					->with('message', 'Schedule deleted successfully');
			}
			
			$channel = DB::table('channels')->where('schedule_id', '=', $schedule->id)->where('state', '=', 0)->count();
			return View::make('member.schedule.forcedelete')
							->with('schedule', $schedule)
							->with('channels', $channel);
		}
	}

	//delete all function
	public function postDeleteall(){
		$doc  = DB::table('doctors')->where('user_id', '=', Auth::id())->first();
		
		$schedule = DB::table('schedules')
						->where('id', Input::get('id'))
						->where('doctor_id', '=', $doc->id)
						->first();
		$schedule = Schedule::find($schedule->id);
						
		if($schedule){
			while ( $channel = DB::table('channels')->where('schedule_id', '=', $schedule->id)->where('state', '=', 0)->first()) {
				$channel = Channel::find($channel->id);
				$patient  = Patient::find($channel->patient_id);
				$user = User::find($patient->user_id);
				$name = $patient->name;
				$doc_name = $doc->name;
				
				Mail::send('emails.auth.cancellation', array('name'=>$name, 'doc_name'=>$doc_name, 'channel'=>$channel), function($message) use ($user, $name) {
					$message->to($user->email, $name)->subject('Appoinment Cancellation');
				});
				$channel->state = 5;
				$channel->save(); 
			}
			DB::table('inactives')->where('schedule_id', '=', $schedule->id)->delete();
			
			$schedule->delete();

			return Redirect::to('member/schedule/index')
					->with('message', 'Schedule has been deleted successfully');
		}

		return Redirect::to('member/schedule/index')
				->with('message', 'Something went wrong. Please try again');
	}


}