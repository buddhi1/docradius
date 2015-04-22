<?php

class StateController extends BaseController{

	public function __construct() {
		$this->beforeFilter('csrf', array('on' => 'post'));
	}

	public function getIndex() {
	// display all the states

		return View::make('admin.state.view')
			->with('states', State::all());
	}

	public function postCreate() {
	// create a new state

		$name = Input::get('name');

		$state_name = DB::table('states')->where('name', $name)->first();

		if(!$state_name) {

			$validator = Validator::make(Input::all(), State::$rules);
			if($validator->passes()) {

				$state = new State();
				$state->name = $name;
				$state->save();

				return Redirect::To('admin/state')
					->with('states', State::all())
					->with('message', 'State Successfully Created');
			}
			
			return Redirect::To('admin/state')
					->with('states', State::all())
					->withErrors($validator)
					->withInput();
		}

		return Redirect::To('admin/state')
			->with('message', 'State name Already Exists');
	}

	public function postDestroy() {
	// delete a State

		$id = Input::get('id');
		$all_towns = array();
		$all_lgas = array();

		$state = State::find($id);

		if($state) {

			$lgas = DB::table('lgas')->where('state_id', '=', $state->id)->get();

			if($lgas) {

				foreach ($lgas as $lga) {

					$all_lgas[] = $lga->name;

					$towns = DB::table('towns')->where('lga_id', '=', $lga->id)->get();

					if($towns) {

						foreach ($towns as $town) {

							$all_towns[$lga->name][] = $town->name;
						}
					}
				}

				return View::make('admin.state.delete')
					->with('all_lgas', $all_lgas)
					->with('state_id', $state->id);
			}

			$state->delete();

			return Redirect::To('admin/state')
				->with('message', 'State Successfully Deleted');
		}

		return Redirect::To('admin/state')
			->with('message', 'Cannot Delete the State');
	}

	public function postDestroyall() {
	// delete all LGA

		$id = Input::get('id');

		$state = State::find($id);

		if($state) {

			while($lga_dat = DB::table('lgas')->where('state_id', '=', $state->id)->first()) {
				$lga = Lga::find($lga_dat->id);

				if($lga) {

					while($town_dat = DB::table('towns')->where('lga_id', '=', $lga->id)->first()) {
						$town = Town::find($town_dat->id);

						if($town) {

							$town->delete();
						}
					}
					$lga->delete();
				}
			}

			$state->delete();

			return Redirect::To('admin/state')
				->with('message', 'State Successfully Deleted');
		}

		return Redirect::To('admin/state')
			->with('message', 'Cannot Delete the State');
	}
}