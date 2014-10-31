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
        /*GlobalHelper::dsm($assessments->calculateScore(4), true);*/
        GlobalHelper::dsm($assessments->getAssessmentResult(1));
        $assessment_data = $assessments->getAssessmentResult(1);
        $viewData = array(
            'name' => $assessment_data['user_data']->name,
            'phone' => $assessment_data['user_data']->phone,
            'email' => $assessment_data['user_data']->email,
            'post_applied' => $assessment_data['user_data']->post_applied,
            'question_data' => $assessment_data['question_data'],
        );
        return View::make('assessment::assessment-data-pdf')->with('data', $viewData);

        $pdf = App::make('dompdf');
        $pdf->loadHTML(View::make('assessment::assessment-data-pdf')->with('data', $viewData));
        $pdfData = $pdf->setPaper('a4')->setOrientation('landscape')->download();

        $file_to_save = '/var/www/html/focalworks-intranet/public/file.pdf';

        //save the pdf file on the server
        file_put_contents($file_to_save, $pdfData);
    });