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

        $json_data=Input::get('quiz');

        try {
            $quid = DB::table('quiz_users')->insertGetId($insert);

            $quiz_insert=array(
                "qu_id" => $quid,
                "answers" => $json_data,
                "created" => date('Y-m-d h:i:s'),
            );

            DB::table('quiz_exams')->insert($quiz_insert);

            return true;
        }
        catch (Exception $e) {
            Log::error('Error while creating quiz user and saving quiz exam :  '.$e->getMessage());
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

    public function getQuizQuestions($designation) {
       $sql="select qq_id,qq_text
            from quiz_questions
            where designation=?
            order by rand()
            limit 0,10";
        $data = DB::select($sql,array($designation));
        $return = array();
        $i=0;
        foreach($data as $row) {
            $options=$this->get_options($row->qq_id);
            $return[$i]['question']=$row->qq_text;
            $return[$i]['question_id']=$row->qq_id;
            $j=0;
            foreach($options as $option) {
                $return[$i]['options'][$j]['option'] = $option->qo_text;
                $return[$i]['options'][$j]['option_id'] = $option->qo_id;
                $j++;
            }
            $i++;
        }
        return $return;
    }

    public function getQuestions($limit=10) {
        $sql="select qq_idqq_text
            from quiz_questions
            order by rand()
            limit 0,".$limit;
        $data = DB::statement($sql);
        return $data;
    }

    public function getDesignation() {
        $designation=DB::table('designation')
            ->get();
        return $designation;
    }

    public function getExams() {
        $data = DB::table('quiz_exams')
            ->join('quiz_users','quiz_exams.qu_id','=','quiz_users.qu_id')
            ->get();
        return $data;
    }

}