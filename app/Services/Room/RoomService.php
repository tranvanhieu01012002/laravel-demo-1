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
        $redis->set($key, Auth::id(), "EX", self::$expired * 60);
        RoomEvent::dispatch($key);
        return $key;
    }
}
