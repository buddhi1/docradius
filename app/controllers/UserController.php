<?php

class UserController extends BaseController {

	public function __construct() {
		$this->beforeFilter('csrf', array('on' => 'post'));
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
				$user->password = Input::get('password');
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
					->with('users', User::all());		
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
					$user->password = $password;
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
}