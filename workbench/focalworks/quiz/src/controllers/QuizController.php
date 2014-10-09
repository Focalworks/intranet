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

    /*
     *  display list of quiz exam conducted
     * */
    public function examList() {

    }

    /*
     *  All added questions list for exam
     * */
    public function questionList($qq_id) {
        
    }


    /*
     *  Add question form
     * */
    public function questionAdd() {
        $this->layout->content = View::make('quiz::question-add');
    }

    /*
     * Handle question form submit
     * */
    public function questionSave() {
       /* echo "<pre>".print_r(Input::all(),true)."</pre>";
        die;*/

        $rules=array(
            'qq_text' => 'required|min:3',
            'correct' => 'required'
        );

        $messages = array(
            'qq_text.required' => 'Question text is required',
            'qq_text.min' => 'Question text should be longer. Min 3 characters',
            'correct.required' => 'Please select correct option'
        );

        $validator = Validator::make(Input::all(), $rules, $messages);

        if ($validator->fails()) {
            // send back to the page with the input data and errors
            GlobalHelper::setMessage('Fix the errors.', 'warning');
            /* setting the error message */
            return Redirect::to('quiz/question_add')->withInput()->withErrors($validator);
        }

        $quiz = new Quiz;

        if ($quiz->saveQuestions(Input::all())) {
            SentryHelper::setMessage('A Question has been saved');
        } else {
            SentryHelper::setMessage('Question has not been saved', 'warning');
        }

        return Redirect::to('quiz/question_add');


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
    public function questionDelete() {

    }

    /*
     *  Handle action of add user form
     * */
    public function userSave() {

    }

    /*
     * get question and its options as a json data
     * */
    public function json_question($qq_id) {
        $quiz = new Quiz;

        $question = $quiz->question_with_options($qq_id);
        echo json_encode($question);
    }

}