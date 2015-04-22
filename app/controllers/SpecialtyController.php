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

	//views the edit page
	public function postEdit(){
		$rec = Specialty::find(Input::get('id'));
		if($rec){
			return View::make('admin.specialty.edit')
				->with('specialty', $rec);
		}
		
		return Redirect::to('admin/specialty/index')
				->with('message', 'Something went wrong. Please try again');
	}

	//edit function
	public function postUpdate(){
		$name = Input::get('name');
		if($name){
			$specialty = Specialty::find(Input::get('id'));
			if($specialty){
				$specialty->name = $name;
				$specialty->save();

				return Redirect::to('admin/specialty/index')
						->with('message', 'Specialty has been edited successfully');
			}

			return Redirect::to('admin/specialty/index')
					->with('message', 'Something went wrong. Please try again');
		}

		return Redirect::to('admin/specialty/index')
				->with('message', 'Specialty name field is required');
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