<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/', function()
    {
        return Redirect::to('user');
    });

Route::get('admin-login', function() {
        return View::make('user.admin-login');
    });

Route::controller('api', 'ApiController');

/* this section is for authenticated users only */
Route::group(array(
        'before' => 'checkAuth'
    ), function ()
    {
        Route::post('entity/delete', 'EntityController@deletEntity');
        Route::get('customise/toggle-menu', 'EntityController@toggleMenuActive');
    });

Event::subscribe('FW\Subscriber\HookSubscriber');