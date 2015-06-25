<?php

class ScheduleControllerRes extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function __construct(){
		$this->beforeFilter('csrf', array('on'=>'post'));
		$this->beforeFilter('receptionist');
	}

	public function index()
	{
		$user_id = Auth::id();
		
		$hosp = DB::table('hospitals')->where('user_id', '=', $user_id)->first();
		$schedule = DB::table('schedules')->where('hospital', '=', $hosp->id)->get();
		if($schedule){
			return Response::json([
				'status' => 200,
				'message' => 'schedules',
				'data' => [
					'schedules' => $schedule,
				],
			]);
		}

		return Response::json([
				'status' => 404,
				'message' => 'no data found',
			]);
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
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

				return Response::json([
						'status' => 400,
						'message' => 'new schedule created',
						'data' => [
								'schedule' => $schedule,
							],
					]);
			}
			return Response::json([
						'status' => 403,
						'message' => 'overlapping time slots',
						'data' => [
							],
					]);
			
		}

		return Response::json([
				'status' => 403,
				'message' => 'request denied, validation failed',
				'data' => [
					'validation' => $validator->errors(),
				],
			]);
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$schedule = Schedule::find($id);

		if($schedule){
			return Response::json([
				'status' => 200,
				'message' => 'schedule data',
				'data' => [
					'schedule' => $schedule,
				],
			]);
		}
		return Response::json([
				'status' => 404,
				'message' => 'data not found',
			]);
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$schedule = Schedule::find($id);
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

					return Response::json([
						'status' => 400,
						'message' => 'schedule updated',
						'data' => [
								'schedule' => $schedule,
							],
					]);
				}
			}

			return Response::json([
				'status' => 403,
				'message' => 'request denied, validation failed',
				'data' => [
					'validation' => $validator->errors(),
				],
			]);
		}
		return Response::json([
				'status' => 403,
				'message' => 'something went wrong',
			]);
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$force = Input::get('force');
		$hosp = DB::table('hospitals')->where('user_id', '=', Auth::id())->pluck('id');		
		$schedule = DB::table('schedules')
						->where('id', '=', $id)
						->where('hospital', '=', $hosp)
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

				return Response::json([
						'status' => 200,
						'message' => 'schedule deleted',
						'data' => [
								'schedule' => $schedule,
							],
					]);
			}

			if($force){
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

				return Response::json([
						'status' => 200,
						'message' => 'schedule deleted',
						'data' => [
								'schedule' => $schedule,
							],
					]);
			}
			DB::table('inactives')->where('schedule_id', '=', $schedule->id)->delete();
			
			$schedule->delete();
			}
			return Response::json([
					'status' => 405,
					'message' => 'cannot delete schedule. has channels'
				]);
			
			$channel = DB::table('channels')->where('schedule_id', '=', $schedule->id)->where('state', '=', 0)->count();
			return View::make('member.schedule.forcedelete')
							->with('schedule', $schedule)
							->with('channels', $channel);
		}

		return Response::json([
				'status' => 403,
				'message' => 'something went wrong',
			]);
	}


}
