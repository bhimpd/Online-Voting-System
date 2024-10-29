<?php

include_once __DIR__ . "/../models/UserRegisterModel.php";

class UserRegisterController
{
    public static function registerUser()
    {
        // Check if data is coming from a POST request
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405); // Method Not Allowed
            echo json_encode(["message" => "Only POST requests are allowed."]);
            return;
        }

       // Retrieve form data
    $data = $_POST;

    // Validate required fields
    if (empty($data['name']) || empty($data['email']) || empty($data['password']) || 
        empty($data['confirm_password']) || empty($data['address']) || 
        empty($data['mobile']) || empty($data['role'])) {
        http_response_code(400); // Bad Request
        echo json_encode(["message" => "Please fill in all required fields."]);
        return;
    }

        // Validate required fields
        if (empty($data['name']) || empty($data['email']) || empty($data['password']) || empty($data['confirm_password']) || empty($data['address']) || empty($data['mobile']) || empty($data['role'])) {
            http_response_code(400); // Bad Request
            echo json_encode(["message" => "Please fill in all required fields."]);
            return;
        }

        // Password validation
        $password = $data['password'];
        $confirmPassword = $data['confirm_password'];

        if ($password !== $confirmPassword) {
            http_response_code(400); // Bad Request
            echo json_encode(["message" => "Passwords do not match."]);
            return;
        }

        // Hash the password for security
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Handle image upload if it exists
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $imageTmpPath = $_FILES['image']['tmp_name'];
            $imageName = $_FILES['image']['name'];
            $imageSize = $_FILES['image']['size'];
            $imageType = $_FILES['image']['type'];

            // Define the directory to save images (you may need to set correct permissions)
            $uploadDir = __DIR__ . "/../uploads/";
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $imagePath = $uploadDir . $imageName;

            // Move uploaded image to the directory
            if (move_uploaded_file($imageTmpPath, $imagePath)) {
                $data['image'] = $imageName; // Save image name or path in the database
            } else {
                http_response_code(500); // Internal Server Error
                echo json_encode(["message" => "Failed to upload image."]);
                return;
            }
        } else {
            $data['image'] = null; // No image uploaded
        }

        // Call the model to insert the user
        $result = UserRegisterModel::createUser($data['name'], $data['email'], $hashedPassword, $data['address'], $data['mobile'], $data['image'], $data['role']);

        if ($result) {
            http_response_code(201); // Created
            echo json_encode(["message" => "User registered successfully."]);
        } else {
            http_response_code(500); // Internal Server Error
            echo json_encode(["message" => "Failed to register user."]);
        }
    }
}
