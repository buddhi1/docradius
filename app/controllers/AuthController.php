<?php

class AuthController extends BaseController{
	public function __construct(){
		$this->beforeFilter('csrf', array('on'=>'post'));
		$this->beforeFilter('guest', array('only'=>array('getLogin')));
	}

	//views login page
	public function getLogin(){
		// front end route ----------------------------- // 
		return View::make('login');
	}

	public function getState(){
		if( Auth::check() ){
			$user = Auth::user();
			if($user->active){
				return Response::json( [ 
					'status' => true,
					'message' => 'user logged in',
					'data' => [
						'user' => [
							'id' => $user->id,
							'type' => $user->type,
							'active' => $user->active,
						],
					], 
				]);
			}else{
				return Response::json( [
					'status' => false,
					'message' => 'user inactive',
					'data' => [
						'user' => [
							'id' => $user->id,
							'type' => $user->type,
							'active' => $user->active,
						],
					], 
				]);
			}
		}else{
			return Response::json([
				'message' => 'not logged in',
				'error' => 'not logged in',
			], 401);
		}
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
				    return Response::json(array( 'status' => 444, 'message' => 'login sucess', 'data' => [ 'user' => Auth::user() ] ));
				}

				// ------------------- admin login faliure ------------ //
				//return Redirect::to('admin/login')->with('message', 'Invalid credentials. Please try again');
					return Response::json(array( 'status' => 555, 'error' => 'invalid admin login', 'data' => array( 'route' => '/' ) ), 401);
			}elseif ($segment == 'login' || $segment == 'member') {
				if (Auth::attempt(['email' => $email, 'password' => $password, 'active'=>1, 'type'=>array('2')]))
				{
					// ------------- doctor login 		-----------------/
				    //return Redirect::to('/');
				    return Response::json(array( 'status' => 444, 'message' => 'login sucess', 'data' => [ 'user' => Auth::user() ] ));
				}else if (Auth::attempt(['email' => $email, 'password' => $password, 'active'=>1, 'type'=>array('3')]))
				{
					// ------------- patient login 		-----------------/
				    //return Redirect::to('/');
				    return Response::json(array( 'status' => 444, 'message' => 'login sucess', 'data' => [ 'user' => Auth::user() ] ));
				}else if (Auth::attempt(['email' => $email, 'password' => $password, 'active'=>1, 'type'=>array('4')]))
				{
					// ------------- hospital login 		-----------------/
				    //return Redirect::to('/');
				    return Response::json(array( 'status' => 444, 'message' => 'login sucess', 'data' => [ 'user' => Auth::user() ] ));
				}

				//return Redirect::to('login')->with('message', 'Invalid credentials. Please try again');
				return Response::json(array( 'status' => 555, 'error' => 'invalid member login', 'data' => array( 'route' => '/' ) ), 401);
			}
 			

			//return Redirect::to('login')->with('message', 'Something went wrong. Please try again');
			return Response::json(array( 'status' => 555, 'error' => 'invalid login, type not defined', 'data' => array( 'route' => '/' ) ),401);
		}

		//return Redirect::to('login')->with('message', 'Following errors occurred.')->withErrors($validator);	
		return Response::json(array( 'status' => 555, 'error' => 'invalid login, validation failed', 'data' => array( 'route' => '/', 'validation' => $validator->errors() ) ),401);
	}


	//logout 
	public function getLogout(){
		// ##################################################### //
		/*
			global logout function
		*/
		// ##################################################### //
		Auth::logout();
		return Response::json(array( 'status' => 200, 'message' => 'logout successful', 'data' => array( 'route' => '/' ) ));

	}


	//member home page

	public function getIndex($type){	
		// ##################################################### //
		/*
			redirect function on authentication status of each member type
			admin can access only admin panel
			members can access only member panel
			a permission denied status is thrown if an invalid member tries to access an invalid route
		*/
		// ##################################################### //

		if(Auth::check()){
			if(Auth::user()->type == 1 && $type == 'admin'){
				//return View::make('admin.layouts.main');
				return Response::json(array(
						'status' => 200,
						'message' => '',
						'route' => '/return admin pannel'
					));
			}
				
			else if( (Auth::user()->type == 2 || Auth::user()->type == 3) && $type == "member" ){
				//return View::make('member.layouts.main');
				return Response::json(array(
						'status' => 200,
						'message' => '',
						'route' => '/return member panel'
					));
			}

			else{
				return Response::json([
						'status' => '401',
						'message' => 'permission denied',
						'route' => '/'
					]);
			}
		}

		return Redirect::to('/');
	}
}