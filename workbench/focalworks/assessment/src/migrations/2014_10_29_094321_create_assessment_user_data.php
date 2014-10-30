<?php

use Illuminate\Database\Migrations\Migration;

class CreateAssessmentUserData extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('assessment_user_data');

        Schema::create('assessment_user_data', function($table) {
                $table->engine = 'InnoDB';
                $table->increments('id');
                $table->string('name');
                $table->string('phone');
                $table->string('email');
                $table->string('post_applied');
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
        Schema::dropIfExists('assessment_user_data');
    }

}