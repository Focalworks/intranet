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

        $data = DB::table('grievances')->where('deleted', 0);

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

        // fetch Grievance count before admin check
        $Grievance = new Grievance;
        $userGrievanceCount = $Grievance->getGrievanceCount($userObj->id);

        // if the permission is not applicable, then see only own
        if (!$access) {
            $data = $data->where('user_id', $userObj->id);
        } else {
            // for admin the Grievance count is total because we are checking count condition.
            $userGrievanceCount = $Grievance->getGrievanceCount();;
        }

        $data = $data->paginate(10);

        $this->layout->content = View::make('grievance::grievance-list')
            ->with('grievanceCount', $userGrievanceCount)
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
        $grievance = $Grievance->getGrievance($id);
        $userObj = Session::get('userObj');

        $grievance->disable_val='';
    
        // if the user is trying to edit / view an entry which he doesn't own
        if ($grievance->user_id != $userObj->id) {
            PermApi::access_check('@');
            $grievance->disable_val='disabled';
        }

        // view will change to read only for tickets status not 1
        if ($grievance->status != 1) {
            $view = 'grievance::grievance-readonly';
        } else {
            $view = 'grievance::grievance-view';
        }

        $this->layout->content = View::make($view)
        ->with('grievance', $grievance);
    }

    /**
     * Handling the update of the post.
     * This is common for both view form and manage form.
     */
    public function handleGrievanceUpdate()
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

        //If update come form mange then not run validation
        if (!(Input::get('status'))) {
            // doing the validation, passing post data, rules and the messages
            $validator = Validator::make(Input::all(), $rules, $messages);

            if ($validator->fails()) {
                // send back to the page with the input data and errors
                GlobalHelper::setMessage('Fix the errors........', 'warning'); // setting the error message
                $gid=Input::get('id');
                return Redirect::to('grievance/view/'.$gid)->withInput()->withErrors($validator);

            }
        }
        $Grievance = new Grievance;

        if ($Grievance->updateGrievance()) {
            SentryHelper::setMessage('Grievance has been updated');
        } else {
            SentryHelper::setMessage('Grievance has not been updated', 'warning');
        }

        return Redirect::to('grievance/list');
    }

    public function handleGrievanceManage($id)
    {
        /*$grievance = Grievance::find($id);
        $this->layout->content = View::make('grievance::grievance-manage')
        ->with('grievance', $grievance);*/
        PermApi::access_check('manage_grievance');
        $userObj = Session::get('userObj');

        $Grievance = new Grievance;
        $grievance = $Grievance->getGrievance($id);
        $grievance->disable_val='';
        // if the user is trying to edit / view an entry which he doesn't own
        if ($grievance->user_id != $userObj->id) {
            $grievance->disable_val='disabled';
        }


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

    //Handle for Request for Reopen 
    public function handleGrievanceRequestReopen()
    {
        //code for request to reopen
        $Grievance = new Grievance;
        if ($Grievance->RequestReoopenGrievance()) {
            SentryHelper::setMessage('Request sent ....');
        } else {
            SentryHelper::setMessage('Request Not sent', 'warning');
        }

        return Redirect::to('grievance/list');
    }
}