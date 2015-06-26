<?php

class SpecialtyControllerRes extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */

	public function __construct() {
		$this->beforeFilter('csrf', array('on' => 'post'));
		$this->beforeFilter('admin');
	}
	
	public function index()
	{
		$specialty = Specialty::all();
		return Response::json([
				'status' => 200,
				'message' => 'specialties',
				'data' => [
						'specialty' => $specialty,
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
			function is used to create specialty
			
			creates specialty and sends created specialty as response
			-	if validator fails, validation erros are send back
		*/
		// ########################################## //

		$validator = Validator::make(Input::all(), Specialty::$rules);
		if($validator->passes()){
			$name = Input::get('name');
			$rec = DB::table('specialties')->where('name', '=', $name)->first();
			if(!$rec){
				$specialty = new Specialty;
				$specialty->name = $name;
				$specialty->save();

				return Response::json([
						'status' => 200,
						'message' => 'new specialty created',
						'data' => [
								'specialty' => $specialty,
							],
					]);
			}
			return Response::json([
						'status' => 403,
						'message' => 'specialty already exists',
						'data' => [
							],
					]);
		}

		return Response::json([
				'status' => 403,
				'message' => 'request denied, validation failed',
				'data' => [
					'validation' => $validator->errors(),
				],
			]);
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$specialty = Specialty::find($id);
		if($specialty){
			return Response::json([
				'status' => 200,
				'message' => 'Specialty',
				'data' =>[
					'specialty' => $specialty,
				],
			]);
		}
		return Response::json([
			'status' => 404,
			'message' => 'data not found',
		]);
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
		//
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$rec = Specialty::find($id);
		if($rec){
			$rec->delete();

			return Response::json([
				'status' => 200,
				'message' => 'specialty deleted',
				'data' => [
						'specialty' => $rec,
					],
			]);		
		}

		return Response::json([
						'status' => 403,
						'message' => 'something went wrong',
						'data' => [
							],
					]);
	}


}
