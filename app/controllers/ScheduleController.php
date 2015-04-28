<?php

class ScheduleController extends BaseController{

	public function __construct(){
		$this->beforeFilter('csrf', array('on'=>'post'));
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

	//views schedule create page
	public function getCreate(){
		return View::make('member.schedule.add')
				->with('states',['' => 'Select a State'] + State::lists('name', 'id'));
	}

	//create function
	public function postCreate(){
		$validator = Validator::make(Input::all(), Schedule::$rules);
		if($validator->passes()){
			$start_time = Input::get('start_time');
			$end_time = Input::get('end_time');
			$day = Input::get('day');
			
			$user_id = Auth::id();
			$logged_doc = DB::table('doctors')->where('user_id', '=', $user_id)->first();
			$doctor_id = $logged_doc->id;
			
			//$doctor_id = 1; //remove this after uncommenting above section
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
				->where('end_time', '>=', $start_time)
				->where('end_time', '<=', $end_time)
				->where('doctor_id', '=', $doctor_id)
				->where('day', '=', $day)
				->first();
			
			if(!$rec1 && !$rec2 && !$rec3 && !$rec4){
				$schedule = new Schedule;
				$schedule->start_time = $start_time;
				$schedule->end_time = $end_time;
				$schedule->day = $day;
				$schedule->doctor_id = $doctor_id;
				$schedule->hospital = Input::get('hospital');
				$schedule->town_id = Input::get('town_id');
				$schedule->no_of_patients = Input::get('no_of_patients');
				$schedule->save();

				return Redirect::to('member/schedule/create')
						->with('message', 'The schedule has been added to timeline successfully');
			}

			return Redirect::to('member/schedule/create')
					->with('message', 'There are overlapping time slots. To enter required schedule please edit or delete the existing schedules');
		}
	}

	//view schedule
	public function getIndex(){
		$user_id = Auth::id();
		
		//$user_id = 1;
		$doc = DB::table('doctors')->where('user_id', '=', $user_id)->first();
		$schedule = DB::table('schedules')->where('doctor_id', '=', $doc->id)->get();

		return View::make('member.schedule.index')
				->with('schedules', $schedule);
	}

	//views edit page
	public function postEdit(){
		$logged_doc = DB::table('doctors')->where('user_id', '=', Auth::id())->first();		
		$schedule = DB::table('schedules')
						->where('id', '=', Input::get('id'))
						->where('doctor_id', '=', $logged_doc->id)
						->first();
		
		if($schedule){			
			$lga = DB::table('towns')->where('id', '=', $schedule->town_id)->pluck('lga_id');
			$state = DB::table('lgas')->where('id', '=', $lga)->pluck('state_id');
			$lga_sel = DB::table('lgas')->where('state_id', '=', $state)->lists('name', 'id');
			$town_sel = DB::table('towns')->where('lga_id', '=', $lga)->lists('name', 'id');

			return View::make('member.schedule.edit')
					->with('schedule', $schedule)
					->with('lga', $lga)
					->with('state', $state)
					->with('lga_sel', $lga_sel)
					->with('town_sel', $town_sel)
					->with('states',['' => 'Select a State'] + State::lists('name', 'id'));
		}

		return Redirect::to('member/schedule/index')
				->with('message', 'Something went wrong. Please try again');
	}

	//update function
	public function postUpdate(){
		$schedule = Schedule::find(Input::get('id'));
		if($schedule){
			$validator = Validator::make(Input::all(), Schedule::$rules);
			if($validator->passes()){
				$start_time = Input::get('start_time');
				$end_time = Input::get('end_time');
				$day = Input::get('day');
				
				$user_id = Auth::id();
				$logged_doc = DB::table('doctors')->where('user_id', '=', $user_id)->first();
				$doctor_id = $logged_doc->id;
				
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
					->where('end_time', '>=', $start_time)
					->where('end_time', '<=', $end_time)
					->where('doctor_id', '=', $doctor_id)
					->where('id', '<>', $schedule->id)
					->where('day', '=', $day)
					->first();
			
				if(!$rec1 && !$rec2 && !$rec3 && !$rec4){
					
					$schedule->start_time = $start_time;
					$schedule->end_time = $end_time;
					$schedule->day = $day;
					$schedule->doctor_id = $doctor_id;
					$schedule->hospital = Input::get('hospital');
					$schedule->town_id = Input::get('town_id');
					$schedule->no_of_patients = Input::get('no_of_patients');
					$schedule->save();

					return Redirect::to('member/schedule/index')
							->with('message', 'The schedule has been updated to timeline successfully');
				}
			}

			return Redirect::to('member/schedule/index')
				->with('message', 'Following erros occurred')
				->withErrors($validator);
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
			//delete form inactives--------------------------------------------
			/*
	
			DELEEEEEETEEEEEEEEEEEEEEEEEE inactives

			********************************************************/
			$schedule->delete();

			return Redirect::to('member/schedule/index')
					->with('message', 'Schedule has been deleted successfully');
		}

		return Redirect::to('member/schedule/index')
				->with('message', 'Something went wrong. Please try again');
	}


}