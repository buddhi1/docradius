<?php

class AuthController extends BaseController{
	public function __construct(){
		$this->beforeFilter('csrf', array('on'=>'post'));
		$this->beforeFilter('guest', array('only'=>array('getLogin', 'postLogin')));
	}

	//views login page
	public function getLogin(){
		// front end route ----------------------------- // 
		return View::make('login');
	}

	//login function
	public function postLogin(){
		// ##################################################### //
		/*
			global login function

			login for all user types
			type: 1 -> admin
				2 	->	doctor
				3 	->	patient
				4 	-> 	hospital

			status: 
				444 -> login success
				555 -> login failed
		*/
		// ##################################################### //
		$validator = Validator::make(Input::all(), User::$login_rules);
		if($validator->passes()){
			$email = Input::get('email');
			$password = Input::get('password');
			$segment = Input::get('type');
			
			if($segment == 'admin'){
				if (Auth::attempt(['email' => $email, 'password' => $password, 'active'=>1, 'type'=>array('1')]))
				{
					// ------------ admin login sucess --------------- //
					// tested
				    //return Redirect::to('/');
				    return Response::json(array( 'status' => 444, 'message' => 'login sucess', 'data' => array( 'route' => '/' ) ));
				}

				// ------------------- admin login faliure ------------ //
				//return Redirect::to('admin/login')->with('message', 'Invalid credentials. Please try again');
					return Response::json(array( 'status' => 555, 'message' => 'invalid admin login', 'data' => array( 'route' => '/' ) ));
			}elseif ($segment == 'login' || $segment == 'member') {
				if (Auth::attempt(['email' => $email, 'password' => $password, 'active'=>1, 'type'=>array('2')]))
				{
					// ------------- doctor login 		-----------------/
				    //return Redirect::to('/');
				    return Response::json(array( 'status' => 444, 'message' => 'login sucess', 'data' => array( 'route' => '/' ) ));
				}else if (Auth::attempt(['email' => $email, 'password' => $password, 'active'=>1, 'type'=>array('3')]))
				{
					// ------------- patient login 		-----------------/
				    //return Redirect::to('/');
				    return Response::json(array( 'status' => 444, 'message' => 'login sucess', 'data' => array( 'route' => '/' ) ));
				}else if (Auth::attempt(['email' => $email, 'password' => $password, 'active'=>1, 'type'=>array('4')]))
				{
					// ------------- hospital login 		-----------------/
				    //return Redirect::to('/');
				    return Response::json(array( 'status' => 444, 'message' => 'login sucess', 'data' => array( 'route' => '/' ) ));
				}

				//return Redirect::to('login')->with('message', 'Invalid credentials. Please try again');
				return Response::json(array( 'status' => 555, 'message' => 'invalid member login', 'data' => array( 'route' => '/' ) ));
			}
 			

			//return Redirect::to('login')->with('message', 'Something went wrong. Please try again');
			return Response::json(array( 'status' => 555, 'message' => 'invalid login, type not defined', 'data' => array( 'route' => '/' ) ));
		}

		//return Redirect::to('login')->with('message', 'Following errors occurred.')->withErrors($validator);	
		return Response::json(array( 'status' => 555, 'message' => 'invalid login, validation failed', 'data' => array( 'route' => '/', 'validation' => $validator ) ));
	}


	//logout 
	public function getLogout(){
		// ##################################################### //
		/*
			global logout function
		*/
		// ##################################################### //
		Auth::logout();
		return Response::json(array( 'status' => 400, 'message' => 'logout successful', 'data' => array( 'route' => '/' ) ));

	}


	//member home page

	public function getIndex(){	
		$segment = Request::segment(1);

		if($segment == ''){			
			 return View::make('hello');
		}else if($segment != '' && Auth::check()){
			if($segment == 'admin'){	
				if(Auth::user()->type == 1){
					return View::make('admin.layouts.main');
				}

				return Redirect::to('/');
				
			}else if($segment == 'member'){
				if(Auth::user()->type == 2 || Auth::user()->type == 3){
					return View::make('member.layouts.main');
				}
				
				return Redirect::to('/');
			}
		}

		return Redirect::to('/');
	}
}