<?php

class InsuranceController extends BaseController{

	public function __construct(){
		$this->beforeFilter('csrf', array('on'=>'post'));
		$this->beforeFilter('admin');
	}

	//displays add page
	public function getCreate(){
		return View::make('admin.insurance.add');
	}

	//create function
	public function postCreate(){
		$validator = Validator::make(Input::all(), Insurance::$rules);

		if($validator->passes()){
			$insurance = new Insurance;
			$insurance->name = Input::get('name');
			$insurance->save();

			return Redirect::to('admin/insurance')
					->with('message', 'The new insurance is added successfully');
		}
		return Redirect::to('admin/insurance/create')
					->with('message', 'Following errors occured')
					->withErrors($validator)
					->withInput();
	}

	//displays all insurances
	public function getIndex(){
		return View::make('admin.insurance.index')
					->with('insurances', Insurance::paginate(10));
	}

	//displays the edit page
	public function postEdit(){
		$insurance = Insurance::find(Input::get('id'));
		if($insurance){
			return View::make('admin.insurance.edit')
						->with('insurance', $insurance);
		}
		return Redirect::to('admin/insurance')
					->with('message', 'Something went wrong');
	}

	//save edited changes
	public function postUpdate(){
		$insurance = Insurance::find(Input::get('id'));
		if($insurance){
			$validator = Validator::make(Input::all(), Insurance::$rules);

			if($validator->passes()){
				$insurance->name = Input::get('name');
				$insurance->save();

				return Redirect::to('admin/insurance')
						->with('message', 'The new insurance is added successfully');
			}
			return Redirect::to('admin/insurance')
					->with('message', 'Following errors occured')
					->withErrors($validator);
		}
		return Redirect::to('admin/insurance')
					->with('message', 'Something went wrong. Please try again.');
	}

	//delete insurance
	public function postDestroy(){
		$insurance = Insurance::find(Input::get('id'));
		if($insurance){
			$insurance->delete();

			return Redirect::to('admin/insurance');
		}
		return Redirect::to('admin/insurance')
					->with('message', 'Something went wrong. Please try again.');
	}
}