<?php

Route::get('comment', 'CommentController@commentPage');
Route::post('comment/get', 'CommentController@getComments');
Route::post('comment/save', 'CommentController@saveComment');
Route::post('comment/delete', 'CommentController@deleteComment');

/**
 * Adding the two directive templates
 */
Route::get('comment/comment-template', function() {
    return View::make('comment::comments-main');
});
Route::get('comment/comment-wrapper-template', function() {
    return View::make('comment::comments-wrapper');
});