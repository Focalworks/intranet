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

    public function getAllTickets() {
        $bid = Input::get('bid');
        $kanban = new Kanban;
        $tickets = $kanban->getAllTickets($bid);
        return $tickets;
    }
}