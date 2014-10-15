<?php

use Illuminate\Database\Migrations\Migration;

class CrateQuizDepartment extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('quiz_department');
        Schema::create('quiz_department',function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('qq_id');
            $table->string('department');
            $table->string('detail');
        });
    }

    public function down()
    {
        Schema::dropIfExists('quiz_department');
    }

}