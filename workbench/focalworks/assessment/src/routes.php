<?php
/**
 * Created by PhpStorm.
 * User: amitav
 * Date: 29/10/14
 * Time: 10:21 AM
 */
Route::controller('assessment-api', 'AssessmentApiController');
Route::get('assessment/test', function() {
        $assessments = new Assessments;
        GlobalHelper::dsm($assessments->calculateScore(4), true);
    });