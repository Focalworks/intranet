<?php

use Illuminate\Database\Migrations\Migration;

class CreateFileManagedTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
	    Schema::dropIfExists('file_managed');
	     
	    Schema::create('file_managed', function ($table)
	    {
	        $table->engine = 'InnoDB';
	        $table->increments('id');
	        $table->unsignedInteger('user_id');
	        $table->string('entity');
	        $table->unsignedInteger('entity_id');
	        $table->string('filename');
	        $table->string('url');
	        $table->string('filemime');
	        $table->integer('filesize');
	        $table->unsignedInteger('status')->default(0); // 0: Temp 1: Published 2: Deleted 3: Archived 
	        $table->timestamps();
	         
	        $table->foreign('user_id')->references('id')->on('users')
	        ->onUpdate('no action')
	        ->onDelete('no action');
	        
	        $table->index('entity_id');
	    
	    });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
	    Schema::dropIfExists('file_managed');
	}

}