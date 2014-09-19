<?php

use Illuminate\Database\Migrations\Migration;

class CreateTicketTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::dropIfExists('kanbanize_tickets');

        Schema::create('kanbanize_tickets', function ($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('board_id');
            $table->integer('taskid');
            $table->string('position', 5)->nullable();
            $table->string('type', 20)->nullable();
            $table->string('assignee', 50);
            $table->string('title');
            $table->longText('description')->nullable();
            $table->integer('subtasks')->nullable();
            $table->integer('subtaskscomplete')->nullable();
            $table->string('color', 10)->nullable();
            $table->string('priority', 10)->nullable();
            $table->string('size')->nullable();
            $table->string('deadline')->nullable();
            $table->string('deadlineoriginalformat')->nullable();
            $table->string('extlink')->nullable();
            $table->string('tags')->nullable();
            $table->string('columnid', 20)->nullable();
            $table->integer('laneid')->nullable();
            $table->string('leadtime')->nullable();
            $table->integer('blocked')->nullable();
            $table->longText('blockedreason')->nullable();
            $table->string('columnname', 10)->nullable();
            $table->string('lanename', 20)->nullable();
            $table->string('columnpath', 20)->nullable();
            $table->string('logedtime')->nullable();

            $table->foreign('user_id')->references('id')->on('users')
                ->onUpdate('no action')
                ->onDelete('no action');

            /*$table->foreign('board_id')->references('board_id')->on('kanbanize_projects')
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
        Schema::dropIfExists('kanbanize_tickets');
	}

}