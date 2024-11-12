<?php
include_once __DIR__ . "/../controllers/VoteController.php";

class VoteRoute
{
    public static function vote()
    {
        VoteController::handleVote();
    }
}

VoteRoute::vote();
