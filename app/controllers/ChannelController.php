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
		$schedule = Schedule::where('doctor_id', '=', $id)->get();
		
		return View::make('channel.schedule')
			->with('schedules', $schedule);
	}
}