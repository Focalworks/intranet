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

            /* code for Anonymous user Grievance -> Anonymous -> username as Anonymous otherwise username*/
            $comment_userid=$uid;
            $grievances_data=DB::table('grievances')->select('anonymous','user_id')->where('id', '=', $nid)->first();
            $anonymous=$grievances_data->anonymous;
            $gri_user_id=$grievances_data->user_id;
            if($anonymous==1 && $comment_userid==$gri_user_id)
            {
                $result['first_name']='Anonymous';
                $result['last_name']='';
            }
            else
            {
                $userdata=  DB::table('users As us') 
                            ->where('id',$uid)->first();
                $result['first_name']=$userdata->first_name;
                $result['last_name']=$userdata->last_name;
            }
            //code for timeago
            $result['created_time']=GlobalHelper::timeAgo($insertData['created']);

            return $result;
        }
    }

    /** Function to get all comments **/
    function get_comments($nid, $section) {
        //$commentData = DB::table('comments')->where('nid', $nid)->where('section', $section)->orderBy(DB::raw('SUBSTRING(thread, 1, (LENGTH(thread) - 1))'))->get();
        $commentData = DB::table('comments as cm')
               ->leftJoin('users As us', 'cm.user_id', '=', 'us.id')
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
            $comment->created_time=GlobalHelper::timeAgo($comment->created);
             /* code for Anonymous user Grievance -> Anonymous -> username as Anonymous otherwise username*/
            $comment_userid=$comment->user_id;
            $grievances_data=DB::table('grievances')->select('anonymous','user_id')->where('id', '=', $comment->nid)->first();
            $anonymous=$grievances_data->anonymous;
            $gri_user_id=$grievances_data->user_id;
            if($anonymous==1 && $comment_userid==$gri_user_id)
            {
                $comment->first_name='Anonymous';
                $comment->last_name='';
            }

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