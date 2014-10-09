<?php

use Illuminate\Database\Migrations\Migration;

class CreateQuizExamTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('quiz_exams');
        Schema::create('quiz_exams',function($table)
            {
                $table->engine = 'InnoDB';
                $table->increments('qe_id');
                $table->integer('qu_id');
                $table->text('qe_questions');
                $table->text('qe_answers');
                $table->string('designation');
                $table->dateTime('created');
                $table->dateTime('changed');

                /*$table->foreign('qu_id')->references('qu_id')->on('quiz_users')
                    ->onUpdate('no action')
                    ->onDelete('no action');*/
            });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('quiz_exams');
    }

}