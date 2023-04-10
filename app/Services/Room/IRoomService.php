<?php

namespace App\Services\Room;

interface IRoomService
{
    public function open(int $id): array;
    public function create();
}
