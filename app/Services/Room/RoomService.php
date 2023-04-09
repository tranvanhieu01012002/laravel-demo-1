<?php

namespace App\Services\Room;;

use Illuminate\Support\Facades\Redis;

class RoomService implements IRoomService
{

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
}
