<?php namespace Focalworks\Grievance;

class GrievanceMails extends \Eloquent {

    /**
     * Sending email about a new Grievance added.
     */
    public function sendGrievanceSaveEmail($grievance)
    {
        $mail_to_address = 'amitav.roy@focalworks.in';
        $mail_from_address = 'amitav.roy@focalworks.in';
        $mail_subject = 'New Grievance added to Intranet';
        $mail_body = \View::make('mailing::emails/new-grievance-email')->with('grievance', $grievance);

        $mailTracker = new \MailTracker;

        $mailTracker->sendMail($mail_to_address, $mail_from_address, $mail_subject,
        $mail_body);
    }

}