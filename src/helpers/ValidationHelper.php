<?php

namespace Helpers;

class ValidationHelper
{
    public static function validateUserInput($data)
    {
        $requiredFields = ['name', 'email', 'password', 'confirm_password', 'address', 'mobile', 'role'];
        foreach ($requiredFields as $field) {
            if (empty($data[$field])) {
                return ["success" => false, "message" => "Please fill in all required fields."];
            }
        }

        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            return ["success" => false, "message" => "Invalid email format."];
        }

        if ($data['password'] !== $data['confirm_password']) {
            return ["success" => false, "message" => "Passwords do not match."];
        }

        return ["success" => true];
    }
}
