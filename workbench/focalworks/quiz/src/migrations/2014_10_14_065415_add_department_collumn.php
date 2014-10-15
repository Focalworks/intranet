<?php

use Illuminate\Database\Migrations\Migration;

class AddDepartmentCollumn extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('quiz_questions',function($table) {
            $table->string('department')->after('qq_text');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('quiz_questions',function($table) {
            $table->dropColumn('department');
        });
    }

}