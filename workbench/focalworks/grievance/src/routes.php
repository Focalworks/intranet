<?php


/* this section is for authenticated users only */
Route::group(array(
    'before' => 'checkAuth'
), function ()
{
    Route::get('grievance/list', 'GrievanceController@handleList');
    Route::get('grievance/add', 'GrievanceController@handleAdd');
    Route::get('grievance/view/{id}', 'GrievanceController@handleGrievanceView');
    Route::get('grievance/readonly/{id}', 'GrievanceController@handleGrievanceReadonly');
    Route::get('grievance/manage/{id}', 'GrievanceController@handleGrievanceManage');
    Route::get('grievance/reset', 'GrievanceController@handleGrievanceFilterRest');
    Route::get('grievance/delete/{id}', 'GrievanceController@handleGrievanceDelete');
    
    Route::group(array(
        'before' => 'csrf'
    ), function ()
    {
        Route::post('grievance/save', 'GrievanceController@handleGrievanceSave');
        Route::post('grievance/update', 'GrievanceController@handleGrievanceUpdate');
        Route::post('grievance/filter', 'GrievanceController@handleGrievanceFilter');
        Route::post('grievance/request_reopen', 'GrievanceController@handleGrievanceRequestReopen');
    });
});