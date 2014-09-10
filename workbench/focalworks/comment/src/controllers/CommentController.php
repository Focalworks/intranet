<?php

class CommentController extends BaseController
{
    
    /**
   * Callback function to fetch all comments per nid 
   */
    public function getComments()
    {
    	if(isset($_POST['nid']) && $_POST['nid']) {
    	  $commentModel = new Comment;
          $commentData = $commentModel->get_comments($_POST['nid'], $_POST['section']);
     	 echo json_encode($commentData);
    	}
    }

    /**
   * Callback function to save comment
   */
    public function saveComment() {
		if(isset($_POST['data']) && $_POST['data']) {
    	  $data = json_decode($_POST['data']);
          $nid = $_POST['nid'];
	      $op = $_POST['op']; /* Edit comment or new comment added */
	      $parent_cid = $_POST['parent_cid'];
        $userObj = Session::get('userObj');
	      $uid = $userObj->id;
	      $commentModel = new Comment;
	      $commentData = $commentModel->save_comments($data, $nid, $parent_cid, $uid, $op);
	      $commentData = json_encode($commentData);
	      echo $commentData;
    	}
    }
    
    /**
   * Callback function to delete comment
   */
    public function deleteComment()
    {
        if(isset($_POST['data']) && $_POST['data']) {
            $data = json_decode($_POST['data']);
            $commentModel = new Comment;
            $commentData = $commentModel->delete_comment($data);
        }
        
    }
}