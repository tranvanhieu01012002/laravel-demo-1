<?php

namespace App\Services\Room;
;
use Illuminate\Support\Facades\Redis;

class RoomService implements IRoomService{

    public function open(int $id): array
    {
        return [
            "data" =>  Redis::get($id)
        ];
    }
}