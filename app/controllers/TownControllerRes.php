<?php

class TownControllerRes extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$lga_id = Input::get('lga_id');

		if(isset($lga_id)){
			$towns = Town::where('lga_id','=',$lga_id)->get();
		}else{
			$towns = Town::all();
		}

		return Response::json([
				'status' => 200,
				'message' => 'state list',
				'data' => [
					'lga'=> $lga_id,
					'towns'=> $towns,
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
			function is used to create towns
			
			creates town and sends created town as response
			-	if validator fails, validation erros are send back
		*/
		// ########################################## //


		$name = Input::get('name');
		$lga_id = Input::get('lga_id');

		
		$validator = Validator::make(Input::all(), Town::$rules);
		if($validator->passes()) {

			$town = new Town();
			$town->name = $name;
			$town->lga_id = $lga_id;
			$town->save();

			return Response::json([
						'status' => 200,
						'message' => 'new town created',
						'data' => [
								'admin' => $town,
							],
					]);

			// return Redirect::To('admin/town')
			// 	->with('towns', Town::all())
			// 	->with('message', 'Town Successfully Created');
		}
		return Response::json([
				'status' => 401,
				'error' => 'request denied, validation failed',
				'data' => [
					'validation' => $validator->errors(),
				],
				'route' => 'town/create'
			],401);
		// return Redirect::To('admin/town')
		// 		->with('towns', Town::all())
		// 		->withErrors($validator)
		// 		->withInput();
		
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$town = Town::find($id);

		if($town){
			return Response::json([
				'status' => 200,
				'message' => 'town data',
				'data' => [
					'town' => $town,
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
		$town = Town::find($id);
		if($town){
			$name = Input::get('name');
			$lga_id = Input::get('lga_id');
			
			$validator = Validator::make(Input::all(), Town::$rules);
			if($validator->passes()) {
				$town->name = Input::get('name');
				$town->save();

				return Response::json([
					'status' => 200,
					'message' => 'town data',
					'data' => [
						'town' => $town,
					],
				]);
			}
			return Response::json([
				'status' => 401,
				'error' => 'request denied, validation failed',
				'data' => [
					'validation' => $validator->errors(),
				],
				'route' => 'town'
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
		$town = Town::find($id);

		if($town) {

			$town->delete();
		
			return Response::json([
				'status'=> 200,
				'message' => 'town deleted successfully',
			]);
		}

		return Response::json([
				'status' => 401,
				'error' => 'request denied, data not found',
			],401);
	}


}
