<?php

class Quiz extends Eloquent
{
    function saveQuestions($input) {

        $qq_text=Input::get('qq_text');
        $correct=Input::get('correct');
        $options=Input::get('qo_text');
        try {

            DB::beginTransaction();

            $qq_id = DB::table('quiz_questions')->insertGetId(
                array(
                    'qq_text' => $qq_text,
                    'created'=>date('Y-m-d h:i:s'),
                    'created_by'=>Session::get('userObj')->id,
                )
            );

            $insert_options=array();

            foreach($options as $key => $option) {
                if($option!='') {
                    $is_correct=0;
                    if($key == $correct) {
                        $is_correct=1;
                    }

                    $insert_options[]=array(
                        'qq_id' => $qq_id,
                        'qo_text' => $option,
                        'is_correct' => $is_correct,
                        'created' => date('Y-m-d h:r:s'),
                    );
                }
            }

            DB::table('quiz_options')->insert($insert_options);

            DB::commit();
            return true;
        }
        catch (Exception $e) {
            var_dump($e->getMessage());die;
            DB::rollback();
            SentryHelper::setMessage($e->getMessage(), 'warning');
            return false;
        }
    }

    function get_options($qq_id) {
        $options= DB::table('quiz_options')
            ->where('qq_id',$qq_id)
            ->get();
        return $options;
    }

    function get_question($qq_id) {
        $question = DB::table('quiz_questions')
                ->where('qq_id',$qq_id)
                ->get();
        return $question;
    }

    function question_with_options($qq_id) {

        $question = $this->get_question($qq_id);
        $options=$this->get_options($qq_id);

        $return=array(
            'question' => $question,
            'options' => $options
        );

        return $return;
    }



}