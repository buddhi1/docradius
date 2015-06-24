<?php

class UserController extends BaseController {

	public function __construct() {
		$this->beforeFilter('csrf', array('on' => 'post'));
		$this->beforeFilter('user', array('only'=>array('postUpdateaccountsettings')));
		$this->beforeFilter('admin', array('except'=>array('getEditaccountsettings', 'postUpdateaccountsettings')));
	}

	//views the create user blade
	public function getCreate(){
		// ------------ front end route ----------//
		return View::make('admin.user.add');
	}

	


	
	//user edit page
	public function postEdit(){
		//views the edit page with all the details of the selected user
		return View::make('admin.user.edit')
					->with('user', User::find(Input::get('id')));		
	}

	

	//views account edit page
	public function editAccount(){
		$doctor = Doctor::find(Input::get('id'));
		if($doctor){
			$user = User::find($doctor->user_id);
			
			if($user){
				return View::make('member.editAccount')
					->with('user', $user)
					->with('type', 1);
			}			
		}

		return Redirect::to('admin/doctor/index')
				->with('message', 'Something went wrong. Please try again');
	}


	public function updateaccountsettings() {
		// save the account chages made
		
		$id = Input::get('id');
		$curr_pass = Input::get('password');
		$new_pass = Input::get('np');
		$confirm_pass = Input::get('cp');
		$email = Input::get('email');

		$user = User::find($id);

		if($user) {
			
			if($curr_pass || $user->email !== $email) {

				if($curr_pass) {

					if(Hash::check($curr_pass, $user->password )){

						if(($new_pass === $confirm_pass) && $new_pass) {

							$user->password = Hash::make($new_pass);
						} else {

							if(Auth::user()->type == 1){

								return Redirect::to('admin')
									->with('message', 'Password mismatched');
							}

							return Redirect::to('member/index')
								->with('message', 'Password mismatched');
						}
					} else {

						if(Auth::user()->type == 1){

							return Redirect::to('admin')
								->with('message', 'Current Password is Incorrect');
						}

						return Redirect::to('member/index')
							->with('message', 'Current Password is Incorrect');
					}
				}
			
				if($email !== $user->email) {

					$validator_user = Validator::make(array('email' => $email), User::$rules_patient);
					if($validator_user->passes()) {

						$user->email = $email;
						$code = str_random(60);
						$user->code = $code;
						$user->active = 0;

						if($user->type == 1) {
							$name = substr($email, 0, strpos($email, '@'));
						} else if($user->type == 2) {
							$name = Doctor::where('user_id', '=', $id)->pluck('name');
						} else if($user->type == 3) {
							$name = Patient::where('user_id', '=', $id)->pluck('name');
						}

						Mail::send('emails.auth.activate', array('name'=>$name, 'link'=>URL::route('account-activate',$code)), function($message) use ($user) {

							$message->to($user->email, 'Pulasthi')->subject('Activate Your Account');
						});
					} else {

						if(Auth::user()->type == 1){

							return Redirect::to('admin')
								->withErrors($validator_user)
								->withInput();
						}

						return Redirect::To('member/index')
							->withErrors($validator_user)
							->withInput();
					}	
				}

				if($user->save()) {
					if(Auth::user()->type == 1){
						return Redirect::to('admin')
								->with('message', 'Account Settings has been Updated');
					}

					return Redirect::to('member/index')
						->with('message', 'Account Settings has been Updated');
				}
			}

			if(Auth::user()->type == 1){
				return Redirect::to('admin')
						->with('message', 'Nothing has been Changed');
			}

			return Redirect::to('member/index')
				->with('message', 'Nothing has been Changed');
		}	
	}

	//display the account edit page for a admin
	public function getEditaccountsettings(){

		$admin = User::find(Auth::user()->id);
		
		if($admin){			
			return View::make('member.editaccount')
				->with('user', $admin)
				->with('type', 0);				
		}

		return Redirect::to('admin/index')
				->with('message', 'Something went wrong. Please try again');
	}

	//
	public function index(){
		return View::make('admin.layouts.main');
	}
}