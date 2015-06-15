<?php

class LgaController extends BaseController{

	public function __construct() {
		$this->beforeFilter('csrf', array('on' => 'post'));
		$this->beforeFilter('admin');
	}

	public function getIndex() {
	// display all the LGAs

		return View::make('admin.lga.view')
			->with('lgas', Lga::all())
			->with('states', State::lists('name', 'id'));
	}

	public function postCreate() {
	// create a new LGA

		$name = Input::get('name');
		$state_id = Input::get('state');

		$lga_name = DB::table('lgas')->where('name', $name)->first();

		if(!$lga_name) {

			$validator = Validator::make(Input::all(), Lga::$rules);
			if($validator->passes()) {

				$lga = new Lga();
				$lga->name = $name;
				$lga->state_id = $state_id;
				$lga->save();

				return Redirect::To('admin/lga')
					->with('lgas', Lga::all())
					->with('message', 'LGA Successfully Created');
			}
			
			return Redirect::To('admin/lga')
					->with('lgas', Lga::all())
					->withErrors($validator)
					->withInput();
		}

		return Redirect::To('admin/lga')
			->with('message', 'LGA name Already Exists');
	}

	public function postDestroy() {
	// delete a LGA

		$id = Input::get('id');

		$lga = Lga::find($id);

		if($lga) {

			$towns = DB::table('towns')->where('lga_id', '=', $lga->id)->get();

			if($towns) {

				return View::make('admin.lga.delete')
					->with('towns', $towns);
			}

			$lga->delete();

			return Redirect::To('admin/lga')
				->with('message', 'LGA Successfully Deleted');
		}

		return Redirect::To('admin/lga')
			->with('message', 'Cannot Delete the LGA');
	}

	public function postDestroyall() {
	// delete a LGA along with the towns inside that LGA

		$id = Input::get('id');

		$lga = Lga::find($id);

		if($lga) {

			while ($dat = DB::table('towns')->where('lga_id', '=', $lga->id)->first()) {
				$town = Town::find($dat->id);
				$town->delete();
			}

			$lga->delete();

			return Redirect::To('admin/lga')
				->with('message', 'LGA Successfully Deleted');
		}

		return Redirect::To('admin/lga')
			->with('message', 'Cannot Delete the LGA');
	}
}