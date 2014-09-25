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

    private function loadGrievance($id)
    {
        $table = $this->table;
        $arrSelect = array(
            $table.'.id', $table.'.title', $table.'.description', $table.'.category',
            $table.'.urgency', $table.'.user_id', $table.'.status', $table.'.created_at', $table.'.updated_at',
            'file_managed.id as fid', 'file_managed.url', 'file_managed.filemime', 'file_managed.filesize'
        );

        $query = DB::table($this->table)->where($this->table.'.id', $id);
        $query->select($arrSelect);
        $query->join('file_managed', 'file_managed.entity_id', '=', $this->table . '.id', 'left');

        $data = $query->first();
        $data->time_ago = GlobalHelper::timeAgo(strtotime($data->created_at));
        $data->comment_count = DB::table('comments')->where('section', 'grievance_view')->where('nid', $id)->count();

        $key = 'grievance_' . $id;
        //Cache::forever($key, $data);

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
            $Grivance->user_id = $userObj->id;
            $Grivance->save(); // save the grievance

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

            // if the form is coming from managed view then save the status as well
            if (Input::get('status')) {
                $Grivance->status = Input::get('status');
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
        $Grievance = Grievance::find($id);
        
        $Grievance->delete();
        
        $FileManaged = FileManaged::find($Grievance->id);
        
        File::delete($FileManaged->url);
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
}