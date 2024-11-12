<?php
include_once __DIR__ . "/../config/Database.php";

class VoteModel
{
    private const TABLE_NAME = "users";

    // Check if the user has already voted
    public static function getUserStatus($userId)
    {
        $db = Database::getConnection();

        $query = "SELECT status FROM " . self::TABLE_NAME . " WHERE id = ?";
        $stmt = $db->prepare($query);
        $stmt->execute([$userId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result ? $result['status'] : null; // Return the status if found, else null
    }

    // Increment vote count for the user if not yet voted
    public static function updateVoteCount($userId)
    {
        $db = Database::getConnection();

        $query = "UPDATE " . self::TABLE_NAME . " SET no_of_votes = no_of_votes + 1 WHERE id = ?";
        $stmt = $db->prepare($query);
        return $stmt->execute([$userId]);
    }

    // Update the user's voting status to 'voted'
    public static function updateUserStatus($userId, $status)
    {
        $db = Database::getConnection();

        $query = "UPDATE " . self::TABLE_NAME . " SET status = ? WHERE id = ?";
        $stmt = $db->prepare($query);
        return $stmt->execute([$status, $userId]);
    }
}
