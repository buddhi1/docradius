<?php

class LgaControllerRes extends \BaseController {
	public function __construct() {
		$this->beforeFilter('csrf', array('on' => 'post'));
		$this->beforeFilter('admin');
	}
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		// display all the LGAs

		// return View::make('admin.lga.view')
		// 	->with('lgas', Lga::all())
		// 	->with('states', State::lists('name', 'id'));

		$state_id  = Input::get('state_id');
		
		if( isset($state_id) ){
			$lgas = Lga::where('state_id','=',$state_id)->get();
		}else{
			$lgas = Lga::all();
		}		

		return Response::json([
			'status' => 200,
			'message' => 'list of lgas',
			'data' => [
				'state_id' => $state_id,
				'lgas' => $lgas,
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

				// return Redirect::To('admin/lga')
				// 	->with('lgas', Lga::all())
				// 	->with('message', 'LGA Successfully Created');
				return Response::json([
					'status' => 200,
					'message' => 'lga created Successfully',
					'data' => [
						'lga' => $lga,
					],
				]);
			}
			
			// return Redirect::To('admin/lga')
			// 		->with('lgas', Lga::all())
			// 		->withErrors($validator)
			// 		->withInput();
			return Response::json([
				'status' => 401,
				'error' => 'request denied, validation failed',
				'data' => [
					'validation' => $validator->errors(),
				],
				'route' => 'lga/create'
			],401);
		}
		return Response::json([
				'status' => 401,
				'error' => 'request denied, lga already exist',
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
		$lga = Lga::find($id);
		if($lga){
			return Response::json([
					'status' => 200,
					'message' => 'lga details',
					'data' => [
						'lga' => $lga,
					],
				]);
		}
		return Response::json([
				'status' => 401,
				'error' => 'request denied, data not found',
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
		$lga = Lga::find($id);
		
		if($lga){
			$name = Input::get('name');
			
			$validator = Validator::make(Input::all(), Lga::$rules);
			if($validator->passes()) {
					$lga->name = Input::get('name');
				$lga->save();

				return Response::json([
					'status' => 200,
					'message' => 'lga data',
					'data' => [
						'lga' => $lga,
					],
				]);
			}
			return Response::json([
				'status' => 401,
				'error' => 'request denied, validation failed',
				'data' => [
					'validation' => $validator->errors(),
				],
				'route' => 'lga'
			],401);
		}
		return Response::json([
				'status' => 401,
				'error' => 'request denied, data not found',
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

		// ############################################# //
		/*
			funcion deletes an lga if no towns are present under it
			function warns and deny deletions if towns are present under the lga
			if force is set true in the request, function deletes the lga and all its towns
		*/
		// ############################################# //
		// delete a LGA
		$force = Input::get('force');

		$lga = Lga::find($id);

		if($lga) {

			$towns = DB::table('towns')->where('lga_id', '=', $lga->id)->get();

			if($towns) {
				if($force){
					$towns->destroy();
					$lga->delete();
					return Response::json([
						'status' => 200,
						'message' => 'lga force deleted with towns',
						'data' => [
							'lga' => $lga,
							'towns' => $towns
						],
					]);
				}
				return Response::json([
					'status' => 401,
					'error' => 'request denied, cannot delete lga, has towns',
				],401);
			}

			$lga->delete();

			return Response::json([
				'status' => 200,
				'message' => 'lga deleted, no towns',
			]);
		}

		return Response::json([
				'status' => 401,
				'error' => 'request denied, data not found',
			],401);
	}


}
