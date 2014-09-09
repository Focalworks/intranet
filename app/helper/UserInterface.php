<?php
/**
 * Created by PhpStorm.
 * User: amitav
 * Date: 6/9/14
 * Time: 6:20 PM
 */

class UserInterface {

    public static function getUserMenuPref()
    {
        $Customisation = new Customisation;
        $userCustData = $Customisation->getCustomisationData();
        return $userCustData->customisation['menu'];
    }

    public static function toggleUserMenuStatus()
    {
        $Customisation = new Customisation;
        $userCustData = $Customisation->getCustomisationData();
        $def = 'active';
        $menuPref = $userCustData->customisation['menu'];

        if ($menuPref == $def) {
            $userCustData->customisation['menu'] = 'normal';
        } else {
            $userCustData->customisation['menu'] = $def;
        }

        $Customisation->saveCustomisationData($userCustData);
    }
}