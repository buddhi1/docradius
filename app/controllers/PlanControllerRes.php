<?php

class PlanControllerRes extends \BaseController {

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
		$plan = DB::table('insurance_plans')
					->leftJoin('insurances', 'insurances.id', '=', 'insurance_plans.insurance_id')
					->select('insurance_plans.id as id', 'insurance_plans.name as name', 'insurances.name as insurance')
					->get();


			return Response::json([
						'status' => 200,
						'message' => 'insurance plans',
						'data' => [
								'insurance_plans' => $plan,
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
			function is used to create insurance plan
			
			creates insurance plan and sends created insurance plan as response
			-	if validator fails, validation erros are send back
		*/
		// ########################################## //

		$validator = Validator::make(Input::all(), Plan::$rules);

		if($validator->passes()){
			$ins_plan = new Plan;
			$ins_plan->name = Input::get('name');
			$ins_plan->insurance_id = Input::get('insurance_id');
			$ins_plan->save();

			return Response::json([
						'status' => 200,
						'message' => 'new insurance plan created',
						'data' => [
								'insurance_plan' => $ins_plan,
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
		$plan = Plan::find($id);
		if($plan){
			return Response::json([
				'status' => 200,
				'message' => 'insurance plan',
				'data' => [
					'insurance_plan' => $plan,
				],
			]);
		}
		return Response::json([
				'status' => 400,
				'message' => 'no data found',
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
		$ins_plan = Plan::find($id);
		if($ins_plan){
			$validator = Validator::make(Input::all(), Plan::$rules);

			if($validator->passes()){
				$ins_plan->name = Input::get('name');
				$ins_plan->insurance_id = Input::get('insurance_id');
				$ins_plan->save();

				return Response::json([
						'status' => 200,
						'message' => 'insurance plan updated',
						'data' => [
								'insurance_plan' => $ins_plan,
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
		$plan = Plan::find($id);

		if($plan){
			$plan->delete();

			return Response::json([
						'status' => 200,
						'message' => 'insurance plan deleted',
						'data' => [
								'insurance_plan' => $plan,
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
