<?php

class SpecialtyController extends BaseController{
	
	public function __construct(){
		$this->beforeFilter('csfr', array('on'=>'post'));
	}

	//views the create page
	public function getCreate(){
		return View::make('admin.specialty.add');
	}

	//create function
	public function postCreate(){
		$validator = Validator::make(Input::all(), Specialty::$rules);
		if($validator->passes()){
			$name = Input::get('name');
			$rec = DB::table('specialties')->where('name', '=', $name)->first();
			if(!$rec){
				$specialty = new Specialty;
				$specialty->name = $name;
				$specialty->save();

				return Redirect::to('admin/specialty/index')
					->with('message', 'New Specialty has been added successfully');
			}

			return Redirect::to('admin/specialty/create')
					->with('message', 'The specialty already exists. Please try with different name');
		}

		return Redirect::to('admin/specialty/create')
				->with('message', 'Following errors occured')
				->withErrors($validator);
	}

	//view all specialties
	public function getIndex(){
		return View::make('admin.specialty.index')
				->with('specialties', Specialty::all());
	}

	//delete function
	public function postDestroy(){
		$rec = Specialty::find(Input::get('id'));
		if($rec){
			$rec->delete();

			return Redirect::to('admin/specialty/index')
						->with('message', 'Specialty has been deleted successfully');		
		}

		return Redirect::to('admin/specialty/index')
					->with('message', 'Something went wrong. Please try again');
	}
} 