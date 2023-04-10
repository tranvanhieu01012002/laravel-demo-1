<?php

namespace App\Services\Room;;

use Illuminate\Support\Facades\Redis;

class RoomService implements IRoomService
{

    private static $expired = 10;

    public function open(int $id): array
    {
        if (Redis::get($id)) {
            return [
                "status" => true,
                "data" =>  $id
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
        $redis->set($key, true, "EX", self::$expired * 60);
        return $key;
    }
}
