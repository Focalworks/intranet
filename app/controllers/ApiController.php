<?php
/**
 * Created by PhpStorm.
 * User: amitav
 * Date: 16/9/14
 * Time: 12:12 PM
 */

class ApiController extends BaseController
{
    private $user;

    public function __construct()
    {
        header("Access-Control-Allow-Origin: *"); // this is required for cross domain.
        if (isset($_SERVER['HTTP_EMAIL']))
        {
            $user = Sentry::findUserByLogin($_SERVER['HTTP_EMAIL']);
            if ($user) {
                $this->user = $user;
            } else {
                App::abort(500, 'Access denied');
            }
        }
    }

    public function postGrievanceList()
    {
        $Grievance = new Grievance;

        $ids = DB::table('grievances')->where('user_id', $this->user->id)->lists('id');

        $grievances = array();

        foreach ($ids as $id) {
            $grievances[] = $Grievance->getGrievance($id);
        }

        return $grievances;
    }

    /*public function getGrievanceList()
    {
        $Grievance = new Grievance;

        $ids = DB::table('grievances')->where('user_id', 3)->lists('id');

        $grievances = array();

        foreach ($ids as $id) {
            $grievances[] = $Grievance->getGrievance($id);
        }

        return $grievances;
    }*/
}