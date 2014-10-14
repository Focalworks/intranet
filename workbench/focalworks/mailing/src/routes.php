<?php
Route::get('sendmail', function  ()
    {
        /*$MailTracker = new MailTracker;

        $mail_to = 'reachme@amitavroy.com';
        $mail_from = 'amitav.roy@focalworks.in';
        $mail_subject = 'Testing the email';
        $mail_body = '<p>This is some <strong>bold</strong> text.';
        $mail_to_name = 'Amitav Roy Offiec';
        $mail_from_name = 'My site admin';
        $MailTracker->sendMail($mail_to, $mail_from, $mail_subject, $mail_body, $mail_to_name, $mail_from_name);*/
    });

Route::group(array('before' => 'checkAuth'), function() {
        Route::get('mailing/list', 'MailingController@handleMailingList');
    });