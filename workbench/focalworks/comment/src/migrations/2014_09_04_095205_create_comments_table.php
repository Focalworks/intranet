<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommentsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::dropIfExists('comments');
    	Schema::create('comments', function($table)
	    {
	      $table->engine = 'InnoDB';
	      $table->increments('cid');
	      $table->integer('pid');
	      $table->integer('nid');
	      $table->unsignedInteger('user_id');
	      $table->string('section', '255');
	      $table->string('thread', '255');
	      $table->text('comment');
	      $table->integer('status');
	      $table->integer('created');
	      $table->integer('changed');

	      $table->index('cid');
	      $table->index('nid');

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
		Schema::dropIfExists('comments');
	}

}
