<?php
/**
 * Created by PhpStorm.
 * User: amitav
 * Date: 29/10/14
 * Time: 10:21 AM
 */
Route::controller('assessment-api', 'AssessmentApiController');
Route::get('assessment/test', function() {
        /*$assessments = new Assessments;
        GlobalHelper::dsm($assessments->calculateScore(4), true);*/

        $pdf = App::make('dompdf');
        $pdf->loadHTML(View::make('assessment::assessment-data-pdf'));
        $pdfData = $pdf->setPaper('a4')->setOrientation('landscape')->download();

        $file_to_save = '/var/www/html/focalworks-intranet/public/file.pdf';
        //save the pdf file on the server
        file_put_contents($file_to_save, $pdfData);
    });