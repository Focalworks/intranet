<?php


/* this section is for authenticated users only */
Route::group(array(
    'before' => 'checkAuth'
), function ()
{
    Route::get('grievance/list', 'GrievanceController@handleList');
    Route::get('grievance/add', 'GrievanceController@handleAdd');
    Route::get('grievance/view/{id}', 'GrievanceController@handleGrievanceView');
    Route::get('grievance/manage/{id}', 'GrievanceController@handleGrievanceManage');
    
    Route::group(array(
        'before' => 'csrf'
    ), function ()
    {
        Route::post('grievance/save', 'GrievanceController@handleGrievanceSave');
        Route::post('grievance/update', 'GrievanceController@handleGrievanceUpdate');
    });
});