<?php

class StateControllerRes extends \BaseController {
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
		return Response::json([
				'status' => 200,
				'message' => 'state list',
				'data' => [
					'states' => State::all(),
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
			function is used to create states
			
			creates state and sends created state as response
			-	if validator fails, validation erros are send back
		*/
		// ########################################## //


		$name = Input::get('name');

			$validator = Validator::make(Input::all(), State::$rules);
			if($validator->passes()) {

				$state = new State();
				$state->name = $name;
				$state->save();

				return Response::json([
						'status' => 200,
						'message' => 'new state created',
						'data' => [
								'admin' => $state,
							],
					]);

				// return Redirect::To('admin/state')
				// 	->with('states', State::all())
				// 	->with('message', 'State Successfully Created');
			}
			
			// return Redirect::To('admin/state')
			// 		->with('states', State::all())
			// 		->withErrors($validator)
			// 		->withInput();
			return Response::json([
				'status' => 401,
				'error' => 'request denied, validation failed',
				'data' => [
					'validation' => $validator->errors(),
				],
				'route' => 'town/create'
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
		$state = State::find($id);

		if ($state) {
			return Response::json([
				'status' => 200,
				'message' => 'state data',
				'data' => [
					'state' => $state,
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
		$force = Input::get('force');
		$state = State::find($id);

		if($state){
			$lgas = DB::table('lgas')->where('state_id', '=', $state->id)->get();

			if(sizeof($lgas) > 0){
				if($force){
					DB::table('lgas')->where('state_id', '=', $state->id)->delete();
					$state->delete();
					return Response::json([
						'status'=> 200,
						'message' => 'state deleted successfully',
					]);
				}
				return Response::json([
						'status'=> 405,
						'message' => 'cannot delete state has LGAs',
					]);
				
			}
			$state->delete();
			return Response::json([
				'status'=> 200,
				'message' => 'state deleted successfully',
			]);
		}
		return Response::json([
				'status' => 401,
				'error' => 'request denied, data not found',
			],401);
	}


}
