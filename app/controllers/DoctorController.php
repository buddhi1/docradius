<?php

class DoctorController extends BaseController{

	public function __construct(){
		$this->beforeFilter('csrf', array('on'=>'post'));
		$this->beforeFilter('adm_doc', array('only'=>array('postUpdate', 'getEdit', 'getEditaccountsettings')));	
		$this->beforeFilter('admin', array('only'=>array('getIndex', 'postDestroy')));
		$this->beforeFilter('receptionist', array('only'=>array('getCreate', 'postCreate')));
	}

	//views create page
	public function getCreate(){
		return View::make('member.doctor.add')
				->with('specialties', Specialty::lists('name', 'id'));
	}

	//create function
	public function postCreate(){		
		
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
					return Redirect::to('member/doctor/create')
							->with('message', 'New doctor has been created sucessfully');
				}

				return Redirect::to('member/doctor/create')
						->with('message', 'Following errors ocurred')
						->withErrors($validator)
						->withInput();
			}

			return Redirect::to('member/doctor/create')
					->with('message', 'The eamil already exists. Please try with different email')
					->withInput();
			
		}

		return Redirect::to('member/doctor/create')
					->with('message', 'Following errors ocurred')
					->withErrors($validator_user)
					->withInput();
	}

	//view all doctors
	// public function getIndex(){
	// 	return View::make('admin.doctor.index')
	// 			->with('doctors', Doctor::all());
	// }

	//view edit page for admin
	public function postEdit(){
		$doctor = Doctor::find(Input::get('id'));
		if($doctor){
			return View::make('member.doctor.edit')
					->with('doctor', $doctor)
					->with('specialties', Specialty::lists('name', 'id'));
		}

		return Redirect::to('admin/doctor/index')
				->with('message', 'Something went wrong. Please try again');
		
	}

	//view edit page for doctor
	public function getEdit(){
		$doc = Doctor::where('user_id', '=', Auth::id())->first();
		if($doc){
			return View::make('member.doctor.edit')
					->with('doctor', $doc)
					->with('specialties', Specialty::lists('name', 'id'));
		}

		return Redirect::to('/') //Set the redirect to member home
				->with('message', 'Something went wrong. Please try again.'); 
	}

	//update function
	public function postUpdate(){
		$validator = Validator::make(Input::all(), Doctor::$rules);
		if($validator->passes()){
			$doctor = Doctor::find(Input::get('id'));
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
				return Redirect::to('admin/doctor/index')
								->with('message', 'Doctor has been updated sucessfully');
			}

			return Redirect::to('admin/doctor/index')
					->with('message', 'Something went wrong. Please try again');
		}

		return Redirect::to('admin/doctor/index')
						->with('message', 'Following errors ocurred')
						->withErrors($validator)
						->withInput();
	}	

	//delete doctor fucntion
	public function postDestroy(){
		$doctor = Doctor::find(Input::get('id'));
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
			return Redirect::to('admin/doctor/index')
						->with('message', 'Doctor has been deleted sucessfully');
		}

		return Redirect::to('admin/doctor/index')
					->with('message', 'Something went wrong. Please try again');
	}

	public function getEditaccountsettings(){
		//display the account edit page for a doctor
		if(Auth::user()->type == 1){
			$doctor = Doctor::find(Input::get('id'));
		}else{
			$doctor = Doctor::where('user_id', '=', Auth::user()->id)->first();
		}
		
		
		if($doctor){
			$user = User::find($doctor->user_id);
			
			if($user){
				return View::make('member.editaccount')
					->with('user', $user)
					->with('type', 0);
			}			
		}

		if(Auth::user()->type == 1){
			return Redirect::to('admin')
				->with('message', 'Something went wrong. Please try again');
		}
		return Redirect::to('member/index')
				->with('message', 'Something went wrong. Please try again');
	}

	public function getAppointments() {
		// show the appointments searching page
		$apps = array();
		
		return View::make('member.doctor.appointments')
					->with('apps', $apps);;
	}

	public function postAppointments() {
		// List all the appointments

		$doc = Doctor::where('user_id','=',Auth::user()->id)->pluck('id');

		$apps =  DB::table('channels')
						->select('channels.time', 'channels.hospital', 'channels.patient_tp', 'patients.name', 'patients.sex', 'users.email')
						->join('patients', 'patients.id','=', 'channels.patient_id')
						->join('users', 'users.id', '=', 'patients.user_id')
						->where('doctor_id', $doc)
						->where('chanelling_date', '=', Input::get('app_date'))
						->get();
		return View::make('member.doctor.appointments')
					->with('apps', $apps);
	}

}