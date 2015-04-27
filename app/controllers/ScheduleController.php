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
			/*			
			login should function to get these data

			$user_id = Auth::id();
			$logged_doc = DB::table('doctors')->where('user_id', '=', $user_id)->get();
			$doctor_id = $logged_doc->id;
			*/
			$doctor_id = 1; //remove this after uncommenting above section
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
		/*
		working for logged user only

		$user_id = Auth::id();
		*/
		$user_id = 1;
		$doc = DB::table('doctors')->where('user_id', '=', $user_id)->first();
		$schedule = DB::table('schedules')->where('doctor_id', '=', $doc->id)->get();
		return View::make('member.schedule.index')
				->with('schedules', $schedule);
	}

	//views edit page
	public function postEdit(){
		$schedule = Schedule::find(Input::get('id'));
		$logged_doc = DB::table('doctors')->where('user_id', '=', Auth::id());
		if($schedule && $logged_doc->user_id == $schedule->doctor_id){
			// typeeeeeeeeeeeeeeeee
		}

		return Redirect::to('member/schedule/index')
				->with('message', 'Something went wrong. Please try again');
	}
}