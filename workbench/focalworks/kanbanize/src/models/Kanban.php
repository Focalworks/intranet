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

    public function getAllProjects() {
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

    public function getAllTickets($bid) {
        $boardList = 'board_list'.$bid;
        $cacheBoard = Cache::get($boardList);
        if(!empty($cacheBoard)) {
            return $cacheBoard;
        }
        else {
            $getBoardList = DB::table($this->ticketTbl)
              ->where('board_id', $bid)
              ->orderBy('board_id', 'desc')->get();
            Cache::forever($boardList, $getBoardList);
            return $getBoardList;
        }
    }

    /*
  * Save tasks in kanbanize_tickets
  * */

    public function saveTicketList($dataArr,$board_id) {
        foreach($dataArr as $index => $data) {
            /*
                * Building field array
            */
            $fieldData[] = array(
                'taskid' => $data['taskid'],
                'user_id' => 1,
                'board_id' => $board_id,
                'position' => $data['position'],
                'type' => $data['type'],
                'assignee' => $data['assignee'],
                'title' => $data['title'],
                'description' => $data['description'],
                'subtasks' => $data['subtasks'],
                'subtaskscomplete' => $data['subtaskscomplete'],
                'color' => $data['color'],
                'priority' => $data['priority'],
                'size' => $data['size'],
                'deadline' => $data['deadline'],
                'deadlineoriginalformat' => $data['deadlineoriginalformat'],
                'extlink' => $data['extlink'],
                'tags' => $data['tags'],
                'columnid' => $data['columnid'],
                'laneid' => $data['laneid'],
                'leadtime' => $data['leadtime'],
                'blocked' => $data['blocked'],
                'blockedreason' => $data['blockedreason'],
                'columnname' => $data['columnname'],
                'lanename' => $data['lanename'],
                'columnpath' => $data['columnpath'],
                'logedtime' => $data['logedtime'],
                'created_at' => date('Y-m-d'),
            );
        }
       // GlobalHelper::dsm($fieldData);
        try {
            DB::table($this->ticketTbl)->insert($fieldData);
            return true;
        }
        catch(Exception $e) {
            Log::error('Error while save ticket :  '.$e->getMessage());
            return false;
        }
    }

    /*
     * Saving logedtime of currant dates
     * */
    function saveLogTime() {

        $row=DB::table('kanbanize_log_time')
            ->get();
        if(!$row) {
            $sql="insert into kanbanize_log_time(`created_at`, `board_id`, `taskid`,`assignee`, `logedtime`)
                select now(),board_id,taskid,assignee,logedtime
                from kanbanize_tickets";
        }
        else {
            $sql="insert into kanbanize_log_time(`created_at`, `board_id`, `taskid`,`assignee`, `logedtime`)
                select now(),t2.board_id,t2.taskid,t2.assignee,t2.logedtime-t1.logedtime
                from kanbanize_tickets t1,
                kanbanize_tickets t2
                where
                t1.taskid=t2.taskid and
                t1.assignee=t2.assignee and
                t2.created_at = CURDATE() and
                t1.created_at = (SELECT max(`created_at`) FROM `kanbanize_tickets` where  `created_at` < CURDATE())";
        }

        try {
            //GlobalHelper::dsm($sql);
            DB::statement($sql);
            Log::info('Log data added date :  '.date('d-m-Y h:i:s'));
            return true;

        }
        catch(Exception $e) {
            Log::error('Error while save task log :  '.$e->getMessage());
            return false;
        }
    }

    function checkCronKey($cron_key) {
        $row=DB::table('variables')
            ->where('name','cron_key')
            ->where('value',$cron_key)
            ->get();

        if($row && $this->isFirstEntry()) {
            return true;
        }
        else {
            Log::error('Invalid Cron key on date:'.date('d-m-Y'));
            return false;
        }
    }

    function isFirstEntry() {
        $row=DB::table('kanbanize_tickets')
            ->where('created_at',date('Y-m-d'))
            ->get();

        if(!$row) {
            return true;
        }
        else {
            Log::error('Invalid cron running, Already cron is run for date :'.date('d-m-Y'));
            return false;
        }
    }

}