<?php

class AdvertisementController extends BaseController{
	public function __consturct(){
		$this->beforeFilter('csfr', array('on'=>'post'));
	}

	//views the create page
	public function getCreate(){
		return View::make('admin.advertisement.add');
	}

	//create function
	public function postCreate(){
		$validator = Validator::make(Input::all(), Advertisement::$rules);
		if($validator->passes()){
			$advert = new Advertisement;
			$advert->description = Input::get('description');
			$advert->doctor_id = 1;
			$advert->active = 0;
			$advert->save();

			return Redirect::to('admin/advert/index')
					->with('message', 'New advertisement added successfully');
		}

		return Redirect::to('admin/advert/create')
				->with('message', 'Following errors occured')
				->withErrors($validator);
	}

	//views all avdertisements
	public function getIndex(){		
		return View::make('admin.advertisement.index')
				->with('adverts', Advertisement::all())
				->with('type', 1);
	}

	//update advertisement state
	public function postUpdate(){
		$advert = Advertisement::find(Input::get('id'));
		if($advert){
			$state = Input::get('state');
			if($state == 0){
				$advert->active = 1;
			}else if($state == 1){
				$advert->active = 0;
			}
			$advert->save();

			return Redirect::to('admin/advert/index')
					->with('message', 'Advertisement activated successfully');
		}

		return Redirect::to('admin/advert/index')
				->with('message', 'Something went wrong. Please try again');
	}

	//delete advertisement
	public function postDestroy(){
		$advert = Advertisement::find(Input::get('id'));
		if($advert){
			$advert->delete();

			return Redirect::to('admin/advert/index')
					->with('message', 'Advertisement deleted successfully');
		}

		return Redirect::to('admin/advert/index')
				->with('message', 'Something went wrong. Please try again');
	}

	//viws the advertisement content
	public function postShow(){
		$advert = Advertisement::find(Input::get('id'));
		if($advert){
			return View::make('admin.advertisement.show')
					->with('advert', $advert);
		}

		return Redirect::to('admin/advert/index')
				->with('message', 'Something went wrong. Please try again');
	}

	//views user own advertisements
	public function allAdvertByUser(){
		$advert = DB::table('advertisements')->where('doctor_id', '=', 1)->get();

		return View::make('admin.advertisement.index')
				->with('advert', $advert);
	}
}