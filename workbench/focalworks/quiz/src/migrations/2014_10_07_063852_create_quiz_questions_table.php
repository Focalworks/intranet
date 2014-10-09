<?php

use Illuminate\Database\Migrations\Migration;

class CreateQuizQuestionsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('quiz_questions');
        Schema::create('quiz_questions',function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('qq_id');
            $table->text('qq_text');
            $table->dateTime('created');
            $table->dateTime('changed');
            $table->integer('created_by');

           /* $table->foreign('qo_answer')->references('qo_id')->on('quiz_options')
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
        Schema::dropIfExists('quiz_questions');
    }

}