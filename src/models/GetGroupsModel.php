<?php

include_once __DIR__ . "/../config/Database.php";

class GetGroupsModel
{
    private const TABLE_NAME = "users";

    public static function getUsersByRole($role = "group")
    {
        // Get database connection
        $db = Database::getConnection();

        // Prepare and execute the query
        $query = "SELECT * FROM " . self::TABLE_NAME . " WHERE role = ?";
        $stmt = $db->prepare($query);
        $stmt->execute([$role]);

        // Fetch user data
        return $stmt->fetchAll(PDO::FETCH_ASSOC); 
    }
}
