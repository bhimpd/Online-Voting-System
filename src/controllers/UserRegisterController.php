<?php

include_once __DIR__ . "/../models/UserRegisterModel.php";

class UserRegisterController
{
    public static function registerUser()
    {
        // Set JSON header for response
        header('Content-Type: application/json');

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

        // Validate email format
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            http_response_code(400); // Bad Request
            echo json_encode(["message" => "Invalid email format."]);
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
            $imageName = basename($_FILES['image']['name']);
            $imageSize = $_FILES['image']['size'];
            $imageType = $_FILES['image']['type'];

            // Validate file type (allow only certain types, e.g., JPEG, PNG)
            $allowedTypes = ['image/jpeg', 'image/png'];
            if (!in_array($imageType, $allowedTypes)) {
                http_response_code(400); // Bad Request
                echo json_encode(["message" => "Invalid image type. Only JPEG and PNG are allowed."]);
                return;
            }

            // Limit file size (e.g., max 2MB)
            if ($imageSize > 2 * 1024 * 1024) {
                http_response_code(400); // Bad Request
                echo json_encode(["message" => "Image file size exceeds the 2MB limit."]);
                return;
            }

            // Define the directory to save images
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

        // Ensure email is unique
        if (UserRegisterModel::isEmailExists($data['email'])) {
            http_response_code(409); // Conflict
            echo json_encode(["message" => "Email already exists."]);
            return;
        }

        // Attempt to register the user
        try {
            $result = UserRegisterModel::createUser(
                $data['name'],
                $data['email'],
                $hashedPassword,
                $data['address'],
                $data['mobile'],
                $data['image'],
                $data['role']
            );

            if ($result) {
                http_response_code(201); // Created
                echo json_encode(["message" => "User registered successfully."]);
            } else {
                throw new Exception("Failed to register user.");
            }
        } catch (Exception $e) {
            http_response_code(500); // Internal Server Error
            echo json_encode(["message" => $e->getMessage()]);
        }
    }
}
