<?php

use Illuminate\Database\Migrations\Migration;

class CreateTimeentryTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('kanbanize_log_time');

        Schema::create('kanbanize_log_time', function ($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->dateTime('created_at');
            $table->unsignedInteger('board_id');
            $table->integer('taskid');
            $table->string('logedtime')->nullable();

            $table->index(array('board_id', 'taskid'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('kanbanize_log_time');
    }

}