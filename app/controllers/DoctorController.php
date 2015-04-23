<?php

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
				$validator = Validator::make(array('name'=> Input::get('name'), 'hospitals'=> Input::get('hospitals'), 'specilties'=> Input::get('specilties'), 'town_id'=> Input::get('town_id')), Doctor::$rules);
				if($validator->passes()){
					$user = new User;
					$user->email = $email;
					$user->password = Hash::make(Input::get('password'));
					$user->save();

					$doctor = new Doctor;
					$doctor->name = Input::get('name');
					$doctor->description = Input::get('description');
					$doctor->experience = Input::get('experience');
					$doctor->tp = Input::get('tp');
					$doctor->special_popup = json_encode(Input::get('special_popup'));	
					$doctor->user_id = $user->id;	
					$doctor->specialties = Input::get('special');			
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
		
}