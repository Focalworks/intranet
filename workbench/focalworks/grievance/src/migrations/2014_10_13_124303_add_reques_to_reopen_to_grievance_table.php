<?php

use Illuminate\Database\Migrations\Migration;

class AddRequesToReopenToGrievanceTable extends Migration {

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
    		$table->text('req_reopen')->nullable()->after('updated_at');
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
			$table->dropColumn('req_reopen');
		});
	}

}