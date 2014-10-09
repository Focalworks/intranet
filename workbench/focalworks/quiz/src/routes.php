<?php

Route::group(array(
        'before' => 'checkAuth'
    ), function ()
    {
        Route::get('quiz/add_user', 'QuizController@addUser');
        Route::get('quiz/exam_list', 'QuizController@examList');
        Route::get('quiz/question_list', 'QuizController@questionList');
        Route::get('quiz/question_add', 'QuizController@questionAdd');
        Route::get('quiz/question_view/{id}', 'QuizController@questionView');
        Route::get('quiz/question_delete/{id}', 'QuizController@questionDelete');
        Route::get('quiz/user_save', 'QuizController@saveUser');
        Route::get('quiz/json_question/{id}','QuizeController@json_question');

        Route::group(array(
                'before' => 'csrf'
            ), function ()
            {
                Route::post('quiz/question_save', 'QuizController@questionSave');
                Route::post('quiz/filter', 'QuizController@handlequizFilter');
            });
    });


?>