<?php

include_once __DIR__ . "/../config/Database.php";

class UserRegisterModel
{
    private const TABLE_NAME = "users";

    public static function createUser($name, $email, $password)
    {
        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Get database connection
        $db = Database::getConnection();

        // Prepare and execute the query
        $query = "INSERT INTO " . self::TABLE_NAME . " (name, email, password, address, mobile, image, voter_role) VALUES (?, ?, ?)";
        $stmt = $db->prepare($query);
        return $stmt->execute([$name, $email, $hashedPassword]);
    }
}
