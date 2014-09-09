<?php

use Illuminate\Database\Migrations\Migration;

class CreateUserCustomisationTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('user_customisation');

        Schema::create('user_customisation', function ($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->text('customisation');

            $table->foreign('user_id')->references('id')->on('users')
                ->onUpdate('no action')
                ->onDelete('no action');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_customisation');
    }

}