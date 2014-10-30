<?php

use Illuminate\Database\Migrations\Migration;

class TagsInAssessment extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('tags_in_assessment');

        Schema::create('tags_in_assessment', function($table) {
                $table->engine = 'InnoDB';
                $table->integer('question_id');
                $table->integer('tag_id');

                $table->index('question_id');
                $table->index('tag_id');
            });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tags_in_assessment');
    }

}