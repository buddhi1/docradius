<?php

class LgaController extends BaseController{

	public function __construct() {
		$this->beforeFilter('csrf', array('on' => 'post'));
		$this->beforeFilter('admin');
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