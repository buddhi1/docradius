create<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHospitalInsuranceTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('hospitalinsurance', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('hospital_id')->unsigned();
			$table->integer('insurance_id')->unsigned();
			$table->foreign('hospital_id')->references('id')->on('hospitals');
			$table->foreign('insurance_id')->references('id')->on('insurances');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('hospitalinsurance');
	}

}
