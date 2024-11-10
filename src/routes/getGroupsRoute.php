<?php

include_once __DIR__ . "/../controllers/GetGroupsController.php";

class GetGroupsRoute
{
    public static function groups()
    {
        GetGroupsController::getgroups(); 
    }
}

GetGroupsRoute::groups();
