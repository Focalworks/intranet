<?php namespace FW\Subscriber;
/**
 * Created by PhpStorm.
 * User: amitav
 * Date: 22/9/14
 * Time: 5:45 PM
 */

class HookSubscriber
{
    public function onGrievanceUpdate($id)
    {
        $key = 'grievance_' . $id;
        \Cache::forget($key);
    }

    public function subscribe($events)
    {
        $events->listen('grievance.cacheClear', 'FW\Subscriber\HookSubscriber@onGrievanceUpdate');
    }
}