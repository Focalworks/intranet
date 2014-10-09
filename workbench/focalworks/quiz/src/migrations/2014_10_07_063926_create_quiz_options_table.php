<?php

use Illuminate\Database\Migrations\Migration;

class CreateQuizOptionsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('quiz_options');
        Schema::create('quiz_options',function($table)
            {
                $table->engine = 'InnoDB';
                $table->increments('qo_id');
                $table->integer('qq_id');
                $table->text('qo_text');
                $table->boolean('is_correct');
                $table->dateTime('created');
                $table->dateTime('changed');
            });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('quiz_options');
    }

}