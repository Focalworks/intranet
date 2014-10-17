<?php

use Illuminate\Database\Migrations\Migration;

class Variables extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('variables');
        Schema::create('variables',function($table) {
            $table->string('name');
            $table->string('value');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('variables');
    }

}