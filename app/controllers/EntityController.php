<?php

class EntityController extends BaseController
{
    public function deletEntity()
    {
        $entity = Input::get('section');
        $id = Input::get('id');
        
        switch ($entity)
        {
            case 'grievance':
                if (PermApi::user_has_permission('manage_grievance'))
                {
                    $Grievance = new Grievance;
                    $Grievance->deleteGrievance($id);
                }
                break;
        }
    }

    public function toggleMenuActive()
    {
        UserInterface::toggleUserMenuStatus();
    }
}