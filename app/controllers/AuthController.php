<?php

class AuthController extends BaseController{
	public function __construct(){
		$this->beforeFilter('csrf', array('on'=>'post'));
		$this->beforeFilter('guest', array('only'=>array('getLogin', 'postLogin')));
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
			
 			if (Auth::attempt(['email' => $email, 'password' => $password, 'active'=>1]))
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

	//member home page
	public function getIndex(){
		if(!Auth::check()){
			return Redirect::to('member/login');
		}
		return View::make('member.layouts.main');
	}
}