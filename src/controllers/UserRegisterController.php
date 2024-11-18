<?php

include_once __DIR__ . "/../models/UserRegisterModel.php";
include_once __DIR__ . "/../helpers/ValidationHelper.php";
include_once __DIR__ . "/../helpers/ImageHelper.php";

use Helpers\ValidationHelper;
use Helpers\ImageHelper;

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

        // Validate text fields
        $validationResult = ValidationHelper::validateUserInput($data);
        if (!$validationResult['success']) {
            http_response_code(400);
            echo json_encode(["message" => $validationResult['message']]);
            return;
        }

        // Ensure image is mandatory and validate it
        if (empty($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
            http_response_code(400);
            echo json_encode(["message" => "Image is required."]);
            return;
        }

        $uploadDir = __DIR__ . "/../uploads/";
        $imageValidationResult = ImageHelper::validateImage($_FILES['image']);
        if (!$imageValidationResult['success']) {
            http_response_code(400);
            echo json_encode(["message" => $imageValidationResult['message']]);
            return;
        }

        // Check email uniqueness
        if (UserRegisterModel::isEmailExists($data['email'])) {
            http_response_code(409); 
            echo json_encode(["message" => "Email already exists."]);
            return;
        }

        // Hash the password
        $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);

        // Upload the image
        $imageUploadResult = ImageHelper::uploadImage($_FILES['image'], $uploadDir);
        if (!$imageUploadResult['success']) {
            http_response_code(500);
            echo json_encode(["message" => $imageUploadResult['message']]);
            return;
        }

        $data['image'] = $imageUploadResult['image'];

        // Set default values for status and no_of_votes
        $data['status'] = isset($data['status']) ? $data['status'] : 'not voted';
        $data['no_of_votes'] = isset($data['no_of_votes']) ? $data['no_of_votes'] : 0;

        // Register the user
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
