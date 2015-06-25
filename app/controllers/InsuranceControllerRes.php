<?php

class InsuranceControllerRes extends \BaseController {

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
		$insurances = Insurance::all();

		return Response::json([
				'status' => 200,
				'message' => 'insurances',
				'data' => [
						'insurances' => $insurances,
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
			function is used to create insurance
			
			creates insurance and sends created insurance as response
			-	if validator fails, validation erros are send back
		*/
		// ########################################## //


		$validator = Validator::make(Input::all(), Insurance::$rules);

		if($validator->passes()){
			$insurance = new Insurance;
			$insurance->name = Input::get('name');
			$insurance->save();

			return Response::json([
						'status' => 200,
						'message' => 'new insurance created',
						'data' => [
								'insurance' => $insurance,
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
		$insurance = Insurance::find($id);

		if($insurance){
			return Response::json([
				'status' => 200,
				'message' => 'insurance',
				'data' => [
						'insurance' => $insurance,
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
		$insurance = Insurance::find($id);
		if($insurance){
			$validator = Validator::make(Input::all(), Insurance::$rules);

			if($validator->passes()){
				$insurance->name = Input::get('name');
				$insurance->save();

				return Response::json([
						'status' => 200,
						'message' => 'insurance updated',
						'data' => [
								'insurance' => $insurance,
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
		return Response::json([
						'status' => 403,
						'message' => 'something went wrong',
						'data' => [
							],
					]);
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$insurance = Insurance::find($id);
		if($insurance){
			$insurance->delete();

			return Response::json([
						'status' => 200,
						'message' => 'insurance deleted',
						'data' => [
								'insurance' => $insurance,
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
