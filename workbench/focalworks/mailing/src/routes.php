<?php

Route::get('sendmail', function  ()
    {
        $grievance = array(
            'title' => 'Something',
            'description' => 'asasdasd',
        );

        return \View::make('mailing::emails/new-grievance-email')->with('grievance', $grievance);
    });

Route::group(array('before' => 'checkAuth'), function() {
        Route::get('mailing/list', 'MailingController@handleMailingList');
    });