<?php

namespace App\Services\Room;;

use App\Events\RoomEvent;
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
        do {
            $key = rand(2, 2000);
        } while (in_array($key, $currentKeys));
        $redis->set($key, Auth::id(), "EX", self::$expired * 60);
        return $key;
    }
}
