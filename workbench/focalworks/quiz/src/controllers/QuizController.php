<?php

class QuizController extends BaseController
{
    /**
     * Defining the master layout.
     *
     * @var string
     */
    protected $layout = 'sentryuser::master';

    /**
     * Calling the constructor to execute any code on load of this class.
     */
    public function __construct()
    {
        /**
         * Setting the layout of the controller to something else
         * if the configuration is present.
         */
         if (Config::get('packages/l4mod/sentryuser/sentryuser.master-tpl') != '')
             $this->layout = Config::get('packages/l4mod/sentryuser/sentryuser.master-tpl');
    }

    public function mainView() {
        $this->layout->content = View::make('quiz::quiz');
    }

    /*
     *  display list of quiz exam conducted
     * */
    public function examList() {
        echo "examList method";
    }

    /*
     *  All added questions list for exam
     * */
    public function questionList() {
        $quiz = new Quiz();
        $department = $quiz->get_departments();
        return View::make('quiz::question-list')->with('department', $department);
    }


    /*
     *  Add question form
     * */
    public function questionAdd() {
        $quiz = new Quiz();
        $designation = $quiz->get_departments();
        return View::make('quiz::question-add')->with('designation', $designation);
    }

    /*
     * Handle question form submit
     * */
    public function questionSave() {

       // print_r(Input::all());die;

        $question=Input::get('question');
        $question=$question[0];

        $options=Input::get('option');

        $queRules=array(
            'qq_text' => 'required|min:3',
            'designation'=>'required'
        );

        $optRule=array(
            'qo_text' => 'required|min:3',
            'is_correct' => 'required'
        );

        $queMessages = array(
            'qq_text.required' => 'Question text is required',
            'designation.required' => 'designation is required',
            'qq_text.min' => 'Question text should be longer. Min 3 characters'
        );

        $optMessage=array(
            'qo_text.required' => 'Option text is required',
            'qo_text.min' => 'Question text should be longer. Min 3 characters'
        );

        $return = array(
            'status' => 1
        );

        $queValidator = Validator::make($question, $queRules, $queMessages);

        if ($queValidator->fails()) {
            $return['status']=0;
            $return['message'][]=$queValidator->messages()->all();
        }

        foreach($options as $option) {
            $optValidator = Validator::make($option, $optRule, $optMessage);
            if ($optValidator->fails()) {
                $return['status']=0;
                $return['message'][]=$optValidator->messages()->all();
            }
        }

        if($return['status'] == 0) {
            return $return;
        }

        $quiz = new Quiz();

        if ($quiz->saveQuestions(Input::all())) {
            $return['status'] = 1;
            $return['message'] = "Question inserted successfully";
        } else {
            $return['status'] = 0;
            $return['message'] = "Something went wrong";
        }
        return json_encode($return);
    }

    /*
     * View question with all options for edit purpose
     * */
    public function questionView($qq_id) {
        $quiz = new Quiz();
        $question = $quiz->get_question($qq_id);
        $this->layout->content = View::make('quiz::question-add')->with('question',$question);
    }

    /*
     *  Delete any question
     * */
    public function questionDelete($qq_id) {
        $quiz = new Quiz();

        if(!$quiz->deleteQuestion($qq_id)) {
            App::abort(500,'Error while deleting question');
        }
    }

    /*
     *  Handle action of add user form
     * */
    public function saveUser() {

        //return Input::all();

        $rules=array(
            'qu_fname' => 'required',
            'qu_designation' => 'required',
            'qu_email' => 'required|email',
            'qu_mobile' => 'required'
        );

        $messages = array(
            'qq_text.required' => 'First name is required',
            'qu_designation.required' => 'Designation type is required',
            'qu_email.required' => 'Email address is required',
            'qu_mobile.required' => 'Mobile number is required',
            'qu_email.email' => 'Invalid email address',
        );

        $validator = Validator::make(Input::all(), $rules, $messages);



        if ($validator->fails()) {

            return $validator->messages();
            //App::abort(500,$validator->messages());

        }
        else {
            $quiz = new Quiz();
            if($quiz->saveUser()) {
                return "Success";
            }
            else {
                return "save fail";
            }
        }
    }

    /*
    * get question list
    * */
    public function jsonQuestionList() {
        $quiz = new Quiz();
        $question = $quiz->get_question();
        return json_encode($question);
    }

    /*
     * get question and its options as a json data
     * */
    public function jsonQuestion($qq_id) {
        $quiz = new Quiz();

        $question = $quiz->question_with_options($qq_id);
        return json_encode($question);die;
    }

}