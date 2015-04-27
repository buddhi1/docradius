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

		$doc = Input::get('practitioner');
		$location = Input::get('location');

		$doctors = DB::table('practitioners')
			->select('id')
			->where($specialties, 'LIKE', '%'.$doc.'%')
			->where(DB::raw('LOWER(name)'))
			->get();
	}
}