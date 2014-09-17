<?php
/**
 * Created by PhpStorm.
 * User: amitav
 * Date: 17/9/14
 * Time: 6:24 PM
 */

class KanbanizeController extends BaseController
{
    public function getTicketList() {
        /*
            * Assigning Variables
        */
        $url = 'http://kanbanize.com/index.php/api/kanbanize/get_all_tasks/boardid/7/format/json';
        $api_key = 'sTez3KazHInejEC6F7vBqebHrGIATISh35PpqsIo';
        $headers = array();
        $headers[] = "apikey: $api_key";
        $headers[] = "Content-type: application/json; charset=utf-8";
        /**
         * Setting CRUL parameters
         */
        $handle = curl_init();
        curl_setopt($handle, CURLOPT_URL, $url);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($handle, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($handle, CURLOPT_POST, true);
        curl_setopt($handle, CURLOPT_HTTPHEADER, $headers);

        $call = (object) 'call';
        $response = curl_exec($handle);
        $call->request_error = curl_error($handle);
        $call->response = $response;
        $call->response_code = (int) curl_getinfo($handle, CURLINFO_HTTP_CODE);
        curl_close($handle);
        $dataArr = json_decode($call->response, true);

        foreach($dataArr as $data) {
            /**
             * Building field array
             */
            $fieldData = array(
                'taskid' => $data['taskid'],
                'user_id' => 1,
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
                'columnpath' => $data['columnpath'],
                'logedtime' => $data['logedtime'],
            );

            /*
                * Checking if task alread there in database
                * If taskid found only update the row
                * Pending : Skip the row with Columname Complete
            */
            $chkTaskId = DB::table('tickets')->where('taskid', $data['taskid'])->first();
            if(!empty($chkTaskId->taskid) && isset($chkTaskId->taskid)) {
                DB::table('tickets')->where('taskid', $data['taskid'])->update($fieldData);
            } else {
                DB::table('tickets')->insert($fieldData);
            }
        }
        echo count($dataArr).' Records Added / Updated';
    }
}