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
	// delete a state

		$id = Input::get('id');

		$state = State::find($id);

		if($state) {

			$state->delete();

			return Redirect::To('admin/state')
				->with('message', 'State Successfully Deleted');
		}

		return Redirect::To('admin/state')
			->with('message', 'Cannot Delete the State');
	}
}