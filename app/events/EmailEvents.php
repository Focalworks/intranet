<?php namespace FW\Emails;

/**
 * All events related to sending email is handled through this class.
 */

class EmailEvents {
    
    public function onGrievanceSave($grievance)
    {
        \Log::info('I was here inside class');
        \Log::info(print_r($grievance, true));
    }

    /**
     * Subscribing to the events and declaring their function.
     */
    public function subscribe($events)
    {
        $events->listen('grievance.save', 'FW\Emails\EmailEvents@onGrievanceSave');
    }

}