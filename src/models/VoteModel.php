<?php
include_once __DIR__ . "/../config/Database.php";

class VoteModel
{
    private const TABLE_NAME = "users";

    // Increment vote count for a specific group
    public static function updateVoteCount($groupId)
    {
        $db = Database::getConnection();

        $query = "UPDATE " . self::TABLE_NAME . " SET no_of_votes = no_of_votes + 1 WHERE id = ?";
        $stmt = $db->prepare($query);
        return $stmt->execute([$groupId]);
    }

    // Update the user status to 'voted'
    public static function updateUserStatus($userId, $status)
    {
        $db = Database::getConnection();

        $query = "UPDATE " . self::TABLE_NAME . " SET status = ? WHERE id = ?";
        $stmt = $db->prepare($query);
        return $stmt->execute([$status, $userId]);
    }
}
