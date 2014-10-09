<?php

use Illuminate\Database\Migrations\Migration;

class AddAnonymousGrievance extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
		Schema::table('grievances', function($table)
		{
    		$table->integer('anonymous')->default(0)->after('status');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		//
		Schema::table('grievances', function($table) {
                $table->dropColumn('anonymous');
        });
	}

}