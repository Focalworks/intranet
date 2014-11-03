<?php

class Grievance extends Eloquent
{
    protected $table = 'grievances';

    public function getGrievanceMultiple(array $ids)
    {
        $data = array();

        foreach ($ids as $id) {
            $data[] = $this->getGrievance($id);
        }

        return $data;
    }
    
    public function getGrievance($id)
    {
        $key = 'grievance_' . $id;
        $data = Cache::get($key);

        if ($data) {
            return $data;
        } else {
            // adding the code to invalidate the cache
            Event::fire('grievance.cacheClear', [$id]);
            return $this->loadGrievance($id);
        }
    }

    public function getGrievanceCount($id = null)
    {
        $query = DB::table($this->table)->where('deleted', 0);

        if ($id != null) {
            $query->where('user_id', $id);
        }

        return $query->count();
    }

    private function loadGrievance($id)
    {
        $table = $this->table;
        $arrSelect = array(
            $table.'.id', $table.'.title', $table.'.description', $table.'.category',
            $table.'.urgency', $table.'.user_id', $table.'.status', $table.'.created_at', $table.'.updated_at',
            $table.'.anonymous',$table.'.req_reopen',
            'file_managed.id as fid', 'file_managed.url', 'file_managed.filemime', 'file_managed.filesize'
        );

        $query = DB::table($this->table)->where($this->table.'.id', $id);
        $query->select($arrSelect);
        $query->join('file_managed', 'file_managed.entity_id', '=', $this->table . '.id', 'left');
        
        $data = $query->first();
        $data->time_ago = GlobalHelper::timeAgo(strtotime($data->created_at));
        $data->comment_count = DB::table('comments')->where('section', 'grievance_view')->where('nid', $id)->count();

        $comments = new Comment;
        $data->comments = $comments->get_comments($id, 'grievance_view');

        $key = 'grievance_' . $id;
        //Cache::forever($key, $data);
        $data->first_name = 'Anonymous';
        $data->last_name = '';
        $data->userimage = "../../".Config::get('sentryuser::sentryuser.default-pic');
    $data->cre_time=date("jS F Y", strtotime($data->created_at));;

        $data->anonymous_val='';
        if($data->anonymous==1)
        {
            $data->anonymous_val='checked';
        }
        else
        {
            $user_table=  DB::table('users As us')->select('first_name','last_name') ->where('id',$data->user_id)->first();
            $data->first_name=$user_table->first_name;
            $data->last_name=$user_table->last_name;

             $user_detail_data=DB::table('user_details')->select('oauth_pic')->where('user_id', '=', $data->user_id)->first();
             if ($user_detail_data->oauth_pic != '')
             {
                $data->userimage=$user_detail_data->oauth_pic;
             }
        }

        $userObj = Session::get('userObj');
        $data->my_user_id=$userObj->id;

        return $data;
    }

    public function saveGrievance($postData)
    {
        try {
            DB::beginTransaction();

            // fetch the user object from session
            $userObj = Session::get('userObj');

            // creating the grievance instance based on the post data.
            $Grivance = new Grievance();
            $Grivance->title = $postData['title'];
            $Grivance->description = $postData['body'];
            $Grivance->category = $postData['category'];
            $Grivance->urgency = $postData['urgency'];
            $Grivance->status = 1;
            $Grivance->anonymous = 0;
            if(isset($postData['anonymous']) && $postData['anonymous']){
                $Grivance->anonymous = 1;
            }

            $Grivance->user_id = $userObj->id;
            $Grivance->save(); // save the grievance

            // handling the file upload
            $this->handleFileUpload($Grivance);

            DB::commit();

            $data = $this->loadGrievance($Grivance->id);

            // adding the code to invalidate the cache
            Event::fire('grievance.cacheClear', [$Grivance->id]);
            Event::fire('grievance.save', [$Grivance]);
            $key = 'grievance_' . $Grivance->id;
            Cache::forever($key, $data);

            return true;
        } catch (Exception $e) {
            DB::rollback();
            SentryHelper::setMessage($e->getMessage(), 'warning');
            return false;
        }
    }

    public function updateGrievance()
    {
        try {
            DB::beginTransaction();

            // fetch the user object from session
            $userObj = Session::get('userObj');

            $Grivance = Grievance::find(Input::get('id'));
            $Grivance->title = Input::get('title');
            $Grivance->description = Input::Get('body');
            $Grivance->category = Input::Get('category');
            $Grivance->urgency = Input::Get('urgency');
            $Grivance->anonymous = Input::Get('anonymous');
            
            // if the form is coming from managed view then save the status as well
            if (Input::get('status')) {
                $ch_status=Input::get('status');
                $Grivance->status = Input::get('status');
                if($ch_status==4)
                {
                    $Grivance->req_reopen=null;
                }
            }
            $Grivance->save();

            // handling the file upload
            $this->handleFileUpload($Grivance);

            DB::commit();

            $data = $this->loadGrievance($Grivance->id);

            // adding the code to invalidate the cache
            Event::fire('grievance.cacheClear', [$Grivance->id]);
            $key = 'grievance_' . $Grivance->id;
            Cache::forever($key, $data);

            return true;

        } catch (Exception $e) {
            DB::rollback();
            SentryHelper::setMessage($e->getMessage(), 'warning');
            return false;
        }
    }

    public function handleFileUpload($Grivance)
    {
        // fetch the user object from session
        $userObj = Session::get('userObj');

        // upload photo if present and entry in file managed table
        if (Input::hasFile('photo') && Input::file('photo')->isValid()) {
            // do when file is present in the post
            if (Input::get('fid') != 0) {
                $file = FileManaged::find(Input::get('fid'));
                $urlToDelete = $file->url;
            }

            $photo = Input::file('photo');
            $image = Image::make($photo->getRealPath());

            $filename = GlobalHelper::sanitize($photo->getClientOriginalName(), true);
            $filename = time() . '_' . $filename;
            $folder = 'grievance/' . $userObj->id . '/';

            $image->resize(null, 240, function ($constraint)
            {
                $constraint->aspectRatio();
            });

            // create the folder if it is not present
            if (! file_exists($folder)) {
                Log::info('Folder created' . $folder);
                mkdir($folder, 0777, true);
            }


            // saving the image on desired folder
            $image->save($folder . $filename);

            // building the data before saving
            $fileManagedData = array(
              'user_id' => $userObj->id,
              'entity' => GRIEVANCE,
              'entity_id' => $Grivance->id,
              'filename' => $filename,
              'url' => $folder . $filename,
              'filemime' => $photo->getMimeType(),
              'filesize' => $photo->getSize(),
            );

            if (Input::get('fid') != 0) {
                // updating the file information in file managed table
                $FileManaged = new FileManaged;
                $FileManaged->updateFileInfo(Input::get('fid'), $fileManagedData);
                Log::info('updating the file information in file managed table');
            } else {
                // saving the file information in file managed table
                $FileManaged = new FileManaged;
                $FileManaged->saveFileInfo($fileManagedData);
                Log::info('saving the file information in file managed table');
            }

            // removing the file only when new file has been uploaded
            if (isset($urlToDelete)) {
                File::delete($urlToDelete);
            }
        }
    }

    public function deleteGrievance($id)
    {
        //Deletes should be soft delete. Should not go away from Database
         $result=DB::table($this->table)
            ->where('id', $id)
            ->update(array('deleted' => 1));
        return $result;
        /*$Grievance = Grievance::find($id);
        $Grievance->delete();
        $FileManaged = FileManaged::find($Grievance->id);
        File::delete($FileManaged->url);*/
    }

    /**
     * Mapping the status with their DB ids.
     * If id is not passed, then the full array is returned 
     * which can be used for dropdown and other uses. 
     * @param unknown $id
     * @return Status name <string>
     */
    public static function getStatusName($id = null)
    {
        $arrStatus = array(
            '1' => 'Submitted',
            '2' => 'In progress',
            '3' => 'Closed',
            '4' => 'Re opened',
        );
        
        if ($id != null) {
            return $arrStatus[$id];
        } else {
            return $arrStatus;
        }
    }

    /**
     * Mapping the username with their DB ids.
     * @param unknown $id
     * @return User name <string>
     */
    public static function getUserName($uid,$anonymous)
    {
        $uname="Anonymous";
        if(!$anonymous)
        {
            $uname1 = DB::table('users')->select('first_name','last_name')->where('id', $uid)->first();
            $uname=$uname1->first_name.' '.$uname1->last_name;
        }
        return $uname;
    }
    /**
     * This is the array of Categories for the grievance / suggestion
     * The array is used to map and also generate the drop downs.
     * 
     * @param string $id            
     * @return Ambigous <string>|multitype:string
     */
    public static function getGrievanceCategories($id = null)
    {
        $arrGrievance = array(
            'complain' => 'Complain',
            'rules' => 'Rules',
            'facility' => 'Facility',
            'hygiene' => 'Hygiene'
        );
        
        if ($id != null) {
            return $arrGrievance[$id];
        } else {
            return $arrGrievance;
        }
    }
    
    public static function getUrgencies($id = null)
    {
        $arrUrgencies = array(
            '3' => 'High',
            '2' => 'Medium',
            '1' => 'Low'
        );
        
        if ($id != null) {
            return $arrUrgencies[$id];
        } else {
            return $arrUrgencies;
        }
    }

    public static function sortColumnLinkHelper($sortArray, $key, $sortBy)
    {
        $columnName = ucwords($key);

        // special case of column names
        switch ($columnName) {
            case 'Created_at':
                $columnName = 'Created at';
                break;
        }

        $linkString = url('grievance/list?sortby=' . $key . '&order=' . $sortArray[$key]);

        if ($sortBy == $key) {
          //$aLink = '<a href="'.$linkString.'" class="'.$sortArray[$sortBy].'">'.$columnName.'<span class="glyphicon glyphicon-sort"></span></a>';
          if($sortArray[$sortBy] == 'asc') {
            $aLink = '<a href="'.$linkString.'" class="'.$sortArray[$sortBy].'">'.$columnName.'&nbsp;<span class="glyphicon glyphicon-arrow-down"></span></a>';
          } else {
            $aLink = '<a href="'.$linkString.'" class="'.$sortArray[$sortBy].'">'.$columnName.'&nbsp;<span class="glyphicon glyphicon-arrow-up"></span></a>';
          }

        } else {
            $aLink = '<a href="'.$linkString.'" class="normal">'.$columnName.'&nbsp;<span class="glyphicon glyphicon-sort"></span></a>';
        }

        return $aLink;
    }
    public function RequestReoopenGrievance()
    {
        try {
            DB::beginTransaction();
            $Grivance = Grievance::find(Input::get('id'));
            $Grivance->req_reopen = Input::get('reason');

            $Grivance->save();
             DB::commit();
             return true;

        } catch (Exception $e) {
            DB::rollback();
            SentryHelper::setMessage($e->getMessage(), 'warning');
            return false;
        }
    }
}