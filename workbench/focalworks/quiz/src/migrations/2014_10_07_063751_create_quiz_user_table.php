<?php

use Illuminate\Database\Migrations\Migration;

class CreateQuizUserTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
        Schema::dropIfExists('quiz_users');
        Schema::create('quiz_users', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('qu_id');
            $table->string('qu_fname');
            $table->string('qu_lname');
            $table->string('qu_email');
            $table->string('qu_designation');
            $table->dateTime('created');
            $table->dateTime('changed');

            $table->index('qu_id');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::dropIfExists('quiz_users');
	}

}