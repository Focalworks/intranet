<?php
/**
 * Created by PhpStorm.
 * User: amitav
 * Date: 29/10/14
 * Time: 11:37 AM
 */

class AssessmentApiController extends BaseController {

    public function getTest()
    {
        $ids = array(1,2,3,4,5);

        $assessment = new Assessments;

        $questions = $assessment->getMultipleAssessment($ids);

        return $questions;
    }

    public function postSubmitAssessment()
    {
        $data = Input::all();
        $data['result_new'] = json_decode(Input::get('result'));
//        Log::info('<pre>' . print_r($data, true) . '</pre>');die;

        $assessments = new Assessments;
        $assessments->saveAssessmentData($data);
    }
}