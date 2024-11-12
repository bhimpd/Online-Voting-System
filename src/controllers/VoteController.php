<?php
include_once __DIR__ . "/../models/VoteModel.php";

class VoteController
{
    public static function handleVote()
    {
        $data = json_decode(file_get_contents("php://input"), true);

        $userId = isset($data['user_id']) ? (int)$data['user_id'] : null;
        $groupId = isset($data['group_id']) ? (int)$data['group_id'] : null;

        if ($userId && $groupId) {
            $voteUpdated = VoteModel::updateVoteCount($groupId);

            if ($voteUpdated) {
                // Update user's status to "voted"
                VoteModel::updateUserStatus($userId, 'voted');

                http_response_code(200);
                echo json_encode(["message" => "Vote cast successfully."]);
            } else {
                http_response_code(500);
                echo json_encode(["message" => "Failed to cast vote."]);
            }
        } else {
            http_response_code(400);
            echo json_encode(["message" => "Invalid data provided."]);
        }
    }
}
