<?php

use Illuminate\Database\Migrations\Migration;

class AssessmentOptions extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('assessment_options');

        Schema::create('assessment_options', function($table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('question_id');
            $table->string('option');
            $table->boolean('correct');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('assessment_options');
    }

}