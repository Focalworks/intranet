<?php namespace FW\Emails;

/**
 * All events related to sending email is handled through this class.
 */

use Focalworks\Grievance\GrievanceMails;

class EmailEvents {
    
    public function onGrievanceSave($grievance)
    {
        $grievanceMails = new GrievanceMails;
        $grievanceMails->sendGrievanceSaveEmail($grievance);
    }

    /**
     * Subscribing to the events and declaring their function.
     */
    public function subscribe($events)
    {
        $events->listen('grievance.save', 'FW\Emails\EmailEvents@onGrievanceSave');
    }

}