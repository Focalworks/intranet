<?php

Route::group(array(
        'before' => 'checkAuth'
    ), function ()
    {
        Route::get('quiz', 'QuizController@mainView');
        Route::get('quiz/question_list', 'QuizController@questionList');
        Route::get('quiz/question_add', 'QuizController@questionAdd');
        Route::get('quiz/question_delete/{id}', 'QuizController@questionDelete');
        Route::post('quiz/question_save', 'QuizController@questionSave');

        Route::get('quiz/exam_list', 'QuizController@examList');
        Route::get('quiz/exam_add', 'QuizController@examAdd');
        Route::get('quiz/exam_view/{id}', 'QuizController@examView');


        Route::get('quiz/user_save', 'QuizController@saveUser');
        Route::get('quiz/json_question/{id}','QuizController@jsonQuestion');
        Route::get('quiz/json_question_list','QuizController@jsonQuestionList');

    });

Route::post('quiz/user_save', 'QuizController@saveUser');


?>