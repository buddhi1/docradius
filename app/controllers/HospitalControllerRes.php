<?php

class HospitalControllerRes extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */

	public function __construct(){
		$this->beforeFilter('csrf', array('on'=>'post'));
		$this->beforeFilter('admin');
	}
	
	public function index()
	{
		$hospitals = DB::table('hospitals')
						->leftJoin('users', 'users.id', '=', 'user_id')
						->leftJoin('towns', 'towns.id', '=', 'town_id')
						->select('hospitals.id as id', 'hospitals.name as name', 'address', 'towns.name as town', 'email', 'hospitals.active as active')
						->get();

		return Response::json([
				'status' => 200,
				'message' => 'hospitals',
				'data' => [
						'hospitals' => $hospitals,
					],
			]);
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		// ########################################## //
		/*
			function is used to create hospital
			
			creates hospital and sends created hospital as response
			-	if validator fails, validation erros are send back
		*/
		// ########################################## //


		//insuarance should be passed as a json array
		//hospital validator
		$validator1 = Validator::make(array('name'=>Input::get('name'), 'address'=>Input::get('address'), 'street'=>Input::get('street'), 'town_id'=>Input::get('town_id'), 'insurances'=>Input::get('insurance')), Hospital::$rules);
		//user validator
		$validator2 = Validator::make(array('email'=>Input::get('email'), 'password'=>Input::get('password')), User::$rules);
		$active = Input::get('active');


		//first validate user and then hospital
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

					return Response::json([
						'status' => 200,
						'message' => 'new hospital created',
						'data' => [
								'hospital' => $hospital,
								'receptionist'=> $user,
							],
					]);
				}
				return Response::json([
					'status' => 401,
					'error' => 'request denied, something went wrong',
				],401);
			}
			return Response::json([
				'status' => 401,
				'error' => 'request denied, validation failed',
				'data' => [
					'validation' => $validator1->errors(),
				],
				'route' => 'hospital'
			],401);	
		}
		return Response::json([
				'status' => 401,
				'error' => 'request denied, validation failed',
				'data' => [
					'validation' => $validator2->errors(),
				],
				'route' => 'hospital'
			],401);	
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$hospital = Hospital::find($id);

		if($hospital){
			return Response::json([
				'status' => 200,
				'message' => 'hospital data',
				'data' => [
					'hospital' => $hospital,
				],
			]);	
		}
		return Response::json([
				'status' => 401,
				'error' => 'request denied, no data found',
			],401);
		
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$hospital = Hospital::find($id);

		if($hospital){

			$validator1 = Validator::make(array('name'=>Input::get('name'), 'address'=>Input::get('address'), 'street'=>Input::get('street'), 'town_id'=>Input::get('town_id'), 'insurances'=>Input::get('insurance')), Hospital::$rules);
			
			$active = Input::get('active');


			if(Input::get('email')){
				if($validator1->passes()){
					$user = User::find($hospital->user_id);
					
					$user->email = Input::get('email');
					$pass = Hash::make(Input::get('password'));
					if(!Hash::check($user->password, $pass) && Input::get('password') !== ""){
						$user->password = $pass;
					}
					
					$user->type = 4;
					$user->active = 0;
					if($active == 1){
						$user->active =1;
					}
					$user->save();
					if($user){
						
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

						return Response::json([
							'status' => 400,
							'message' => 'hospital updated',
							'data' => [
									'hospital' => $hospital,
									'receptionist'=> $user,
								],
						]);
					}
					return Response::json([
						'status' => 401,
						'error' => 'request denied, something went wrong',
					],401);
				}
				return Response::json([
					'status' => 401,
					'error' => 'request denied, validation failed',
					'data' => [
						'validation' => $validator1->errors(),
					],
				],401);
			}
			return Response::json([
				'status' => 401,
				'error' => 'request denied, validation failed',
				'data' => [
					'validation' => 'email field is required',
				],
			],401);
		}
		return Response::json([
					'status' => 401,
					'error' => 'request denied, something went wrong',
				],401);		
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$hospital = Hospital::find($id);
		if($hospital){
			$user = User::find($hospital->user_id);
			$hospital->delete();
			if($user){
				$user->delete();
			}			
			return Response::json([
				'status' => 400,
				'message' => 'hospital deleted',
				'data' => [
						'hospital' => $hospital,
					],
			]);
		}
		return Response::json([
				'status' => 401,
				'error' => 'request denied, data not found',
			],401);
	}


}
