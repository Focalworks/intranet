<?php

use Illuminate\Database\Migrations\Migration;

class CreateAssessmentUserResult extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::dropIfExists('assessment_user_result');

        Schema::create('assessment_user_result', function($table) {
                $table->engine = 'InnoDB';
                $table->increments('id');
                $table->unsignedInteger('user_id');
                $table->unsignedInteger('question_id');
                $table->unsignedInteger('option_id');
                $table->boolean('status');

                /*$table->foreign('user_id')->references('id')->on('assessment_user_data');
                $table->foreign('question_id')->references('id')->on('assessments');
                $table->foreign('option_id')->references('id')->on('assessment_options');*/
            });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::dropIfExists('assessment_user_result');
	}

}