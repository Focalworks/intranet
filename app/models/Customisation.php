<?php
/**
 * Created by PhpStorm.
 * User: amitav
 * Date: 6/9/14
 * Time: 4:55 PM
 */

class Customisation extends Eloquent
{
    protected $table = 'user_customisation';

    /**
     * This function is to fetch the user's interface customisation data.
     * @return mixed
     */
    public function getCustomisationData()
    {
        $userObj = Session::get('userObj');
        $uid = $userObj->id;

        $cacheData = Cache::get('customise_' . $uid);
        if ($cacheData) {
            return $cacheData;
        }

        $query = DB::table($this->table)->where('user_id', $uid)->first();

        if ($query) {
            // data present, send the data
            $query->customisation = unserialize($query->customisation);
            Cache::forever('customise_'.$uid, $query);
            return $query;
        }
        else {
            // insert default data
            return $this->setDefaultData($uid);
        }
    }

    /**
     * When a user's customisation data is not present, this function is called to set his basic data
     * cache it and return back by calling the parent function again.
     * @param $uid
     * @return mixed
     */
    private function setDefaultData($uid)
    {
        // default data array
        $defaultData = array(
            'menu' => 'active',
        );

        $data = array(
            'user_id' => $uid,
            'customisation' => serialize($defaultData)
        );

        DB::table($this->table)->insert($data);

        $customisationData = $this->getCustomisationData();

        Cache::forever('customise_' . $uid, $customisationData);

        return $customisationData;
    }

    public function saveCustomisationData($data)
    {
        $userObj = Session::get('userObj');
        $uid = $userObj->id;
        Cache::forget('customise_' . $uid);

        Cache::forever('customise_'.$uid, $data);

        DB::table($this->table)->where('user_id', $uid)->update(array(
                'customisation' => serialize($data->customisation)
            ));


    }
}