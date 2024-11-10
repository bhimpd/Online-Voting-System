<?php

include_once __DIR__ . "/../models/UserLoginModel.php";

class UserLoginController
{
    public static function loginUser()
    {
        $data = json_decode(file_get_contents("php://input"), true);

        $email = isset($data['email']) ? $data['email'] : null;
        $password = isset($data['password']) ? trim($data['password']) : null;

        if ($email && $password) {
            $user = UserLoginModel::getUserByEmail($email);

            if ($user) {
                $valid = password_verify($password, $user['password']);
    
                if ($valid) {
                    http_response_code(200); 
                    echo json_encode(["message" => "Login successful.", "user" => $user]);
                } else {
                    http_response_code(401); 
                    echo json_encode(["message" => "Invalid password."]);
                }
            } else {
                http_response_code(404);
                echo json_encode(["message" => "User not found."]);
            }
        } else {
            http_response_code(400); 
            echo json_encode(["message" => "Email or password is missing."]);
        }
    }
}
