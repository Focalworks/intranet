<?php

use Illuminate\Database\Migrations\Migration;

class AddSoftDeleteToGrievanceTable extends Migration {

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
    		$table->integer('deleted')->default(0)->after('updated_at');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('grievances',  function($table)
		{
			//
			$table->dropColumn('deleted');
		});
	}

}