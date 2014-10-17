<?php

class Quiz extends Eloquent
{
    function saveQuestions($input) {

        $question=Input::get('question');
        $question=$question[0];
        $options=Input::get('option');
        $removed = Input::get('removed');
        try {

            DB::beginTransaction();

            if(isset($question['qq_id']) && $question['qq_id']!='') {

                DB::table('quiz_questions')
                    ->where('qq_id',$question['qq_id'])
                    ->update(
                    array(
                        'qq_text' => $question['qq_text'],
                        'designation'=> $question['designation'],
                        'changed' => date('Y-m-d h:i:s')
                    )
                );

                if(count($removed) > 0) {
                    DB::table('quiz_options')
                        ->where('qq_id',$question['qq_id'])
                        ->whereIn('qo_id',$removed)
                        ->delete();
                }

                foreach($options as $option) {
                    $option_array=array(
                        'qo_text' => $option['qo_text'],
                        'is_correct' => $option['is_correct'],
                    );

                    if(isset($option['qo_id']) && $option['qo_id']!='') {
                        $option_array['changed'] = date('Y-m-d h:r:s');
                        DB::table('quiz_options')
                            ->where('qq_id',$question['qq_id'])
                            ->where('qo_id',$option['qo_id'])
                            ->update($option_array);

                    }
                    else {

                        $option_array['qq_id'] = $question['qq_id'];
                        $option_array['created'] = date('Y-m-d h:r:s');
                        DB::table('quiz_options')->insert($option_array);
                    }
                }

            }
            else {
                $qq_id = DB::table('quiz_questions')->insertGetId(
                    array(
                        'qq_text' => $question['qq_text'],
                        'created'=>date('Y-m-d h:i:s'),
                        'designation'=> $question['designation'],
                        'created_by'=>Session::get('userObj')->id,
                    )
                );



                $insert_options=array();

                foreach($options as $key => $option) {
                    $insert_options[]=array(
                        'qq_id' => $qq_id,
                        'qo_text' => $option['qo_text'],
                        'is_correct' => $option['is_correct'],
                        'created' => date('Y-m-d h:r:s'),
                    );
                }

                DB::table('quiz_options')->insert($insert_options);
            }

            DB::commit();
            return true;
        }
        catch (Exception $e) {
            DB::rollback();
            SentryHelper::setMessage('error',$e->getMessage());
            return false;
        }
    }

    function get_options($qq_id) {
        $options= DB::table('quiz_options')
            ->where('qq_id',$qq_id)
            ->get();
        return $options;
    }

    function get_question($qq_id=false) {
        $question = DB::table('quiz_questions');

        if($qq_id) {
            $question->where('qq_id',$qq_id);
        }

        $question=$question->get();
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

    function saveUser() {
        $insert=array();
        $insert['qu_fname'] = Input::get('qu_fname');
        $insert['qu_designation'] = Input::get('qu_designation');
        $insert['qu_email'] = Input::get('qu_email');
        $insert['qu_mobile'] = Input::get('qu_mobile');
        $insert['created']= date('Y-m-d h:i:s');

        try {
            DB::table('quiz_users')->insert($insert);
            return true;
        }
        catch (Exception $e) {
            Log::error('Error while creating quiz user :  '.$e->getMessage());
            return false;
        }
    }

    function get_departments() {
        $departments = DB::table('quiz_department')
            ->lists('department');
        return $departments;
    }

    function deleteQuestion($qq_id) {
        try {
            DB::table('quiz_questions')
                ->where('qq_id',$qq_id)
                ->delete();
            return true;
        }
        catch (Exception $e) {
            Log::error('Error while delete quiz user :  '.$e->getMessage());
            return false;
        }

    }
}