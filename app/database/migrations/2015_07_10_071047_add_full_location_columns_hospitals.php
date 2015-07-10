<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFullLocationColumnsHospitals extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('hospitals', function(Blueprint $table)
		{
			$table->integer('state_id')->nullable();
			$table->integer('lga_id')->nullable();
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
			$table->dropColumn('state_id');
			$table->dropColumn('lga_id');
		});
	}

}
