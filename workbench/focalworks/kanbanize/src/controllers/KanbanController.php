<?php
/**
 * Created by PhpStorm.
 * User: amitav
 * Date: 22/9/14
 * Time: 10:01 PM
 */

class KanbanController extends BaseController
{
    public function getAllProjects()
    {
        $kanban = new Kanban;
        $projects = $kanban->getAllProjects();
        return $projects;
    }
}