<?php
/**
 * Created by PhpStorm.
 * User: amitav
 * Date: 29/10/14
 * Time: 11:37 AM
 */

class AssessmentApiController extends BaseController {

    /**
     * This url will send the test assessment questions to the mobile application
     * @return array
     */
    public function getTest()
    {
        $ids = array(1,2,3,4,5);

        $assessment = new Assessments;

        $questions = $assessment->getMultipleAssessment($ids);

        return $questions;
    }

    /**
     * This url is fetching the user data and assessment result
     * from the mobile application
     *
     * @throws Exception
     */
    public function postSubmitAssessment()
    {
        Log::info('Assessment submitted');
        $data = Input::all();
        $data['result_new'] = json_decode(Input::get('result'));
        $assessments = new Assessments;
        $assessments->saveAssessmentData($data);
        die; // not sure why this is required, but without die two emails are getting fired.
    }

    /**
     * This url will return all the required tags
     * @return array
     */
    public function getTags()
    {
        $tags = array(
            '1' => 'PHP',
            '3' => 'ASP .NET',
            '4' => 'Java',
            '5' => 'Android',
            '6' => 'Flash',
            '7' => 'Designer',
            '8' => 'Tester',
        );

        return $tags;
    }
}