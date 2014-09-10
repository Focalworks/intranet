<?php

Route::get('comment', 'CommentController@commentPage');
Route::post('comment/get', 'CommentController@getComments');
Route::post('comment/save', 'CommentController@saveComment');
Route::post('comment/delete', 'CommentController@deleteComment');
