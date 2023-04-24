<?php

namespace App\Services\Room;;

use App\Constants\Question;
use App\Constants\Room;
use App\Models\User;
use App\Services\Question\QuestionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;
use \Illuminate\Redis\Connections\Connection;

class RoomService implements IRoomService
{
    public function open(int $id): array
    {
        $redis = Redis::connection();
        $redisValueInfo = $redis->get($id);
        $redisValueInfo = json_decode($redisValueInfo);
        $roomOwnerId = $redisValueInfo->user_id;
        if ($roomOwnerId) {

            $user = Auth::user();
            $this->addToRedis($redis, $user, $id);

            $user->id === $roomOwnerId ? $isOwner = true : $isOwner = false;
            return [
                "status" => true,
                "data" =>  $id,
                "user_id" => $roomOwnerId,
                "is_owner" => $isOwner,
                "set_question_id" => $redisValueInfo->set_question_id
            ];
        } else {
            return [
                "status" => false,
                "data" =>  "Room not found"
            ];
        }
    }

    public function create(Request $request)
    {
        $setQuestionId = $request->input("set_question_id");
        $redis = Redis::connection();
        $currentKeys = $redis->keys("*");
        $key = $this->generateRoomId(2, 2000, $currentKeys);
        $userId = Auth::id();
        $redisValueInfo = [
            "user_id" => $userId,
            "set_question_id" => $setQuestionId
        ];
        $redis->set($key, json_encode($redisValueInfo), "EX", Room::EXPIRED_TIME * 60);

        return [
            "room" => $key,
            "user_id" => $userId,
            "set_question_id" => $setQuestionId
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
