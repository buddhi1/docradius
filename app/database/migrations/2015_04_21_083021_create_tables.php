<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTables extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users', function($table){
			$table->increments('id');
			$table->string('email');
			$table->string('password');
			$table->boolean('type')->nullable();
			$table->string('code');
			$table->boolean('active')->nullable();
			$table->rememberToken();
			$table->timestamps();
		});

		Schema::create('states', function($table){
			$table->increments('id');
			$table->string('name');
			$table->timestamps();
		});

		Schema::create('lgas', function($table){
			$table->increments('id');
			$table->string('name');
			$table->integer('state_id')->unsigned();
			$table->foreign('state_id')->references('id')->on('states');
			$table->timestamps();
		});

		Schema::create('towns', function($table){
			$table->increments('id');
			$table->string('name');
			$table->integer('lga_id')->unsigned();
			$table->foreign('lga_id')->references('id')->on('lgas');
			$table->timestamps();
		});

		Schema::create('specialties', function($table){
			$table->increments('id');
			$table->string('name');
			$table->timestamps();
		});

		Schema::create('doctors', function($table){
			$table->increments('id');
			$table->string('name');
			$table->boolean('active');
			$table->string('description')->nullable();
			$table->string('experience')->nullable();
			$table->string('tp')->nullable();
			$table->text('specialties');
			$table->string('profile_picture')->nullable();
			$table->integer('user_id')->unsigned();
			$table->foreign('user_id')->references('id')->on('users');
			$table->string('special_popup')->nullable();
			$table->timestamps();
		});

		Schema::create('patients', function($table){
			$table->increments('id');
			$table->string('name');
			$table->boolean('active');
			$table->string('tp')->nullable();
			$table->boolean('sex');
			$table->integer('town_id')->unsigned();
			$table->foreign('town_id')->references('id')->on('towns');
			$table->integer('user_id')->unsigned();
			$table->foreign('user_id')->references('id')->on('users');
			$table->timestamps();
		});

		Schema::create('advertisements', function($table){
			$table->increments('id');
			$table->string('image')->nullable();
			$table->string('link')->nullable();
			$table->timestamps();
		});

		Schema::create('jobs', function($table){
			$table->increments('id');
			$table->string('title')->nullable();
			$table->text('description')->nullable();
			$table->string('email');
			$table->boolean('active');
			$table->integer('user_id')->unsigned();
			$table->foreign('user_id')->references('id')->on('users');
			$table->timestamps();
		});

		Schema::create('schedules', function($table){
			$table->increments('id');
			$table->time('start_time');
			$table->time('end_time');
			$table->integer('no_of_patients');
			$table->string('hospital');
			$table->string('day');
			$table->integer('doctor_id')->unsigned();
			$table->foreign('doctor_id')->references('id')->on('doctors');
			$table->integer('town_id')->unsigned();
			$table->foreign('town_id')->references('id')->on('towns');
			$table->timestamps();
		});

		Schema::create('channels', function($table){
			$table->increments('id');
			$table->boolean('state')->nullable();
			$table->time('time');
			$table->date('chanelling_date');
			$table->string('town_id');
			$table->string('hospital');
			$table->string('patient_tp');
			$table->integer('doctor_id')->nullable();
			$table->integer('patient_id')->nullable();
			$table->integer('schedule_id')->nullable();
			$table->timestamps();
		});


		Schema::create('histories', function($table){
			$table->increments('id');
			$table->dateTime('date_time');
			$table->boolean('visit_state');
			$table->integer('doctor_id')->nullable();
			$table->integer('patient_id')->nullable();
			$table->timestamps();
		});


		Schema::create('inactives', function($table){
			$table->increments('id');			
			$table->integer('doctor_id')->unsigned();
			$table->foreign('doctor_id')->references('id')->on('doctors');
			$table->integer('schedule_id')->unsigned();
			$table->foreign('schedule_id')->references('id')->on('schedules');
			$table->timestamps();
		});		
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('users');
		Schema::drop('states');
		Schema::drop('lgas');
		Schema::drop('towns');
		Schema::drop('specilties');
		Schema::drop('doctors');
		Schema::drop('patients');
		Schema::drop('advetisements');
		Schema::drop('jobs');
		Schema::drop('schedules');
		Schema::drop('channels');
		Schema::drop('histories');
		Schema::drop('inactives');
	}

}
