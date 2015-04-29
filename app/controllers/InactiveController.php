<?php

class InactiveController extends BaseController{
	public function __construct(){
		$this->beforeFilter('csrf', array('on'=>'post'));
		$this->beforeFilter('doctor');
	}

	//views inactive page
	public function getIndex(){
		
		$doc = Doctor::where('user_id', '=', Auth::id())->first();		
		$inactives = DB::table('inactives')
						->leftJoin('schedules', 'inactives.schedule_id', '=', 'schedules.id')
						->where('inactives.doctor_id', '=', $doc->id)
						->select('inactives.id as id','end_time', 'start_time','date', 'hospital', 'schedules.id as schedule_id')						
		        		->paginate(10);
		return View::make('member.inactive.index')
					->with('inactives', $inactives); 
	}

	//create function
	public function postCreate(){
		$date = Input::get('date');
		$schedule_id = Input::get('schedule');
		$doc = Doctor::where('user_id', '=', Auth::id())->first();	
		$rec = DB::table('inactives')
					->where('doctor_id', '=', $doc->id)
					->where('date', '=', $date)
					->where('schedule_id', '=', $schedule_id)
					->get();
		
		if(!$rec){
			$inactive = new Inactive;
			$inactive->date = $date;
			$inactive->doctor_id = $doc->id;
			$inactive->schedule_id = $schedule_id;
			$inactive->save();
			
			while($channel = DB::table('channels')->where('schedule_id', '=', $inactive->schedule_id)->where('state', '=', 0)->first()){
				$channel = Channel::find($channel->id);
				$patient  = Patient::find($channel->patient_id);
				$user = User::find($patient->user_id);
				$name = $patient->name;
				$doc_name = $doc->name;
				
				// Mail::send('emails.auth.cancellation', array('name'=>$name, 'doc_name'=>$doc_name, 'channel'=>$channel), function($message) use ($user, $name) {
				// 	$message->to($user->email, $name)->subject('Appoinment Cancellation');
				// });
				$channel->state = 5;
				$channel->save(); 
			}			
			

			return Redirect::to('member/calendar/index')
				->with('message', 'Cancellation added to calendar successfully');
		}	

		return Redirect::to('member/calendar/index')
				->with('message', 'The selected time slot is already a cancelled one');
	}	

	// populate schedule dropdown
	public function getScheduledropdown() {	
		$timestamp = strtotime(Input::get('date'));
		$day = date('D', $timestamp);
		$days = array('Sun'=>'0',
						'Mon'=>'1',
						'Thu'=>'2',
						'Wed'=>'3',
						'Thu'=>'4',
						'Fri'=>'5',
						'Sat'=>'6',);
		
		$schedules = Schedule::where('day','=',$days[$day])->get();
	
		return Response::json($schedules);
	}

	//make inactive timeslot a active one
	public function postDestroy(){
		$doc = Doctor::where('user_id', '=', Auth::id())->first();
		
		$inactive = DB::table('inactives')
						->where('id', '=', Input::get('id'))
						->where('doctor_id', '=', $doc->id)
						->first();
		
		if($inactive){
			$inactive = Inactive::find($inactive->id);
			$inactive->delete();

			return Redirect::to('member/calendar')
					->with('message', 'Activated the time slot successfully');
		}

		return Redirect::to('member/calendar')
				->with('message', 'Somethign went wrong. Please try again');
	}
}