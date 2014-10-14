<?php

use Illuminate\Database\Migrations\Migration;

class UpdateQuizUsers extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('quiz_users', function($table)
        {
            $table->string('qu_mobile')->after('qu_email');
            $table->dropColumn('qu_lname');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('quiz_users', function($table)
        {
            $table->dropColumn('qu_mobile');
            $table->string('qu_lname');
        });
    }

}