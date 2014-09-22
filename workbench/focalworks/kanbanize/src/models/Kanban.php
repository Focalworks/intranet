<?php
/**
 * Created by PhpStorm.
 * User: amitav
 * Date: 22/9/14
 * Time: 10:08 PM
 */

class Kanban extends Eloquent
{
    protected $projectTbl;
    protected $ticketTbl;

    public function __construct()
    {
        $this->projectTbl = 'kanbanize_projects';
        $this->ticketTbl = 'kanbanize_tickets';
    }

    public function getAllProjects()
    {
        $key = 'all_projects';
        $cacheData = Cache::get($key);

        if ($cacheData) {
            // if cache data is present
            return $cacheData;
        }
        else {
            // fire the query
            $query = DB::table($this->projectTbl)->get();

            // save in cache
            Cache::forever($key, $query);

            // return query data
            return $query;
        }
    }
}