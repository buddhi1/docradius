<?php

class HospitalController extends BaseController{

	public function __construct(){
		$this->beforeFilter('csrf', array('on'=>'post'));
	}

	public function getDropdowns() {
	// populate LGA dropdown
		
		$id = Input::get('state_id');
		$lgas = Lga::where('state_id','=',$id)->get();
	
		return Response::json($lgas);
	}

	public function getTowndrop() {
	// populate Town dropdown
		
		$id = Input::get('lga_id');
		$towns = Town::where('lga_id','=',$id)->get();
	
		return Response::json($towns);
	}


	//displays the add page
	public function getCreate(){
		return View::make('admin.hospital.add')
					->with('states',['' => 'Select a State'] + State::lists('name', 'id'))
					->with('insurances', Insurance::all());
	}

	//create function
	public function postCreate(){
		$validator1 = Validator::make(array('name'=>Input::get('name'), 'address'=>Input::get('address'), 'street'=>Input::get('street'), 'town_id'=>Input::get('town_id'), 'insurances'=>Input::get('insurance')), Hospital::$rules);
		$validator2 = Validator::make(array('email'=>Input::get('email'), 'password'=>Input::get('password')), User::$rules);
		$active = Input::get('active');


		if($validator2->passes()){
			if($validator1->passes()){
				$user = new User;
				$user->email = Input::get('email');
				$user->password = Hash::make(Input::get('password'));
				$user->type = 4;
				$user->active = 0;
				if($active == 1){
					$user->active =1;
				}
				$user->save();
				if($user){
					$hospital = new Hospital;
					$hospital->name = Input::get('name');
					$hospital->insurances = json_encode(Input::get('insurance'));
					$hospital->address = Input::get('address').', '.Input::get('street');
					$hospital->town_id = Input::get('town_id');
					$hospital->user_id = $user->id;
					$hospital->active = 0;
					if($active ==1){
						$hospital->active = 1;
					}
					$hospital->save();

					return Redirect::to('admin/hospital/create')
								->with('message', 'The hospital has been added successfully');
				}
				return Redirect::to('admin/hospital/create')
							->with('message', 'Something went wrong. Please try again');
			}
			return Redirect::to('admin/hospital/create')
					->with('message', 'Following errors occurred')
					->withErrors($validator1)
					->withInput();
		}
		return Redirect::to('admin/hospital/create')
					->with('message', 'Following errors occurred')
					->withErrors($validator2)
					->withInput();
	}

	//dispplay index page
	public function getIndex(){
		$hospitals = DB::table('hospitals')
						->leftJoin('users', 'users.id', '=', 'user_id')
						->leftJoin('towns', 'towns.id', '=', 'town_id')
						->select('hospitals.id as id', 'hospitals.name as name', 'address', 'towns.name as town', 'email', 'hospitals.active as active')
						->paginate(10);

		return  View::make('admin.hospital.index')
					->with('hospitals', $hospitals);
	}

	//diplays the edit page
	public function postEdit(){
		$hospital = Hospital::find(Input::get('id'));
		if($hospital){
			$user = User::find($hospital->user_id);
			if($user){
				$town = Town::find($hospital->town_id);
				$lga = Lga::find($town->lga_id);

				return View::make('admin.hospital.edit')
							->with('hospital', $hospital)
							->with('state_sel', $lga->state_id)
							->with('lga_sel', $lga->id)
							->with('user', $user)
							->with('states',['' => 'Select a State'] + State::lists('name', 'id'))
							->with('insurances', Insurance::all());
			}
		}
	}
}