<?php

class UserControllerRes extends \BaseController {
	public function __construct() {
		$this->beforeFilter('csrf', array('on' => 'post'));
		$this->beforeFilter('user', array('only'=>array('postUpdateaccountsettings')));
		$this->beforeFilter('admin', array('except'=>array('getEditaccountsettings', 'postUpdateaccountsettings')));
	}
		/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//views the index page with availabale user details
		$users = DB::table('users')
					->where('type', '=', 1)
					//->where('id', '<>', Auth::id())
					->select('id', 'email', 'active')
					->get();

		return Response::json([
				'status' => 200,
				'message' => 'admin list',
				'data' => [
					'users' => $users,
				],
			]);
		//return View::make('admin.user.index')
		//			->with('users', $users);	
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		// ########################################## //
		/*
			function is used to create admin users
			-	validator checks if same user email exists
			creates user and sends created user as response
			-	if validator fails, validation erros are send back
		*/
		// ########################################## //
		$validator = Validator::make(Input::all(), User::$rules);
		//validates for rules which are defined in the model
		if($validator->passes()){
			$email = Input::get('email');
			//checks the email already exists or not 
			
				$user = new User;
				$user->email = $email;
				$user->password = Hash::make(Input::get('password'));
				$user->type = 1;
				$user->active = 1;
				$user->save(); //saves the user record

				//return Redirect::to('admin/user/index')
				//			->with('message', 'New user has been created successfully');
				return Response::json([
						'status' => 200,
						'message' => 'new admin created',
						'data' => [
								'admin' => $user,
							],
						'route' => 'admin'
					]);
		}

		//returns if rules are been violated
		//return Redirect::to('admin/user/create')
		//			->with('message', 'The following erros occured')
		//			->withErrors($validator);

		return Response::json([
				'status' => 401,
				'error' => 'request denied, validation failed',
				'data' => [
					'validation' => $validator->errors(),
				],
				'route' => 'user/create'
			],401);
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$user = User::find($id);

		return Response::json([
			'status' => 200,
			'message' => 'admin user data',
			'data' => [
				'admin' => $user,
			],
		]);	
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$user = User::find($id);
		if($user){
			$email = Input::get('email');
			if($email){			
				$password = Input::get('password');
				if($user->password != $password){
					$user->password = Hash::make($password);
				}
				$user->email = $email;
				$user->save();

				//return Redirect::to('admin/user/index')
				//		->with('message', 'The user has been edited successfully');
				return Response::json([
						'status' => 200,
						'message' => 'user updated',
						'data' => [
							'admin' => $user,
						],
					]);
			}

			//if no email is inserted
			//return Redirect::to('admin/user/index')
			//		->with('message', 'Email field is required. Please try again');
			return Response::json([
						'status' => 403,
						'message' => 'email not defined',
					]);

		}

		//if invalid user id is sent, then redirect to index page
		//return Redirect::to('admin/user/index')
		//		->with('message', 'Something went wrong. Please try again');	
		return Response::json([
						'status' => 403,
						'message' => 'user id not identified',
					]);
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$user = User::find($id);
		if($user){
			$user->delete();


			// return Response::json([
			// 			'status' => 200,
			// 			'message' => 'user deleted successfully',
			// 		]);
		}

		//if invalid user id is sent, then redirect to index page
		return Response::json([
						'status' => 403,
						'message' => 'user not found',
					]);	
	}


}
