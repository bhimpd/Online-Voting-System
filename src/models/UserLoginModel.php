<?php

include_once __DIR__ . "/../config/Database.php";

class UserLoginModel
{
    private const TABLE_NAME = "users";

    public static function getUserByEmail($email)
    {
        // Get database connection
        $db = Database::getConnection();

        // Prepare and execute the query
        $query = "SELECT * FROM " . self::TABLE_NAME . " WHERE email = ?";
        $stmt = $db->prepare($query);
        $stmt->execute([$email]);

        // Fetch user data
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
