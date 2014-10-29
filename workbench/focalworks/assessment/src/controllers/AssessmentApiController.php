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
        $ids = array(5,6,7);
        $questions = array();

        $assessment = new Assessments;

        foreach ($ids as $id) {
            $questions[] = $assessment->getAssessment($id);
        }
        return $questions;
    }
}