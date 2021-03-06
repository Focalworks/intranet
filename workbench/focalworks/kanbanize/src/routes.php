<?php

Route::get('project/fetch/{cron_key}', 'KanbanizeController@getProjectList');
Route::get('ticket/fetch/{cron_key}', 'KanbanizeController@fetchAllTickets');

/* this section is for authenticated users only */
Route::group(array(
    'before' => 'checkAuth'
), function ()
{
    Route::get('kanbanize', 'KanbanizeController@getLandingPage');
    Route::controller('kanban', 'KanbanController');

    /**
     * Templates
     */
    Route::get('kanban-api/templates/project-list', function() {return View::make('kanbanize::templates.projects');});
    Route::get('kanban-api/templates/tickets', function() {return View::make('kanbanize::templates.tickets');});

    Route::group(array(
        'before' => 'csrf'
    ), function ()
    {

    });
});