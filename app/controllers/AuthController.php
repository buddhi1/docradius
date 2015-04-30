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
			$segment = Request::segment(1);
			
			if($segment == 'admin'){
				if (Auth::attempt(['email' => $email, 'password' => $password, 'active'=>1, 'type'=>array('1')]))
				{
				    return Redirect::to('/');
				}

				return Redirect::to('admin/login')
					->with('message', 'Invalid credentials. Please try again');
			}elseif ($segment == 'login') {
				if (Auth::attempt(['email' => $email, 'password' => $password, 'active'=>1, 'type'=>array('2')]))
				{
				    return Redirect::to('/');
				}else if (Auth::attempt(['email' => $email, 'password' => $password, 'active'=>1, 'type'=>array('3')]))
				{
				    return Redirect::to('/');
				}

				return Redirect::to('login')
					->with('message', 'Invalid credentials. Please try again');
			}
 			

			return Redirect::to('login')
					->with('message', 'Something went wrong. Please try again');
		}

		return Redirect::to('login')
			->with('message', 'Following errors occurred.')
			->withErrors($validator);
	}

	//logout 
	public function getLogout(){
		Auth::logout();
		return Redirect::to('/');
	}

	//member home page
	public function getIndex(){		
		 return View::make('hello');
	}
}