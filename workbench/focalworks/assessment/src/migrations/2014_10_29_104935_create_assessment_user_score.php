<?php

use Illuminate\Database\Migrations\Migration;

class CreateAssessmentUserScore extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::dropIfExists('assessment_user_score');

        Schema::create('assessment_user_score', function($table) {
                $table->engine = 'InnoDB';
                $table->increments('id');
                $table->unsignedInteger('user_id');
                $table->integer('correct_answers');
                $table->integer('score');
            });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::dropIfExists('assessment_user_score');
	}

}