<?php

include_once __DIR__ . "/../models/UserLoginModel.php";

class UserLoginController
{
    public static function loginUser()
    {
        // Get the POST data (assuming this is coming from Postman)
        $data = json_decode(file_get_contents("php://input"), true);

        // Store the email and password in separate variables
        $email = isset($data['email']) ? $data['email'] : null;
        $password = isset($data['password']) ? $data['password'] : null;

        

        // Check if both email and password are set
        if ($email && $password) {
            // Call the model to verify the user
            $user = UserLoginModel::getUserByEmail($email);

            if ($user) {
                $passwordToHash = 'admin123';
$hashedPassword = password_hash($passwordToHash, PASSWORD_DEFAULT);
echo "pp" . $hashedPassword;
die("fucker");

                // Verify the password
                if (password_verify($password, $user['password'])) {
                    
                    // Successful login
                    http_response_code(200); // OK
                    echo json_encode(["message" => "Login successful.", "user" => $user]);
                } else {
                    // Password does not match
                    http_response_code(401); // Unauthorized
                    echo json_encode(["message" => "Invalid password."]);
                }
            } else {
                // User not found
                http_response_code(404); // Not Found
                echo json_encode(["message" => "User not found."]);
            }
        } else {
            // Email or password is missing
            http_response_code(400); // Bad Request
            echo json_encode(["message" => "Email or password is missing."]);
        }
    }
}
