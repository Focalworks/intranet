<?php

use Illuminate\Database\Migrations\Migration;

class QuizDesignation extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('quiz_designation');
        Schema::create('quiz_designation',function($table){
            $table->string('designation');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('quiz_designation');
    }

}