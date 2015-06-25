<?php

class JobControllerRes extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function __construct() {

		$this->beforeFilter('csrf', array('on' => 'post'));
		//$this->beforeFilter('adm_doc', array('except'=>array('postMakejobactive')));
		//$this->beforeFilter('doctor');
	}

	public function index()
	{
		if(Auth::user()->type == 2){

			$jobs = Job::where('user_id', '=', Auth::id())->get();


		}else if(Auth::user()->type == 1){
			$jobs = Job::all();
		}
		if(sizeOf($jobs) > 0){
			return Response::json([
				'status' => 200,
				'message' => 'health jobs',
				'data' => [
						'health_jobs' => $jobs,
					],
			]);
		}
		return Response::json([
				'status' => 404,
				'message' => 'no data found',
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
			function is used to create health jobs
			
			creates health jobs and sends created health jobs as response
			-	if validator fails, validation erros are send back
		*/
		// ########################################## //

		$validator = Validator::make(Input::all(), Job::$rules);
		if($validator->passes()){
			$title = Input::get('title');
			$des = Input::get('des');
			$email_admin = Input::get('email');
			$email = Auth::user()->email;
			$user_id = Auth::user()->id;
			
			$job = new Job;

			

				$job->title = $title;
				$job->description = $des;
				if(!$email_admin) {
					$job->email = $email;
				} else {
					$job->email = $email_admin;
				}
				$job->user_id = $user_id;
				if(Auth::user()->type === 1) {

					$job->active = 1;
				}

				if($job->save()) {

					return Response::json([
						'status' => 200,
						'message' => 'new health job created',
						'data' => [
								'health_job' => $job,
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
		//
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
		$job = Job::find($id);

		if($job) {

			$job->delete();
			return Response::json([
					'status' => 200,
					'message' => 'health job deleted',
					'data' => [
							'health_job' => $job,
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
