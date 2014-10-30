<?php
/**
 * Created by PhpStorm.
 * User: amitav
 * Date: 29/10/14
 * Time: 10:23 AM
 */

class Assessments extends Eloquent {

    protected $table = 'assessments';

    /**
     * This function is going to save the full question
     * @param $data
     * @return bool
     * @throws Exception
     */
    public function saveQuiz($data)
    {
        $data = $data[0];

        try {
            DB::beginTransaction();

            // saving the question
            $assessments = new Assessments;
            $assessments->title = $data['question'];
            $assessments->status = 1;
            $assessments->save();

            // get the question id
            $question_id = $assessments->id;

            // saving the options for the question
            $options = $data['options'];
            $option_data = array();
            foreach ($options as $key => $option) {
                $option_data[$key]['question_id'] = $question_id;
                $option_data[$key]['option'] = $option['option'];
                $option_data[$key]['correct'] = $option['correct'];
            }
            DB::table('assessment_options')->insert($option_data);

            $tags = $data['tags'];
            $tag_data = array();
            foreach ($tags as $key => $tag) {
                $tag_data[$key]['question_id'] = $question_id;
                $tag_data[$key]['tag_id'] = $tag;
            }
            DB::table('tags_in_assessment')->insert($tag_data);

            DB::commit();
            return true;
        }
        catch (Exception $e) {
            DB::rollback();
            SentryHelper::setMessage('error',$e->getMessage());
            throw $e;
            return false;
        }
    }

    public function getMultipleAssessment(array $ids)
    {
        $assessments = array();

        foreach ($ids as $id)
        {
            $assessments[] = $this->getAssessment($id);
        }

        return $assessments;
    }

    /**
     * This function will fetch an assessment based on the id provided.
     * @param null $id
     * @return array
     */
    public function getAssessment($id)
    {
        // check if cache present or else will query DB
        $cacheData = Cache::get('assessment_'.$id);
        if ($cacheData)
            return $cacheData;

        $q = DB::table($this->table);

        $q->where('id', $id);

        $data = $q->first();
        $data->options = $this->getAssessmentOptions($data->id);

        // setting the cache
        Cache::put('assessment_'.$id, $data, 10);

        return $data;
    }

    private function getAssessmentOptions($question_id)
    {
        $opt = DB::table('assessment_options');
        $opt->where('question_id', $question_id);
        return $opt->get();
    }

    public function saveAssessmentData($data)
    {
        try {
            DB::beginTransaction();

            // saving the user data
            $userData = array(
                'name' => $data['name'],
                'phone' => $data['phone'],
                'email' => $data['email'],
                'post_applied' => $data['post_applied'],
                'created_at' => date('Y-m-d h:m:s', time()),
                'updated_at' => date('Y-m-d h:m:s', time()),
            );
            $user_id = DB::table('assessment_user_data')->insertGetId($userData);

            // saving the final user result
            $result = $data['result_new'];
            $finalResultData = array();
            foreach ($result as $r) {
                $finalResultData[] = array(
                    'user_id' => $user_id,
                    'question_id' => $r->q_id,
                    'option_id' => isset($r->option_id) ? $r->option_id : null,
                    'status' => $r->status,
                );
            }

            DB::table('assessment_user_result')->insert($finalResultData);

            // saving the score of the user
            $score = $this->calculateScore($user_id);
            DB::table('assessment_user_score')->insert(array(
                    'user_id' => $user_id,
                    'correct_answers' => $score,
                ));

            DB::commit();

            Event::fire('score.submit', [$user_id]);

            return true;
        } catch (Exception $e) {
            DB::rollback();
            SentryHelper::setMessage('error',$e->getMessage());
            throw $e;
            return false;
        }
    }

    public function calculateScore($user_id)
    {
        // get the result
        $result = DB::table('assessment_user_result')->where('user_id', $user_id);
        $result = $result->get();

        // get all question ids
        $questions_ids = array();
        foreach ($result as $r) {
            $questions_ids[] = $r->question_id;
        }

        // fetch all questions
        $assessments = new Assessments;
        $all_assessments = $assessments->getMultipleAssessment($questions_ids);

        // get the correct answers for each question
        $question_answers = array();
        foreach ($all_assessments as $assessment)
        {
            $question_answers[$assessment->id] = $this->getAssessmentCorrectAnswer($assessment);
        }

        $score = 0;

        foreach ($result as $r) {
            if ($r->option_id == $question_answers[$r->question_id]['correct_option']) {
                $score = $score + 1;
            }
        }

        return $score;
    }

    private function getAssessmentCorrectAnswer($assessment)
    {
        $data = array();
        $data['question_id'] = $assessment->id;
        $data['correct_option'] = 0;

        foreach ($assessment->options as $option) {
            if ($option->correct == 1) {
                $data['correct_option'] = $option->id;
            }
        }

        return $data;
    }
}