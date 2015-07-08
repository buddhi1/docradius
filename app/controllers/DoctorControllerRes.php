<?php

class DoctorControllerRes extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */

	public function __construct(){
		$this->beforeFilter('csrf', array('on'=>'post'));
		$this->beforeFilter('adm_doc', array('only'=>array('Update', 'edit')));	
		$this->beforeFilter('admin', array('only'=>array('index', 'destroy')));
		$this->beforeFilter('receptionist', array('only'=>array('create', 'store')));
	}

	public function index()
	{
		$doctors = Doctor::all();

		return Response::json([
				'status' => 200,
				'message' => 'state list',
				'data' => [
					'states' => $doctors,
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
			function is used to create doctors
			
			creates doctors and sends created doctor as response
			-	if validator fails, validation erros are send back
		*/
		// ########################################## //


		$validator_user = Validator::make(array('email'=> Input::get('email'), 'password'=> Input::get('password')), User::$rules);
		
		if($validator_user->passes()){
			$email = Input::get('email');
			$rec = DB::table('users')->where('email', '=', $email)->get();
			if(!$rec){

				$validator = Validator::make(array('name'=> Input::get('name'), 'specialties'=> Input::get('specialties'), 'reg_no'=> Input::get('reg_no')), Doctor::$rules);
				if($validator->passes()){
					$user = new User;
					$user->email = $email;
					$user->password = Hash::make(Input::get('password'));
					$user->type = 2;
					$user->active = 0;
					$user->save();

					$doctor = new Doctor;
					$doctor->name = Input::get('name');
					$doctor->description = Input::get('description');
					$doctor->experience = Input::get('experience');
					$doctor->tp = Input::get('tp');
					$doctor->special_popup = Input::get('special_popup');	
					$doctor->user_id = $user->id;
					$doctor->reg_no = Input::get('reg_no');
					$doctor->specialties = json_encode(explode(',', Input::get('specialties')));			
					$image_data = Input::get('image_data');
		 			if($image_data){
						$img_name = time().'.jpeg';
						$im = imagecreatefromjpeg($image_data);
						imagejpeg($im, 'uploads/profile_pictures/'.$img_name, 70);
						imagedestroy($im);
						$doctor->profile_picture = $img_name;
					}
					$doctor->save();

					if(!$doctor){
						$user->delete();
					}
					return Response::json([
						'status' => 200,
						'message' => 'new doctor created',
						'data' => [
								'doctor' => $doctor,
							],
					]);
				}
				return Response::json([
					'status' => 401,
					'error' => 'request denied, validation failed',
					'data' => [
						'validation' => $validator->errors(),
					],
					'route' => 'doctor/create'
				],401);	
				
			}	
			return Response::json([
				'status' => 401,
				'error' => 'request denied, eamil already exists',
			],401);				
		}
		return Response::json([
					'status' => 401,
					'error' => 'request denied, validation failed',
					'data' => [
						'validation' => $validator_user->errors(),
					],
					'route' => 'doctor/create'
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
		$doctor = Doctor::find($id);

		if($doctor){
			return Response::json([
				'status'=> 200,
				'message' => 'doctor data',
				'data' => [
					'doctor'=> $doctor
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
		$validator = Validator::make(Input::all(), Doctor::$rules);
		if($validator->passes()){
			$doctor = Doctor::find($id);
			$user = User::find($doctor->user_id);
			if($doctor){				
				if(Input::get('active') == 1){
					if($doctor->active != 1 ){
						$name = substr($user->email, 0, stripos($user->email, "@"));
						Mail::send('emails.auth.verify', array('name'=>$name), function($message) use ($user, $name) {
							$message->to($user->email, $name)->subject('Vefication notice');
						});
					}				

					$doctor->active = 1;
					$user->active = 1;
				}else{
					$doctor->active = 0;
					$user->active = 0;
				}
				$doctor->name = Input::get('name');
				$doctor->description = Input::get('description');
				$doctor->experience = Input::get('experience');
				$doctor->tp = Input::get('tp');
				$doctor->special_popup = Input::get('special_popup');
				$doctor->reg_no = Input::get('reg_no');
				$doctor->specialties = json_encode(explode(',', Input::get('specialties')));			
				$image_data = Input::get('image_data');
	 			if($image_data){
	 				$path = 'uploads/profile_pictures/'.$doctor->profile_picture;
	 				if(file_exists($path)){
						unlink($path);
					}
					$img_name = time().'.jpeg';
					$im = imagecreatefromjpeg($image_data);
					imagejpeg($im, 'uploads/profile_pictures/'.$img_name, 70);
					imagedestroy($im);
					$doctor->profile_picture = $img_name;
				}
				$doctor->save();
				$user->save();
				return Response::json([
						'status' => 200,
						'message' => 'doctor updated',
						'data' => [
								'doctor' => $doctor,
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
						'validation' => $validator->errors(),
					],
					'route' => 'doctor/create'
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
		$doctor = Doctor::find($id);
		if($doctor){
			$inactive = Inactive::where('doctor_id', '=', $doctor->id)->first();
			if($inactive){
				DB::table('inactives')->where('doctor_id', '=', $doctor->id)->delete();
			}
			$schedule = Schedule::where('doctor_id', '=', $doctor->id)->first();
			if($schedule){
				DB::table('schedules')->where('doctor_id', '=', $doctor->id)->delete();
			}
			
			$doctor->delete();
			$user = User::find($doctor->user_id);
			if($user){
				$job = Job::where('user_id', '=', $user->id)->first();
				if($job){
					DB::table('job')->where('user_id', '=', $user->id)->delete();
				}
				$user->delete();
			}
			$path = 'uploads/profile_pictures/'.$doctor->profile_picture;
			if(file_exists($path)){
				unlink($path);
			}
			return Response::json([
				'status'=> 200,
				'message' => 'doctor deleted',
			]);
		}

		return Response::json([
				'status' => 401,
				'error' => 'request denied, data not found',
			],401);
	}


}
