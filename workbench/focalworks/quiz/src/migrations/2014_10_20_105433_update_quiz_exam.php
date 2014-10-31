<?php

use Illuminate\Database\Migrations\Migration;

class UpdateQuizExam extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('quiz_exams', function($table)
        {
            $table->dropColumn('qe_questions');
            $table->dropColumn('qe_answers');
            $table->dropColumn('designation');
            $table->dropColumn('changed');
            $table->text('answers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('quiz_exams');
    }

}