<?php

use Illuminate\Database\Migrations\Migration;

class MailAttachmentField extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mail_tracker',
            function  ($table)
            {
                $table->text('attachment')->default("");
            });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }

}