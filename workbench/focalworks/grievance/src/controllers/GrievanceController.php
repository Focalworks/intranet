<?php

class GrievanceController extends BaseController
{
    /**
     * Defining the master layout.
     *
     * @var string
     */
    protected $layout = 'sentryuser::master';

    /**
     * Calling the constructor to execute any code on load of this class.
     */
    public function __construct()
    {
        /**
         * Setting the layout of the controller to something else
             * if the configuration is present.
             */
         if (Config::get('packages/l4mod/sentryuser/sentryuser.master-tpl') != '')
             $this->layout = Config::get('packages/l4mod/sentryuser/sentryuser.master-tpl');
    }

    /**
     * This page is displaying the list of Grievances.
     * If the user has manage Grievances permission,
     * then he will have some additional rights.
     */
    public function handleList()
    {
        // fetch get data
        $sortBy = Input::get('sortby');
        $orderBy = Input::get('order');

        $data = DB::table('grievances');

        // populating the array for default sorting
        $arrSortLinks = array(
            'category' => 'desc',
            'urgency' => 'desc',
            'created_at' => 'desc',
            'status' => 'desc',
        );

        // flag to check sort based on which pagination link will change in view.
        $sort = false;
        if ($sortBy && $orderBy) {
            // if the sort is set, then the conditions will change

            $arrSortLinks[$sortBy] = $orderBy;

            $sortOrder = array(
                Input::get('sortby') => Input::get('order')
            );

            $paginateSort = array(
                'sortby' => Input::get('sortby'),
                'order' => Input::get('order'),
            );

            $sort = true;

            if (array_key_exists($sortBy, $arrSortLinks)) {
                if ($arrSortLinks[$sortBy] == 'desc') {
                    $arrSortLinks[$sortBy] = 'asc';
                } else {
                    $arrSortLinks[$sortBy] = 'desc';
                }
            }
        }
        else {
            $sortOrder = array(
                'urgency' => 'desc',
                'created_at' => 'desc',
                'status' => 'desc',
            );
        }

        // building the query with orderBy clauses
        foreach ($sortOrder as $col => $ord) {
            $data->orderBy($col, $ord);
        }

        // get the user session
        $userObj = Session::get('userObj');

        if(isset($userObj->grievanceFilter)) {
            $whereClause = $userObj->grievanceFilter;
            foreach ($whereClause as $key => $value) {
                $data->where($key, $value);
            }
        }

        // check if the user has access to manage permissions. Based on this, manage link will come
        $access = PermApi::user_has_permission('manage_grievance');

        // if the permission is not applicable, then see only own 
        if (!$access) {
            $data = $data->where('user_id', $userObj->id);
        }

        $data = $data->paginate(10);

        $this->layout->content = View::make('grievance::grievance-list')
            ->with('sortArray', $arrSortLinks)
            ->with('userObj', $userObj)
            ->with('sortBy', $sortBy)
            ->with('filters', (isset($whereClause)) ? $whereClause : false)
            ->with('sort', ($sort === true) ? $paginateSort : false)
            ->with('grievances', $data)
            ->with('access', $access);
    }

    /**
     * The add Grievance / Suggestion form
     */
    public function handleAdd()
    {
        $this->layout->content = View::make('grievance::grievance-add');
    }

    /**
     * Handling the saving of the Grievance form.
     */
    public function handleGrievanceSave()
    {
        $rules = array(
            'title' => 'required|min:3',
            'body' => 'required|min:10',
            'category' => 'required',
            'urgency' => 'required',
        );

        $messages = array(
            'title.required' => 'A title is required',
            'title.min' => 'Title should be longer. Min 3 characters',
        );

        // doing the validation, passing post data, rules and the messages
        $validator = Validator::make(Input::all(), $rules, $messages);

        if ($validator->fails()) {
            // send back to the page with the input data and errors
            GlobalHelper::setMessage('Fix the errors.', 'warning'); // setting the error message
            return Redirect::to('grievance/add')->withInput()->withErrors($validator);
        }

        $Grievance = new Grievance;

        if ($Grievance->saveGrievance(Input::all())) {
            SentryHelper::setMessage('A new Grievance has been saved');
        } else {
            SentryHelper::setMessage('Grievance has not been saved', 'warning');
        }

        return Redirect::to('grievance/list');
    }

    /**
     * This is the view which will be used by the user who created the post.
     * @param unknown $id
     */
    public function handleGrievanceView($id)
    {
        $Grievance = new Grievance;
        $grievance = $Grievance->getGrievance($id)->first();
        $userObj = Session::get('userObj');

        // if the user is trying to edit / view an entry which he doesn't own
        if ($grievance->user_id != $userObj->id) {
            PermApi::access_check('@');
        }

        $this->layout->content = View::make('grievance::grievance-view')
        ->with('grievance', $grievance);
    }

    /**
     * Handling the update of the post.
     * This is common for both view form and manage form.
     */
    public function handleGrievanceUpdate()
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

            DB::commit();

            SentryHelper::setMessage('Grievance has been updated');

            return Redirect::to('grievance/list');

        } catch (Exception $e) {
            DB::rollback();
            SentryHelper::setMessage($e->getMessage(), 'warning');
            return Redirect::to('grievance/list');
        }
    }

    public function handleGrievanceManage($id)
    {
        /*$grievance = Grievance::find($id);
        $this->layout->content = View::make('grievance::grievance-manage')
        ->with('grievance', $grievance);*/
        PermApi::access_check('manage_grievance');

        $Grievance = new Grievance;
        $grievance = $Grievance->getGrievance($id)->first();

        $this->layout->content = View::make('grievance::grievance-manage')
            ->with('grievance', $grievance);
    }

    public function handleGrievanceFilter()
    {
        $userObj = Session::get('userObj');

        $postData = Input::all();

        unset($postData['_token']);

        $filterArr = array();

        // removing the null elements
        foreach ($postData as $key => $value) {
            if ($value != "") {
                $filterArr[$key] = $value;
            }
        }

        if (count($filterArr) > 0) {
            $userObj->grievanceFilter = $filterArr;
            Session::set('userObj', $userObj);
        }

        return Redirect::to('grievance/list');
    }

    public function handleGrievanceFilterRest()
    {
        $userObj = Session::get('userObj');

        unset($userObj->grievanceFilter);

        Session::set('userObj', $userObj);

        return Redirect::to('grievance/list');
    }
}