<?php

include_once __DIR__ . "/../models/GetGroupsModel.php";

class GetGroupsController
{
    public static function getgroups(){
        $users = GetGroupsModel::getUsersByRole();
        
        if (!$users){
            http_response_code(404);
            echo json_encode(["message" => "Users not found."]);
        }
        else{
            http_response_code(200); 
            echo json_encode(["message" => "Data fetched successful.", "users" => $users]);

        }
    }
}