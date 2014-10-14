<?php
use Illuminate\Database\Migrations\Migration;

class CreateMailTracker extends Migration
{

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up ()
  {
    Schema::dropIfExists('mail_tracker');
    Schema::create('mail_tracker', 
        function  ($table)
        {
          $table->engine = 'InnoDB';
          $table->increments('mail_id')->comment('This is comment');
          $table->string('mail_to_address',100);
          $table->string('mail_to_name',100);
          $table->string('mail_from_address',100);
          $table->string('mail_from_name',100);
          $table->string('mail_subject',100);
          $table->text('mail_body');
          $table->integer('mail_created');
          $table->integer('mail_sent');
          $table->tinyInteger('mail_status');          
        });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down ()
  {
    Schema::dropIfExists('mail_tracker');
  }
}