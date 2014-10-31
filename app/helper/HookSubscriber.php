<?php namespace FW\Subscriber;
/**
 * Created by PhpStorm.
 * User: amitav
 * Date: 22/9/14
 * Time: 5:45 PM
 */

//use Focalworks\Assessment\

class HookSubscriber
{
    public function onGrievanceUpdate($id)
    {
        $key = 'grievance_' . $id;
        \Cache::forget($key);
    }

    public function onScoreSubmit($user_id)
    {
        \Log::info('I was here'.$user_id);
        $assessments = new \Assessments;
        $assessments->generateUserAssessmentPDF($user_id);
        //$assessments->sendResultEmail($user_id);
    }

    public function subscribe($events)
    {
        $events->listen('grievance.cacheClear', 'FW\Subscriber\HookSubscriber@onGrievanceUpdate');
        $events->listen('score.submit', 'FW\Subscriber\HookSubscriber@onScoreSubmit');
    }
}