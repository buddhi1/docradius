<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterAdvertisementsAndJobs extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{

		Schema::rename('advetisements', 'advertisements');

		Schema::table('advertisements', function($table)
		{
		    $table->boolean('active')->nullable();
		});

		Schema::table('jobs', function($table)
		{
		    $table->boolean('active')->nullable();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('advertisements', function($table)
		{
		    $table->dropColumn('active');
		});

		Schema::table('jobs', function($table)
		{
		    $table->dropColumn('active');
		});

		Schema::rename('advertisements', 'advetisements');

	}

}
