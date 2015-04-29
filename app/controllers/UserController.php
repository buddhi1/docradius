<?php

class UserController extends BaseController {

	public function __construct() {
		$this->beforeFilter('csrf', array('on' => 'post'));
		$this->beforeFilter('guest', array('only'=>array('postUpdateaccountsettings')));
		$this->beforeFilter('admin', array('except'=>array('getEditaccountsettings', 'postUpdateaccountsettings')));
	}

	//views the create user blade
	public function getCreate(){
		
		return View::make('admin.user.add');
	}

	//user create function
	public function postCreate(){
		$validator = Validator::make(Input::all(), User::$rules);
		//validates for rules which are defined in the model
		if($validator->passes()){
			$email = Input::get('email');
			$rec = DB::table('users')->where('email', '=', $email)->get();
			//checks the email already exists or not 
			if(!$rec){
				$user = new User;
				$user->email = $email;
				$user->password = Hash::make(Input::get('password'));
				$user->type = 1;
				$user->active = 0;
				$user->save(); //saves the user record

				return Redirect::to('admin/user/index')
							->with('message', 'New user has been created successfully');
			}

			//returns whun the m=email already exists
			return Redirect::to('admin/user/create')
						->with('message', 'The user email already exitsts. Please enter a different email');
		}

		//returns if rules are been violated
		return Redirect::to('admin/user/create')
					->with('message', 'The following erros occured')
					->withErrors($validator);
	}

	//all user page
	public function getIndex(){
		//views the index page with availabale user details
		return View::make('admin.user.index')
					->with('users', User::where('type', '=', 1)->get());		
	}

	//user edit page
	public function postEdit(){
		//views the edit page with all the details of the selected user
		return View::make('admin.user.edit')
					->with('user', User::find(Input::get('id')));		
	}

	//user edit function
	public function postUpdate(){
		$user = User::find(Input::get('id'));
		if($user){
			$email = Input::get('email');
			if($email){			
				$password = Input::get('password');
				if($user->password != $password){
					$user->password = Hash::make($password);
				}
				$user->email = $email;
				$user->save();

				return Redirect::to('admin/user/index')
						->with('message', 'The user has been edited successfully');
			}

			//if no email is inserted
			return Redirect::to('admin/user/index')
					->with('message', 'Email field is required. Please try again');
		}

		//if invalid user id is sent, then redirect to index page
		return Redirect::to('admin/user/index')
				->with('message', 'Something went wrong. Please try again');	
	}

	//delete user
	public function postDestroy(){
		$user = User::find(Input::get('id'));
		if($user){
			$user->delete();

			return Redirect::to('admin/user/index')
					->with('message', 'The user deleted successfully');
		}

		//if invalid user id is sent, then redirect to index page
		return Redirect::to('admin/user/index')
				->with('message', 'Something went wrong. Please try again');	
	}

	//views account edit page
	public function postEditaccount(){
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


	public function postUpdateaccountsettings() {
		// save the account chages made

		//$id = Input::get('id');
		$curr_pass = Input::get('password');
		$new_pass = Input::get('np');
		$confirm_pass = Input::get('cp');
		$email = Input::get('email');

		$user = User::find(Auth::user()->id);

		if($user) {


			if($curr_pass && Auth::user()->email === $email) {

				if($curr_pass) {

					if(Hash::check($curr_pass, Auth::user()->password )){

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
			
				if($email !== Auth::user()->email) {

					$validator_user = Validator::make(array('email' => $email), User::$rules_patient);
					if($validator_user->passes()) {

						$user->email = $email;
						$code = str_random(60);
						$user->code = $code;
						$user->active = 0;
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
	public function editaccountsettings(){
	
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