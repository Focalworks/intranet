<?php
/**
 * Created by PhpStorm.
 * User: amitav
 * Date: 29/10/14
 * Time: 10:23 AM
 */

class Assessments extends Eloquent {

    protected $table = 'assessments';

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


    public function getAssessment($id = null)
    {
        $questions = array();
        $q = DB::table($this->table);

        if ($id != null)
            $q->where('id', $id);

        $data = $q->get();

        foreach ($data as $d) {
            $d->options = $this->getAssessmentOptions($d->id);
            $questions = $d;
        }

        return $questions;
    }

    private function getAssessmentOptions($question_id)
    {
        $opt = DB::table('assessment_options');
        $opt->where('question_id', $question_id);
        return $opt->get();
    }
}