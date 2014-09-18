<?php


/* this section is for authenticated users only */
Route::group(array(
    'before' => 'checkAuth'
), function ()
{
    Route::get('ticket/fetch', 'KanbanizeController@fetchAllTickets');
    Route::get('project/fetch', 'KanbanizeController@getProjectList');

    Route::group(array(
        'before' => 'csrf'
    ), function ()
    {

    });
});