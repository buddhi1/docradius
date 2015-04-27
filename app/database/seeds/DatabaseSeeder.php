<?php

class UserTableSeeder extends Seeder
{

	public function run()
	{
	    
	    //DB::table('users')->delete();
	    User::create(array(
	        'email' => 'admin@docradius.dev',	       
	        'password' => Hash::make('pass'),
	        'type' => 1,
	        'active' => 1
	    ));

	}
}

class DatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Eloquent::unguard();

		$this->call('UserTableSeeder');
	}

}
