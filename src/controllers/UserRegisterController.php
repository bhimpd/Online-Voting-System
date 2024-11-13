<?php

include_once __DIR__ . "/../models/UserRegisterModel.php";

class UserRegisterController
{
    public static function registerUser()
    {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405); 
            echo json_encode(["message" => "Only POST requests are allowed."]);
            return;
        }

        // Retrieve form data
        $data = $_POST;

        if (empty($data['name']) || empty($data['email']) || empty($data['password']) || 
            empty($data['confirm_password']) || empty($data['address']) || 
            empty($data['mobile']) || empty($data['role'])) {
            http_response_code(400); // Bad Request
            echo json_encode(["message" => "Please fill in all required fields."]);
            return;
        }

        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            http_response_code(400); 
            echo json_encode(["message" => "Invalid email format."]);
            return;
        }

        $password = $data['password'];
        $confirmPassword = $data['confirm_password'];

        if ($password !== $confirmPassword) {
            http_response_code(400); 
            echo json_encode(["message" => "Passwords do not match."]);
            return;
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Handle image upload if it exists
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $imageTmpPath = $_FILES['image']['tmp_name'];
            $imageName = basename($_FILES['image']['name']);
            $imageSize = $_FILES['image']['size'];
            $imageType = $_FILES['image']['type'];

            // Validate file type and extension (JPEG, JPG, PNG only)
            $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
            $allowedExtensions = ['jpeg', 'jpg', 'png'];
            $fileExtension = strtolower(pathinfo($imageName, PATHINFO_EXTENSION));

            if (!in_array($imageType, $allowedTypes) || !in_array($fileExtension, $allowedExtensions)) {
                http_response_code(400);
                echo json_encode(["message" => "Invalid image type. Only JPEG, JPG, and PNG files are allowed."]);
                return;
            }

            // Limit file size (e.g., max 2MB)
            if ($imageSize > 2 * 1024 * 1024) {
                http_response_code(400); 
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

        if (UserRegisterModel::isEmailExists($data['email'])) {
            http_response_code(409); // Conflict
            echo json_encode(["message" => "Email already exists."]);
            return;
        }

        // Set default values for status and no_of_votes if not provided
        $data['status'] = isset($data['status']) ? $data['status'] : 'not voted'; // Default to 'not voted'
        $data['no_of_votes'] = isset($data['no_of_votes']) ? $data['no_of_votes'] : 0; // Default to 0 votes

        // Attempt to register the user
        try {
            $result = UserRegisterModel::createUser(
                $data['name'],
                $data['email'],
                $hashedPassword,
                $data['address'],
                $data['mobile'],
                $data['image'],
                $data['role'],
                $data['status'],
                $data['no_of_votes']
            );

            if ($result) {
                http_response_code(201); 
                echo json_encode(["message" => "User registered successfully."]);
            } else {
                throw new Exception("Failed to register user.");
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(["message" => $e->getMessage()]);
        }
    }
}
