<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveIsuaranceColumnFromHospitalsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('hospitals', function(Blueprint $table)
		{
			$table->dropColumn('insurances');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('hospitals', function(Blueprint $table)
		{
			$table->text('insurances')->nullable();
		});
	}

}
