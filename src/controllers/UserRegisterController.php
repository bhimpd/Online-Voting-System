<?php

include_once __DIR__ . "/../models/UserRegisterModel.php";

class UserRegisterController
{
    public static function registerUser()
    {
        // Get the POST data (assuming this is coming from Postman)
        $data = json_decode(file_get_contents("php://input"), true);

        // Store the password and confirm_password in separate variables
        $password = isset($data['password']) ? $data['password'] : null;
        $confirmPassword = isset($data['confirm_password']) ? $data['confirm_password'] : null;

        // Check if both password and confirm_password are set
        if ($password && $confirmPassword) {
            // Compare the passwords
            if ($password === $confirmPassword) {
                // Call the model to insert the user
                $result = UserRegisterModel::createUser($data['name'], $data['email'], $password, $data['address'], $data['mobile'],$data['image'], $data['voter_role']);

                if ($result) {
                    http_response_code(201); // Created
                    echo json_encode(["message" => "User registered successfully."]);
                } else {
                    http_response_code(500); // Internal Server Error
                    echo json_encode(["message" => "Failed to register user."]);
                }
            } else {
                // Passwords do not match
                http_response_code(400); // Bad Request
                echo json_encode(["message" => "Passwords do not match."]);
            }
        } else {
            // Password or confirm_password is missing
            http_response_code(400); // Bad Request
            echo json_encode(["message" => "Password or confirm password is missing."]);
        }
    }
}
