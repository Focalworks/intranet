<?php

class Comment extends Eloquent
{
    /** Save comments **/
    function save_comments($commentsObj, $nid, $parent_cid, $uid, $op) {
        if($op && $op == 'Edit') {
            $cid = DB::table('comments')
                ->where('cid',$parent_cid)
                ->update(array('comment' => $commentsObj->comment));
        }else {
            $result = array();

            $thread = $this->generate_thread($nid, $parent_cid);

            $insertData = array(
              'pid'=> $parent_cid,
              'nid' => $nid,
              'user_id' => $uid,
              'section' => $commentsObj->section,
              'thread' => $thread,
              'comment' => $commentsObj->comment,
              'created'=> time(),
              'changed'=> time(),
            );

            $cid = DB::table('comments')->insertGetId($insertData);

            // adding the code to invalidate the cache
            Event::fire('grievance.cacheClear', [$nid]);

            $result = $insertData;
            $result['cid'] = $cid;
            $result['date'] = date('d/m/Y h:m a', time());
            $result['username'] = 'Komal Savla';

            $userdata = $this->get_userdata($insertData['created'],$insertData['user_id'],$insertData['nid']);
            $result['first_name'] = $userdata->first_name;
            $result['last_name'] = $userdata->last_name;
            $result['userimage'] = $userdata->userimage;
            $result['created_time'] = $userdata->created_time;
            $result['isaccess'] = $userdata->isaccess;

            return $result;
        }
    }

    /** Function to get all comments **/
    function get_comments($nid, $section) {
        //$commentData = DB::table('comments')->where('nid', $nid)->where('section', $section)->orderBy(DB::raw('SUBSTRING(thread, 1, (LENGTH(thread) - 1))'))->get();
        $commentData = DB::table('comments as cm')
                ->where('nid', $nid)->where('section', $section)
                ->orderBy(DB::raw('SUBSTRING(thread, 1, (LENGTH(thread) - 1))'))->get();

        $commentObj = new stdClass();
        $commentObj->comments = array();
        $temp = array();
        $commentObj = $this->buildNestedComments($commentData);
       
        return $commentObj;
    }

    /** Function to delete comments **/
    function delete_comment($comment_data) {
        /* Delete all child comments */
        DB::table('comments')->where('pid', '=', $comment_data->cid)->delete();
        DB::table('comments')->where('cid', '=', $comment_data->cid)->delete();
    }

    /** Helper function to built array for parent child comments **/
    function buildNestedComments($comments, $parentId = 0) {
        $nestedObj = array();
        foreach ($comments as $comment) {
            //Comment time ago
           
            $userdata = $this->get_userdata($comment->created,$comment->user_id,$comment->nid);
            $comment->first_name = $userdata->first_name;
            $comment->last_name = $userdata->last_name;
            $comment->userimage = $userdata->userimage;
            $comment->created_time = $userdata->created_time;
            $comment->isaccess = $userdata->isaccess;

            if ($comment->pid == $parentId) {
                $children = $this->buildNestedComments($comments, $comment->cid);
                if ($children) {
                    $comment->children = $children;
                }
                $nestedObj[] = $comment;
            }

        }

        return $nestedObj;
    }

    /* Helper Function to get Userimage*/
    function get_userdata($created,$comment_userid,$nid)
    {
        $userdata = new stdClass();
        $userdata->created_time = GlobalHelper::timeAgo($created);
        $userdata->first_name = 'Anonymous';
        $userdata->last_name = '';
        $userdata->userimage = "../../".Config::get('sentryuser::sentryuser.default-pic');

        /* code for Anonymous user Grievance -> Anonymous -> username as Anonymous otherwise username*/
        $grievances_data = DB::table('grievances')->select('anonymous','user_id')->where('id', '=', $nid)->first();
        $anonymous = $grievances_data->anonymous;
        $gri_user_id = $grievances_data->user_id;

        //if 0 then edit and delete not display otherwise edit and delete will display
        $userObj = Session::get('userObj');
        $userdata->isaccess = 0;
        if($comment_userid == $userObj->id)
        {
            $userdata->isaccess = 1;
        }

        /* if user is not anonymous then get name and image*/
        if(!($anonymous == 1 && $comment_userid == $gri_user_id))
        {
            $user_table=  DB::table('users As us')->select('first_name','last_name') ->where('id',$comment_userid)->first();
            $userdata->first_name=$user_table->first_name;
            $userdata->last_name=$user_table->last_name;

             $user_detail_data=DB::table('user_details')->select('oauth_pic')->where('user_id', '=', $comment_userid)->first();
             if ($user_detail_data->oauth_pic != '')
             {
                $userdata->userimage=$user_detail_data->oauth_pic;
             }
        }
        return $userdata;
    }

    /** Helper function to generate thread id for comments (01/, 01.00) **/
    function generate_thread($nid, $pid) {
        if($pid == 0) {
            // This is a comment with no parent comment (depth 0): we start
            // by retrieving the maximum thread level.
            $max_thread = DB::table('comments')->where('nid', $nid)->max('thread');

            $max = rtrim($max_thread, '/');
            // We need to get the value at the correct depth.
            $parts = explode('.', $max);
            $firstsegment = $parts[0];
            // Finally, build the thread field for this new comment.
            $thread = $this->int2vancode($this->vancode2int($firstsegment) + 1) . '/';
            $data['thread'] = $thread;
        }
        else {
            // This is a comment with a parent comment, so increase the part of the
            // thread value at the proper depth.
            // Get the parent comment:
            $parent_thread_result = DB::table('comments')->where('cid', $pid)->pluck('thread');

            // Strip the "/" from the end of the parent thread.
            $parent_thread = (string) rtrim((string) $parent_thread_result, '/');

            // Get the max value in *this* thread.
            $max_thread = DB::table('comments')
              ->where('nid', $nid)
              ->where('thread', 'LIKE', $parent_thread . ".%")
              ->max('thread');

            if ($max_thread == '') {
                // First child of this parent.
                $thread = $parent_thread . '.' . $this->int2vancode(0) . '/';
            }
            else {
                // Strip the "/" at the end of the thread.
                $max = rtrim($max_thread, '/');
                // Get the value at the correct depth.
                $parts = explode('.', $max);
                $parent_depth = count(explode('.', $parent_thread));
                $last = $parts[$parent_depth];
                // Finally, build the thread field for this new comment.
                $thread = $parent_thread . '.' . $this->int2vancode($this->vancode2int($last) + 1) . '/';
            }
            $data['thread'] = $thread;
        }

        return $data['thread'];
    }

    /**
     * Encode vancode back to an integer.
     */
    function int2vancode($i = 0) {
        $num = base_convert((int) $i, 10, 36);
        $length = strlen($num);
        return chr($length + ord('0') - 1) . $num;
    }

    /**
     * Decode vancode back to an integer.
     */
    function vancode2int($c = '00') {
        return base_convert(substr($c, 1), 36, 10);
    }

}