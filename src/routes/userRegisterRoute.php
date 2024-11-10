<?php

include_once __DIR__ . "/../controllers/UserRegisterController.php";

class UserRegisterRoute
{
    public static function register()
    {
        UserRegisterController::registerUser(); 
    }
}

UserRegisterRoute::register();
