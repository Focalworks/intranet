<?php
/**
 * Created by PhpStorm.
 * User: amitav
 * Date: 29/10/14
 * Time: 10:21 AM
 */

Route::controller('assessment-api', 'AssessmentApiController');
Route::get('html', function() {
        /*$str = "This is some <b>bold</b> text.";*/
        $str = "<?php ?> ";

        echo GlobalHelper::convertStringToHTML($str);die;

    });
Route::get('assessment/test', function() {
        $assessments = new Assessments;
        return $assessments->generateUserAssessmentPDF(1);

        /*$mail = new MailTracker;
        $mail->sendMail('amitavroy@gmail.com',
            'amitav.roy@focalworks.in',
            'Test',
            'Test',
            'Amitav Office',
            'Amitav Gmail');*/
    });