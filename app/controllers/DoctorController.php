<?php
/*  

********************** USE FILTERS APPROPRIATELY :-)

*/
class DoctorController extends BaseController{

	public function __construct(){
		$this->beforeFilter('csrf', array('on'=>'post'));
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

				$validator = Validator::make(array('name'=> Input::get('name'), 'specialties'=> Input::get('specialties')), Doctor::$rules);
				if($validator->passes()){
					$user = new User;
					$user->email = $email;
					$user->password = Hash::make(Input::get('password'));
					$user->type = 1;
					$user->active = 0;
					$user->save();

					$doctor = new Doctor;
					$doctor->name = Input::get('name');
					$doctor->description = Input::get('description');
					$doctor->experience = Input::get('experience');
					$doctor->tp = Input::get('tp');
					$doctor->special_popup = Input::get('special_popup');	
					$doctor->user_id = $user->id;	
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
	public function getIndex(){
		return View::make('admin.doctor.index')
				->with('doctors', Doctor::all());
	}

	//view edit page
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
			$doctor->delete();
			$user = User::find($doctor->user_id);
			if($user){
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
}