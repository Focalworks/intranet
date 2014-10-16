<?php

use Illuminate\Database\Migrations\Migration;

class KanbanizeTicketsAddDate extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /* add created_at column in KanbanizeTickets */
        Schema::table('kanbanize_tickets', function($table) {
            $table->date('created_at');
            $table->string('assignee')->after('taskid');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('kanbanize_tickets', function($table) {
            $table->dropColumn('created_at');
            $table->dropColumn('assignee');
        });
    }

}