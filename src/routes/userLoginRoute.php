<?php

include_once __DIR__ . "/../controllers/UserLoginController.php";

class userLoginRoute
{
    public static function login()
    {
        UserLoginController::loginUser(); 
    }
}

userLoginRoute::login();
