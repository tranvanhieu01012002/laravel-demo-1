<?php

namespace App\Services\Room;;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;

class RoomService implements IRoomService
{

    private static $expired = 10;

    public function open(int $id): array
    {
        $roomOwnerId = Redis::get($id);
        if ($roomOwnerId) {
            $isOwner = false;
            if (Auth::id() === $roomOwnerId) {
                $isOwner = true;
            }
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
        $redis->set($key, $userId, "EX", self::$expired * 60);

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
}
