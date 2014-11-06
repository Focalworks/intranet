<?php
/**
 * Created by PhpStorm.
 * User: amitav
 * Date: 11/6/14
 * Time: 5:33 PM
 */

class AssessmentController extends BaseController {

    protected $layout = 'master.master';

    public function getLandingPage()
    {
        $this->layout->content = View::make('assessment::templates.assessment-land');
    }
}