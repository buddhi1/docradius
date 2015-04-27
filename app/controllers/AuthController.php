<?php

class AuthController extends BaseController{
	public function __construct(){
		$this->beforeFilter('csrf', array('on'=>'post'));
	}

	//views login page
	public function getLogin(){
		return View::make('login');
	}

	//login function
	public function postLogin(){
		$validator = Validator::make(Input::all(), User::$login_rules);
		if($validator->passes()){
			$email = Input::get('email');
			$password = Input::get('password');
			
 			if (Auth::attempt(['email' => $email, 'password' => $password]))
			{
			    return Redirect::to('/');
			}

			return Redirect::to('member/login')
					->with('message', 'Invalid credentials. Please try again');
		}

		return Redirect::to('member/login')
			->with('message', 'Following errors occurred.')
			->withErrors($validator);
	}

	//logout 
	public function getLogout(){
		Auth::logout();
		return Redirect::to('member/login');
	}
}