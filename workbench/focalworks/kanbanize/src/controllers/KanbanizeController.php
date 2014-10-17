<?php
/**
 * Created by PhpStorm.
 * User: amitav
 * Date: 18/9/14
 * Time: 4:11 PM
 */

class KanbanizeController extends BaseController
{
    protected $apikey;
    protected $ticket_table;
    protected $project_table;
    protected $log_time_table;

    /**
     * Defining the master layout.
     *
     * @var string
     */
    protected $layout = 'sentryuser::master';

    public function __construct() {
        /**
         * Setting the layout of the controller to something else
         * if the configuration is present.
         */
        if (Config::get('packages/l4mod/sentryuser/sentryuser.master-tpl') != '')
            $this->layout = Config::get('packages/l4mod/sentryuser/sentryuser.master-tpl');

        $this->apikey = 'sTez3KazHInejEC6F7vBqebHrGIATISh35PpqsIo';
        $this->ticket_table = 'kanbanize_tickets';
        $this->project_table = 'kanbanize_projects';
        $this->log_time_table = 'kanbanize_log_time';
    }

    public function getLandingPage() {
        $this->layout->content = View::make('kanbanize::kanban-land');
    }

    public function setCurlInit($url) {
        $this->layout = '';
        $api_key = $this->apikey;
        /**
         * Assigning Variables
         */
        $headers = array();
        $headers[] = "apikey: $api_key";
        $headers[] = "Content-type: application/json; charset=utf-8";

        /*
         * Setting CRUL parameters
         */
        $handle = curl_init();
        curl_setopt($handle, CURLOPT_URL, $url);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($handle, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($handle, CURLOPT_POST, true);
        curl_setopt($handle, CURLOPT_POSTFIELDS, array('json' => 'hello'));
        curl_setopt($handle, CURLOPT_HTTPHEADER, $headers);

        $call = (object) 'call';
        $response = curl_exec($handle);
        $call->request_error = curl_error($handle);
        $call->response = $response;

        $call->response_code = (int) curl_getinfo($handle, CURLINFO_HTTP_CODE);
        curl_close($handle);

        return $call;
    }

    public function getProjectList($cron_key) {
        $this->layout = '';

        // checking the authentication before running cron
        $model = new Kanban();
        if(!$model->checkCronKey($cron_key)) {
            return "Invalid Cron key";
        }

        /**
         * Assigning Variables
         */
        $url = 'http://kanbanize.com/index.php/api/kanbanize/get_projects_and_boards/format/json';

        $response = $this->setCurlInit($url);

        $dataArr = json_decode($response->response, true);

        $finalArr = array();

        $numOfProjects = count($dataArr['projects']);
        $counter = 0;

        for ($i = 0; $i < $numOfProjects; $i++)
        {
            $numOfBoards = count($dataArr['projects'][$i]['boards']);
            for ($j = 0; $j < $numOfBoards; $j++)
            {
                $finalArr[$counter]['project_id'] = $dataArr['projects'][$i]['id'];
                $finalArr[$counter]['project_name'] = $dataArr['projects'][$i]['name'];
                $finalArr[$counter]['board_id'] = $dataArr['projects'][$i]['boards'][$j]['id'];
                $finalArr[$counter]['board_name'] = $dataArr['projects'][$i]['boards'][$j]['name'];
                $counter++;
            }
        }

        DB::table($this->project_table)->truncate();
        DB::table($this->project_table)->insert($finalArr);
    }

    public function fetchAllTickets($cron_key) {
        $this->layout = '';

        $model = new Kanban();
        if(!$model->checkCronKey($cron_key)) {
            return "Invalid Cron key";
        }

        $ids = DB::table($this->project_table)->lists('board_id');

        foreach ($ids as $id) {
            $this->getTicketList($id);
            $key = 'board_list'.$id;
            Cache::forget($key);
        }

        $kanban = new Kanban();

        /* save logtime in   */
        $kanban->saveLogTime();
    }

    private function getTicketList($id) {
        $this->layout = '';
        /**
         * Assigning Variables
         */
        $url = "http://kanbanize.com/index.php/api/kanbanize/get_all_tasks/boardid/{$id}/container/yes/fromdate/now/todate/now/format/json";

        $response = $this->setCurlInit($url);

        $dataArr = json_decode($response->response, true);

        //GlobalHelper::dsm($dataArr);

        $kanban = new Kanban();
        $kanban->saveTicketList($dataArr,$id);
    }
}