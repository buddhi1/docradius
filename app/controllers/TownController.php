<?php

class TownController extends BaseController{

	public function __construct() {
		$this->beforeFilter('csrf', array('on' => 'post'));
	}

	public function getIndex() {
	// display all the Towns

		return View::make('admin.town.view')
			->with('town', Town::all())
			->with('lgas', Lga::lists('name', 'id'))
			->with('states', State::lists('name', 'id'));
	}

	public function postCreate() {
	// create a new Town

		$name = Input::get('name');
		$lga_id = Input::get('lgas');
		$town_id = Input::get('towns');

		$town_name = DB::table('towns')->where('name', $name)->first();

		if(!$town_name) {

			$validator = Validator::make(Input::all(), Town::$rules);
			if($validator->passes()) {

				$town = new Town();
				$town->name = $name;
				$town->lga_id = $lga_id;
				$town->town_id = $town_id;
				$town->save();

				return Redirect::To('admin/town')
					->with('towns', Town::all())
					->with('message', 'Town Successfully Created');
			}
			
			return Redirect::To('admin/town')
					->with('towns', Town::all())
					->withErrors($validator)
					->withInput();
		}

		return Redirect::To('admin/town')
			->with('message', 'Town name Already Exists');
	}

	public function postDestroy() {
	// delete a Town

		$id = Input::get('id');

		$town = Town::find($id);

		if($town) {

			$town->delete();

			return Redirect::To('admin/town')
				->with('message', 'Town Successfully Deleted');
		}

		return Redirect::To('admin/town')
			->with('message', 'Cannot Delete the Town');
	}
}