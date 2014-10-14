<?php
/**
 * Created by PhpStorm.
 * User: Amitav Roy
 * Date: 7/16/14
 * Time: 12:29 PM
 */

class MailingController extends BaseController {

    protected $layout = 'sentryuser::master';

    public function handleMailingList()
    {
        $MailTracker = new MailTracker;
        $mail_entries = $MailTracker->getMailEntries()->paginate(10);
        $this->layout->content = View::make('mailing::mail-listing')->with('mail_entries', $mail_entries);
    }
} 