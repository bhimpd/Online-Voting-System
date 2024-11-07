<?php

include_once __DIR__ . "/../config/Database.php";

class UserRegisterModel
{
    private const TABLE_NAME = "users";

    public static function createUser($name, $email, $hashedPassword, $address, $mobile, $image, $role)
    {
       
        // Get database connection
        $db = Database::getConnection();

        // Prepare and execute the query
        $query = "INSERT INTO " . self::TABLE_NAME . " (name, email, password, address, mobile, image, role) VALUES (?, ?, ?,?, ?, ?, ?)";
        $stmt = $db->prepare($query);
        return $stmt->execute([$name, $email, $hashedPassword,$address, $mobile, $image, $role]);
    }

     // Method to check if an email already exists
     public static function isEmailExists($email)
     {
         // Get database connection
         $db = Database::getConnection();
 
         // Prepare and execute the query
         $query = "SELECT COUNT(*) FROM " . self::TABLE_NAME . " WHERE email = ?";
         $stmt = $db->prepare($query);
         $stmt->execute([$email]);
 
         // Fetch the count and return true if the email exists, false otherwise
         $count = $stmt->fetchColumn();
         return $count > 0;
     }
}
