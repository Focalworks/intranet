<?php

use Illuminate\Database\Migrations\Migration;

class CreateGrievanceTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
	    Schema::dropIfExists('grievances');
	    
	    Schema::create('grievances', function ($table)
	    {
	        $table->engine = 'InnoDB';
	        $table->increments('id');
	        $table->string('title');
	        $table->text('description');
	        $table->string('category');
	        $table->string('photo_url');
	        $table->string('urgency'); // 1: Low 2: Medium 3: High
	        $table->unsignedInteger('user_id');
	        $table->unsignedInteger('status'); // 1: just added 2: in progress 3: closed 4: re-opened
	        $table->timestamps();
	        
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
	    Schema::dropIfExists('grievances');
	}

}