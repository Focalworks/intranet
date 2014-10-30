<?php

use Illuminate\Database\Migrations\Migration;

class Assessment extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('assessments');

        /*Drop old quiz table*/
        Schema::dropIfExists('quiz_department');
        Schema::dropIfExists('quiz_designation');
        Schema::dropIfExists('quiz_exams');
        Schema::dropIfExists('quiz_options');
        Schema::dropIfExists('quiz_questions');
        Schema::dropIfExists('quiz_users');

        Schema::create('assessments', function($table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('title');
            $table->tinyInteger('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('assessments');

        /*Drop old quiz table*/
        Schema::dropIfExists('quiz_department');
        Schema::dropIfExists('quiz_designation');
        Schema::dropIfExists('quiz_exams');
        Schema::dropIfExists('quiz_options');
        Schema::dropIfExists('quiz_questions');
        Schema::dropIfExists('quiz_users');
    }

}