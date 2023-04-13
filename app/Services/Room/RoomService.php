<?php

namespace App\Services\Room;;

use App\Constants\Question;
use App\Constants\Room;
use App\Models\User;
use App\Services\Question\QuestionService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;
use \Illuminate\Redis\Connections\Connection;

class RoomService implements IRoomService
{
    public function open(int $id): array
    {
        $redis = Redis::connection();
        $roomOwnerId = $redis->get($id);
        if ($roomOwnerId) {
            
            $user = Auth::user();
            $this->addToRedis($redis, $user, $id);

            $user->id === $roomOwnerId ? $isOwner = true : $isOwner = false;
            return [
                "status" => true,
                "data" =>  $id,
                "user_id" => $roomOwnerId,
                "is_owner" => $isOwner
            ];
        } else {
            return [
                "status" => false,
                "data" =>  "Room not found"
            ];
        }
    }

    public function create()
    {
        $redis = Redis::connection();
        $currentKeys = $redis->keys("*");
        $key = $this->generateRoomId(2, 2000, $currentKeys);
        $userId = Auth::id();
        $redis->set($key, $userId, "EX", Room::EXPIRED_TIME * 60);

        return [
            "room" => $key,
            "user_id" => $userId
        ];
    }

    public function generateRoomId(int $start, int $end, $listKeys): int
    {
        do {
            $key = rand($start, $end);
        } while (in_array($key, $listKeys));
        return $key;
    }

    public function addToRedis(Connection $redis, User $user, int $roomId)
    {
        $userStartGame = [
            "user_id" => $user->id,
            "user_name" => $user->name,
            "score" => Question::START_SCORE,
        ];

        $key = QuestionService::handleKey($roomId, $user->id);
        $redis->set($key, json_encode($userStartGame), "EX", Room::EXPIRED_TIME * 60);
    }
}
